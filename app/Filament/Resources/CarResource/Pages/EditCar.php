<?php

namespace App\Filament\Resources\CarResource\Pages;

use App\Filament\Resources\CarResource;
use App\Mail\CarInForMaintenance;
use App\Mail\CarReadyForPickupMail;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Mail;

class EditCar extends EditRecord
{
    protected static string $resource = CarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {

        $car = $this->record;


        // dd($car->status);
        if($car->status == 'done') {
            Mail::to($car->user->email)->send(new CarReadyForPickupMail($car));
        }
        elseif($car->status == 'maintenance') {
            Mail::to($car->user->email)->send(new CarInForMaintenance($car));
        }
    }
}
