@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Live Dashboard')
@section('page-subtitle', 'Real-time parking monitoring & analytics')

@section('content')
<div class="space-y-6">

    {{-- ===== STATS CARDS ===== --}}
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-6">

        {{-- Total Vehicles Today --}}
        <div class="stat-card p-6 border-b-4 border-b-[var(--app-info)]">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-semibold text-[var(--app-text-secondary)] uppercase tracking-wider">Vehicles Today</p>
                    <p class="text-4xl font-extrabold text-[var(--app-text)] mt-2 counter-animate" id="stat-total-today">{{ $totalVehiclesToday }}</p>
                    <p class="text-[11px] font-medium text-[var(--app-text-muted)] mt-1">All entries for today</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-[var(--app-info-soft)] flex items-center justify-center">
                    <svg class="w-6 h-6 text-[var(--app-info)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Active Vehicles (IN) --}}
        <div class="stat-card p-6 border-b-4 border-b-[var(--app-success)]">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-semibold text-[var(--app-text-secondary)] uppercase tracking-wider">Active</p>
                    <p class="text-4xl font-extrabold text-[var(--app-success)] mt-2 counter-animate" id="stat-active-in">{{ $activeVehicles }}</p>
                    <p class="text-[11px] font-medium text-[var(--app-text-muted)] mt-1">Currently parked (IN)</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-[var(--app-success-soft)] flex items-center justify-center">
                    <svg class="w-6 h-6 text-[var(--app-success)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Total Revenue --}}
        <div class="stat-card p-6 border-b-4 border-b-[var(--app-warning)]">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-semibold text-[var(--app-text-secondary)] uppercase tracking-wider">Revenue</p>
                    <p class="text-3xl font-extrabold text-[var(--app-text)] mt-2 counter-animate" id="stat-revenue">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                    <p class="text-[11px] font-medium text-[var(--app-text-muted)] mt-1">Earnings today</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-[var(--app-warning-soft)] flex items-center justify-center">
                    <svg class="w-6 h-6 text-[var(--app-warning)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Pending Top-Up Requests --}}
        <div class="stat-card p-6 border-b-4 border-b-[var(--app-warning)]">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-semibold text-[var(--app-text-secondary)] uppercase tracking-wider">Top-Ups</p>
                    <p class="text-4xl font-extrabold text-[var(--app-warning)] mt-2 counter-animate" id="stat-pending-topups">{{ $pendingTopUpRequests }}</p>
                    <p class="text-[11px] font-medium text-[var(--app-text-muted)] mt-1">Pending approval</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-[var(--app-warning-soft)] flex items-center justify-center">
                    <svg class="w-6 h-6 text-[var(--app-warning)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Total Students --}}
        <div class="stat-card p-6 border-b-4 border-b-[var(--app-primary)]">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-semibold text-[var(--app-text-secondary)] uppercase tracking-wider">Students</p>
                    <p class="text-4xl font-extrabold text-[var(--app-primary)] mt-2 counter-animate" id="stat-students">{{ $totalStudents }}</p>
                    <p class="text-[11px] font-medium text-[var(--app-text-muted)] mt-1">Registered accounts</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-[var(--app-primary-soft)] flex items-center justify-center">
                    <svg class="w-6 h-6 text-[var(--app-primary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== CHART & ACTIVITY ===== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Revenue Chart --}}
        <div class="lg:col-span-2 app-card p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="app-section-title text-lg">Revenue Overview</h3>
                    <p class="app-subtitle text-sm">Last 7 days</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-[var(--app-info-soft)] flex items-center justify-center">
                    <svg class="w-5 h-5 text-[var(--app-info)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                    </svg>
                </div>
            </div>
            <div class="relative" style="height: 280px;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        {{-- Quick Info --}}
        <div class="app-card p-6">
            <h3 class="app-section-title text-lg mb-4">Pricing Info</h3>
            <div class="space-y-4">
                <div class="flex items-center gap-4 p-3.5 rounded-xl bg-[var(--app-surface-soft)] border border-[var(--app-border)]">
                    <div class="w-10 h-10 rounded-lg bg-[var(--app-info-soft)] flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-[var(--app-info)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-[var(--app-text-secondary)]">First Hour</p>
                        <p class="text-base font-bold text-[var(--app-text)]">Rp 2.000</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 p-3.5 rounded-xl bg-[var(--app-surface-soft)] border border-[var(--app-border)]">
                    <div class="w-10 h-10 rounded-lg bg-[var(--app-success-soft)] flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-[var(--app-success)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-[var(--app-text-secondary)]">Next Hours</p>
                        <p class="text-base font-bold text-[var(--app-text)]">Rp 1.000/jam</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 p-3.5 rounded-xl bg-[var(--app-surface-soft)] border border-[var(--app-border)]">
                    <div class="w-10 h-10 rounded-lg bg-[var(--app-warning-soft)] flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-[var(--app-warning)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-[var(--app-text-secondary)]">Default Balance</p>
                        <p class="text-base font-bold text-[var(--app-text)]">Rp 10.000</p>
                    </div>
                </div>
            </div>

            <div class="mt-6 p-4 rounded-xl bg-[var(--app-primary-softer)] border border-[var(--app-primary-soft)]">
                <p class="text-xs font-bold text-[var(--app-primary)] uppercase tracking-wider mb-2">ESP32 API Endpoint</p>
                <code class="text-sm font-mono text-[var(--app-text)] font-semibold break-all">POST /api/scan</code>
                <p class="text-[11px] text-[var(--app-text-secondary)] mt-1 font-medium">Body: { "uid": "RFID_UID" }</p>
            </div>
        </div>
    </div>

    {{-- ===== PARKING TABLE ===== --}}
    <div class="app-card overflow-hidden">
        <div class="px-6 py-5 border-b border-[var(--app-border)] flex items-center justify-between">
            <div>
                <h3 class="app-section-title text-lg">Live Parking Activity</h3>
                <p class="app-subtitle text-sm mt-0.5">Real-time entry & exit monitoring</p>
            </div>
            <div class="flex items-center gap-2 text-xs font-bold text-[var(--app-success)] bg-[var(--app-success-soft)] px-3 py-1.5 rounded-full">
                <span class="w-2 h-2 rounded-full bg-[var(--app-success)] pulse-live"></span>
                Auto-updating
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left" id="parking-table">
                <thead>
                    <tr class="bg-[var(--app-surface-soft)]">
                        <th class="px-6 py-4 text-xs font-bold text-[var(--app-text-secondary)] uppercase tracking-wider">User</th>
                        <th class="px-6 py-4 text-xs font-bold text-[var(--app-text-secondary)] uppercase tracking-wider">Entry Time</th>
                        <th class="px-6 py-4 text-xs font-bold text-[var(--app-text-secondary)] uppercase tracking-wider">Exit Time</th>
                        <th class="px-6 py-4 text-xs font-bold text-[var(--app-text-secondary)] uppercase tracking-wider">Duration</th>
                        <th class="px-6 py-4 text-xs font-bold text-[var(--app-text-secondary)] uppercase tracking-wider">Cost</th>
                        <th class="px-6 py-4 text-xs font-bold text-[var(--app-text-secondary)] uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody id="parking-table-body" class="divide-y divide-[var(--app-border)]">
                    @forelse($recentParkings as $parking)
                    <tr id="parking-row-{{ $parking->id }}" class="table-row-hover bg-[var(--app-surface)]">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-[var(--app-primary)] flex items-center justify-center text-white text-sm font-bold shadow-sm">
                                    {{ strtoupper(substr($parking->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-bold text-sm text-[var(--app-text)]">{{ $parking->user->name }}</div>
                                    <div class="text-[11px] font-medium text-[var(--app-text-muted)] font-mono">{{ $parking->user->rfid_uid }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-[var(--app-text-secondary)]">{{ $parking->entry_time->format('d M Y, H:i') }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-[var(--app-text-secondary)]">{{ $parking->exit_time ? $parking->exit_time->format('d M Y, H:i') : '-' }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-[var(--app-text-secondary)]">{{ $parking->formatted_duration }}</td>
                        <td class="px-6 py-4 text-sm font-bold text-[var(--app-text)]">{{ $parking->formatted_cost }}</td>
                        <td class="px-6 py-4">
                            @if($parking->status === 'IN')
                                <span class="app-badge-success">● IN</span>
                            @else
                                <span class="app-badge-neutral">● OUT</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="app-empty-state max-w-sm mx-auto">
                                <div class="app-empty-icon">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                </div>
                                <p class="text-base font-bold text-[var(--app-text)] mb-1">No parking records</p>
                                <p class="text-sm text-[var(--app-text-secondary)]">Records will appear here when vehicles are scanned</p>
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
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('revenueChart');
        if (ctx && typeof Chart !== 'undefined') {

            // Helper to get CSS variable values
            const getVar = (name) => getComputedStyle(document.documentElement).getPropertyValue(name).trim();

            const isDark = document.documentElement.classList.contains('dark');
            const textColor = getVar('--app-text-muted') || (isDark ? '#94a3b8' : '#64748b');
            const gridColor = getVar('--app-border') || (isDark ? 'rgba(148, 163, 184, 0.1)' : 'rgba(226, 232, 240, 0.8)');
            const tooltipBg = getVar('--app-surface-soft') || (isDark ? '#1e293b' : '#f8fafc');
            const tooltipText = getVar('--app-text') || (isDark ? '#ffffff' : '#1e293b');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($revenueChart['labels']),
                    datasets: [{
                        label: 'Revenue (Rp)',
                        data: @json($revenueChart['data']),
                        backgroundColor: 'rgba(37, 99, 235, 0.2)', // primary soft
                        borderColor: '#2563eb', // primary
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
                            backgroundColor: tooltipBg,
                            titleColor: textColor,
                            bodyColor: tooltipText,
                            bodyFont: { weight: 'bold', family: "'Poppins', sans-serif" },
                            titleFont: { family: "'Poppins', sans-serif" },
                            borderColor: gridColor,
                            borderWidth: 1,
                            padding: 12,
                            cornerRadius: 12,
                            displayColors: false,
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: {
                                color: textColor,
                                font: { size: 12, weight: '500', family: "'Poppins', sans-serif" }
                            },
                            border: { display: false }
                        },
                        y: {
                            grid: {
                                color: gridColor,
                                drawBorder: false,
                            },
                            ticks: {
                                color: textColor,
                                font: { size: 12, family: "'Poppins', sans-serif" },
                                callback: function(value) {
                                    if (value >= 1000) {
                                        return 'Rp ' + (value/1000) + 'k';
                                    }
                                    return 'Rp ' + value;
                                }
                            },
                            border: { display: false }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
