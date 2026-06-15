<?php

namespace App\Http\Controllers;

use App\Models\Parking;
use App\Models\TopUpRequest;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    /**
     * Show the dashboard page.
     */
    public function index()
    {
        $totalVehiclesToday = Parking::today()->count();
        $activeVehicles = Parking::active()->count();
        $totalRevenue = Transaction::whereDate('created_at', today())->sum('amount');
        $recentParkings = Parking::with('user')
            ->orderByDesc('id')
            ->limit(20)
            ->get();

        // Revenue data for chart (last 7 days)
        $revenueChart = $this->getRevenueChartData();

        // Top-up request stats
        $pendingTopUpRequests = TopUpRequest::pending()->count();
        $totalStudents = User::where('role', 'student')->count();

        return view('dashboard.index', compact(
            'totalVehiclesToday',
            'activeVehicles',
            'totalRevenue',
            'recentParkings',
            'revenueChart',
            'pendingTopUpRequests',
            'totalStudents',
        ));
    }

    /**
     * API endpoint for real-time dashboard data refresh.
     */
    public function apiData(): JsonResponse
    {
        return response()->json([
            'total_today' => Parking::today()->count(),
            'active_in' => Parking::active()->count(),
            'revenue' => Transaction::whereDate('created_at', today())->sum('amount'),
            'parkings' => Parking::with('user')
                ->orderByDesc('id')
                ->limit(20)
                ->get()
                ->map(fn($p) => [
                    'id' => $p->id,
                    'user' => [
                        'name' => $p->user->name,
                        'rfid_uid' => $p->user->rfid_uid,
                    ],
                    'entry_time' => $p->entry_time->format('Y-m-d H:i:s'),
                    'exit_time' => $p->exit_time?->format('Y-m-d H:i:s'),
                    'duration' => $p->duration,
                    'cost' => $p->cost,
                    'status' => $p->status,
                ]),
        ]);
    }

    /**
     * Get revenue chart data for last 7 days.
     */
    private function getRevenueChartData(): array
    {
        $labels = [];
        $data = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $labels[] = $date->format('d M');
            $data[] = Transaction::whereDate('created_at', $date)->sum('amount');
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }
}
