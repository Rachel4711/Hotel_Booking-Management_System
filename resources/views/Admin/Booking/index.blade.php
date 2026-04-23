@extends('Layouts.admin')
@section('content')

<style>
    .booking-filter-bar {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
        gap: 12px;
        align-items: end;
    }
    .booking-filter-bar .form-field { display: flex; flex-direction: column; gap: 4px; }
    .booking-filter-bar label {
        font-size: 12px;
        font-weight: 600;
        color: #4e3c30;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }
    .booking-filter-bar input,
    .booking-filter-bar select {
        padding: 8px 10px;
        border: 1px solid #d8c9bd;
        border-radius: 8px;
        font-size: 14px;
        background: #fff;
    }
    .booking-filter-actions { display: flex; gap: 8px; align-items: center; }
    .badge-status {
        display: inline-block;
        padding: 3px 9px;
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
    .code-cell { font-family: Consolas, Menlo, monospace; font-size: 12px; color: #4e3c30; }
</style>

<div class="page-shell">
    <div class="page-header">
        <div>
            <h1 class="page-title">Bookings</h1>
            <p class="page-subtitle">Manage all hotel bookings</p>
        </div>
        <div>
            <a href="{{ route('bookings.create') }}" class="form-action primary">
                <i class="uil uil-plus"></i> Add Booking
            </a>
        </div>
    </div>

    <section class="panel-section" style="margin-bottom: 14px;">
        <form method="GET" action="{{ route('bookings.index') }}" class="booking-filter-bar">
            <div class="form-field">
                <label for="f-search">Search Booking ID</label>
                <input id="f-search" type="text" name="search" value="{{ $filters['search'] }}" placeholder="BKG-XXXXXXXX">
            </div>

            <div class="form-field">
                <label for="f-status">Status</label>
                <select id="f-status" name="status">
                    <option value="">All</option>
                    @foreach ($statuses as $s)
                        <option value="{{ $s }}" {{ $filters['status'] === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-field">
                <label for="f-pstatus">Payment Status</label>
                <select id="f-pstatus" name="payment_status">
                    <option value="">All</option>
                    @foreach ($paymentStatuses as $p)
                        <option value="{{ $p }}" {{ $filters['payment_status'] === $p ? 'selected' : '' }}>{{ ucfirst($p) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-field">
                <label for="f-pmethod">Payment Method</label>
                <select id="f-pmethod" name="payment_method">
                    <option value="">All</option>
                    @foreach ($paymentMethods as $m)
                        <option value="{{ $m }}" {{ $filters['payment_method'] === $m ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $m)) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-field">
                <label for="f-in">Check-in from</label>
                <input id="f-in" type="date" name="check_in" value="{{ $filters['check_in'] }}">
            </div>

            <div class="form-field">
                <label for="f-out">Check-out by</label>
                <input id="f-out" type="date" name="check_out" value="{{ $filters['check_out'] }}">
            </div>

            <div class="form-field booking-filter-actions">
                <button type="submit" class="form-action primary" style="padding: 9px 16px;">Filter</button>
                <a href="{{ route('bookings.index') }}" class="form-action secondary" style="padding: 9px 16px;">Reset</a>
            </div>
        </form>
    </section>

    <section class="panel-section">
        <div class="table-wrap">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Customer</th>
                        <th>Hotel / Room</th>
                        <th>Check-in</th>
                        <th>Check-out</th>
                        <th>Qty</th>
                        <th>Total (RM)</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Method</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bookings as $b)
                        <tr>
                            <td class="code-cell">{{ $b->booking_code }}</td>
                            <td>
                                {{ $b->user->name ?? '—' }}
                                <div style="font-size: 11px; color: #8a7562;">{{ $b->user->email ?? '' }}</div>
                            </td>
                            <td>
                                {{ $b->room->hotel->name ?? '—' }}
                                <div style="font-size: 11px; color: #8a7562;">{{ $b->room->name ?? '' }}</div>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($b->check_in)->format('Y-m-d') }}</td>
                            <td>{{ \Carbon\Carbon::parse($b->check_out)->format('Y-m-d') }}</td>
                            <td>{{ $b->room_quantity }}</td>
                            <td>{{ number_format($b->total_price, 2) }}</td>
                            <td><span class="badge-status bs-{{ $b->status }}">{{ ucfirst($b->status) }}</span></td>
                            <td><span class="badge-status bs-{{ $b->payment_status }}">{{ ucfirst($b->payment_status) }}</span></td>
                            <td style="text-transform: capitalize;">{{ $b->payment_method ? str_replace('_', ' ', $b->payment_method) : '—' }}</td>
                            <td>
                                <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                                    <a href="{{ route('bookings.show', $b) }}" class="form-action btn-view" style="padding: 5px 10px; font-size: 12px;">View</a>
                                    <a href="{{ route('bookings.edit', $b) }}" class="form-action btn-edit" style="padding: 5px 10px; font-size: 12px;">Edit</a>
                                    <form method="POST" action="{{ route('bookings.destroy', $b) }}"
                                          onsubmit="return confirm('Delete booking {{ $b->booking_code }}?');" style="margin: 0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="form-action btn-delete" style="padding: 5px 10px; font-size: 12px;">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" style="text-align: center; padding: 30px; color: #7e6552;">
                                No bookings match the current filters.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 14px;">
            {{ $bookings->links() }}
        </div>
    </section>
</div>
@endsection
