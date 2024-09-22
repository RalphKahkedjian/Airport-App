<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    protected $fillable =
    [
        'user_id',
        'ticket_id',
        'booking_date',
    ];
    // vise_verse the user-booking function in user model
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // each booking belong to one ticket 
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
