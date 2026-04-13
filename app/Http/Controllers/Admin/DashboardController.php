<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Hotel;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('Admin.dashboard', [
            'totalCustomers' => User::where('role', 'user')->count(),
            'totalHotels' => Hotel::count(),
            'totalBookings' => Booking::count(),
        ]);
    }
}
