<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Hotel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();
        $role = strtolower(trim($request->string('role')->toString()));
        $role = in_array($role, ['user', 'admin'], true) ? $role : '';

        $users = User::query()
            ->when($role !== '', function ($query) use ($role) {
                $query->whereRaw('LOWER(role) = ?', [$role]);
            })
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('Admin.dashboard', [
            'totalCustomers' => User::where('role', 'user')->count(),
            'totalHotels' => Hotel::count(),
            'totalBookings' => Booking::count(),
            'users' => $users,
            'search' => $search,
            'role' => $role,
        ]);
    }
}
