@extends('Layouts.user')

@section('title', 'My Bookings')

@push('styles')
<style>
    .history-shell { display: flex; flex-direction: column; gap: 24px; padding: 12px 0 40px; }
    .history-header h1 { margin: 0 0 6px; font-size: 32px; color: #2f2723; }
    .history-header p { margin: 0; color: #7e6552; }

    .history-empty {
        padding: 60px 30px;
        background: #fff;
        border: 1px dashed #d8c9bd;
        border-radius: 18px;
        text-align: center;
        color: #7e6552;
    }
    .history-empty a {
        display: inline-block;
        margin-top: 14px;
        padding: 10px 22px;
        background: #7e6552;
        color: #fff;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
    }

    .booking-card {
        background: #fff;
        border: 1px solid #eadfd3;
        border-radius: 16px;
        padding: 22px 24px;
        box-shadow: 0 6px 20px rgba(60, 40, 25, 0.06);
        display: grid;
        grid-template-columns: minmax(0, 1fr) 220px;
        gap: 18px;
        align-items: stretch;
    }
    .booking-card-main { min-width: 0; }
    .booking-code {
        font-size: 12px;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: #a38a73;
        font-weight: 700;
    }
    .booking-hotel { margin: 4px 0 10px; font-size: 20px; color: #2f2723; font-weight: 700; }
    .booking-meta {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 12px 24px;
        font-size: 14px;
        color: #5a4a3d;
    }
    .booking-meta strong { color: #2f2723; display: block; margin-top: 2px; }

    .booking-side {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        gap: 12px;
        padding-left: 18px;
        border-left: 1px solid #eadfd3;
    }
    .booking-side-top { display: flex; flex-direction: column; gap: 6px; align-items: flex-start; }
    .booking-total { font-size: 22px; font-weight: 700; color: #2f2723; }
    .booking-total-label { font-size: 12px; color: #7e6552; }

    .badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
    }
    .badge-pending   { background: #fff4d9; color: #8a6d1a; }
    .badge-confirmed { background: #dff5e3; color: #1d6b3a; }
    .badge-cancelled { background: #f8d7da; color: #7a1d24; }
    .badge-unpaid    { background: #f3e3d8; color: #7a4a1f; }
    .badge-paid      { background: #dff5e3; color: #1d6b3a; }

    .booking-actions { display: flex; gap: 8px; }
    .btn-action {
        flex: 1;
        text-align: center;
        padding: 9px 12px;
        border-radius: 9px;
        font-weight: 600;
        font-size: 13px;
        text-decoration: none;
        border: none;
        cursor: pointer;
    }
    .btn-view   { background: #7e6552; color: #fff; }
    .btn-view:hover { background: #6a5443; }
    .btn-cancel { background: #fff; color: #8a2d24; border: 1px solid #e0b5af; }
    .btn-cancel:hover { background: #fbe9e7; }

    @media (max-width: 720px) {
        .booking-card { grid-template-columns: 1fr; }
        .booking-side { border-left: none; border-top: 1px solid #eadfd3; padding-left: 0; padding-top: 14px; }
    }
</style>
@endpush

@section('content')
@if (session('error'))
    <div class="notice error" style="padding:15px; background:rgba(207,74,60,0.1); color:#8a2d24; border-radius:12px; margin-bottom:20px; border:1px solid rgba(207,74,60,0.2);">
        {{ session('error') }}
    </div>
@endif

<div class="history-shell">
    <div class="history-header">
        <h1>My Bookings</h1>
        <p>Review your past and upcoming stays.</p>
    </div>

    @forelse ($bookings as $b)
        <article class="booking-card">
            <div class="booking-card-main">
                <span class="booking-code">{{ $b->booking_code }}</span>
                <h2 class="booking-hotel">{{ $b->hotel->name ?? 'Hotel removed' }}</h2>

                <div class="booking-meta">
                    <div>
                        Check-in
                        <strong>{{ \Carbon\Carbon::parse($b->check_in)->format('M d, Y') }}</strong>
                    </div>
                    <div>
                        Check-out
                        <strong>{{ \Carbon\Carbon::parse($b->check_out)->format('M d, Y') }}</strong>
                    </div>
                    <div>
                        Guests
                        <strong>{{ $b->guest_count }}</strong>
                    </div>
                    <div>
                        Rooms
                        <strong>{{ $b->rooms_count }} ({{ $b->room_types }} {{ \Illuminate\Support\Str::plural('type', $b->room_types) }})</strong>
                    </div>
                </div>
            </div>

            <div class="booking-side">
                <div class="booking-side-top">
                    <span class="badge badge-{{ $b->status }}">{{ ucfirst($b->status) }}</span>
                    <span class="badge badge-{{ $b->payment_status }}">{{ ucfirst($b->payment_status) }}</span>
                    <div>
                        <div class="booking-total-label">Total</div>
                        <div class="booking-total">RM {{ number_format($b->total_price, 2) }}</div>
                    </div>
                </div>

                <div class="booking-actions">
                    <a href="{{ route('user.booking.show', $b->booking_code) }}" class="btn-action btn-view">View</a>

                    @if ($b->status !== 'cancelled' && \Carbon\Carbon::parse($b->check_in)->startOfDay()->isFuture())
                        <form method="POST"
                              action="{{ route('user.booking.cancel', $b->booking_code) }}"
                              onsubmit="return confirm('Cancel booking {{ $b->booking_code }}? This cannot be undone.');"
                              style="flex:1; margin:0;">
                            @csrf
                            <button type="submit" class="btn-action btn-cancel" style="width:100%;">Cancel</button>
                        </form>
                    @endif
                </div>
            </div>
        </article>
    @empty
        <div class="history-empty">
            <p style="margin:0; font-size:16px;">You have no bookings yet.</p>
            <a href="{{ route('home') }}">Browse hotels</a>
        </div>
    @endforelse
</div>
@endsection
