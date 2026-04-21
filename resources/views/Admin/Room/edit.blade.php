@extends('Layouts.admin')
@section('content')

<div class="page-shell">
    <a href="{{ route('hotel.rooms.index', $room->hotel_id) }}" class="hotel-back-link">
        <i class="uil uil-arrow-left"></i>
        Back to Rooms
    </a>

    <div class="page-header">
        <div>
            <h1 class="page-title">Edit Room</h1>
            <p class="page-subtitle">Updating {{ $room->name }}</p>
        </div>
    </div>

    <section class="panel-section">
        <form method="POST" action="{{ route('rooms.update', $room) }}" class="hotel-form-grid">
            @csrf
            @method('PUT')

            <div class="hotel-field-group">
                <label class="hotel-field-label" for="name">Room Name</label>
                <input id="name" type="text" name="name" class="input" value="{{ old('name', $room->name) }}" required>
            </div>

            <div class="hotel-field-group">
                <label class="hotel-field-label" for="type">Room Type</label>
                <select id="type" name="type" class="input" required>
                    <option value="single" {{ old('type', $room->type) == 'single' ? 'selected' : '' }}>Single</option>
                    <option value="double" {{ old('type', $room->type) == 'double' ? 'selected' : '' }}>Double</option>
                    <option value="queen" {{ old('type', $room->type) == 'queen' ? 'selected' : '' }}>Queen</option>
                    <option value="king" {{ old('type', $room->type) == 'king' ? 'selected' : '' }}>King</option>
                </select>
            </div>

            <div class="hotel-field-group">
                <label class="hotel-field-label" for="capacity">Capacity (Pax)</label>
                <input id="capacity" type="number" min="1" name="capacity" class="input" value="{{ old('capacity', $room->capacity) }}" required>
            </div>

            <div class="hotel-field-group">
                <label class="hotel-field-label" for="price">Price Per Night</label>
                <input id="price" type="number" step="0.01" min="0" name="price" class="input" value="{{ old('price', $room->price) }}" required>
            </div>

            <div class="hotel-field-group hotel-span-2">
                <label class="hotel-field-label" for="total_rooms">Total Inventory</label>
                <input id="total_rooms" type="number" min="1" name="total_rooms" class="input" value="{{ old('total_rooms', $room->total_rooms) }}" required>
            </div>

            <div class="hotel-form-actions hotel-span-2">
                <button type="submit" class="form-action primary">Save Room Changes</button>
            </div>
        </form>
    </section>
</div>
@endsection