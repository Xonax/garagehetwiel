<?php

namespace App\Mail;

use App\Models\Car;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CarInForMaintenance extends Mailable
{
    use Queueable, SerializesModels;

    public $car;
    public function __construct(Car $car)
    {
        $this->car = $car;
    }

    public function build()
    {
        return $this->subject('Your Car is Ready for Pickup!')
            ->view('emails.car_in_for_maintenance')
            ->with(['car' => $this->car]);
    }
}
