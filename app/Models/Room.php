<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $table = 'rooms';
    protected $fillable = [
        'hotel_id',
        'name',
        'type',
        'capacity',
        'price',
        'status',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // room availability logic
    public function isAvailable($checkIn, $checkOut)
    {
        return !$this->bookings()
            ->where('status', 'confirmed')
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->where('check_in', '<', $checkOut)
                    ->where('check_out', '>', $checkIn);
            })
            ->exists();
    }
}
