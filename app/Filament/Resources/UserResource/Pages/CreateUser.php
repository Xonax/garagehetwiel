<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Mail\Mailer;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function afterCreate()
    {
        $user = $this->record;

        Log::info("Attempting to send verification email to: " . $user->email);

        try {
            // ✅ Send Laravel's built-in verification email
            event(new Registered($user));

            Log::info("✅ Verification email sent to: " . $user->email);
        } catch (\Exception $e) {
            Log::error("❌ Verification email failed: " . $e->getMessage());
        }
    }
}
