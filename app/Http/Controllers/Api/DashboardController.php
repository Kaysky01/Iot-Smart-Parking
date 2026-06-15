<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Parking;
use App\Models\Transaction;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $totalToday = Parking::whereDate('entry_time', $today)->count();
        $activeIn = Parking::where('status', 'IN')->count();
        $revenue = Transaction::whereDate('created_at', $today)->sum('amount');
        $parkings = Parking::with('user')->orderByDesc('id')->take(20)->get();
        return response()->json([
            'total_today' => $totalToday,
            'active_in' => $activeIn,
            'revenue' => 'Rp ' . number_format($revenue, 0, ',', '.'),
            'parkings' => $parkings,
        ]);
    }
}
