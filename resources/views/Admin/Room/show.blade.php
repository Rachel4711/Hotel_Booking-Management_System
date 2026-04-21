@extends('Layouts.admin')
@section('content')

<div class="page-shell">
    <a href="{{ route('hotel.rooms.index', $room->hotel_id) }}" class="hotel-back-link">
        <i class="uil uil-arrow-left"></i>
        Back to Rooms
    </a>

    <div class="page-header">
        <div>
            <h1 class="page-title">Room Details</h1>
            <p class="page-subtitle">Viewing {{ $room->name }}</p>
        </div>
        <a href="{{ route('rooms.edit', $room) }}" class="form-action secondary">Edit Room</a>
    </div>

    <section class="panel-section">
        <div class="hotel-form-grid">
            <div class="hotel-field-group">
                <label class="hotel-field-label">Belongs to Hotel</label>
                <input type="text" class="input" value="{{ $room->hotel->name }}" readonly>
            </div>

            <div class="hotel-field-group">
                <label class="hotel-field-label">Room Name</label>
                <input type="text" class="input" value="{{ $room->name }}" readonly>
            </div>

            <div class="hotel-field-group">
                <label class="hotel-field-label">Type</label>
                <input type="text" class="input" style="text-transform: capitalize;" value="{{ $room->type }}" readonly>
            </div>

            <div class="hotel-field-group">
                <label class="hotel-field-label">Capacity</label>
                <input type="text" class="input" value="{{ $room->capacity }} Pax" readonly>
            </div>

            <div class="hotel-field-group">
                <label class="hotel-field-label">Price</label>
                <input type="text" class="input" value="RM {{ number_format($room->price, 2) }}" readonly>
            </div>

            <div class="hotel-field-group">
                <label class="hotel-field-label">Total Inventory Available</label>
                <input type="text" class="input" value="{{ $room->total_rooms }} Rooms" readonly>
            </div>
        </div>
    </section>
</div>
@endsection