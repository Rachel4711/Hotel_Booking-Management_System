<?php

use App\Models\Hotel;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\User\ProfileController as UserProfileController;
use Illuminate\Http\Request;
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

// Serve user photos
Route::get('/photo/{path}', function ($path) {
    $filePath = storage_path('app/public/' . $path);
    if (file_exists($filePath)) {
        return response()->file($filePath);
    }
    abort(404);
})->where('path', '.+')->name('photo.show');

// User Auth
Route::middleware('auth')->group(function () {

    Route::get('/home', function (Request $request) {
        $selectedState = $request->query('state', 'All');
        $searchTerm = trim((string) $request->query('search', ''));
        $isKeywordSearch = $searchTerm !== '';
        $effectiveSelectedState = $isKeywordSearch ? 'All' : $selectedState;

        $hotelStates = Hotel::query()
            ->select('state')
            ->whereNotNull('state')
            ->where('state', '!=', '')
            ->distinct()
            ->orderBy('state')
            ->pluck('state')
            ->values();

        $hotelsQuery = Hotel::query();

        if ($effectiveSelectedState !== 'All') {
            $hotelsQuery->where('state', $effectiveSelectedState);
        }

        if ($searchTerm !== '') {
            $normalizedSearchTerm = mb_strtolower($searchTerm);

            $hotelsQuery->where(function ($query) use ($normalizedSearchTerm) {
                $query->whereRaw('LOWER(name) LIKE ?', ['%' . $normalizedSearchTerm . '%'])
                    ->orWhereRaw('LOWER(state) LIKE ?', ['%' . $normalizedSearchTerm . '%']);
            });
        }

        $filteredHotels = $hotelsQuery
            ->orderByDesc('click_count')
            ->orderBy('name')
            ->get();

        $clickedHotels = Hotel::query()
            ->where('click_count', '>', 0)
            ->orderByDesc('click_count')
            ->orderBy('name')
            ->get();

        $hotPickedHotels = Hotel::query()
            ->where('click_count', 0)
            ->orderBy('name')
            ->get();

        if ($clickedHotels->count() >= 3) {
            $recommendations = $clickedHotels->take(3);
        } elseif ($clickedHotels->isNotEmpty()) {
            $recommendations = $clickedHotels->concat($hotPickedHotels)->take(3);
        } else {
            $recommendations = $hotPickedHotels->take(3);
        }

        return view('User.home', [
            'selectedState' => $effectiveSelectedState,
            'searchTerm' => $searchTerm,
            'hotelStates' => $hotelStates,
            'filteredHotels' => $filteredHotels,
            'recommendations' => $recommendations,
        ]);
    })->name('home');

    Route::get('/user/profile', [UserProfileController::class, 'edit'])->name('user.profile.edit');
    Route::put('/user/profile', [UserProfileController::class, 'update'])->name('user.profile.update');

    // Booking Page
    Route::get('/hotel/{id}/book', function ($id) {
        $hotel = Hotel::findOrFail($id);
        $hotel->increment('click_count');

        return view('User.booking', compact('id', 'hotel'));
    })->name('user.booking.create');

    // Payment Page
    Route::get('/booking/{booking_id}/payment', function ($booking_id) {
        return view('User.payment', compact('booking_id'));
    })->name('user.payment');

    // History Page
    Route::get('/user/history', function () {
        return view('User.history');
    })->name('user.history');
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