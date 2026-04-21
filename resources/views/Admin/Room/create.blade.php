@extends('Layouts.admin')
@section('content')

<div class="page-shell">
    <a href="{{ route('hotel.rooms.index', $hotel->id) }}" class="hotel-back-link">
        <i class="uil uil-arrow-left"></i>
        Back to {{ $hotel->name }} Rooms
    </a>

    <div class="page-header">
        <div>
            <h1 class="page-title">Add Room</h1>
            <p class="page-subtitle">Creating a new room for {{ $hotel->name }}</p>
        </div>
    </div>

    <section class="panel-section">
        <form method="POST" action="{{ route('rooms.store') }}" class="hotel-form-grid">
            @csrf
            
            {{-- Hidden input to link the room to the hotel --}}
            <input type="hidden" name="hotel_id" value="{{ $hotel->id }}">

            <div class="hotel-field-group">
                <label class="hotel-field-label" for="name">Room Name</label>
                <input id="name" type="text" name="name" class="input" value="{{ old('name') }}" required placeholder="e.g. Deluxe Ocean View">
            </div>

            <div class="hotel-field-group">
                <label class="hotel-field-label" for="type">Room Type</label>
                <select id="type" name="type" class="input" required>
                    <option value="single" {{ old('type') == 'single' ? 'selected' : '' }}>Single</option>
                    <option value="double" {{ old('type') == 'double' ? 'selected' : '' }}>Double</option>
                    <option value="queen" {{ old('type') == 'queen' ? 'selected' : '' }}>Queen</option>
                    <option value="king" {{ old('type') == 'king' ? 'selected' : '' }}>King</option>
                </select>
            </div>

            <div class="hotel-field-group">
                <label class="hotel-field-label" for="capacity">Capacity (Pax)</label>
                <input id="capacity" type="number" min="1" name="capacity" class="input" value="{{ old('capacity') }}" required>
            </div>

            <div class="hotel-field-group">
                <label class="hotel-field-label" for="price">Price Per Night</label>
                <input id="price" type="number" step="0.01" min="0" name="price" class="input" value="{{ old('price') }}" required>
            </div>

            <div class="hotel-field-group hotel-span-2">
                <label class="hotel-field-label" for="total_rooms">Total Number of this Room Type Available</label>
                <input id="total_rooms" type="number" min="1" name="total_rooms" class="input" value="{{ old('total_rooms', 1) }}" required>
            </div>

            <div class="hotel-form-actions hotel-span-2">
                <button type="submit" class="form-action primary">Add Room</button>
                <a href="{{ route('hotel.rooms.index', $hotel->id) }}" class="form-action secondary">Cancel</a>
            </div>
        </form>
    </section>
</div>
@endsection