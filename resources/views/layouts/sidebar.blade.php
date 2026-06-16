{{-- ===== SIDEBAR NAVIGATION ===== --}}
<aside class="app-sidebar w-72 flex flex-col flex-shrink-0 h-screen border-r fixed lg:static inset-y-0 left-0 z-30 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out" id="sidebar">

    {{-- Logo & Close Button --}}
    <div class="h-20 flex items-center justify-between px-8 border-b border-transparent">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center shadow-md shadow-blue-600/20">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <div>
                <h1 class="app-section-title text-lg tracking-tight leading-none">Smart Parking</h1>
                <p class="text-blue-500 dark:text-blue-400 text-xs font-semibold tracking-wider uppercase mt-1">IoT System</p>
            </div>
        </div>

        {{-- Close button (Mobile only again) --}}
        <button onclick="toggleSidebar()" class="lg:hidden text-[var(--app-text-muted)] hover:text-[var(--app-text)] p-1 rounded-lg hover:bg-[var(--app-surface-soft)] transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    {{-- Navigation Links --}}
    <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
        <p class="text-[11px] font-bold text-[var(--app-text-muted)] uppercase tracking-wider px-4 mb-4">Menu</p>

        <a href="{{ route('dashboard') }}" id="nav-dashboard"
           class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium
                  {{ request()->is('dashboard') || request()->is('/') ? 'active' : '' }}">
            <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
            </svg>
            Dashboard
        </a>

        <a href="{{ route('users.index') }}" id="nav-users"
           class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium
                  {{ request()->is('users') ? 'active' : '' }}">
            <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
            Users
        </a>

        <a href="{{ route('students.index') }}" id="nav-students"
           class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium
                  {{ request()->is('students') ? 'active' : '' }}">
            <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
            </svg>
            Students
        </a>

        <a href="{{ route('parkings.index') }}" id="nav-parkings"
           class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium
                  {{ request()->is('parkings') ? 'active' : '' }}">
            <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
            </svg>
            Parkings
        </a>

        <a href="{{ route('transactions.index') }}" id="nav-transactions"
           class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium
                  {{ request()->is('transactions') ? 'active' : '' }}">
            <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            Transactions
        </a>

        <a href="{{ route('topups.index') }}" id="nav-topups"
           class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium
                  {{ request()->is('topups') ? 'active' : '' }}">
            <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Top Up
        </a>

        <a href="{{ route('topup-requests.index') }}" id="nav-topup-requests"
           class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium
                  {{ request()->is('topup-requests') ? 'active' : '' }}">
            <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
            </svg>
            Requests
            @php $pendingCount = \App\Models\TopUpRequest::pending()->count(); @endphp
            @if($pendingCount > 0)
                <span class="ml-auto bg-[var(--app-warning)] text-white text-[10px] font-bold rounded-full min-w-[20px] h-5 px-1.5 flex items-center justify-center animate-pulse">{{ $pendingCount }}</span>
            @endif
        </a>

        <p class="text-[11px] font-bold text-[var(--app-text-muted)] uppercase tracking-wider px-4 mb-4 mt-8">Display</p>

        <a href="{{ route('gate-screen') }}" id="nav-gate" target="_blank"
           class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium">
            <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
            Gate Screen
            <svg class="w-3.5 h-3.5 ml-auto opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
            </svg>
        </a>
    </nav>

    {{-- Bottom Section: User & Logout --}}
    <div class="p-4 border-t border-[var(--app-border)]">
        @auth
        <div class="flex items-center gap-3 p-3 rounded-2xl bg-[var(--app-surface-soft)] border border-[var(--app-border)]">
            <div class="w-9 h-9 rounded-xl bg-[var(--app-primary-soft)] text-[var(--app-primary)] flex items-center justify-center font-bold">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-[var(--app-text)] truncate">{{ Auth::user()->name }}</p>
                <p class="text-xs text-[var(--app-text-muted)] truncate">{{ Auth::user()->email }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-8 h-8 rounded-lg text-[var(--app-text-muted)] hover:text-[var(--app-danger)] hover:bg-[var(--app-danger-soft)] flex items-center justify-center transition-colors" title="Logout">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                </button>
            </form>
        </div>
        @endauth
    </div>
</aside>
