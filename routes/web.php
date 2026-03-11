<?php

use Illuminate\Support\Facades\Route;

// Admin Layout
Route::get('/', function () {
    return view('Layouts.admin');
});

// Dashboard
Route::get('/dashboard', function () {
    return view('Admin.dashboard');
})->name('dashboard');

// Customers
Route::get('/customers', function () {
    return view('Admin.Customer.index');
})->name('customers.index');

Route::get('/customers/create', function () {
    return view('Admin.Customer.create');
})->name('customers.create');

// Hotels
Route::get('/hotels', function () {
    return view('Admin.Hotel.index');
})->name('hotels.index');

Route::get('/hotels/create', function () {
    return view('Admin.Hotel.create');
})->name('hotels.create');

// Bookings
Route::get('/bookings', function () {
    return view('Admin.Booking.index');
})->name('bookings.index');

Route::get('/bookings/create', function () {
    return view('Admin.Booking.create');
})->name('bookings.create');

//Admin Profile
Route::get('/admin/profile', function () {
    return view('Admin.profile');
})->name('admin.profile');