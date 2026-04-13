<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\User\ProfileController as UserProfileController;
use Illuminate\Support\Facades\Route;

// Website home
Route::get('/', function () {
    return view('index');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::get('/admin/login', [AuthController::class, 'showAdminLogin'])->name('adminLogin');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');

Route::middleware('guest')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::post('/admin/login', [AuthController::class, 'adminLogin'])->name('adminLogin.submit');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::post('/reset-password', [ForgotPasswordController::class, 'reset'])->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/user/profile', [UserProfileController::class, 'edit'])->name('user.profile.edit');
    Route::put('/user/profile', [UserProfileController::class, 'update'])->name('user.profile.update');
});

Route::middleware(['auth', 'can:viewAny,App\\Models\\User'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/customers', [AdminUserController::class, 'index'])->name('customers.index');
    Route::get('/customers/create', [AdminUserController::class, 'create'])->name('customers.create');
    Route::post('/customers', [AdminUserController::class, 'store'])->name('customers.store');
    Route::get('/customers/{customer}', [AdminUserController::class, 'show'])->name('customers.show');
    Route::get('/customers/{customer}/edit', [AdminUserController::class, 'edit'])->name('customers.edit');
    Route::put('/customers/{customer}', [AdminUserController::class, 'update'])->name('customers.update');
    Route::delete('/customers/{customer}', [AdminUserController::class, 'destroy'])->name('customers.destroy');

    Route::get('/admin/profile', [AdminProfileController::class, 'edit'])->name('admin.profile');
    Route::put('/admin/profile', [AdminProfileController::class, 'update'])->name('admin.profile.update');

    Route::get('/hotels', function () {
        return view('Admin.Hotel.index');
    })->name('hotels.index');
    Route::get('/hotels/create', function () {
        return view('Admin.Hotel.create');
    })->name('hotels.create');
    Route::get('/bookings', function () {
        return view('Admin.Booking.index');
    })->name('bookings.index');
    Route::get('/bookings/create', function () {
        return view('Admin.Booking.create');
    })->name('bookings.create');
});