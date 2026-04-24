@extends('Layouts.admin')

@section('content')

@php
    $isFiltered = ($search ?? '') !== '' || ($role ?? '') !== '';
@endphp

<h1>Admin Dashboard</h1>

{{-- @if(session('success'))
    <div class="notice success">{{ session('success') }}</div>
@endif --}}

<div class="dashboard-stats" style="display:grid; grid-template-columns:repeat(3, minmax(0, 1fr)); gap:12px; margin:12px 0 16px;">
        <div class="card" style="padding:12px;">
            <h3 style="margin:0; font-size:16px;">Total Customers</h3>
            <p style="margin:6px 0 0; font-size:24px; font-weight:700;">{{ $totalCustomers }}</p>
        </div>
        <div class="card" style="padding:12px;">
            <h3 style="margin:0; font-size:16px;">Total Hotels</h3>
            <p style="margin:6px 0 0; font-size:24px; font-weight:700;">{{ $totalHotels }}</p>
        </div>
        <div class="card" style="padding:12px;">
            <h3 style="margin:0; font-size:16px;">Total Bookings</h3>
            <p style="margin:6px 0 0; font-size:24px; font-weight:700;">{{ $totalBookings }}</p>
        </div>
</div>

@php
    $statusRows = collect($bookingStatusBreakdown ?? [])->keyBy('key');

    $confirmed = $statusRows->get('confirmed', ['count' => 0, 'percentage' => 0, 'color' => '#10b981']);
    $pending = $statusRows->get('pending', ['count' => 0, 'percentage' => 0, 'color' => '#f59e0b']);
    $cancelled = $statusRows->get('cancelled', ['count' => 0, 'percentage' => 0, 'color' => '#ef4444']);

    $confirmedPct = (float) ($confirmed['percentage'] ?? 0);
    $pendingPct = (float) ($pending['percentage'] ?? 0);
    $cancelledPct = (float) ($cancelled['percentage'] ?? 0);

    $firstStop = min(100, $confirmedPct);
    $secondStop = min(100, $confirmedPct + $pendingPct);

    $donutBackground = $totalBookings > 0
        ? "conic-gradient({$confirmed['color']} 0% {$firstStop}%, {$pending['color']} {$firstStop}% {$secondStop}%, {$cancelled['color']} {$secondStop}% 100%)"
        : 'conic-gradient(#cbd5e1 0% 100%)';
@endphp

<section class="dashboard-analytics card">
    <div class="analytics-head">
        <div>
            <h2>Booking Status Breakdown</h2>
            <p>Snapshot of confirmed, pending, and cancelled bookings across all hotels.</p>
        </div>
        <div class="analytics-total-bookings">
            <span>Total Bookings</span>
            <strong>{{ number_format($totalBookings) }}</strong>
        </div>
    </div>

    <div class="analytics-grid">
        <div class="analytics-donut-wrap">
            <div class="analytics-donut" style="--donut-bg: {{ $donutBackground }};">
                <div class="analytics-donut-center">
                    <span>Confirmed</span>
                    <strong>{{ number_format((int) $confirmed['count']) }}</strong>
                    <em>{{ number_format($confirmedPct, 1) }}%</em>
                </div>
            </div>
        </div>

        <div class="analytics-details">
            <div class="analytics-status-list">
                @foreach ($bookingStatusBreakdown as $row)
                    <article class="analytics-status-item">
                        <div class="analytics-label-wrap">
                            <span class="analytics-dot" style="background: {{ $row['color'] }};"></span>
                            <span class="analytics-label">{{ $row['label'] }}</span>
                        </div>
                        <div class="analytics-values">
                            <strong>{{ number_format((int) $row['count']) }}</strong>
                            <span>{{ number_format((float) $row['percentage'], 1) }}%</span>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="analytics-stack" aria-label="Booking status stacked bar">
                <span style="width: {{ max(0, min(100, $confirmedPct)) }}%; background: {{ $confirmed['color'] }};" title="Confirmed: {{ number_format($confirmedPct, 1) }}%"></span>
                <span style="width: {{ max(0, min(100, $pendingPct)) }}%; background: {{ $pending['color'] }};" title="Pending: {{ number_format($pendingPct, 1) }}%"></span>
                <span style="width: {{ max(0, min(100, $cancelledPct)) }}%; background: {{ $cancelled['color'] }};" title="Cancelled: {{ number_format($cancelledPct, 1) }}%"></span>
            </div>
        </div>
    </div>
</section>

@php
    $revenueRows = collect($revenueTrend ?? []);
    $chartWidth = 640;
    $chartHeight = 220;
    $paddingX = 28;
    $paddingY = 20;
    $plotWidth = $chartWidth - ($paddingX * 2);
    $plotHeight = $chartHeight - ($paddingY * 2);

    $revenueMax = max(1, (float) $revenueRows->max('total'));
    $pointCount = max(1, $revenueRows->count() - 1);

    $chartPoints = $revenueRows->values()->map(function (array $row, int $index) use ($paddingX, $paddingY, $plotWidth, $plotHeight, $pointCount, $revenueMax): array {
        $x = $paddingX + (($index / $pointCount) * $plotWidth);
        $y = $paddingY + ($plotHeight - (((float) $row['total'] / $revenueMax) * $plotHeight));

        return [
            'x' => round($x, 2),
            'y' => round($y, 2),
            'label' => $row['label'],
            'short_label' => $row['short_label'],
            'total' => (float) $row['total'],
        ];
    })->all();

    $polylinePoints = collect($chartPoints)
        ->map(fn (array $point): string => $point['x'] . ',' . $point['y'])
        ->implode(' ');

    $firstPoint = $chartPoints[0] ?? ['x' => $paddingX, 'y' => $paddingY + $plotHeight];
    $lastPoint = collect($chartPoints)->last() ?? ['x' => $paddingX + $plotWidth, 'y' => $paddingY + $plotHeight];
    $areaPoints = $firstPoint['x'] . ',' . ($paddingY + $plotHeight) . ' ' . $polylinePoints . ' ' . $lastPoint['x'] . ',' . ($paddingY + $plotHeight);

    $averageMonthlyRevenue = $revenueRows->count() > 0
        ? ((float) $revenueRows->sum('total') / $revenueRows->count())
        : 0;

    $isRevenueUp = (float) ($revenueChangePercentage ?? 0) >= 0;
@endphp

<section class="dashboard-revenue-analytics card">
    <div class="revenue-head">
        <div>
            <h2>Revenue Trends</h2>
            <p>Monthly revenue from paid, non-cancelled bookings over the last 6 months.</p>
        </div>
        <div class="revenue-kpis">
            <article>
                <span>6-Month Revenue</span>
                <strong>RM {{ number_format((float) ($totalRevenueInPeriod ?? 0), 2) }}</strong>
            </article>
            <article>
                <span>This Month</span>
                <strong>RM {{ number_format((float) ($currentMonthRevenue ?? 0), 2) }}</strong>
            </article>
            <article class="{{ $isRevenueUp ? 'revenue-up' : 'revenue-down' }}">
                <span>MoM Change</span>
                <strong>{{ $isRevenueUp ? '+' : '' }}{{ number_format((float) ($revenueChangePercentage ?? 0), 1) }}%</strong>
            </article>
        </div>
    </div>

    <div class="revenue-chart-wrap">
        <svg viewBox="0 0 {{ $chartWidth }} {{ $chartHeight }}" class="revenue-chart" role="img" aria-label="Revenue trend line chart">
            @for ($i = 0; $i <= 4; $i++)
                @php
                    $lineY = $paddingY + (($i / 4) * $plotHeight);
                    $lineValue = $revenueMax - (($i / 4) * $revenueMax);
                @endphp
                <line x1="{{ $paddingX }}" y1="{{ $lineY }}" x2="{{ $paddingX + $plotWidth }}" y2="{{ $lineY }}" class="chart-grid-line"></line>
                <text x="4" y="{{ $lineY + 4 }}" class="chart-grid-label">RM {{ number_format($lineValue, 0) }}</text>
            @endfor

            <polygon points="{{ $areaPoints }}" class="chart-area"></polygon>
            <polyline points="{{ $polylinePoints }}" class="chart-line"></polyline>

            @foreach ($chartPoints as $point)
                <circle cx="{{ $point['x'] }}" cy="{{ $point['y'] }}" r="4" class="chart-point"></circle>
            @endforeach
        </svg>

        <div class="revenue-x-axis">
            @foreach ($chartPoints as $point)
                <span title="{{ $point['label'] }}: RM {{ number_format($point['total'], 2) }}">{{ $point['short_label'] }}</span>
            @endforeach
        </div>
    </div>

    <div class="revenue-foot">
        <span>Average monthly revenue: <strong>RM {{ number_format($averageMonthlyRevenue, 2) }}</strong></span>
    </div>
</section>



@endsection