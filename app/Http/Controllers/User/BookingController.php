<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function create($hotelId)
    {
        $hotel = Hotel::with('rooms')->findOrFail($hotelId);
        return view('User.hotel', compact('hotel'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'room_id' => 'required|exists:rooms,id',
            'room_quantity' => 'required|integer|min:1',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'guest_count' => 'required|integer|min:1',
        ]);

        $checkIn = \Carbon\Carbon::parse($validated['check_in'])->startOfDay();
        $checkOut = \Carbon\Carbon::parse($validated['check_out'])->startOfDay();
        $nights = (int) $checkOut->diff($checkIn)->days;
        
        if ($nights <= 0) {
            return back()->with('error', 'Check-out date must be after check-in date.');
        }
        
        $room = Room::where('hotel_id', $validated['hotel_id'])
                    ->where('id', $validated['room_id'])
                    ->first();

        if (!$room) {
            return back()->with('error', 'The selected room does not belong to this hotel.');
        }

        $quantity = $validated['room_quantity'];

        $maxCapacity = $room->capacity * $quantity;
        if ($validated['guest_count'] > $maxCapacity) {
            return back()->with('error', "The selected room(s) can only accommodate up to {$maxCapacity} guests. Please increase room quantity or change room type.");
        }

        if (!$room->isAvailable($validated['check_in'], $validated['check_out'], $quantity)) {
            return back()->with('error', "Sorry, {$room->name} does not have enough available rooms for the chosen dates.");
        }

        $subtotal = $room->price * $quantity * $nights;
        $tax = $subtotal * 0.06;
        $totalPrice = $subtotal + $tax;

        $bookingCode = 'BKG-' . strtoupper(Str::random(8));

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'room_id' => $room->id,
            'check_in' => $validated['check_in'],
            'check_out' => $validated['check_out'],
            'room_quantity' => $quantity,
            'guest_count' => $validated['guest_count'],
            'total_price' => $totalPrice,
            'status' => 'pending',
            'booking_code' => $bookingCode, 
        ]);

        return redirect()->route('user.payment', ['booking_id' => $booking->id])
            ->with('success', 'Booking initiated successfully. Please complete your payment.');
    }

    public function showPayment($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);

        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        $relatedBookings = Booking::with('room.hotel')
            ->where('booking_code', $booking->booking_code)
            ->get();

        $totalOrderPrice = $relatedBookings->sum('total_price');

        return view('User.payment', compact('booking', 'relatedBookings', 'totalOrderPrice'));
    }

    public function processPayment(Request $request, $bookingId)
    {
        $validated = $request->validate([
            'payment_method' => 'required|in:card,online_banking,e-wallet',
        ]);

        $booking = Booking::findOrFail($bookingId);

        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        Booking::where('booking_code', $booking->booking_code)
            ->where('user_id', Auth::id())
            ->update([
                'payment_method' => $validated['payment_method'],
                'payment_status' => 'paid', 
                'status' => 'confirmed',
            ]);

        return redirect()->route('user.history')->with('success', 'Payment successful! Your booking is confirmed.');
    }


    public function checkAvailability(Request $request)
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'quantity' => 'required|integer|min:1',
        ]);

        $room = Room::findOrFail($validated['room_id']);
        $availableQuantity = $room->availableQuantity($validated['check_in'], $validated['check_out']);
        $isAvailable = $availableQuantity >= $validated['quantity'];

        return response()->json([
            'available' => $isAvailable,
            'available_quantity' => $availableQuantity,
        ]);
    }
}