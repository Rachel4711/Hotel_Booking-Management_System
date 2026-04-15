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



@endsection