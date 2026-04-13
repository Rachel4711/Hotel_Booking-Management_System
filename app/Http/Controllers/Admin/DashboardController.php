<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Hotel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DashboardController extends Controller
{
    public function index(Request $request): Response
    {
        $search = $request->has('search')
            ? trim((string) $request->query('search', ''))
            : trim((string) $request->cookie('admin_dashboard_search', ''));

        $role = $request->has('role')
            ? strtolower(trim((string) $request->query('role', '')))
            : strtolower(trim((string) $request->cookie('admin_dashboard_role', '')));
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

        return response()->view('Admin.dashboard', [
            'totalCustomers' => User::where('role', 'user')->count(),
            'totalHotels' => Hotel::count(),
            'totalBookings' => Booking::count(),
            'users' => $users,
            'search' => $search,
            'role' => $role,
        ])
            ->cookie('admin_dashboard_search', $search, 43200)
            ->cookie('admin_dashboard_role', $role, 43200);
    }
}
