@extends('Layouts.admin')
@section('content')

<div class="page-shell">
    <a href="{{ route('hotels.index') }}" class="hotel-back-link">
        <i class="uil uil-arrow-left"></i>
        Back to Hotel List
    </a>

    <div class="page-header">
        <div>
            <h1 class="page-title">Manage Rooms</h1>
            <p class="page-subtitle">Rooms for {{ $hotel->name }}</p>
        </div>
    </div>

    <section class="panel-section" style="margin-bottom:14px;">
        <div class="hotel-filter-row" style="justify-content: flex-end;">
            <div class="hotel-filter-right">
                <a href="{{ route('hotel.rooms.create', $hotel->id) }}" class="form-action secondary">Add New Room</a>
            </div>
        </div>
    </section>

    <section class="panel-section">
        <div class="table-wrap">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Room Name</th>
                        <th>Type</th>
                        <th>Capacity</th>
                        <th>Price (RM)</th>
                        <th>Total Rooms</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rooms as $room)
                        <tr>
                            <td>{{ $room->name }}</td>
                            <td><span class="badge" style="text-transform: capitalize;">{{ $room->type }}</span></td>
                            <td>{{ $room->capacity }} Pax</td>
                            <td>{{ number_format($room->price, 2) }}</td>
                            <td>{{ $room->total_rooms }}</td>
                            <td>
                                <div style="display:flex; gap:8px;">
                                    <a href="{{ route('rooms.show', $room) }}" class="form-action btn-view" style="padding:6px 12px; font-size:12px;">View</a>
                                    <a href="{{ route('rooms.edit', $room) }}" class="form-action btn-edit" style="padding:6px 12px; font-size:12px;">Edit</a>
                                    
                                    <form method="POST" action="{{ route('rooms.destroy', $room) }}" onsubmit="return confirm('Are you sure you want to delete this room?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="form-action btn-delete" style="padding:6px 12px; font-size:12px;">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">No rooms found for this hotel.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div style="margin-top:14px;">
            {{ $rooms->links() }}
        </div>
    </section>
</div>
@endsection