<?php

namespace App\Models;

use App\Mail\CarReadyForPickupMail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class Car extends Model
{
    protected $guarded = [
        'id'
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function options()
    {
        return $this->belongsToMany(Option::class, 'car_options');
    }
}
