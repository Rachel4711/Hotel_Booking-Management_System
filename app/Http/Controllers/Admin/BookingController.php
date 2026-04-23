<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BookingController extends Controller
{
    private const STATUSES = ['pending', 'confirmed', 'cancelled'];
    private const PAYMENT_STATUSES = ['unpaid', 'paid'];
    private const PAYMENT_METHODS = ['card', 'online_banking', 'e-wallet'];

    public function index(Request $request): View
    {
        $filters = [
            'search'         => trim((string) $request->query('search', '')),
            'status'         => in_array($request->query('status'), self::STATUSES, true) ? $request->query('status') : '',
            'payment_status' => in_array($request->query('payment_status'), self::PAYMENT_STATUSES, true) ? $request->query('payment_status') : '',
            'payment_method' => in_array($request->query('payment_method'), self::PAYMENT_METHODS, true) ? $request->query('payment_method') : '',
            'check_in'       => $request->query('check_in', ''),
            'check_out'      => $request->query('check_out', ''),
        ];

        $bookings = Booking::query()
            ->with(['user', 'room.hotel'])
            ->when($filters['search'] !== '', function ($q) use ($filters) {
                $q->where('booking_code', 'like', '%' . $filters['search'] . '%');
            })
            ->when($filters['status'] !== '', fn ($q) => $q->where('status', $filters['status']))
            ->when($filters['payment_status'] !== '', fn ($q) => $q->where('payment_status', $filters['payment_status']))
            ->when($filters['payment_method'] !== '', fn ($q) => $q->where('payment_method', $filters['payment_method']))
            ->when($filters['check_in'] !== '', fn ($q) => $q->whereDate('check_in', '>=', $filters['check_in']))
            ->when($filters['check_out'] !== '', fn ($q) => $q->whereDate('check_out', '<=', $filters['check_out']))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('Admin.Booking.index', [
            'bookings'        => $bookings,
            'filters'         => $filters,
            'statuses'        => self::STATUSES,
            'paymentStatuses' => self::PAYMENT_STATUSES,
            'paymentMethods'  => self::PAYMENT_METHODS,
        ]);
    }

    public function create(): View
    {
        return view('Admin.Booking.create', [
            'users'           => User::where('role', 'user')->orderBy('name')->get(['id', 'name', 'email']),
            'hotels'          => Hotel::with(['rooms' => fn ($q) => $q->orderBy('name')])->orderBy('name')->get(),
            'statuses'        => self::STATUSES,
            'paymentStatuses' => self::PAYMENT_STATUSES,
            'paymentMethods'  => self::PAYMENT_METHODS,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateBooking($request);

        $room = Room::findOrFail($validated['room_id']);
        $nights = $this->nightsBetween($validated['check_in'], $validated['check_out']);
        $subtotal = $room->price * $validated['room_quantity'] * $nights;
        $totalPrice = $subtotal * 1.06;

        Booking::create([
            'booking_code'   => Booking::generateCode(),
            'user_id'        => $validated['user_id'],
            'room_id'        => $validated['room_id'],
            'check_in'       => $validated['check_in'],
            'check_out'      => $validated['check_out'],
            'room_quantity'  => $validated['room_quantity'],
            'guest_count'    => $validated['guest_count'],
            'total_price'    => $totalPrice,
            'status'         => $validated['status'],
            'payment_status' => $validated['payment_status'],
            'payment_method' => $validated['payment_method'] ?? null,
        ]);

        return redirect()->route('bookings.index')->with('success', 'Booking created successfully.');
    }

    public function show(Booking $booking): View
    {
        $booking->load(['user', 'room.hotel']);

        $nights = $this->nightsBetween($booking->check_in, $booking->check_out);

        return view('Admin.Booking.show', compact('booking', 'nights'));
    }

    public function edit(Booking $booking): View
    {
        $booking->load(['user', 'room.hotel']);

        return view('Admin.Booking.edit', [
            'booking'         => $booking,
            'users'           => User::where('role', 'user')->orderBy('name')->get(['id', 'name', 'email']),
            'hotels'          => Hotel::with(['rooms' => fn ($q) => $q->orderBy('name')])->orderBy('name')->get(),
            'statuses'        => self::STATUSES,
            'paymentStatuses' => self::PAYMENT_STATUSES,
            'paymentMethods'  => self::PAYMENT_METHODS,
        ]);
    }

    public function update(Request $request, Booking $booking): RedirectResponse
    {
        $validated = $this->validateBooking($request);

        $room = Room::findOrFail($validated['room_id']);
        $nights = $this->nightsBetween($validated['check_in'], $validated['check_out']);
        $subtotal = $room->price * $validated['room_quantity'] * $nights;
        $totalPrice = $subtotal * 1.06;

        $booking->update([
            'user_id'        => $validated['user_id'],
            'room_id'        => $validated['room_id'],
            'check_in'       => $validated['check_in'],
            'check_out'      => $validated['check_out'],
            'room_quantity'  => $validated['room_quantity'],
            'guest_count'    => $validated['guest_count'],
            'total_price'    => $totalPrice,
            'status'         => $validated['status'],
            'payment_status' => $validated['payment_status'],
            'payment_method' => $validated['payment_method'] ?? null,
        ]);

        return redirect()->route('bookings.index')->with('success', 'Booking updated successfully.');
    }

    public function destroy(Booking $booking): RedirectResponse
    {
        $booking->delete();

        return redirect()->route('bookings.index')->with('success', 'Booking deleted successfully.');
    }

    private function validateBooking(Request $request): array
    {
        return $request->validate([
            'user_id'        => ['required', 'exists:users,id'],
            'room_id'        => ['required', 'exists:rooms,id'],
            'check_in'       => ['required', 'date'],
            'check_out'      => ['required', 'date', 'after:check_in'],
            'room_quantity'  => ['required', 'integer', 'min:1'],
            'guest_count'    => ['required', 'integer', 'min:1'],
            'status'         => ['required', Rule::in(self::STATUSES)],
            'payment_status' => ['required', Rule::in(self::PAYMENT_STATUSES)],
            'payment_method' => ['nullable', Rule::in(self::PAYMENT_METHODS)],
        ]);
    }

    private function nightsBetween($checkIn, $checkOut): int
    {
        $in = Carbon::parse($checkIn)->startOfDay();
        $out = Carbon::parse($checkOut)->startOfDay();
        return max(1, (int) $out->diffInDays($in));
    }
}
