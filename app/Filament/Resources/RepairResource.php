<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RepairResource\Pages;
use App\Filament\Resources\RepairResource\RelationManagers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Repair;
use Filament\Actions\CreateAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RepairResource extends Resource
{
    protected static ?string $model = Repair::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Forms\Components\Select::make('car_id')
                    ->relationship('car', 'license_plate')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\Hidden::make('date')
                    ->default(now())
                    ->required(),

                Forms\Components\TextInput::make('type')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('description')
                    ->rows(4),

                Forms\Components\TextInput::make('cost')
                    ->numeric()
                    ->prefix('€'),

                Forms\Components\FileUpload::make('invoice_path')
                    ->label('Invoice')
                    ->directory('invoices')
                    ->acceptedFileTypes(['application/pdf'])
                    ->maxSize(2048),

//                Forms\Components\Repeater::make('parts')
//                    ->relationship('parts')
//                    ->schema([
//                        Forms\Components\Select::make('part_id')
//                            ->relationship('parts', 'name')
//                            ->required(),
//                        Forms\Components\TextInput::make('quantity')
//                            ->numeric()
//                            ->required(),
//                    ])
//                    ->columns(2)
//                    ->label('Used Parts')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('car.license_plate')
                    ->label('Car')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('date')
                    ->sortable()
                    ->date(),

                Tables\Columns\TextColumn::make('type')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('cost')
                    ->sortable()
                    ->money('EUR'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Added On')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('invoice_path')
                    ->label('Invoice')
                    ->formatStateUsing(fn ($state) => $state ?
                        '<a href="' . route('admin.invoices.download', $state) . '" target="_blank" class="text-blue-500">Download Invoice</a>'
                        : 'No Invoice')
                    ->html(),
            ])
            ->filters([
                Tables\Filters\Filter::make('recent')
                    ->query(fn ($query) => $query->where('date', '>=', now()->subDays(30))),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRepairs::route('/'),
            'create' => Pages\CreateRepair::route('/create'),
            'edit' => Pages\EditRepair::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return Auth::user()->is_admin;
    }
}
