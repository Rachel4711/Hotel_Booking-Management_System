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
            'selected_rooms' => 'required|array|min:1', 
            'selected_rooms.*' => 'exists:rooms,id',
            'room_quantities' => 'required|array',
            'room_quantities.*' => 'required|integer|min:1',
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
        
        // Verify all selected rooms belong to the requested hotel
        $hotelRoomIds = Room::where('hotel_id', $validated['hotel_id'])->pluck('id')->toArray();
        foreach ($validated['selected_rooms'] as $roomId) {
            if (!in_array($roomId, $hotelRoomIds)) {
                return back()->with('error', 'One or more selected rooms do not belong to this hotel.');
            }
        }
        
        $bookingCode = 'BKG-' . strtoupper(Str::random(8));
        $firstBookingId = null;

        foreach ($validated['selected_rooms'] as $roomId) {
            $room = Room::findOrFail($roomId);
            $quantity = $validated['room_quantities'][$roomId] ?? 1;
            
            if ($quantity <= 0) {
                return back()->with('error', "Invalid quantity for {$room->name}.");
            }

            if (!$room->isAvailable($validated['check_in'], $validated['check_out'], $quantity)) {
                return back()->with('error', "Sorry, {$room->name} does not have enough available rooms for the chosen dates.");
            }

            $totalPrice = $room->price * $quantity * $nights;

            $booking = Booking::create([
                'user_id' => Auth::id(),
                'room_id' => $roomId,
                'check_in' => $validated['check_in'],
                'check_out' => $validated['check_out'],
                'room_quantity' => $quantity,
                'guest_count' => $validated['guest_count'],
                'total_price' => $totalPrice,
                'status' => 'pending',
                'booking_code' => $bookingCode, 
            ]);

            if (!$firstBookingId) {
                $firstBookingId = $booking->id;
            }
        }

        return redirect()->route('user.payment', ['booking_id' => $firstBookingId])
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

    public function calculatePrice(Request $request)
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'quantity' => 'required|integer|min:1',
        ]);

        $room = Room::findOrFail($validated['room_id']);
        $checkIn = \Carbon\Carbon::parse($validated['check_in'])->startOfDay();
        $checkOut = \Carbon\Carbon::parse($validated['check_out'])->startOfDay();
        $nights = (int) $checkOut->diff($checkIn)->days;
        
        if ($nights <= 0) {
            $nights = 1;
        }
        
        $quantity = $validated['quantity'];

        $pricePerNight = $room->price;
        $totalPrice = $pricePerNight * $quantity * $nights;

        return response()->json([
            'price_per_night' => $pricePerNight,
            'nights' => $nights,
            'quantity' => $quantity,
            'total_price' => $totalPrice,
        ]);
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