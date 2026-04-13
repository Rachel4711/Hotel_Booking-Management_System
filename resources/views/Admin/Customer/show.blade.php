@extends('Layouts.admin')
@section('content')

<div class="page-shell">
    <div class="page-header">
        <div>
            <h1 class="page-title">Customer Detail</h1>
            <p class="page-subtitle">View customer information and their booking history.</p>
        </div>
        <a class="button-outline" href="{{ route('customers.index') }}">Back to list</a>
    </div>

    <div class="panel-section detail-grid">
        <div class="detail-card">
            <span class="badge">Profile</span>
            <div class="detail-list">
                <div class="detail-item"><strong>ID</strong><span>{{ $customer->id }}</span></div>
                <div class="detail-item"><strong>Name</strong><span>{{ $customer->name }}</span></div>
                <div class="detail-item"><strong>Email</strong><span>{{ $customer->email }}</span></div>
                <div class="detail-item"><strong>Phone</strong><span>{{ $customer->phone ?? '-' }}</span></div>
                <div class="detail-item"><strong>Role</strong><span>{{ $customer->role }}</span></div>
            </div>
        </div>

        <div class="detail-card">
            <span class="badge">Bookings</span>
            <div class="table-wrap" style="margin-top:14px;">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Code</th>
                            <th>Room</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customer->bookings as $booking)
                            <tr>
                                <td>{{ $booking->id }}</td>
                                <td>{{ $booking->booking_code }}</td>
                                <td>{{ $booking->room?->name ?? '-' }}</td>
                                <td>{{ $booking->check_in }}</td>
                                <td>{{ $booking->check_out }}</td>
                                <td><span class="badge">{{ $booking->status }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="6">No bookings found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
