@vite([
        'resources/css/Admin/dashboard.css',
    ])
@extends('Layouts.admin')

@section('content')

<h1>Dashboard</h1>

<div class="dashboard-grid">

    <div class="card">
        <h3>Total Customers</h3>
        <p>120</p>
    </div>

    <div class="card">
        <h3>Total Hotels</h3>
        <p>25</p>
    </div>

    <div class="card">
        <h3>Total Bookings</h3>
        <p>340</p>
    </div>

</div>

@endsection