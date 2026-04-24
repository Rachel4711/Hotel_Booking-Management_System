<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Hotel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    private const BOOKING_STATUSES = ['confirmed', 'pending', 'cancelled'];

    private const STATUS_COLORS = [
        'confirmed' => '#10b981',
        'pending' => '#f59e0b',
        'cancelled' => '#ef4444',
    ];

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

        $totalBookings = Booking::count();

        $statusCounts = Booking::query()
            ->selectRaw('LOWER(status) as status, COUNT(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        $bookingStatusBreakdown = collect(self::BOOKING_STATUSES)
            ->map(function (string $status) use ($statusCounts, $totalBookings): array {
                $count = (int) ($statusCounts[$status] ?? 0);
                $percentage = $totalBookings > 0
                    ? round(($count / $totalBookings) * 100, 1)
                    : 0.0;

                return [
                    'key' => $status,
                    'label' => ucfirst($status),
                    'count' => $count,
                    'percentage' => $percentage,
                    'color' => self::STATUS_COLORS[$status],
                ];
            })
            ->values()
            ->all();

        $periodStart = now()->startOfMonth()->subMonths(5);
        $periodEnd = now()->endOfMonth();

        $monthlyRevenueRaw = Booking::query()
            ->whereBetween('created_at', [$periodStart, $periodEnd])
            ->where('payment_status', 'paid')
            ->where('status', '!=', 'cancelled')
            ->get(['created_at', 'total_price'])
            ->groupBy(function (Booking $booking): string {
                return Carbon::parse($booking->created_at)->format('Y-m');
            })
            ->map(function ($group): float {
                return (float) $group->sum(function (Booking $booking): float {
                    return (float) $booking->total_price;
                });
            });

        $revenueTrend = collect(range(0, 5))
            ->map(function (int $monthOffset) use ($periodStart, $monthlyRevenueRaw): array {
                $month = $periodStart->copy()->addMonths($monthOffset);
                $monthKey = $month->format('Y-m');
                $total = (float) ($monthlyRevenueRaw[$monthKey] ?? 0);

                return [
                    'month_key' => $monthKey,
                    'label' => $month->format('M Y'),
                    'short_label' => $month->format('M'),
                    'total' => round($total, 2),
                ];
            })
            ->values()
            ->all();

        $totalRevenueInPeriod = collect($revenueTrend)->sum('total');
        $currentMonthRevenue = (float) (collect($revenueTrend)->last()['total'] ?? 0);
        $previousMonthRevenue = (float) (collect($revenueTrend)->slice(-2, 1)->first()['total'] ?? 0);
        $revenueChangePercentage = $previousMonthRevenue > 0
            ? round((($currentMonthRevenue - $previousMonthRevenue) / $previousMonthRevenue) * 100, 1)
            : ($currentMonthRevenue > 0 ? 100.0 : 0.0);

        return response()->view('Admin.dashboard', [
            'totalCustomers' => User::where('role', 'user')->count(),
            'totalHotels' => Hotel::count(),
            'totalBookings' => $totalBookings,
            'bookingStatusBreakdown' => $bookingStatusBreakdown,
            'revenueTrend' => $revenueTrend,
            'totalRevenueInPeriod' => $totalRevenueInPeriod,
            'currentMonthRevenue' => $currentMonthRevenue,
            'revenueChangePercentage' => $revenueChangePercentage,
            'users' => $users,
            'search' => $search,
            'role' => $role,
        ])
            ->cookie('admin_dashboard_search', $search, 43200)
            ->cookie('admin_dashboard_role', $role, 43200);
    }
}
