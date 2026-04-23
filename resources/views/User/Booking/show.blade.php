@extends('Layouts.user')

@section('title', 'Booking ' . $code)

@push('styles')
<style>
    .detail-shell { padding: 12px 0 40px; display: flex; flex-direction: column; gap: 22px; }
    .back-link {
        color: #7e6552;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .back-link:hover { color: #4e3c30; }

    .detail-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        flex-wrap: wrap;
        gap: 16px;
        padding-bottom: 16px;
        border-bottom: 1px solid #eadfd3;
    }
    .detail-header h1 { margin: 0; font-size: 30px; color: #2f2723; }
    .detail-header .booking-ref {
        font-size: 12px;
        color: #a38a73;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        font-weight: 700;
    }
    .detail-badges { display: flex; gap: 8px; flex-wrap: wrap; }

    .detail-grid {
        display: grid;
        grid-template-columns: minmax(0, 2fr) minmax(280px, 1fr);
        gap: 24px;
    }

    .panel {
        background: #fff;
        border: 1px solid #eadfd3;
        border-radius: 16px;
        padding: 22px 24px;
        box-shadow: 0 6px 20px rgba(60, 40, 25, 0.06);
    }
    .panel h2 { margin: 0 0 14px; font-size: 18px; color: #2f2723; }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 14px 22px;
        font-size: 14px;
        color: #5a4a3d;
    }
    .info-grid strong { color: #2f2723; display: block; margin-top: 2px; }

    .rooms-table { width: 100%; border-collapse: collapse; }
    .rooms-table th, .rooms-table td {
        padding: 10px 12px;
        text-align: left;
        font-size: 14px;
        border-bottom: 1px solid #f0e6db;
    }
    .rooms-table th { color: #7e6552; font-weight: 700; font-size: 12px; text-transform: uppercase; letter-spacing: 0.06em; }
    .rooms-table tr:last-child td { border-bottom: none; }
    .rooms-table td.num { text-align: right; }

    .summary-row { display: flex; justify-content: space-between; padding: 6px 0; color: #5a4a3d; font-size: 14px; }
    .summary-row.total {
        font-weight: 700;
        font-size: 20px;
        color: #2f2723;
        border-top: 1px solid #eadfd3;
        margin-top: 10px;
        padding-top: 12px;
    }

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

    .action-row { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 8px; }
    .btn {
        padding: 10px 20px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        border: none;
        cursor: pointer;
    }
    .btn-primary { background: #7e6552; color: #fff; }
    .btn-primary:hover { background: #6a5443; }
    .btn-danger  { background: #fff; color: #8a2d24; border: 1px solid #e0b5af; }
    .btn-danger:hover { background: #fbe9e7; }

    @media (max-width: 860px) {
        .detail-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
@if (session('error'))
    <div class="notice error" style="padding:15px; background:rgba(207,74,60,0.1); color:#8a2d24; border-radius:12px; margin-bottom:20px; border:1px solid rgba(207,74,60,0.2);">
        {{ session('error') }}
    </div>
@endif

<div class="detail-shell">
    <a href="{{ route('user.history') }}" class="back-link">&larr; Back to My Bookings</a>

    <div class="detail-header">
        <div>
            <div class="booking-ref">Booking {{ $code }}</div>
            <h1>{{ $hotel->name ?? 'Hotel removed' }}</h1>
            @if ($hotel && !empty($hotel->state))
                <p style="margin: 4px 0 0; color: #7e6552;">{{ $hotel->state }}</p>
            @endif
        </div>
        <div class="detail-badges">
            <span class="badge badge-{{ $first->status }}">{{ ucfirst($first->status) }}</span>
            <span class="badge badge-{{ $first->payment_status }}">{{ ucfirst($first->payment_status) }}</span>
        </div>
    </div>

    <div class="detail-grid">
        <div style="display: flex; flex-direction: column; gap: 18px;">
            <section class="panel">
                <h2>Stay Details</h2>
                <div class="info-grid">
                    <div>
                        Check-in
                        <strong>{{ \Carbon\Carbon::parse($first->check_in)->format('D, M d, Y') }}</strong>
                    </div>
                    <div>
                        Check-out
                        <strong>{{ \Carbon\Carbon::parse($first->check_out)->format('D, M d, Y') }}</strong>
                    </div>
                    <div>
                        Nights
                        <strong>{{ $nights }}</strong>
                    </div>
                    <div>
                        Guests
                        <strong>{{ $first->guest_count }}</strong>
                    </div>
                    <div>
                        Booked on
                        <strong>{{ $first->created_at?->format('M d, Y H:i') ?? '—' }}</strong>
                    </div>
                    <div>
                        Payment method
                        <strong>{{ $first->payment_method ? str_replace('_', ' ', ucfirst($first->payment_method)) : 'Not paid yet' }}</strong>
                    </div>
                </div>
            </section>

            <section class="panel">
                <h2>Rooms</h2>
                <table class="rooms-table">
                    <thead>
                        <tr>
                            <th>Room</th>
                            <th>Type</th>
                            <th class="num">Qty</th>
                            <th class="num">Price/Night</th>
                            <th class="num">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rows as $row)
                            <tr>
                                <td>{{ $row->room->name ?? '—' }}</td>
                                <td style="text-transform: capitalize;">{{ $row->room->type ?? '—' }}</td>
                                <td class="num">{{ $row->room_quantity }}</td>
                                <td class="num">RM {{ number_format($row->room->price ?? 0, 2) }}</td>
                                <td class="num">RM {{ number_format($row->total_price, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>
        </div>

        <aside style="display: flex; flex-direction: column; gap: 18px;">
            <section class="panel">
                <h2>Payment Summary</h2>
                @php
                    $subtotal = $totalPrice;
                    $tax = $subtotal * 0.06;
                    $grand = $subtotal + $tax;
                @endphp
                <div class="summary-row"><span>Subtotal</span><span>RM {{ number_format($subtotal, 2) }}</span></div>
                <div class="summary-row"><span>Tax (6%)</span><span>RM {{ number_format($tax, 2) }}</span></div>
                <div class="summary-row total"><span>Total</span><span>RM {{ number_format($grand, 2) }}</span></div>

                <div class="action-row">
                    @if ($first->payment_status === 'unpaid' && $first->status !== 'cancelled')
                        <a href="{{ route('user.payment', $first->id) }}" class="btn btn-primary" style="flex:1; text-align:center;">Complete Payment</a>
                    @endif

                    @if ($canCancel)
                        <form method="POST"
                              action="{{ route('user.booking.cancel', $code) }}"
                              onsubmit="return confirm('Cancel booking {{ $code }}? This cannot be undone.');"
                              style="flex:1; margin:0;">
                            @csrf
                            <button type="submit" class="btn btn-danger" style="width:100%;">Cancel Booking</button>
                        </form>
                    @endif
                </div>

                @if (!$canCancel && $first->status !== 'cancelled')
                    <p style="margin: 12px 0 0; font-size: 12px; color: #7e6552;">
                        Check-in date has passed — this booking can no longer be cancelled.
                    </p>
                @endif
            </section>
        </aside>
    </div>
</div>
@endsection
