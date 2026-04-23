<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'user')->get();
        $rooms = Room::with('hotel')->get();

        if ($users->isEmpty() || $rooms->isEmpty()) {
            return;
        }

        // [status, payment_status, payment_method, check_in_offset_days, nights]
        // Negative offset = past stay; positive = upcoming; 0 = starting today.
        $scenarios = [
            ['confirmed', 'paid',   'card',           -40, 3],  // past stay, paid by card
            ['confirmed', 'paid',   'online_banking', -14, 2],  // recent past, online banking
            ['confirmed', 'paid',   'e-wallet',       -7,  4],  // last week, e-wallet
            ['confirmed', 'paid',   'card',            5,  3],  // upcoming, paid
            ['confirmed', 'paid',   'online_banking', 21,  5],  // future, paid
            ['pending',   'unpaid', null,              3,  2],  // upcoming, awaiting payment
            ['pending',   'unpaid', null,             10,  1],  // upcoming short stay
            ['pending',   'paid',   'e-wallet',       14,  3],  // paid but admin not yet confirmed
            ['cancelled', 'unpaid', null,             -5,  2],  // cancelled before arrival
            ['cancelled', 'paid',   'card',            8,  4],  // paid then cancelled (refund case)
        ];

        foreach ($scenarios as $i => [$status, $paymentStatus, $paymentMethod, $offsetDays, $nights]) {
            $user = $users[$i % $users->count()];
            $room = $rooms[$i % $rooms->count()];

            $roomQuantity = rand(1, min(2, max(1, (int) $room->total_rooms)));
            $guestCount   = min($room->capacity * $roomQuantity, max(1, $room->capacity * $roomQuantity - rand(0, 1)));

            $checkIn  = Carbon::today()->addDays($offsetDays);
            $checkOut = (clone $checkIn)->addDays($nights);

            $subtotal   = $room->price * $roomQuantity * $nights;
            $totalPrice = round($subtotal * 1.06, 2);

            Booking::create([
                'booking_code'   => Booking::generateCode(),
                'user_id'        => $user->id,
                'room_id'        => $room->id,
                'check_in'       => $checkIn->toDateString(),
                'check_out'      => $checkOut->toDateString(),
                'room_quantity'  => $roomQuantity,
                'guest_count'    => $guestCount,
                'total_price'    => $totalPrice,
                'status'         => $status,
                'payment_status' => $paymentStatus,
                'payment_method' => $paymentMethod,
            ]);
        }
    }
}
