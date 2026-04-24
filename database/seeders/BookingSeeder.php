<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::query()->where('role', 'user')->get();
        $rooms = Room::all();

        if ($users->isEmpty() || $rooms->isEmpty()) {
            return;
        }

        $today = Carbon::today();
        $paymentMethods = ['card', 'online_banking', 'e-wallet'];

        foreach ($rooms as $room) {
            // Keep booking volume realistic while still covering many hotels.
            $bookingCount = rand(0, 2);

            for ($i = 0; $i < $bookingCount; $i++) {
                $checkIn = $today->copy()->addDays(rand(-30, 45));
                $nights = rand(1, 5);
                $checkOut = $checkIn->copy()->addDays($nights);

                $maxQuantity = max(1, min(3, (int) $room->total_rooms));
                $roomQuantity = rand(1, $maxQuantity);

                $maxGuests = max($roomQuantity, (int) $room->capacity * $roomQuantity);
                $guestCount = rand($roomQuantity, $maxGuests);

                $status = 'pending';
                $paymentStatus = 'unpaid';
                $paymentMethod = null;

                if ($checkOut->isPast()) {
                    $status = 'confirmed';
                    $paymentStatus = 'paid';
                } elseif ($checkIn->greaterThan($today->copy()->addDays(3)) && rand(1, 100) <= 40) {
                    $status = 'confirmed';
                    $paymentStatus = 'paid';
                } elseif (rand(1, 100) <= 10) {
                    $status = 'cancelled';
                }

                if ($paymentStatus === 'paid') {
                    $paymentMethod = $paymentMethods[array_rand($paymentMethods)];
                }

                Booking::create([
                    'booking_code' => Booking::generateCode(),
                    'user_id' => $users->random()->id,
                    'room_id' => $room->id,
                    'check_in' => $checkIn->toDateString(),
                    'check_out' => $checkOut->toDateString(),
                    'room_quantity' => $roomQuantity,
                    'guest_count' => $guestCount,
                    'total_price' => $room->price * $nights * $roomQuantity,
                    'status' => $status,
                    'payment_status' => $paymentStatus,
                    'payment_method' => $paymentMethod,
                ]);
            }
        }
    }
}