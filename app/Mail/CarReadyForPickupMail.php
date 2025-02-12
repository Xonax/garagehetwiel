<?php

namespace App\Mail;

use App\Models\Car;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CarReadyForPickupMail extends Mailable
{
    use SerializesModels;

    public $car;

    public function __construct(Car $car)
    {
        $this->car = $car;
    }

    public function build()
    {
        return $this->subject('Your Car is Ready for Pickup!')
            ->view('emails.car_ready_for_pickup')
            ->with(['car' => $this->car]);
    }
}
