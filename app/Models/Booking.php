<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'bookings';
    protected $fillable = [
        'booking_code',
        'user_id',
        'room_id',
        'check_in',
        'check_out',
        'total_price',
        'status',
        'payment_status',
        'payment_method',
    ];

    // auto generate booking code
    protected static function booted()
    {
        static::creating(function ($booking) {
            $booking->booking_code = 'BK' . strtoupper(uniqid());
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
