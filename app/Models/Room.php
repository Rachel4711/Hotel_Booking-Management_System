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
        'total_rooms',
        'image',
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
    public function availableQuantity($checkIn, $checkOut)
    {
        $bookedRooms = $this->bookings()
            ->whereIn('status', ['pending', 'confirmed'])
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->where('check_in', '<', $checkOut)
                      ->where('check_out', '>', $checkIn);
            })
            ->sum('room_quantity'); 

        return $this->total_rooms - $bookedRooms;
    }

    public function isAvailable($checkIn, $checkOut, $requestedQuantity = 1)
    {
        return $this->availableQuantity($checkIn, $checkOut) >= $requestedQuantity;
    }
}
