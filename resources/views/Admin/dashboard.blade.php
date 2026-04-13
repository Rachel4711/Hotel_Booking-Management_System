@extends('Layouts.admin')

@section('content')

<h1>Dashboard</h1>

<div class="dashboard-grid">

    <div class="card">
        <h3>Total Customers</h3>
        <p>{{ $totalCustomers }}</p>
    </div>

    <div class="card">
        <h3>Total Hotels</h3>
        <p>{{ $totalHotels }}</p>
    </div>

    <div class="card">
        <h3>Total Bookings</h3>
        <p>{{ $totalBookings }}</p>
    </div>

</div>

@endsection