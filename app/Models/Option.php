<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    //
    protected $fillable = [
        'name',
    ];

    public function cars()
    {
        return $this->belongsToMany(Car::class, 'car_options');
    }
}

