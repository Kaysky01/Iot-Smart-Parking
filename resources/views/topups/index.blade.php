@extends('layouts.app')

@section('title', 'Top-Up Balance')
@section('page-title', 'Top-Up Management')
@section('page-subtitle', 'Manage and increase RFID user balances')

@section('content')
<div class="space-y-6">

    {{-- ===== STATS CARDS ===== --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Today's Total Amount --}}
        <div class="stat-card p-6 border-b-4 border-b-[var(--app-success)]">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-semibold text-[var(--app-text-secondary)] uppercase tracking-wider">Top-Ups Today</p>
                    <p class="text-3xl font-extrabold text-[var(--app-text)] mt-2 counter-animate" id="stat-topups-today">Rp {{ number_format($totalTopUpsToday, 0, ',', '.') }}</p>
                    <p class="text-[11px] font-medium text-[var(--app-text-muted)] mt-1">Total value added today</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-[var(--app-success-soft)] flex items-center justify-center">
                    <svg class="w-6 h-6 text-[var(--app-success)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Today's Transaction Count --}}
        <div class="stat-card p-6 border-b-4 border-b-[var(--app-primary)]">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-semibold text-[var(--app-text-secondary)] uppercase tracking-wider">Transactions</p>
                    <p class="text-3xl font-extrabold text-[var(--app-text)] mt-2 counter-animate" id="stat-topups-count">{{ $totalTopUpsCount }}</p>
                    <p class="text-[11px] font-medium text-[var(--app-text-muted)] mt-1">Processed today</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-[var(--app-primary-soft)] flex items-center justify-center">
                    <svg class="w-6 h-6 text-[var(--app-primary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
            </div>
        </div>

        {{-- All-Time Total Amount --}}
        <div class="stat-card p-6 border-b-4 border-b-[var(--app-warning)]">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-semibold text-[var(--app-text-secondary)] uppercase tracking-wider">All-Time Revenue</p>
                    <p class="text-3xl font-extrabold text-[var(--app-text)] mt-2 counter-animate" id="stat-topups-alltime">Rp {{ number_format($totalTopUpsAllTime, 0, ',', '.') }}</p>
                    <p class="text-[11px] font-medium text-[var(--app-text-muted)] mt-1">Total historical top-ups</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-[var(--app-warning-soft)] flex items-center justify-center">
                    <svg class="w-6 h-6 text-[var(--app-warning)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== TWO COLUMN LAYOUT ===== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left Column: New Top-Up Form --}}
        <div class="lg:col-span-1 space-y-6">
            <div class="app-card p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-[var(--app-primary-soft)] text-[var(--app-primary)] border border-[color-mix(in_srgb,var(--app-primary)_20%,transparent)] flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="app-section-title text-lg">New Top-Up</h3>
                        <p class="text-sm text-[var(--app-text-muted)] mt-0.5">Increase account balance</p>
                    </div>
                </div>

                {{-- Toast Success message --}}
                @if(session('success'))
                    <div class="mb-4 p-4 rounded-xl bg-[var(--app-success-soft)] border border-[color-mix(in_srgb,var(--app-success)_20%,transparent)] text-[var(--app-success)] text-sm font-semibold flex items-start gap-3">
                        <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <form id="topup-form" method="POST" action="{{ route('topups.store') }}" class="space-y-5">
                    @csrf

                    <input type="hidden" name="user_id" id="selected-user-id" value="{{ old('user_id') }}">
                    <input type="hidden" id="selection-mode" value="search">

                    {{-- User Selection Mode Toggle --}}
                    <div class="space-y-2">
                        <label class="block text-[11px] font-bold text-[var(--app-text-secondary)] uppercase tracking-wider mb-1.5">Selection Mode</label>
                        <div class="flex p-1 rounded-xl bg-[var(--app-surface-soft)] border border-[var(--app-border)]">
                            <button type="button" id="btn-mode-search" class="flex-grow py-2 text-xs font-bold rounded-lg transition-all bg-[var(--app-surface)] text-[var(--app-primary)] shadow-sm border border-[var(--app-border)]">
                                🔍 Search Database
                            </button>
                            <button type="button" id="btn-mode-manual" class="flex-grow py-2 text-xs font-semibold rounded-lg transition-all text-[var(--app-text-secondary)] hover:text-[var(--app-text)] hover:bg-[var(--app-surface)]">
                                🎴 RFID Tap
                            </button>
                        </div>
                    </div>

                    {{-- Mode 1: Search Autocomplete --}}
                    <div id="section-search" class="space-y-2">
                        <label class="block text-[11px] font-bold text-[var(--app-text-secondary)] uppercase tracking-wider mb-1.5">Search User</label>

                        <div id="search-input-container" class="relative {{ old('user_id') ? 'hidden' : '' }}">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[var(--app-text-muted)]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" id="search-user-input" autocomplete="off"
                                   placeholder="Type user name or RFID..."
                                   class="app-input pl-11">

                            <div id="search-results" class="hidden absolute left-0 right-0 mt-2 bg-[var(--app-surface)] rounded-xl shadow-lg border border-[var(--app-border)] max-h-60 overflow-y-auto z-50 divide-y divide-[var(--app-border)]">
                            </div>
                        </div>

                        <div id="selected-user-card" class="p-4 rounded-xl bg-[var(--app-primary-softer)] border border-[var(--app-primary-soft)] relative {{ old('user_id') ? '' : 'hidden' }}">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-[var(--app-primary)] flex items-center justify-center text-white font-bold" id="selected-user-avatar">
                                    -
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-bold text-[var(--app-text)] truncate" id="selected-user-name">-</h4>
                                    <div class="flex items-center gap-2 mt-1">
                                        <code class="app-code bg-transparent border-0 p-0 text-[10px] text-[var(--app-text-muted)] font-bold uppercase" id="selected-user-rfid">-</code>
                                        <span class="text-xs font-bold text-[var(--app-text-secondary)]" id="selected-user-balance">-</span>
                                    </div>
                                </div>
                                <button type="button" id="btn-change-user"
                                        class="px-3 py-1.5 rounded-lg text-xs font-bold text-[var(--app-danger)] hover:bg-[var(--app-danger-soft)] transition-colors border border-[color-mix(in_srgb,var(--app-danger)_20%,transparent)]">
                                    Change
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Mode 2: Manual RFID / Card Tap --}}
                    <div id="section-manual" class="space-y-4 hidden">
                        <div class="p-4 rounded-xl border border-dashed border-[var(--app-border-strong)] bg-[var(--app-surface-soft)] flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <span class="relative flex h-3 w-3">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[var(--app-success)] opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-3 w-3 bg-[var(--app-success)]"></span>
                                </span>
                                <div>
                                    <p class="text-xs font-bold text-[var(--app-text)]">Live RFID Listener</p>
                                    <p class="text-[11px] font-medium text-[var(--app-text-muted)]" id="tap-listener-status">Ready. Tap card on reader...</p>
                                </div>
                            </div>
                            <span class="text-2xl animate-pulse grayscale opacity-70">🎴</span>
                        </div>

                        <div class="space-y-2">
                            <label for="manual-rfid-input" class="block text-[11px] font-bold text-[var(--app-text-secondary)] uppercase tracking-wider mb-1.5">RFID UID</label>
                            <input type="text" name="rfid_uid" id="manual-rfid-input" placeholder="Enter Card ID..."
                                   class="app-input font-mono uppercase text-sm">
                        </div>

                        <div id="rfid-lookup-info" class="hidden p-4 rounded-xl bg-[var(--app-surface-soft)] border border-[var(--app-border)] space-y-3">
                        </div>
                    </div>

                    {{-- Top-Up Amount field --}}
                    <div class="space-y-2">
                        <label for="amount-input" class="block text-[11px] font-bold text-[var(--app-text-secondary)] uppercase tracking-wider mb-1.5">Amount (IDR)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-[var(--app-text-secondary)] font-bold text-base pointer-events-none">Rp</span>
                            <input type="number" name="amount" id="amount-input" required min="1000" max="10000000" value="{{ old('amount') }}"
                                   placeholder="0"
                                   class="app-input pl-11 font-bold text-lg">
                        </div>

                        {{-- Quick selects --}}
                        <div class="grid grid-cols-3 gap-2 mt-2">
                            <button type="button" class="btn-quick-select app-button-secondary py-2 text-xs font-bold" data-amount="10000">10k</button>
                            <button type="button" class="btn-quick-select app-button-secondary py-2 text-xs font-bold" data-amount="20000">20k</button>
                            <button type="button" class="btn-quick-select app-button-secondary py-2 text-xs font-bold" data-amount="50000">50k</button>
                            <button type="button" class="btn-quick-select app-button-secondary py-2 text-xs font-bold" data-amount="100000">100k</button>
                            <button type="button" class="btn-quick-select app-button-secondary py-2 text-xs font-bold" data-amount="200000">200k</button>
                            <button type="button" class="btn-quick-select app-button-secondary py-2 text-xs font-bold" data-amount="500000">500k</button>
                        </div>
                        @error('amount')
                            <p class="text-xs text-[var(--app-danger)] mt-1 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Payment Method Selection --}}
                    <div class="space-y-2">
                        <label class="block text-[11px] font-bold text-[var(--app-text-secondary)] uppercase tracking-wider mb-1.5">Payment Method</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="relative flex items-center justify-between p-3 rounded-xl border border-[var(--app-border)] cursor-pointer bg-[var(--app-surface-soft)] hover:border-[var(--app-primary-soft)] hover:bg-[var(--app-primary-softer)] transition-all select-none">
                                <input type="radio" name="method" value="cash" checked class="sr-only peer">
                                <div class="flex items-center gap-2">
                                    <span class="text-[var(--app-text-muted)] peer-checked:text-[var(--app-primary)] grayscale peer-checked:grayscale-0">💵</span>
                                    <span class="text-sm font-bold text-[var(--app-text-secondary)] peer-checked:text-[var(--app-primary)]">Cash</span>
                                </div>
                                <div class="w-4 h-4 rounded-full border-2 border-[var(--app-border-strong)] flex items-center justify-center peer-checked:border-[var(--app-primary)] peer-checked:bg-[var(--app-primary)]">
                                    <div class="w-1.5 h-1.5 rounded-full bg-white hidden peer-checked:block"></div>
                                </div>
                            </label>

                            <label class="relative flex items-center justify-between p-3 rounded-xl border border-[var(--app-border)] cursor-pointer bg-[var(--app-surface-soft)] hover:border-[var(--app-primary-soft)] hover:bg-[var(--app-primary-softer)] transition-all select-none">
                                <input type="radio" name="method" value="transfer" class="sr-only peer">
                                <div class="flex items-center gap-2">
                                    <span class="text-[var(--app-text-muted)] peer-checked:text-[var(--app-primary)] grayscale peer-checked:grayscale-0">🏦</span>
                                    <span class="text-sm font-bold text-[var(--app-text-secondary)] peer-checked:text-[var(--app-primary)]">Transfer</span>
                                </div>
                                <div class="w-4 h-4 rounded-full border-2 border-[var(--app-border-strong)] flex items-center justify-center peer-checked:border-[var(--app-primary)] peer-checked:bg-[var(--app-primary)]">
                                    <div class="w-1.5 h-1.5 rounded-full bg-white hidden peer-checked:block"></div>
                                </div>
                            </label>

                            <label class="relative flex items-center justify-between p-3 rounded-xl border border-[var(--app-border)] cursor-pointer bg-[var(--app-surface-soft)] hover:border-[var(--app-primary-soft)] hover:bg-[var(--app-primary-softer)] transition-all select-none">
                                <input type="radio" name="method" value="qris" class="sr-only peer">
                                <div class="flex items-center gap-2">
                                    <span class="text-[var(--app-text-muted)] peer-checked:text-[var(--app-primary)] grayscale peer-checked:grayscale-0">📱</span>
                                    <span class="text-sm font-bold text-[var(--app-text-secondary)] peer-checked:text-[var(--app-primary)]">QRIS</span>
                                </div>
                                <div class="w-4 h-4 rounded-full border-2 border-[var(--app-border-strong)] flex items-center justify-center peer-checked:border-[var(--app-primary)] peer-checked:bg-[var(--app-primary)]">
                                    <div class="w-1.5 h-1.5 rounded-full bg-white hidden peer-checked:block"></div>
                                </div>
                            </label>

                            <label class="relative flex items-center justify-between p-3 rounded-xl border border-[var(--app-border)] cursor-pointer bg-[var(--app-surface-soft)] hover:border-[var(--app-primary-soft)] hover:bg-[var(--app-primary-softer)] transition-all select-none">
                                <input type="radio" name="method" value="other" class="sr-only peer">
                                <div class="flex items-center gap-2">
                                    <span class="text-[var(--app-text-muted)] peer-checked:text-[var(--app-primary)] grayscale peer-checked:grayscale-0">💳</span>
                                    <span class="text-sm font-bold text-[var(--app-text-secondary)] peer-checked:text-[var(--app-primary)]">Other</span>
                                </div>
                                <div class="w-4 h-4 rounded-full border-2 border-[var(--app-border-strong)] flex items-center justify-center peer-checked:border-[var(--app-primary)] peer-checked:bg-[var(--app-primary)]">
                                    <div class="w-1.5 h-1.5 rounded-full bg-white hidden peer-checked:block"></div>
                                </div>
                            </label>
                        </div>
                        @error('method')
                            <p class="text-xs text-[var(--app-danger)] mt-1 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Notes field --}}
                    <div class="space-y-2">
                        <label for="notes-textarea" class="block text-[11px] font-bold text-[var(--app-text-secondary)] uppercase tracking-wider mb-1.5">Notes (Optional)</label>
                        <textarea name="notes" id="notes-textarea" rows="2" maxlength="500"
                                  placeholder="E.g., Bank Ref No, ticket id..."
                                  class="app-textarea resize-none"></textarea>
                        @error('notes')
                            <p class="text-xs text-[var(--app-danger)] mt-1 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Submit Button --}}
                    <div class="pt-2">
                        <button type="submit" id="btn-submit-topup"
                                class="app-button-primary w-full py-3.5 font-bold tracking-wide flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Submit Top-Up
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Right Column: Top-Up History Table --}}
        <div class="lg:col-span-2 app-card overflow-hidden h-fit">
            <div class="px-6 py-5 border-b border-[var(--app-border)] flex items-center justify-between">
                <div>
                    <h3 class="app-section-title text-lg">Top-Up History</h3>
                    <p class="app-subtitle text-sm mt-0.5">Live record of all account top-ups</p>
                </div>
                <div class="flex items-center gap-2 text-xs font-bold text-[var(--app-success)] bg-[var(--app-success-soft)] px-3 py-1.5 rounded-full border border-[color-mix(in_srgb,var(--app-success)_20%,transparent)]">
                    <span class="w-2 h-2 rounded-full bg-[var(--app-success)] pulse-live"></span>
                    Auto-updating
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-[var(--app-surface-soft)] border-b border-[var(--app-border)]">
                            <th class="px-6 py-4 text-xs font-bold text-[var(--app-text-secondary)] uppercase tracking-wider">Date & Time</th>
                            <th class="px-6 py-4 text-xs font-bold text-[var(--app-text-secondary)] uppercase tracking-wider">User</th>
                            <th class="px-6 py-4 text-xs font-bold text-[var(--app-text-secondary)] uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-4 text-xs font-bold text-[var(--app-text-secondary)] uppercase tracking-wider">Balance Flow</th>
                            <th class="px-6 py-4 text-xs font-bold text-[var(--app-text-secondary)] uppercase tracking-wider">Method</th>
                        </tr>
                    </thead>
                    <tbody id="topups-table-body" class="divide-y divide-[var(--app-border)]">
                        @forelse($topups as $topup)
                        <tr id="topup-row-{{ $topup->id }}" class="table-row-hover bg-[var(--app-surface)]">
                            <td class="px-6 py-4 text-sm font-medium text-[var(--app-text-secondary)]">
                                {{ $topup->created_at->format('d M Y, H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-[var(--app-info-soft)] text-[var(--app-info)] flex items-center justify-center text-sm font-bold border border-[color-mix(in_srgb,var(--app-info)_20%,transparent)]">
                                        {{ strtoupper(substr($topup->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-[var(--app-text)]">{{ $topup->user->name }}</div>
                                        <div class="text-xs text-[var(--app-text-muted)] mt-0.5">{{ $topup->user->rfid_uid }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-bold text-[var(--app-success)]">
                                {{ $topup->formatted_amount }}
                            </td>
                            <td class="px-6 py-4 text-[11px] text-[var(--app-text-secondary)] font-mono font-bold">
                                {{ $topup->formatted_balance_before }} <span class="mx-1 opacity-50">➔</span> {{ $topup->formatted_balance_after }}
                            </td>
                            <td class="px-6 py-4">
                                @if($topup->method === 'cash')
                                    <span class="app-badge-neutral text-[var(--app-info)] bg-[var(--app-info-soft)]">Cash</span>
                                @elseif($topup->method === 'transfer')
                                    <span class="app-badge-neutral text-cyan-600 bg-cyan-100 dark:text-cyan-400 dark:bg-cyan-900/30">Transfer</span>
                                @elseif($topup->method === 'qris')
                                    <span class="app-badge-neutral text-purple-600 bg-purple-100 dark:text-purple-400 dark:bg-purple-900/30">QRIS</span>
                                @else
                                    <span class="app-badge-neutral">Other</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="app-empty-state max-w-sm mx-auto">
                                    <div class="app-empty-icon">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-base font-bold text-[var(--app-text)] mb-1">No top-ups recorded yet</p>
                                    <p class="text-sm text-[var(--app-text-secondary)]">Top-ups processed by admins will appear here</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($topups->hasPages())
            <div class="px-6 py-4 border-t border-[var(--app-border)] bg-[var(--app-surface-soft)]">
                {{ $topups->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Selection Mode Switching
        const btnModeSearch = document.getElementById('btn-mode-search');
        const btnModeManual = document.getElementById('btn-mode-manual');
        const sectionSearch = document.getElementById('section-search');
        const sectionManual = document.getElementById('section-manual');
        const selectionModeInput = document.getElementById('selection-mode');

        const selectedUserId = document.getElementById('selected-user-id');
        const manualRfidInput = document.getElementById('manual-rfid-input');

        function switchMode(mode) {
            selectionModeInput.value = mode;

            const activeClass = ['bg-[var(--app-surface)]', 'text-[var(--app-primary)]', 'shadow-sm', 'border-[var(--app-border)]'];
            const inactiveClass = ['text-[var(--app-text-secondary)]', 'hover:text-[var(--app-text)]', 'hover:bg-[var(--app-surface)]'];

            if (mode === 'search') {
                btnModeSearch.classList.add(...activeClass);
                btnModeSearch.classList.remove(...inactiveClass);
                btnModeManual.classList.add(...inactiveClass);
                btnModeManual.classList.remove(...activeClass);

                sectionSearch.classList.remove('hidden');
                sectionManual.classList.add('hidden');

                manualRfidInput.value = '';
                document.getElementById('rfid-lookup-info').classList.add('hidden');
                document.getElementById('rfid-lookup-info').innerHTML = '';
            } else {
                btnModeManual.classList.add(...activeClass);
                btnModeManual.classList.remove(...inactiveClass);
                btnModeSearch.classList.add(...inactiveClass);
                btnModeSearch.classList.remove(...activeClass);

                sectionManual.classList.remove('hidden');
                sectionSearch.classList.add('hidden');

                selectedUserId.value = '';
                document.getElementById('selected-user-card').classList.add('hidden');
                document.getElementById('search-input-container').classList.remove('hidden');
            }
        }

        btnModeSearch.addEventListener('click', () => switchMode('search'));
        btnModeManual.addEventListener('click', () => switchMode('manual'));

        // Mode 1: Autocomplete search
        const searchInput = document.getElementById('search-user-input');
        const searchResults = document.getElementById('search-results');
        const selectedUserCard = document.getElementById('selected-user-card');
        const searchContainer = document.getElementById('search-input-container');

        const cardAvatar = document.getElementById('selected-user-avatar');
        const cardName = document.getElementById('selected-user-name');
        const cardRfid = document.getElementById('selected-user-rfid');
        const cardBalance = document.getElementById('selected-user-balance');
        const btnChangeUser = document.getElementById('btn-change-user');

        const amountInput = document.getElementById('amount-input');
        const quickSelectBtns = document.querySelectorAll('.btn-quick-select');

        let searchDebounce;
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchDebounce);
                const query = this.value.trim();

                if (query.length < 2) {
                    searchResults.classList.add('hidden');
                    searchResults.innerHTML = '';
                    return;
                }

                searchDebounce = setTimeout(() => {
                    fetch(`/topups/search-users?q=${encodeURIComponent(query)}`)
                        .then(r => r.json())
                        .then(users => {
                            searchResults.innerHTML = '';
                            if (users.length === 0) {
                                const emptyItem = document.createElement('div');
                                emptyItem.className = 'px-4 py-3.5 text-sm text-[var(--app-text-muted)] text-center font-semibold';
                                emptyItem.textContent = 'No users found';
                                searchResults.appendChild(emptyItem);
                            } else {
                                users.forEach(user => {
                                    const item = document.createElement('button');
                                    item.type = 'button';
                                    item.className = 'w-full text-left px-4 py-3 hover:bg-[var(--app-surface-soft)] flex items-center justify-between transition-colors focus:outline-none';
                                    item.innerHTML = `
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-[var(--app-info-soft)] text-[var(--app-info)] flex items-center justify-center text-xs font-bold border border-[color-mix(in_srgb,var(--app-info)_20%,transparent)]">
                                                ${user.name.charAt(0).toUpperCase()}
                                            </div>
                                            <div>
                                                <div class="font-bold text-[var(--app-text)] text-sm">${user.name}</div>
                                                <div class="text-xs text-[var(--app-text-muted)] font-mono mt-0.5">${user.rfid_uid}</div>
                                            </div>
                                        </div>
                                        <div class="text-[11px] font-bold text-[var(--app-text-secondary)] bg-[var(--app-surface-strong)] px-2 py-1 rounded-md">
                                            ${user.formatted_balance}
                                        </div>
                                    `;
                                    item.addEventListener('click', () => selectUser(user));
                                    searchResults.appendChild(item);
                                });
                            }
                            searchResults.classList.remove('hidden');
                        })
                        .catch(err => {
                            console.error('Failed to search users:', err);
                        });
                }, 200);
            });

            document.addEventListener('click', function(e) {
                if (!searchResults.contains(e.target) && e.target !== searchInput) {
                    searchResults.classList.add('hidden');
                }
            });
        }

        function selectUser(user) {
            selectedUserId.value = user.id;

            cardAvatar.textContent = user.name.charAt(0).toUpperCase();
            cardName.textContent = user.name;
            cardRfid.textContent = user.rfid_uid;
            cardBalance.textContent = 'Current: ' + user.formatted_balance;

            searchContainer.classList.add('hidden');
            selectedUserCard.classList.remove('hidden');
            searchResults.classList.add('hidden');
            searchResults.innerHTML = '';
            if (searchInput) searchInput.value = '';
        }

        if (btnChangeUser) {
            btnChangeUser.addEventListener('click', function() {
                selectedUserId.value = '';
                searchContainer.classList.remove('hidden');
                selectedUserCard.classList.add('hidden');
                if (searchInput) {
                    searchInput.focus();
                }
            });
        }

        // Mode 2: Manual RFID Entry & Look-up
        const rfidLookupInfo = document.getElementById('rfid-lookup-info');
        let lookupDebounce;

        if(manualRfidInput) {
            manualRfidInput.addEventListener('input', function() {
                clearTimeout(lookupDebounce);
                const rfid = this.value.trim().toUpperCase();

                if (rfid.length === 0) {
                    rfidLookupInfo.classList.add('hidden');
                    rfidLookupInfo.innerHTML = '';
                    return;
                }

                lookupDebounce = setTimeout(() => {
                    performRfidLookup(rfid);
                }, 300);
            });
        }

        function performRfidLookup(rfid) {
            rfidLookupInfo.classList.remove('hidden');
            rfidLookupInfo.innerHTML = `
                <div class="flex items-center justify-center py-2">
                    <svg class="animate-spin h-5 w-5 text-[var(--app-primary)]" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-xs text-[var(--app-text-secondary)] font-medium ml-2">Checking card...</span>
                </div>
            `;

            fetch(`/topups/search-users?q=${encodeURIComponent(rfid)}`)
                .then(r => r.json())
                .then(users => {
                    const exactMatch = users.find(u => u.rfid_uid.toUpperCase() === rfid);

                    if (exactMatch) {
                        rfidLookupInfo.innerHTML = `
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-[var(--app-success-soft)] text-[var(--app-success)] flex items-center justify-center text-xs font-bold border border-[color-mix(in_srgb,var(--app-success)_20%,transparent)]">
                                        ${exactMatch.name.charAt(0).toUpperCase()}
                                    </div>
                                    <div>
                                        <div class="font-bold text-[var(--app-text)] text-sm">${exactMatch.name}</div>
                                        <div class="text-[10px] text-[var(--app-success)] font-bold uppercase tracking-wider">Registered User</div>
                                    </div>
                                </div>
                                <div class="text-[11px] font-bold text-[var(--app-text-secondary)] bg-[var(--app-surface-strong)] px-2 py-1 rounded-md">
                                    Bal: ${exactMatch.formatted_balance}
                                </div>
                            </div>
                        `;
                    } else {
                        rfidLookupInfo.innerHTML = `
                            <div class="space-y-3">
                                <div class="flex items-center gap-2">
                                    <span class="text-[var(--app-warning)]">⚠️</span>
                                    <span class="text-[11px] font-bold text-[var(--app-warning)] uppercase tracking-wider">New Card (Unregistered)</span>
                                </div>
                                <div class="space-y-1.5">
                                    <label for="new-user-name-input" class="block text-[10px] font-bold text-[var(--app-text-secondary)] uppercase tracking-wider">Assign User Name</label>
                                    <input type="text" name="new_user_name" id="new-user-name-input" required
                                           placeholder="Enter name to register and top-up..."
                                           class="app-input text-sm py-2 px-3">
                                </div>
                            </div>
                        `;
                    }
                })
                .catch(err => {
                    console.error('RFID check failed:', err);
                    rfidLookupInfo.innerHTML = `<span class="text-xs font-bold text-[var(--app-danger)]">Lookup failed. Please type user name below anyway.</span>`;
                });
        }

        // Live card tap WebSocket listener
        if (window.Echo) {
            const listenerStatus = document.getElementById('tap-listener-status');

            window.Echo.channel('gate-screen')
                .listen('.scan.result', (e) => {
                    switchMode('manual');

                    if(manualRfidInput) {
                        manualRfidInput.value = e.rfid_uid;
                    }

                    if (listenerStatus) {
                        listenerStatus.innerHTML = `<span class="text-[var(--app-success)] font-bold">✨ Card tapped! ID: ${e.rfid_uid}</span>`;
                        setTimeout(() => {
                            listenerStatus.textContent = 'Ready. Tap card on reader...';
                        }, 5000);
                    }

                    if (window.showToast) {
                        window.showToast(`🎴 Card Tapped: ${e.rfid_uid}`, 'info');
                    }

                    performRfidLookup(e.rfid_uid);
                });
        }

        // Quick select amount buttons
        quickSelectBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const amt = this.dataset.amount;
                amountInput.value = amt;

                quickSelectBtns.forEach(b => {
                    b.classList.remove('!bg-[var(--app-primary-softer)]', '!border-[var(--app-primary-soft)]', '!text-[var(--app-primary)]');
                });
                this.classList.add('!bg-[var(--app-primary-softer)]', '!border-[var(--app-primary-soft)]', '!text-[var(--app-primary)]');
            });
        });

        if(amountInput) {
            amountInput.addEventListener('input', function() {
                quickSelectBtns.forEach(b => {
                    b.classList.remove('!bg-[var(--app-primary-softer)]', '!border-[var(--app-primary-soft)]', '!text-[var(--app-primary)]');
                });
            });
        }

        // Real-time echo listening for topups
        if (window.Echo) {
            window.Echo.channel('topup-channel')
                .listen('.topup.created', (e) => {
                    const topup = e.topup;

                    if (window.showToast) {
                        window.showToast(`💰 Balance Top-Up: ${window.formatIDR(topup.amount)} for ${topup.user.name}`, 'success');
                    }

                    updateStats(topup.amount);
                    prependTopUpRow(topup);
                });
        }

        function updateStats(amount) {
            const todayAmtEl = document.getElementById('stat-topups-today');
            const countEl = document.getElementById('stat-topups-count');
            const alltimeEl = document.getElementById('stat-topups-alltime');

            if (todayAmtEl) {
                const currentVal = parseInt(todayAmtEl.textContent.replace(/[^0-9]/g, '')) || 0;
                animateCounter('stat-topups-today', currentVal + amount);
            }
            if (countEl) {
                const currentCount = parseInt(countEl.textContent) || 0;
                animateCounter('stat-topups-count', currentCount + 1);
            }
            if (alltimeEl) {
                const currentVal = parseInt(alltimeEl.textContent.replace(/[^0-9]/g, '')) || 0;
                animateCounter('stat-topups-alltime', currentVal + amount);
            }
        }

        function animateCounter(elementId, targetValue) {
            const el = document.getElementById(elementId);
            if (!el) return;

            const current = parseInt(el.textContent.replace(/[^0-9]/g, '')) || 0;
            const diff = targetValue - current;
            if (diff === 0) return;

            const steps = 20;
            const increment = diff / steps;
            let step = 0;

            const timer = setInterval(() => {
                step++;
                const nextVal = Math.round(current + increment * step);
                if (elementId.includes('count')) {
                    el.textContent = nextVal;
                } else {
                    el.textContent = window.formatIDR(nextVal);
                }
                if (step >= steps) {
                    if (elementId.includes('count')) {
                        el.textContent = targetValue;
                    } else {
                        el.textContent = window.formatIDR(targetValue);
                    }
                    clearInterval(timer);
                }
            }, 30);
        }

        function prependTopUpRow(topup) {
            const tbody = document.getElementById('topups-table-body');
            if (!tbody) return;

            const emptyStateRow = tbody.querySelector('.app-empty-state');
            if (emptyStateRow) {
                tbody.innerHTML = '';
            }

            const tr = document.createElement('tr');
            tr.id = `topup-row-${topup.id}`;
            tr.className = 'table-row-hover row-highlight border-b border-[var(--app-border)] bg-[var(--app-surface)]';

            const methodBadges = {
                cash: '<span class="app-badge-neutral text-[var(--app-info)] bg-[var(--app-info-soft)]">Cash</span>',
                transfer: '<span class="app-badge-neutral text-cyan-600 bg-cyan-100 dark:text-cyan-400 dark:bg-cyan-900/30">Transfer</span>',
                qris: '<span class="app-badge-neutral text-purple-600 bg-purple-100 dark:text-purple-400 dark:bg-purple-900/30">QRIS</span>',
                other: '<span class="app-badge-neutral">Other</span>'
            };

            tr.innerHTML = `
                <td class="px-6 py-4 text-sm font-medium text-[var(--app-text-secondary)]">
                    ${window.formatDateTime(topup.created_at)}
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-[var(--app-info-soft)] text-[var(--app-info)] flex items-center justify-center text-sm font-bold border border-[color-mix(in_srgb,var(--app-info)_20%,transparent)]">
                            ${topup.user.name.charAt(0).toUpperCase()}
                        </div>
                        <div>
                            <div class="font-bold text-[var(--app-text)]">${topup.user.name}</div>
                            <div class="text-xs text-[var(--app-text-muted)] mt-0.5">${topup.user.rfid_uid}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 font-bold text-[var(--app-success)]">
                    ${window.formatIDR(topup.amount)}
                </td>
                <td class="px-6 py-4 text-[11px] text-[var(--app-text-secondary)] font-mono font-bold">
                    ${window.formatIDR(topup.balance_before)} <span class="mx-1 opacity-50">➔</span> ${window.formatIDR(topup.balance_after)}
                </td>
                <td class="px-6 py-4">
                    ${methodBadges[topup.method] || methodBadges.other}
                </td>
            `;

            tbody.insertBefore(tr, tbody.firstChild);

            if (tbody.children.length > 20 && !document.querySelector('.pagination')) {
                tbody.removeChild(tbody.lastChild);
            }
        }
    });
</script>
@endpush
