@extends('Layouts.user')

@section('title', $hotel->name . ' - Hotel Details & Booking')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/hotel.css') }}">
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
<div class="hotel-page-container">
    
    <div class="hotel-header-wrapper">
        <div class="hotel-image-col">
            <img id="mainImage" src="{{ $getCoverImage($hotel) }}" alt="{{ $hotel->name }}" class="main-img">
            
            @if($hotel->images && count($hotel->images) > 0)
            <div class="gallery-row">
                @foreach($hotel->images as $image)
                {{-- 修复 1：相册小图也应用图片解析逻辑 --}}
                <img src="{{ filter_var($image, FILTER_VALIDATE_URL) ? $image : route('photo.show', $image) }}" alt="Gallery" class="gallery-thumb" onmouseover="document.getElementById('mainImage').src=this.src;">
                @endforeach
            </div>
            @endif
        </div>

        <div class="hotel-info-col">
            <h1 class="hotel-title">{{ $hotel->name }}</h1>
            <p class="hotel-location">📍 {{ $hotel->address }}</p>
            <div class="hotel-desc-box">
                <p><strong>Description:</strong> {{ $hotel->description }}</p>
                <p><strong>Policies:</strong> {{ $hotel->policies }}</p>
            </div>
        </div>
    </div>

    <form id="bookingForm" method="POST" action="{{ route('user.booking.store') }}" class="booking-form-layout">
        @csrf
        <input type="hidden" name="hotel_id" value="{{ $hotel->id }}">

        <div class="booking-left-col">
            <h2 class="section-heading">Select Dates & Rooms</h2>

            {{-- 修复 2：增加错误提示框，这样如果提交失败，你就能看到后端报了什么错，而不是“没反应” --}}
            @if ($errors->any())
                <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
                    <ul style="margin: 0; padding-left: 20px; font-weight: bold;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('error'))
                <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #f5c6cb; font-weight: bold;">
                    {{ session('error') }}
                </div>
            @endif
            
            <div class="dates-grid">
                <div class="input-group">
                    <label>Check-in Date</label>
                    <input type="date" name="check_in" id="checkIn" required class="form-control" onchange="updateLiveSummary()">
                </div>
                <div class="input-group">
                    <label>Check-out Date</label>
                    <input type="date" name="check_out" id="checkOut" required class="form-control" onchange="updateLiveSummary()">
                </div>
                <div class="input-group">
                    <label>Guests</label>
                    <input type="number" name="guest_count" value="1" min="1" required class="form-control">
                </div>
            </div>

            <div class="rooms-grid">
                @forelse($hotel->rooms as $room)
                <div class="room-card">
                    {{-- Room Image with fallback --}}
                    <div class="room-image-wrapper" style="margin-bottom: 15px;">
                        <img src="{{ filter_var($room->image, FILTER_VALIDATE_URL) ? $room->image : route('photo.show', $room->image) }}" 
                            alt="{{ $room->name }}" 
                            style="width: 100%; height: 180px; object-fit: cover; border-radius: 8px;"
                            onerror="this.src='https://images.unsplash.com/photo-1631049307261-da0ec440d628?auto=format&fit=crop&w=500&q=60';">
                    </div>
                    <div class="room-header-flex">
                        <div>
                            <h3 class="room-name">{{ $room->name }}</h3>
                            <span class="room-type-badge">{{ ucfirst($room->type) }}</span>
                        </div>
                        <span class="room-capacity">👥 {{ $room->capacity }} Guests</span>
                    </div>

                    <div class="room-price-box">
                        <span class="price-val">RM {{ number_format($room->price, 2) }}</span> / night
                    </div>

                    <div class="room-actions">
                        <label class="checkbox-label">
                            <input type="checkbox" name="selected_rooms[]" value="{{ $room->id }}" class="room-checkbox" 
                                   data-name="{{ $room->name }}" 
                                   data-price="{{ $room->price }}" 
                                   onchange="updateLiveSummary()">
                            <span>Select Room</span>
                        </label>
                        
                        <div class="qty-group">
                            <label>Qty:</label>
                            <input type="number" name="room_quantities[{{ $room->id }}]" class="room-quantity form-control" 
                                   data-room-id="{{ $room->id }}" value="1" min="1" max="{{ $room->total_rooms }}" 
                                   onchange="updateLiveSummary()">
                        </div>
                    </div>
                    
                    <p class="availability-text">Available: <strong>{{ $room->total_rooms }}</strong></p>
                </div>
                @empty
                <div class="empty-state">No rooms available for this hotel.</div>
                @endforelse
            </div>
        </div>

        <div class="booking-right-col">
            <div class="sticky-summary-card">
                <h3 class="summary-title">Booking Summary</h3>
                <div id="liveSummaryContent" class="summary-body">
                    <p class="text-muted text-center" style="color: #888;">Please select dates and at least one room to see pricing.</p>
                </div>
                <button type="button" id="btnProceed" class="btn-primary mt-3" disabled onclick="submitBookingForm()">Proceed to Payment</button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Helper function to format date as DD/MM/YYYY
    function formatDate(dateString) {
        const [year, month, day] = dateString.split('-');
        return `${day}/${month}/${year}`;
    }

    // Helper function to calculate nights between two dates
    function calculateNights(checkInStr, checkOutStr) {
        // Parse YYYY-MM-DD format directly
        const [inYear, inMonth, inDay] = checkInStr.split('-').map(Number);
        const [outYear, outMonth, outDay] = checkOutStr.split('-').map(Number);
        
        // Create dates at midnight UTC to avoid timezone issues
        const checkIn = new Date(Date.UTC(inYear, inMonth - 1, inDay));
        const checkOut = new Date(Date.UTC(outYear, outMonth - 1, outDay));
        
        // Calculate difference in days
        const diffTime = checkOut - checkIn;
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        
        return diffDays;
    }

    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('checkIn').setAttribute('min', today);
    document.getElementById('checkOut').setAttribute('min', today);

    document.getElementById('checkIn').addEventListener('change', function() {
        document.getElementById('checkOut').setAttribute('min', this.value);
        updateLiveSummary();
    });

    function updateLiveSummary() {
        const checkIn = document.getElementById('checkIn').value;
        const checkOut = document.getElementById('checkOut').value;
        const selectedCheckboxes = document.querySelectorAll('.room-checkbox:checked');
        const summaryContent = document.getElementById('liveSummaryContent');
        const btnProceed = document.getElementById('btnProceed');

        if (!checkIn || !checkOut || selectedCheckboxes.length === 0) {
            summaryContent.innerHTML = '<p class="text-muted text-center" style="color: #888;">Please select dates and at least one room to see pricing.</p>';
            btnProceed.disabled = true;
            return;
        }

        // Calculate nights using simple comparison
        const nights = calculateNights(checkIn, checkOut);

        if (nights <= 0) {
            summaryContent.innerHTML = '<p style="color:#dc3545; font-weight:bold;" class="text-center">Check-out must be after Check-in.</p>';
            btnProceed.disabled = true;
            return;
        }

        let subtotal = 0;
        let html = `<div class="summary-nights text-muted mb-2" style="color:#666;">
            <strong>${formatDate(checkIn)}</strong> → <strong>${formatDate(checkOut)}</strong><br>
            ${nights} Night(s) Stay
        </div>`;
        html += `<div class="summary-items">`;

        selectedCheckboxes.forEach(cb => {
            const roomId = cb.value;
            const name = cb.dataset.name;
            const price = parseFloat(cb.dataset.price);
            const qtyInput = document.querySelector(`.room-quantity[data-room-id="${roomId}"]`);
            const qty = parseInt(qtyInput.value) || 1;

            const roomTotal = price * qty * nights;
            subtotal += roomTotal;

            html += `
                <div class="summary-item-row" style="display:flex; justify-content:space-between; margin-bottom:8px;">
                    <span class="item-name">${name} (x${qty})</span>
                    <span class="item-price" style="font-weight:bold;">RM ${roomTotal.toFixed(2)}</span>
                </div>
            `;
        });

        html += `</div>`; 

        const tax = subtotal * 0.06;
        const total = subtotal + tax;

        html += `
            <div class="summary-totals mt-3" style="border-top: 1px solid #eee; padding-top: 15px; margin-top: 15px;">
                <div class="summary-item-row text-muted" style="display:flex; justify-content:space-between; color:#666;">
                    <span>Subtotal</span>
                    <span>RM ${subtotal.toFixed(2)}</span>
                </div>
                <div class="summary-item-row text-muted" style="display:flex; justify-content:space-between; color:#666; margin-bottom: 10px;">
                    <span>Tax (6%)</span>
                    <span>RM ${tax.toFixed(2)}</span>
                </div>
                <div class="summary-item-row fw-bold total-row" style="display:flex; justify-content:space-between; font-size: 18px; margin-top: 10px; color:#333;">
                    <span>Total</span>
                    <span class="total-val" style="color: #7e6552; font-size: 22px; font-weight: bold;">RM ${total.toFixed(2)}</span>
                </div>
            </div>
        `;

        summaryContent.innerHTML = html;
        btnProceed.disabled = false; // 启用按钮
    }

    function submitBookingForm() {
        const form = document.getElementById('bookingForm');
        if (form.checkValidity()) {
            form.submit();
        } else {
            form.reportValidity();
        }
    }
</script>
@endpush
@endsection