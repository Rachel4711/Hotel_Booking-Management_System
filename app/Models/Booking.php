<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Booking extends Model
{
    protected $table = 'bookings';
    protected $fillable = [
        'booking_code',
        'user_id',
        'room_id',
        'check_in',
        'check_out',
        'room_quantity',
        'guest_count',
        'total_price',
        'status',
        'payment_status',
        'payment_method',
    ];

    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date',
        'total_price' => 'decimal:2',
    ];

    public static function generateCode(): string
    {
        do {
            $code = 'BKG-' . strtoupper(Str::random(8));
        } while (static::where('booking_code', $code)->exists());

        return $code;
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
