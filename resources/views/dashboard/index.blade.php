@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Live Dashboard')
@section('page-subtitle', 'Real-time parking monitoring & analytics')

@section('content')
<div class="space-y-6">

    {{-- ===== STATS CARDS ===== --}}
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-6">

        {{-- Total Vehicles Today --}}
        <div class="stat-card relative overflow-hidden bg-slate-800 rounded-2xl shadow-md border border-slate-700 p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider">Vehicles Today</p>
                    <p class="text-4xl font-extrabold text-white mt-2 counter-animate" id="stat-total-today">{{ $totalVehiclesToday }}</p>
                    <p class="text-xs text-slate-500 mt-1">All entries for today</p>
                </div>
                <div class="w-14 h-14 rounded-2xl gradient-primary flex items-center justify-center shadow-lg shadow-blue-500/20">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
            <div class="absolute -bottom-4 -right-4 w-24 h-24 rounded-full bg-blue-900/20 opacity-50"></div>
        </div>

        {{-- Active Vehicles (IN) --}}
        <div class="stat-card relative overflow-hidden bg-slate-800 rounded-2xl shadow-md border border-slate-700 p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider">Active Vehicles</p>
                    <p class="text-4xl font-extrabold text-emerald-400 mt-2 counter-animate" id="stat-active-in">{{ $activeVehicles }}</p>
                    <p class="text-xs text-slate-500 mt-1">Currently parked (IN)</p>
                </div>
                <div class="w-14 h-14 rounded-2xl gradient-success flex items-center justify-center shadow-lg shadow-emerald-500/20">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
            <div class="absolute -bottom-4 -right-4 w-24 h-24 rounded-full bg-emerald-900/20 opacity-50"></div>
        </div>

        {{-- Total Revenue --}}
        <div class="stat-card relative overflow-hidden bg-slate-800 rounded-2xl shadow-md border border-slate-700 p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider">Today's Revenue</p>
                    <p class="text-4xl font-extrabold text-white mt-2 counter-animate" id="stat-revenue">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                    <p class="text-xs text-slate-500 mt-1">Total earnings today</p>
                </div>
                <div class="w-14 h-14 rounded-2xl gradient-warning flex items-center justify-center shadow-lg shadow-amber-500/20">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="absolute -bottom-4 -right-4 w-24 h-24 rounded-full bg-amber-900/20 opacity-50"></div>
        </div>

        {{-- Pending Top-Up Requests --}}
        <div class="stat-card relative overflow-hidden bg-slate-800 rounded-2xl shadow-md border border-slate-700 p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider">Pending Top-Ups</p>
                    <p class="text-4xl font-extrabold text-amber-400 mt-2 counter-animate" id="stat-pending-topups">{{ $pendingTopUpRequests }}</p>
                    <p class="text-xs text-slate-500 mt-1">Awaiting approval</p>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-amber-500/20 flex items-center justify-center">
                    <svg class="w-7 h-7 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
            </div>
            <div class="absolute -bottom-4 -right-4 w-24 h-24 rounded-full bg-amber-900/20 opacity-50"></div>
        </div>

        {{-- Total Students --}}
        <div class="stat-card relative overflow-hidden bg-slate-800 rounded-2xl shadow-md border border-slate-700 p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider">Students</p>
                    <p class="text-4xl font-extrabold text-blue-400 mt-2 counter-animate" id="stat-students">{{ $totalStudents }}</p>
                    <p class="text-xs text-slate-500 mt-1">Registered students</p>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-blue-500/20 flex items-center justify-center">
                    <svg class="w-7 h-7 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                    </svg>
                </div>
            </div>
            <div class="absolute -bottom-4 -right-4 w-24 h-24 rounded-full bg-blue-900/20 opacity-50"></div>
        </div>
    </div>

    {{-- ===== CHART & ACTIVITY ===== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Revenue Chart --}}
        <div class="lg:col-span-2 bg-slate-800 rounded-2xl shadow-md border border-slate-700 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-white">Revenue Overview</h3>
                    <p class="text-sm text-slate-400">Last 7 days</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-blue-900/30 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                    </svg>
                </div>
            </div>
            <div class="relative" style="height: 280px;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        {{-- Quick Info --}}
        <div class="bg-slate-800 rounded-2xl shadow-md border border-slate-700 p-6">
            <h3 class="text-lg font-bold text-white mb-4">Pricing Info</h3>
            <div class="space-y-4">
                <div class="flex items-center gap-3 p-3 rounded-xl bg-blue-900/20">
                    <div class="w-10 h-10 rounded-lg bg-blue-500/20 flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-200">First Hour</p>
                        <p class="text-lg font-bold text-blue-400">Rp 2.000</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 p-3 rounded-xl bg-emerald-900/20">
                    <div class="w-10 h-10 rounded-lg bg-emerald-500/20 flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-200">Next Hours</p>
                        <p class="text-lg font-bold text-emerald-400">Rp 1.000/jam</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 p-3 rounded-xl bg-amber-900/20">
                    <div class="w-10 h-10 rounded-lg bg-amber-500/20 flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-200">Default Balance</p>
                        <p class="text-lg font-bold text-amber-400">Rp 10.000</p>
                    </div>
                </div>
            </div>

            <div class="mt-6 p-4 rounded-xl bg-slate-700/50 border border-slate-600">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">ESP32 API Endpoint polinela</p>
                <code class="text-sm font-mono text-blue-400 break-all">POST /api/scan</code>
                <p class="text-xs text-slate-500 mt-1">Body: { "uid": "RFID_UID" }</p>
            </div>
        </div>
    </div>

    {{-- ===== PARKING TABLE ===== --}}
    <div class="bg-slate-800 rounded-2xl shadow-md border border-slate-700 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-700 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-white">Live Parking Activity</h3>
                <p class="text-sm text-slate-400 mt-0.5">Real-time entry & exit monitoring</p>
            </div>
            <div class="flex items-center gap-2 text-xs font-semibold text-emerald-400 bg-emerald-900/30 px-3 py-1.5 rounded-full">
                <span class="w-2 h-2 rounded-full bg-emerald-500 pulse-live"></span>
                Auto-updating
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full" id="parking-table">
                <thead>
                    <tr class="bg-slate-700/50">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">User</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Entry Time</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Exit Time</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Duration</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Cost</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody id="parking-table-body" class="divide-y divide-slate-700">
                    @forelse($recentParkings as $parking)
                    <tr id="parking-row-{{ $parking->id }}" class="table-row-hover">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full gradient-primary flex items-center justify-center text-white text-sm font-bold">
                                    {{ strtoupper(substr($parking->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-semibold text-slate-200">{{ $parking->user->name }}</div>
                                    <div class="text-xs text-slate-500">{{ $parking->user->rfid_uid }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-300">{{ $parking->entry_time->format('d M Y, H:i') }}</td>
                        <td class="px-6 py-4 text-slate-300">{{ $parking->exit_time ? $parking->exit_time->format('d M Y, H:i') : '-' }}</td>
                        <td class="px-6 py-4 text-slate-300">{{ $parking->formatted_duration }}</td>
                        <td class="px-6 py-4 font-semibold text-slate-200">{{ $parking->formatted_cost }}</td>
                        <td class="px-6 py-4">
                            @if($parking->status === 'IN')
                                <span class="badge-in">● IN</span>
                            @else
                                <span class="badge-out">● OUT</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-slate-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                                <p class="text-slate-400 font-medium">No parking records yet</p>
                                <p class="text-sm text-slate-500 mt-1">Records will appear here when vehicles are scanned</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection



@push('scripts')
<script>
    // Revenue Chart (Chart.js)
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('revenueChart');
        if (ctx && typeof Chart !== 'undefined') {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($revenueChart['labels']),
                    datasets: [{
                        label: 'Revenue (Rp)',
                        data: @json($revenueChart['data']),
                        backgroundColor: 'rgba(37, 99, 235, 0.15)',
                        borderColor: 'rgba(37, 99, 235, 0.8)',
                        borderWidth: 2,
                        borderRadius: 8,
                        borderSkipped: false,
                        barThickness: 32,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
                                }
                            },
                            backgroundColor: '#1e293b',
                            titleColor: '#94a3b8',
                            bodyColor: '#ffffff',
                            bodyFont: { weight: 'bold' },
                            padding: 12,
                            cornerRadius: 10,
                            displayColors: false,
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: {
                                color: '#94a3b8',
                                font: { size: 12, weight: '500' }
                            }
                        },
                        y: {
                            grid: {
                                color: 'rgba(148, 163, 184, 0.1)',
                                drawBorder: false,
                            },
                            ticks: {
                                color: '#94a3b8',
                                font: { size: 12 },
                                callback: function(value) {
                                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                }
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
