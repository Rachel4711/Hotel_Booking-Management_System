@extends('Layouts.user')

@section('title', $hotel->name . ' - Booking')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/booking.css') }}">
@endpush

@php
    $getCoverImage = function ($hotel) {
        $coverImage = $hotel->cover_image ?? $hotel->image_url ?? '';
        if ($coverImage === '') return 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=900&q=80';
        if (filter_var($coverImage, FILTER_VALIDATE_URL)) return $coverImage;
        return route('photo.show', $coverImage);
    };
@endphp

@section('content')
<div class="booking-container">
    <div style="margin-bottom: 40px;">
        <div style="display: flex; gap: 30px; margin-bottom: 30px;">
            <div style="flex: 1; min-width: 400px;">
                <img id="mainImage" src="{{ $getCoverImage($hotel) }}" alt="{{ $hotel->name }}" class="hotel-img-lg">
            </div>

            <div style="flex: 1;">
                <h1 class="page-title">{{ $hotel->name }}</h1>
                <p class="text-muted">📍 {{ $hotel->address }}</p>
                <p class="text-muted" style="line-height: 1.6;">
                    <strong>Description:</strong> {{ $hotel->description }}
                </p>
                <p class="text-muted" style="line-height: 1.6;">
                    <strong>Policies:</strong> {{ $hotel->policies }}
                </p>

                @if($hotel->images && count($hotel->images) > 0)
                <div style="display: flex; gap: 10px; margin-top: 20px;">
                    @foreach($hotel->images as $image)
                    <img src="{{ filter_var($image, FILTER_VALIDATE_URL) ? $image : route('photo.show', $image) }}" 
                         alt="Hotel image" class="gallery-img"
                         onmouseover="document.getElementById('mainImage').src=this.src;">
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="info-box" style="padding: 40px; margin-bottom: 40px;">
        <h2 class="section-title" style="font-size: 24px;">Select Your Room</h2>

        <form id="bookingForm" method="POST" action="{{ route('user.booking.store') }}" style="margin-bottom: 30px;">
            @csrf
            <input type="hidden" name="hotel_id" value="{{ $hotel->id }}">
            <input type="hidden" name="room_quantity" id="finalQuantity" value="1">

            <div class="grid-auto-cols" style="margin-bottom: 30px;">
                <div>
                    <label class="form-label">Check-in Date</label>
                    <input type="date" name="check_in" id="checkIn" required class="form-control" onchange="updateAvailability()">
                </div>
                <div>
                    <label class="form-label">Check-out Date</label>
                    <input type="date" name="check_out" id="checkOut" required class="form-control" onchange="updateAvailability()">
                </div>
                <div>
                    <label class="form-label">Number of Guests</label>
                    <input type="number" name="guest_count" id="guestCount" value="1" min="1" required class="form-control">
                </div>
            </div>

            <div id="roomsContainer" class="room-grid">
                @forelse($hotel->rooms as $room)
                <div class="room-card">
                    <div class="flex-between" style="align-items: start; margin-bottom: 15px;">
                        <div>
                            <h3 class="sub-title">{{ $room->name }}</h3>
                            <p class="label-text">Type: <strong style="color:#666;">{{ ucfirst($room->type) }}</strong></p>
                        </div>
                        <span style="background: #f0ebe7; padding: 6px 12px; border-radius: 6px; font-size: 12px; color: #666;">
                            👥 {{ $room->capacity }}
                        </span>
                    </div>

                    <div class="info-box" style="margin-bottom: 15px;">
                        <p class="label-text" style="color: #666;">Price per night</p>
                        <p class="price-large">RM {{ number_format($room->price, 2) }}</p>
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label class="form-label" style="font-size: 14px;">Quantity</label>
                        <input type="number" class="room-quantity form-control" data-room-id="{{ $room->id }}" value="1" min="1" max="{{ $room->total_rooms }}" onchange="updateAvailability()">
                    </div>

                    <div class="highlight-box" style="margin-bottom: 15px;">
                        <p class="text-muted" style="margin:0; font-size:13px;">
                            Availability: <span class="availability-status" data-room-id="{{ $room->id }}" style="font-weight: 600;">
                                {{ $room->total_rooms }} available
                            </span>
                        </p>
                    </div>

                    <label class="radio-label">
                        <input type="radio" name="room_id" value="{{ $room->id }}" required style="width: 18px; height: 18px;">
                        <span style="color: #333; font-weight: 600;">Select this room</span>
                    </label>
                </div>
                @empty
                <div style="grid-column: 1 / -1; padding: 40px; text-align: center; background: white; border-radius: 12px; border: 2px dashed #ddd;">
                    <p style="color: #999; font-size: 16px; margin: 0;">No rooms available for this hotel</p>
                </div>
                @endforelse
            </div>

            <div style="margin-top: 30px; background: white; padding: 20px; border-radius: 12px; border: 2px solid #e8e0d8;">
                <div class="flex-between" style="flex-wrap: wrap; gap: 20px;">
                    <div id="priceSummary" style="font-size: 16px; color: #666;">
                        <p style="margin: 0; color: #999;">Please select dates and room to see pricing</p>
                    </div>
                    <button type="submit" class="btn-primary" style="width: auto; padding: 12px 30px;">
                        Proceed to Summary
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function updateAvailability() {
    const checkIn = document.getElementById('checkIn').value;
    const checkOut = document.getElementById('checkOut').value;

    if (!checkIn || !checkOut) return;

    document.querySelectorAll('.room-quantity').forEach(input => {
        const roomId = input.dataset.roomId;
        const quantity = input.value;

        fetch('{{ route("user.booking.check-availability") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                room_id: roomId,
                check_in: checkIn,
                check_out: checkOut,
                quantity: quantity,
            })
        })
        .then(res => res.json())
        .then(data => {
            const status = document.querySelector(`.availability-status[data-room-id="${roomId}"]`);
            if (status) {
                status.textContent = data.available ? 
                    `${data.available_quantity} available` : 
                    `Only ${data.available_quantity} available`;
                status.style.color = data.available ? '#27a745' : '#dc3545';
            }
        });
    });

    updatePriceSummary();
}

function updatePriceSummary() {
    const checkIn = document.getElementById('checkIn').value;
    const checkOut = document.getElementById('checkOut').value;
    const roomRadio = document.querySelector('input[name="room_id"]:checked');

    if (!checkIn || !checkOut || !roomRadio) {
        document.getElementById('priceSummary').innerHTML = 
            '<p style="margin: 0; color: #999;">Please select dates and room to see pricing</p>';
        return;
    }

    const roomId = roomRadio.value;
    const quantityInput = document.querySelector(`.room-quantity[data-room-id="${roomId}"]`);
    const quantity = quantityInput.value;

    // Fix: Set the hidden input so the controller receives the correct room quantity
    document.getElementById('finalQuantity').value = quantity;

    fetch('{{ route("user.booking.calculate-price") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            room_id: roomId,
            check_in: checkIn,
            check_out: checkOut,
            quantity: quantity,
        })
    })
    .then(res => res.json())
    .then(data => {
        const total = data.total_price;
        document.getElementById('priceSummary').innerHTML = `
            <p style="margin: 0; color: #666;">
                <strong>${data.nights} nights</strong> × 
                <strong>${data.quantity} room(s)</strong> × 
                <strong>RM ${data.price_per_night.toFixed(2)}</strong> = 
                <span class="price-large">RM ${total.toFixed(2)}</span>
            </p>
        `;
    });
}

const today = new Date().toISOString().split('T')[0];
document.getElementById('checkIn').setAttribute('min', today);
document.getElementById('checkOut').setAttribute('min', today);

document.getElementById('checkIn').addEventListener('change', function() {
    document.getElementById('checkOut').setAttribute('min', this.value);
});

document.querySelectorAll('input[name="room_id"]').forEach(radio => {
    radio.addEventListener('change', updatePriceSummary);
});
</script>
@endpush
@endsection