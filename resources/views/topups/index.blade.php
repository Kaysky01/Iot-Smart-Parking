@extends('layouts.app')

@section('title', 'Top-Up Balance')
@section('page-title', 'Top-Up Management')
@section('page-subtitle', 'Manage and increase RFID user balances')

@section('content')
<div class="space-y-6">

    {{-- ===== STATS CARDS ===== --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Today's Total Amount --}}
        <div class="stat-card relative overflow-hidden bg-surface dark:bg-slate-800 rounded-2xl shadow-md border border-slate-100 dark:border-slate-700 p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Top-Ups Today</p>
                    <p class="text-3xl font-extrabold text-slate-800 dark:text-white mt-2 counter-animate" id="stat-topups-today">Rp {{ number_format($totalTopUpsToday, 0, ',', '.') }}</p>
                    <p class="text-xs text-slate-400 mt-1">Total value added today</p>
                </div>
                <div class="w-14 h-14 rounded-2xl gradient-success flex items-center justify-center shadow-lg shadow-emerald-500/20">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="absolute -bottom-4 -right-4 w-24 h-24 rounded-full bg-emerald-50 dark:bg-emerald-900/20 opacity-50"></div>
        </div>

        {{-- Today's Transaction Count --}}
        <div class="stat-card relative overflow-hidden bg-surface dark:bg-slate-800 rounded-2xl shadow-md border border-slate-100 dark:border-slate-700 p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Transactions Today</p>
                    <p class="text-3xl font-extrabold text-slate-800 dark:text-white mt-2 counter-animate" id="stat-topups-count">{{ $totalTopUpsCount }}</p>
                    <p class="text-xs text-slate-400 mt-1">Processed top-ups today</p>
                </div>
                <div class="w-14 h-14 rounded-2xl gradient-primary flex items-center justify-center shadow-lg shadow-blue-500/20">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
            </div>
            <div class="absolute -bottom-4 -right-4 w-24 h-24 rounded-full bg-blue-50 dark:bg-blue-900/20 opacity-50"></div>
        </div>

        {{-- All-Time Total Amount --}}
        <div class="stat-card relative overflow-hidden bg-surface dark:bg-slate-800 rounded-2xl shadow-md border border-slate-100 dark:border-slate-700 p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">All-Time Revenue Added</p>
                    <p class="text-3xl font-extrabold text-slate-800 dark:text-white mt-2 counter-animate" id="stat-topups-alltime">Rp {{ number_format($totalTopUpsAllTime, 0, ',', '.') }}</p>
                    <p class="text-xs text-slate-400 mt-1">Total historical top-ups</p>
                </div>
                <div class="w-14 h-14 rounded-2xl gradient-warning flex items-center justify-center shadow-lg shadow-amber-500/20">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="absolute -bottom-4 -right-4 w-24 h-24 rounded-full bg-amber-50 dark:bg-amber-900/20 opacity-50"></div>
        </div>
    </div>

    {{-- ===== TWO COLUMN LAYOUT ===== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left Column: New Top-Up Form --}}
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-surface dark:bg-slate-800 rounded-2xl shadow-md border border-slate-100 dark:border-slate-700 p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl gradient-primary flex items-center justify-center shadow-md shadow-blue-500/10">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-800 dark:text-white">New Top-Up</h3>
                        <p class="text-sm text-slate-400">Increase account balance</p>
                    </div>
                </div>

                {{-- Toast Success/Error message --}}
                @if(session('success'))
                    <div class="mb-4 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-400 text-sm flex items-start gap-3">
                        <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <form id="topup-form" method="POST" action="{{ route('topups.store') }}" class="space-y-5">
                    @csrf
                    
                    {{-- Hidden Selector Mode & User ID --}}
                    <input type="hidden" name="user_id" id="selected-user-id" value="{{ old('user_id') }}">
                    <input type="hidden" id="selection-mode" value="search">

                    {{-- User Selection Mode Toggle --}}
                    <div class="space-y-2">
                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Selection Mode</label>
                        <div class="flex p-1 rounded-xl bg-slate-100 dark:bg-slate-900 border border-slate-200/50 dark:border-slate-700/50">
                            <button type="button" id="btn-mode-search" class="flex-grow py-2 text-xs font-bold rounded-lg transition-all bg-white dark:bg-slate-800 text-blue-600 dark:text-blue-400 shadow-sm border border-slate-200/20 dark:border-slate-700/20">
                                🔍 Search Database
                            </button>
                            <button type="button" id="btn-mode-manual" class="flex-grow py-2 text-xs font-semibold rounded-lg transition-all text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200">
                                🎴 RFID Tap / Manual
                            </button>
                        </div>
                    </div>

                    {{-- Mode 1: Search Autocomplete --}}
                    <div id="section-search" class="space-y-2">
                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Search User</label>
                        
                        {{-- Search Input (Visible when no user is selected) --}}
                        <div id="search-input-container" class="relative {{ old('user_id') ? 'hidden' : '' }}">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" id="search-user-input" autocomplete="off"
                                   placeholder="Type user name or RFID..."
                                   class="w-full pl-11 pr-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                            
                            {{-- Autocomplete Results --}}
                            <div id="search-results" class="hidden absolute left-0 right-0 mt-2 bg-white dark:bg-slate-800 rounded-xl shadow-xl border border-slate-100 dark:border-slate-700 max-h-60 overflow-y-auto z-50 divide-y divide-slate-100 dark:divide-slate-700">
                            </div>
                        </div>

                        {{-- Selected User Info Card --}}
                        <div id="selected-user-card" class="p-4 rounded-xl bg-blue-50/50 dark:bg-blue-950/20 border border-blue-100 dark:border-blue-900/50 relative {{ old('user_id') ? '' : 'hidden' }}">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full gradient-info flex items-center justify-center text-white font-bold" id="selected-user-avatar">
                                    -
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-bold text-slate-800 dark:text-white truncate" id="selected-user-name">-</h4>
                                    <div class="flex items-center gap-2 mt-1">
                                        <code class="text-xs font-mono bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded text-slate-600 dark:text-slate-300" id="selected-user-rfid">-</code>
                                        <span class="text-xs text-slate-500 dark:text-slate-400" id="selected-user-balance">-</span>
                                    </div>
                                </div>
                                <button type="button" id="btn-change-user"
                                        class="px-3 py-1.5 rounded-lg text-xs font-semibold text-red-600 hover:bg-red-50 dark:hover:bg-red-950/30 transition-colors border border-red-200 dark:border-red-900/50">
                                    Change
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Mode 2: Manual RFID / Card Tap --}}
                    <div id="section-manual" class="space-y-4 hidden">
                        {{-- Scanner Tap Pulse Card --}}
                        <div class="p-4 rounded-xl border border-dashed border-slate-300 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/30 flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <span class="relative flex h-3.5 w-3.5">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-emerald-500"></span>
                                </span>
                                <div>
                                    <p class="text-xs font-bold text-slate-700 dark:text-slate-300">Live RFID Listener</p>
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400" id="tap-listener-status">Ready. Tap card on reader...</p>
                                </div>
                            </div>
                            <span class="text-2xl animate-pulse">🎴</span>
                        </div>

                        {{-- RFID UID Input --}}
                        <div class="space-y-2">
                            <label for="manual-rfid-input" class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">RFID UID</label>
                            <input type="text" name="rfid_uid" id="manual-rfid-input" placeholder="Enter Card ID manually (e.g. 83FD62A)..."
                                   class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-white font-mono text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all uppercase">
                        </div>

                        {{-- Lookup Status Display & New Registration Input --}}
                        <div id="rfid-lookup-info" class="hidden p-4 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 space-y-3">
                            {{-- Content loaded by JS: "Card Registered to..." OR "Unregistered Card" --}}
                        </div>
                    </div>

                    {{-- Top-Up Amount field --}}
                    <div class="space-y-2">
                        <label for="amount-input" class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Amount (IDR)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 font-bold text-base pointer-events-none">Rp</span>
                            <input type="number" name="amount" id="amount-input" required min="1000" max="10000000" value="{{ old('amount') }}"
                                   placeholder="0"
                                   class="w-full pl-11 pr-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-white font-bold text-lg focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                        </div>
                        
                        {{-- Quick selects --}}
                        <div class="grid grid-cols-3 gap-2 mt-2">
                            <button type="button" class="btn-quick-select py-2 rounded-xl text-xs font-semibold text-slate-600 dark:text-slate-300 bg-slate-50 dark:bg-slate-900 hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-200/60 dark:border-slate-700/60 transition-all" data-amount="10000">10k</button>
                            <button type="button" class="btn-quick-select py-2 rounded-xl text-xs font-semibold text-slate-600 dark:text-slate-300 bg-slate-50 dark:bg-slate-900 hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-200/60 dark:border-slate-700/60 transition-all" data-amount="20000">20k</button>
                            <button type="button" class="btn-quick-select py-2 rounded-xl text-xs font-semibold text-slate-600 dark:text-slate-300 bg-slate-50 dark:bg-slate-900 hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-200/60 dark:border-slate-700/60 transition-all" data-amount="50000">50k</button>
                            <button type="button" class="btn-quick-select py-2 rounded-xl text-xs font-semibold text-slate-600 dark:text-slate-300 bg-slate-50 dark:bg-slate-900 hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-200/60 dark:border-slate-700/60 transition-all" data-amount="100000">100k</button>
                            <button type="button" class="btn-quick-select py-2 rounded-xl text-xs font-semibold text-slate-600 dark:text-slate-300 bg-slate-50 dark:bg-slate-900 hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-200/60 dark:border-slate-700/60 transition-all" data-amount="200000">200k</button>
                            <button type="button" class="btn-quick-select py-2 rounded-xl text-xs font-semibold text-slate-600 dark:text-slate-300 bg-slate-50 dark:bg-slate-900 hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-200/60 dark:border-slate-700/60 transition-all" data-amount="500000">500k</button>
                        </div>
                        @error('amount')
                            <p class="text-xs text-red-500 mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Payment Method Selection --}}
                    <div class="space-y-2">
                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Payment Method</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="relative flex items-center justify-between p-3 rounded-xl border border-slate-200 dark:border-slate-700 cursor-pointer bg-slate-50 dark:bg-slate-900/50 hover:bg-slate-100 dark:hover:bg-slate-800/80 transition-all select-none">
                                <input type="radio" name="method" value="cash" checked class="sr-only peer">
                                <div class="flex items-center gap-2">
                                    <span class="text-slate-400 peer-checked:text-blue-500">💵</span>
                                    <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">Cash</span>
                                </div>
                                <div class="w-4 h-4 rounded-full border-2 border-slate-300 dark:border-slate-600 flex items-center justify-center peer-checked:border-blue-500 peer-checked:bg-blue-500">
                                    <div class="w-1.5 h-1.5 rounded-full bg-white hidden peer-checked:block"></div>
                                </div>
                            </label>
                            
                            <label class="relative flex items-center justify-between p-3 rounded-xl border border-slate-200 dark:border-slate-700 cursor-pointer bg-slate-50 dark:bg-slate-900/50 hover:bg-slate-100 dark:hover:bg-slate-800/80 transition-all select-none">
                                <input type="radio" name="method" value="transfer" class="sr-only peer">
                                <div class="flex items-center gap-2">
                                    <span class="text-slate-400 peer-checked:text-blue-500">🏦</span>
                                    <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">Transfer</span>
                                </div>
                                <div class="w-4 h-4 rounded-full border-2 border-slate-300 dark:border-slate-600 flex items-center justify-center peer-checked:border-blue-500 peer-checked:bg-blue-500">
                                    <div class="w-1.5 h-1.5 rounded-full bg-white hidden peer-checked:block"></div>
                                </div>
                            </label>

                            <label class="relative flex items-center justify-between p-3 rounded-xl border border-slate-200 dark:border-slate-700 cursor-pointer bg-slate-50 dark:bg-slate-900/50 hover:bg-slate-100 dark:hover:bg-slate-800/80 transition-all select-none">
                                <input type="radio" name="method" value="qris" class="sr-only peer">
                                <div class="flex items-center gap-2">
                                    <span class="text-slate-400 peer-checked:text-blue-500">📱</span>
                                    <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">QRIS</span>
                                </div>
                                <div class="w-4 h-4 rounded-full border-2 border-slate-300 dark:border-slate-600 flex items-center justify-center peer-checked:border-blue-500 peer-checked:bg-blue-500">
                                    <div class="w-1.5 h-1.5 rounded-full bg-white hidden peer-checked:block"></div>
                                </div>
                            </label>

                            <label class="relative flex items-center justify-between p-3 rounded-xl border border-slate-200 dark:border-slate-700 cursor-pointer bg-slate-50 dark:bg-slate-900/50 hover:bg-slate-100 dark:hover:bg-slate-800/80 transition-all select-none">
                                <input type="radio" name="method" value="other" class="sr-only peer">
                                <div class="flex items-center gap-2">
                                    <span class="text-slate-400 peer-checked:text-blue-500">💳</span>
                                    <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">Other</span>
                                </div>
                                <div class="w-4 h-4 rounded-full border-2 border-slate-300 dark:border-slate-600 flex items-center justify-center peer-checked:border-blue-500 peer-checked:bg-blue-500">
                                    <div class="w-1.5 h-1.5 rounded-full bg-white hidden peer-checked:block"></div>
                                </div>
                            </label>
                        </div>
                        @error('method')
                            <p class="text-xs text-red-500 mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Notes field --}}
                    <div class="space-y-2">
                        <label for="notes-textarea" class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Notes (Optional)</label>
                        <textarea name="notes" id="notes-textarea" rows="2" maxlength="500"
                                  placeholder="E.g., Bank Ref No, ticket id..."
                                  class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all resize-none"></textarea>
                        @error('notes')
                            <p class="text-xs text-red-500 mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" id="btn-submit-topup"
                            class="w-full py-4 rounded-xl gradient-primary hover:opacity-95 text-white font-bold tracking-wide shadow-lg shadow-blue-500/20 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 flex items-center justify-center gap-2 transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Submit Top-Up
                    </button>
                </form>
            </div>
        </div>

        {{-- Right Column: Top-Up History Table --}}
        <div class="lg:col-span-2 bg-surface dark:bg-slate-800 rounded-2xl shadow-md border border-slate-100 dark:border-slate-700 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white">Top-Up History</h3>
                    <p class="text-sm text-slate-400 mt-0.5">Live record of all account top-ups</p>
                </div>
                <div class="flex items-center gap-2 text-xs font-semibold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/30 px-3 py-1.5 rounded-full">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 pulse-live"></span>
                    Auto-updating
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-700/50">
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Date & Time</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">User</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Admin</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Balance Flow</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Method</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Notes</th>
                        </tr>
                    </thead>
                    <tbody id="topups-table-body" class="divide-y divide-slate-100 dark:divide-slate-700">
                        @forelse($topups as $topup)
                        <tr id="topup-row-{{ $topup->id }}" class="table-row-hover">
                            <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-400">
                                {{ $topup->created_at->format('d M Y, H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full gradient-info flex items-center justify-center text-white text-sm font-bold">
                                        {{ strtoupper(substr($topup->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-semibold text-slate-800 dark:text-slate-200">{{ $topup->user->name }}</div>
                                        <div class="text-xs text-slate-400">{{ $topup->user->rfid_uid }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-slate-700 dark:text-slate-300">
                                {{ $topup->admin ? $topup->admin->name : '-' }}
                            </td>
                            <td class="px-6 py-4 font-bold text-emerald-600">
                                {{ $topup->formatted_amount }}
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-500 dark:text-slate-400 font-mono">
                                {{ $topup->formatted_balance_before }} <span class="mx-1 text-slate-300">➔</span> {{ $topup->formatted_balance_after }}
                            </td>
                            <td class="px-6 py-4">
                                @if($topup->method === 'cash')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200 dark:bg-blue-950/30 dark:text-blue-400 dark:border-blue-900/50">Cash</span>
                                @elseif($topup->method === 'transfer')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-cyan-50 text-cyan-700 border border-cyan-200 dark:bg-cyan-950/30 dark:text-cyan-400 dark:border-cyan-900/50">Transfer</span>
                                @elseif($topup->method === 'qris')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-purple-50 text-purple-700 border border-purple-200 dark:bg-purple-950/30 dark:text-purple-400 dark:border-purple-900/50">QRIS</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-50 text-slate-700 border border-slate-200 dark:bg-slate-900/30 dark:text-slate-400 dark:border-slate-800">Other</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-400 max-w-xs truncate" title="{{ $topup->notes }}">
                                {{ $topup->notes ?? '-' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-slate-300 dark:text-slate-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <p class="text-slate-400 font-medium">No top-ups recorded yet</p>
                                    <p class="text-sm text-slate-400 mt-1">Top-ups processed by admins will appear here</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($topups->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700">
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
            
            const activeClass = ['bg-white', 'dark:bg-slate-800', 'text-blue-600', 'dark:text-blue-400', 'shadow-sm', 'border', 'border-slate-200/20', 'dark:border-slate-700/20', 'font-bold'];
            const inactiveClass = ['text-slate-500', 'hover:text-slate-800', 'dark:text-slate-400', 'dark:hover:text-slate-200', 'font-semibold'];

            if (mode === 'search') {
                // Button styles
                btnModeSearch.classList.add(...activeClass);
                btnModeSearch.classList.remove(...inactiveClass);
                btnModeManual.classList.add(...inactiveClass);
                btnModeManual.classList.remove(...activeClass);

                // Section visibility
                sectionSearch.classList.remove('hidden');
                sectionManual.classList.add('hidden');

                // Clear manual inputs to ensure form doesn't submit stale values
                manualRfidInput.value = '';
                document.getElementById('rfid-lookup-info').classList.add('hidden');
                document.getElementById('rfid-lookup-info').innerHTML = '';
            } else {
                // Button styles
                btnModeManual.classList.add(...activeClass);
                btnModeManual.classList.remove(...inactiveClass);
                btnModeSearch.classList.add(...inactiveClass);
                btnModeSearch.classList.remove(...activeClass);

                // Section visibility
                sectionManual.classList.remove('hidden');
                sectionSearch.classList.add('hidden');

                // Clear search selected ID
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
                                emptyItem.className = 'px-4 py-3.5 text-sm text-slate-400 text-center';
                                emptyItem.textContent = 'No users found';
                                searchResults.appendChild(emptyItem);
                            } else {
                                users.forEach(user => {
                                    const item = document.createElement('button');
                                    item.type = 'button';
                                    item.className = 'w-full text-left px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 flex items-center justify-between transition-colors focus:outline-none';
                                    item.innerHTML = `
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full gradient-info flex items-center justify-center text-white text-xs font-bold">
                                                ${user.name.charAt(0).toUpperCase()}
                                            </div>
                                            <div>
                                                <div class="font-semibold text-slate-800 dark:text-slate-200 text-sm">${user.name}</div>
                                                <div class="text-xs text-slate-400 font-mono">${user.rfid_uid}</div>
                                            </div>
                                        </div>
                                        <div class="text-xs font-bold text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-slate-800 px-2 py-1 rounded-lg">
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

            // Close search dropdown on click outside
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

        function performRfidLookup(rfid) {
            rfidLookupInfo.classList.remove('hidden');
            rfidLookupInfo.innerHTML = `
                <div class="flex items-center justify-center py-2">
                    <svg class="animate-spin h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-xs text-slate-500 ml-2">Checking card...</span>
                </div>
            `;

            fetch(`/topups/search-users?q=${encodeURIComponent(rfid)}`)
                .then(r => r.json())
                .then(users => {
                    // Try to find exact RFID match
                    const exactMatch = users.find(u => u.rfid_uid.toUpperCase() === rfid);

                    if (exactMatch) {
                        rfidLookupInfo.innerHTML = `
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full gradient-success flex items-center justify-center text-white text-xs font-bold">
                                        ${exactMatch.name.charAt(0).toUpperCase()}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-800 dark:text-slate-200 text-sm">${exactMatch.name}</div>
                                        <div class="text-xs text-emerald-500 font-semibold">Registered User</div>
                                    </div>
                                </div>
                                <div class="text-xs font-bold text-slate-700 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 px-2 py-1 rounded">
                                    Balance: ${exactMatch.formatted_balance}
                                </div>
                            </div>
                        `;
                    } else {
                        rfidLookupInfo.innerHTML = `
                            <div class="space-y-3">
                                <div class="flex items-center gap-2">
                                    <span class="text-amber-500">⚠️</span>
                                    <span class="text-xs font-bold text-amber-600 dark:text-amber-400">New Card (Unregistered)</span>
                                </div>
                                <div class="space-y-1">
                                    <label for="new-user-name-input" class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Assign User Name</label>
                                    <input type="text" name="new_user_name" id="new-user-name-input" required
                                           placeholder="Enter name to register and top-up..."
                                           class="w-full px-3 py-2 rounded-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm text-slate-800 dark:text-white focus:outline-none focus:ring-1 focus:ring-blue-500">
                                </div>
                            </div>
                        `;
                    }
                })
                .catch(err => {
                    console.error('RFID check failed:', err);
                    rfidLookupInfo.innerHTML = `<span class="text-xs text-red-500">Lookup failed. Please type user name below anyway.</span>`;
                });
        }

        // Live card tap WebSocket listener
        if (window.Echo) {
            const listenerStatus = document.getElementById('tap-listener-status');
            
            window.Echo.channel('gate-screen')
                .listen('.scan.result', (e) => {
                    // Highlight the manual tab & switch
                    switchMode('manual');
                    
                    // Populate input
                    manualRfidInput.value = e.rfid_uid;
                    
                    // Show animation / status feedback
                    if (listenerStatus) {
                        listenerStatus.innerHTML = `<span class="text-emerald-500 font-bold">✨ Card tapped! ID: ${e.rfid_uid}</span>`;
                        setTimeout(() => {
                            listenerStatus.textContent = 'Ready. Tap card on reader...';
                        }, 5000);
                    }

                    // Show success toast
                    if (window.showToast) {
                        window.showToast(`🎴 Card Tapped: ${e.rfid_uid}`, 'info');
                    }

                    // Perform database check
                    performRfidLookup(e.rfid_uid);
                });
        }

        // Quick select amount buttons
        quickSelectBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const amt = this.dataset.amount;
                amountInput.value = amt;
                
                quickSelectBtns.forEach(b => b.classList.remove('ring-2', 'ring-blue-500', 'border-blue-500', 'bg-blue-50/50', 'dark:bg-blue-950/20'));
                this.classList.add('ring-2', 'ring-blue-500', 'border-blue-500', 'bg-blue-50/50', 'dark:bg-blue-950/20');
            });
        });

        amountInput.addEventListener('input', function() {
            quickSelectBtns.forEach(b => b.classList.remove('ring-2', 'ring-blue-500', 'border-blue-500', 'bg-blue-50/50', 'dark:bg-blue-950/20'));
        });

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

            const emptyStateRow = tbody.querySelector('td[colspan]');
            if (emptyStateRow) {
                tbody.innerHTML = '';
            }

            const tr = document.createElement('tr');
            tr.id = `topup-row-${topup.id}`;
            tr.className = 'table-row-hover row-highlight border-b border-slate-100 dark:border-slate-700';

            const methodBadges = {
                cash: '<span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200 dark:bg-blue-950/30 dark:text-blue-400 dark:border-blue-900/50">Cash</span>',
                transfer: '<span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-cyan-50 text-cyan-700 border border-cyan-200 dark:bg-cyan-950/30 dark:text-cyan-400 dark:border-cyan-900/50">Transfer</span>',
                qris: '<span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-purple-50 text-purple-700 border border-purple-200 dark:bg-purple-950/30 dark:text-purple-400 dark:border-purple-900/50">QRIS</span>',
                other: '<span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-50 text-slate-700 border border-slate-200 dark:bg-slate-900/30 dark:text-slate-400 dark:border-slate-800">Other</span>'
            };

            tr.innerHTML = `
                <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-400">
                    ${window.formatDateTime(topup.created_at)}
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full gradient-info flex items-center justify-center text-white text-sm font-bold">
                            ${topup.user.name.charAt(0).toUpperCase()}
                        </div>
                        <div>
                            <div class="font-semibold text-slate-800 dark:text-slate-200">${topup.user.name}</div>
                            <div class="text-xs text-slate-400">${topup.user.rfid_uid}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 text-sm font-medium text-slate-700 dark:text-slate-300">
                    ${topup.admin ? topup.admin.name : '-'}
                </td>
                <td class="px-6 py-4 font-bold text-emerald-600">
                    ${window.formatIDR(topup.amount)}
                </td>
                <td class="px-6 py-4 text-xs text-slate-500 dark:text-slate-400 font-mono">
                    ${window.formatIDR(topup.balance_before)} <span class="mx-1 text-slate-300">➔</span> ${window.formatIDR(topup.balance_after)}
                </td>
                <td class="px-6 py-4">
                    ${methodBadges[topup.method] || methodBadges.other}
                </td>
                <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-400 max-w-xs truncate" title="${topup.notes || ''}">
                    ${topup.notes || '-'}
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
