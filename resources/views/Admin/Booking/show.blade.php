@extends('Layouts.admin')
@section('content')

<style>
    .detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 16px 28px;
        font-size: 14px;
        color: #5a4a3d;
    }
    .detail-grid strong { color: #2f2723; display: block; margin-top: 3px; }
    .panel-title { margin: 0 0 14px; font-size: 18px; color: #2f2723; }
    .badge-status {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .bs-pending   { background: #fff4d9; color: #8a6d1a; }
    .bs-confirmed { background: #dff5e3; color: #1d6b3a; }
    .bs-cancelled { background: #f8d7da; color: #7a1d24; }
    .bs-unpaid    { background: #f3e3d8; color: #7a4a1f; }
    .bs-paid      { background: #dff5e3; color: #1d6b3a; }
    .code-cell { font-family: Consolas, Menlo, monospace; font-size: 14px; color: #4e3c30; }
</style>

<div class="page-shell">
    <a href="{{ route('bookings.index') }}" class="hotel-back-link">
        <i class="uil uil-arrow-left"></i> Back to Bookings
    </a>

    <div class="page-header">
        <div>
            <h1 class="page-title">Booking <span class="code-cell">{{ $booking->booking_code }}</span></h1>
            <p class="page-subtitle">Created {{ $booking->created_at?->format('M d, Y H:i') ?? '—' }}</p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('bookings.edit', $booking) }}" class="form-action btn-edit">Edit</a>
            <form method="POST" action="{{ route('bookings.destroy', $booking) }}"
                  onsubmit="return confirm('Delete booking {{ $booking->booking_code }}?');" style="margin: 0;">
                @csrf
                @method('DELETE')
                <button type="submit" class="form-action btn-delete">Delete</button>
            </form>
        </div>
    </div>

    <section class="panel-section" style="margin-bottom: 14px;">
        <h2 class="panel-title">Stay & Guest</h2>
        <div class="detail-grid">
            <div>Status <strong><span class="badge-status bs-{{ $booking->status }}">{{ ucfirst($booking->status) }}</span></strong></div>
            <div>Payment Status <strong><span class="badge-status bs-{{ $booking->payment_status }}">{{ ucfirst($booking->payment_status) }}</span></strong></div>
            <div>Payment Method <strong style="text-transform: capitalize;">{{ $booking->payment_method ? str_replace('_', ' ', $booking->payment_method) : '—' }}</strong></div>
            <div>Check-in <strong>{{ \Carbon\Carbon::parse($booking->check_in)->format('D, M d, Y') }}</strong></div>
            <div>Check-out <strong>{{ \Carbon\Carbon::parse($booking->check_out)->format('D, M d, Y') }}</strong></div>
            <div>Nights <strong>{{ $nights }}</strong></div>
            <div>Room Quantity <strong>{{ $booking->room_quantity }}</strong></div>
            <div>Guest Count <strong>{{ $booking->guest_count }}</strong></div>
            <div>Total Price <strong>RM {{ number_format($booking->total_price, 2) }}</strong></div>
        </div>
    </section>

    <section class="panel-section" style="margin-bottom: 14px;">
        <h2 class="panel-title">Customer</h2>
        @if ($booking->user)
            <div class="detail-grid">
                <div>Name <strong>{{ $booking->user->name }}</strong></div>
                <div>Email <strong>{{ $booking->user->email }}</strong></div>
                <div>Phone <strong>{{ $booking->user->phone ?? '—' }}</strong></div>
                <div>Customer ID <strong><a href="{{ route('customers.show', $booking->user) }}">#{{ $booking->user->id }}</a></strong></div>
            </div>
        @else
            <p style="color: #8a7562;">Customer has been deleted.</p>
        @endif
    </section>

    <section class="panel-section">
        <h2 class="panel-title">Room</h2>
        @if ($booking->room)
            <div class="detail-grid">
                <div>Hotel <strong>{{ $booking->room->hotel->name ?? '—' }}</strong></div>
                <div>Room Name <strong>{{ $booking->room->name }}</strong></div>
                <div>Type <strong style="text-transform: capitalize;">{{ $booking->room->type }}</strong></div>
                <div>Capacity <strong>{{ $booking->room->capacity }} pax</strong></div>
                <div>Price/Night <strong>RM {{ number_format($booking->room->price, 2) }}</strong></div>
            </div>
        @else
            <p style="color: #8a7562;">Room has been deleted.</p>
        @endif
    </section>
</div>
@endsection
