<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CarResource\Pages;
use App\Filament\Resources\CarResource\RelationManagers;
use App\Mail\CarReadyForPickupMail;
use App\Models\brand;
use App\Models\Car;
use App\Status;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use PhpParser\Node\Expr\AssignOp\Mod;
use Illuminate\Support\Facades\Http;
use function Laravel\Prompts\alert;

class CarResource extends Resource
{
    protected static ?string $model = Car::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('license_plate')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->placeholder('99-XX-99')
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, callable $set) {

                        $formattedPlate = strtoupper(str_replace('-', '', $state));

                        $response = Http::get('https://opendata.rdw.nl/resource/m9d7-ebf2.json', [
                            'kenteken' => $formattedPlate,
                        ]);

                        if ($response->successful() && !empty($response->json())) {
                            $vehicleData = $response->json()[0];

                            $brand = Brand::firstOrCreate([
                                'name' => $vehicleData['merk'] ?? 'Onbekend',
                            ]);

                            $set('brand_id', $brand->id);
                            $set('model', $vehicleData['handelsbenaming'] ?? 'Onbekend');
                            $set('year', substr($vehicleData['datum_eerste_toelating'] ?? '', 0, 4));

                        }
                        elseif (empty($response->json())) {
                            Notification::make()
                                ->title('No results found')
                                ->body('No results found for license plate ' . $formattedPlate)
                                ->send();

                        }
                    }),

                Forms\Components\Select::make('status')
                    ->required()
                    ->options(Status::class),

                Forms\Components\TextInput::make('model')
                    ->required()
                    ->visible(fn (Get $get): bool => !empty($get('license_plate')))
                    ->maxLength(255)
                    ->minLength(3),

                Forms\Components\Select::make('brand_id')
                    ->relationship("brand", "name")
                    ->visible(fn (Get $get): bool => !empty($get('license_plate')))
                    ->preload()
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('year')
                    ->visible(fn (Get $get): bool => !empty($get('license_plate')))
                    ->required(),

                // Admins can select a user, but regular users can't
                Forms\Components\Select::make('user_id')
                    ->required()
                    ->label('User')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->default(fn () => !Auth::user()->is_admin ? Auth::id() : null)
                    ->visible(fn () => Auth::user()->is_admin),
                    Hidden::make('user_id')
                    ->default(fn () => !Auth::user()->is_admin ? Auth::id() : null)
                    ->visible(fn () => !Auth::user()->is_admin),

                Forms\Components\Select::make('car_options')
                    ->visible(fn (Get $get): bool => !empty($get('license_plate')))
                    ->multiple()
                    ->relationship('options', 'name')
                    ->preload()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('license_plate')
                ->searchable(),
                Tables\Columns\TextColumn::make('model')
                ->searchable(),
                Tables\Columns\TextColumn::make('year')
                ->searchable(),
                Tables\Columns\TextColumn::make('user.name'),
                Tables\Columns\TextColumn::make('brand.name'),
                Tables\Columns\TextColumn::make('options.name')
                    ->label('Options')
                    ->placeholder('No options')
                    ->listWithLineBreaks(),
            ])
            ->filters([
                //
                Tables\Filters\SelectFilter::make('status')
                ->options(Status::class),
                Tables\Filters\SelectFilter::make('user_id')
                ->label('User')
                ->relationship('user', 'name'),
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
            'index' => Pages\ListCars::route('/'),
            'create' => Pages\CreateCar::route('/create'),
            'edit' => Pages\EditCar::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        // Restrict non-admin users to only see their own cars
        if (!Auth::user()->is_admin) {
            return $query->where('user_id', Auth::id());
        }

        return $query;
    }

    public static function canViewAny(): bool
    {
        return true;
    }

    public static function canView(Model $record): bool
    {
        return Auth::user()->is_admin || $record->user_id === Auth::id();
    }

    public static function canEdit(Model $record): bool
    {
        return Auth::user()->is_admin || $record->user_id === Auth::id();
    }

    public static function canDelete(Model $record): bool
    {
        // return Auth::user()->is_admin() || $record->user_id === Auth::id();
        return true;
    }

    public static function canCreate(): bool
    {
        return true;
    }
}
