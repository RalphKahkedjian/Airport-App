<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;
    protected $fillable =
    [
        'departure',
        'destination',
        'flight_number',
        'seat_number',
        'price',
        'departure_time',
        'arrival_time',
        'status',
    ];

    // one ticket has many bookings  
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
