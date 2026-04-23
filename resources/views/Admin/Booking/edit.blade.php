@extends('Layouts.admin')
@section('content')

<style>
    .form-field { display: flex; flex-direction: column; gap: 6px; }
    .form-field label { font-size: 13px; font-weight: 600; color: #4e3c30; }
    .form-field input, .form-field select {
        padding: 10px 12px;
        border: 1px solid #d8c9bd;
        border-radius: 8px;
        font-size: 14px;
        background: #fff;
    }
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }
    .span-2 { grid-column: span 2; }
    @media (max-width: 720px) { .form-grid { grid-template-columns: 1fr; } .span-2 { grid-column: auto; } }
    .code-cell { font-family: Consolas, Menlo, monospace; color: #4e3c30; }
</style>

<div class="page-shell">
    <a href="{{ route('bookings.index') }}" class="hotel-back-link">
        <i class="uil uil-arrow-left"></i> Back to Bookings
    </a>

    <div class="page-header">
        <div>
            <h1 class="page-title">Edit Booking <span class="code-cell">{{ $booking->booking_code }}</span></h1>
            <p class="page-subtitle">Update stay, customer, room, and payment details.</p>
        </div>
    </div>

    <section class="panel-section">
        <form method="POST" action="{{ route('bookings.update', $booking) }}">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="form-field">
                    <label for="user_id">Customer</label>
                    <select id="user_id" name="user_id" required>
                        <option value="">— Select a customer —</option>
                        @foreach ($users as $u)
                            <option value="{{ $u->id }}"
                                {{ (string) old('user_id', $booking->user_id) === (string) $u->id ? 'selected' : '' }}>
                                {{ $u->name }} ({{ $u->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-field">
                    <label for="hotel_picker">Hotel</label>
                    <select id="hotel_picker" required>
                        <option value="">— Select a hotel —</option>
                        @foreach ($hotels as $h)
                            <option value="{{ $h->id }}">{{ $h->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-field span-2">
                    <label for="room_id">Room</label>
                    <select id="room_id" name="room_id" required>
                        <option value="">— Select a room —</option>
                    </select>
                </div>

                <div class="form-field">
                    <label for="check_in">Check-in</label>
                    <input id="check_in" type="date" name="check_in"
                           value="{{ old('check_in', \Carbon\Carbon::parse($booking->check_in)->format('Y-m-d')) }}" required>
                </div>

                <div class="form-field">
                    <label for="check_out">Check-out</label>
                    <input id="check_out" type="date" name="check_out"
                           value="{{ old('check_out', \Carbon\Carbon::parse($booking->check_out)->format('Y-m-d')) }}" required>
                </div>

                <div class="form-field">
                    <label for="room_quantity">Room Quantity</label>
                    <input id="room_quantity" type="number" min="1" name="room_quantity"
                           value="{{ old('room_quantity', $booking->room_quantity) }}" required>
                </div>

                <div class="form-field">
                    <label for="guest_count">Guest Count</label>
                    <input id="guest_count" type="number" min="1" name="guest_count"
                           value="{{ old('guest_count', $booking->guest_count) }}" required>
                </div>

                <div class="form-field">
                    <label for="status">Status</label>
                    <select id="status" name="status" required>
                        @foreach ($statuses as $s)
                            <option value="{{ $s }}" {{ old('status', $booking->status) === $s ? 'selected' : '' }}>
                                {{ ucfirst($s) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-field">
                    <label for="payment_status">Payment Status</label>
                    <select id="payment_status" name="payment_status" required>
                        @foreach ($paymentStatuses as $p)
                            <option value="{{ $p }}" {{ old('payment_status', $booking->payment_status) === $p ? 'selected' : '' }}>
                                {{ ucfirst($p) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-field">
                    <label for="payment_method">Payment Method</label>
                    <select id="payment_method" name="payment_method">
                        <option value="">— None —</option>
                        @foreach ($paymentMethods as $m)
                            <option value="{{ $m }}" {{ old('payment_method', $booking->payment_method) === $m ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $m)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="hotel-form-actions" style="margin-top: 20px; display: flex; gap: 10px;">
                <button type="submit" class="form-action primary">Save Changes</button>
                <a href="{{ route('bookings.index') }}" class="form-action secondary">Cancel</a>
            </div>
        </form>
    </section>
</div>

@php
    $roomsByHotel = $hotels->mapWithKeys(fn ($h) => [
        $h->id => $h->rooms->map(fn ($r) => [
            'id' => $r->id,
            'name' => $r->name,
            'type' => $r->type,
            'price' => $r->price,
        ])->values(),
    ]);
    $initialHotelId = (string) ($booking->room->hotel_id ?? '');
    $initialRoomId  = (string) old('room_id', $booking->room_id);
@endphp
<script>
    const roomsByHotel = @json($roomsByHotel);

    const hotelPicker = document.getElementById('hotel_picker');
    const roomSelect = document.getElementById('room_id');
    const initialHotelId = @json($initialHotelId);
    const initialRoomId = @json($initialRoomId);

    function populateRooms(hotelId, selected = null) {
        roomSelect.innerHTML = '';
        const rooms = roomsByHotel[hotelId] || [];
        if (!rooms.length) {
            roomSelect.innerHTML = '<option value="">No rooms available</option>';
            return;
        }
        const placeholder = document.createElement('option');
        placeholder.value = '';
        placeholder.textContent = '— Select a room —';
        roomSelect.appendChild(placeholder);
        rooms.forEach(r => {
            const opt = document.createElement('option');
            opt.value = r.id;
            opt.textContent = `${r.name} (${r.type} · RM ${Number(r.price).toFixed(2)})`;
            if (selected && String(selected) === String(r.id)) opt.selected = true;
            roomSelect.appendChild(opt);
        });
    }

    hotelPicker.addEventListener('change', e => populateRooms(e.target.value));

    // Preselect hotel & room from the booking (or old() on validation error)
    if (initialRoomId) {
        let matchedHotel = initialHotelId;
        if (!matchedHotel) {
            for (const [hotelId, rooms] of Object.entries(roomsByHotel)) {
                if (rooms.some(r => String(r.id) === String(initialRoomId))) {
                    matchedHotel = hotelId;
                    break;
                }
            }
        }
        if (matchedHotel) {
            hotelPicker.value = matchedHotel;
            populateRooms(matchedHotel, initialRoomId);
        }
    } else if (initialHotelId) {
        hotelPicker.value = initialHotelId;
        populateRooms(initialHotelId);
    }
</script>

@endsection
