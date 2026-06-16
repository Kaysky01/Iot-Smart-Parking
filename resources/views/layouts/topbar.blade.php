{{-- ===== TOP BAR ===== --}}
<header class="app-topbar h-20 border-b flex items-center justify-between px-4 sm:px-6 lg:px-8 flex-shrink-0 gap-4">
    {{-- Left: Mobile Menu & Page Title --}}
    <div class="flex items-center gap-4">
        {{-- Hamburger Menu (All Devices) --}}
        <button onclick="toggleSidebar()" class="w-10 h-10 rounded-xl bg-[var(--app-surface-soft)] border border-[var(--app-border)] flex items-center justify-center text-[var(--app-text-secondary)] hover:text-[var(--app-primary)] hover:border-[var(--app-primary-soft)] transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>

        <div>
            <h2 class="text-lg sm:text-xl font-bold text-[var(--app-text)] truncate">@yield('page-title', 'Dashboard')</h2>
            <p class="text-xs sm:text-sm text-[var(--app-text-muted)] mt-0.5 truncate hidden sm:block">@yield('page-subtitle', 'Smart Parking IoT System')</p>
        </div>
    </div>

    {{-- Right: Actions --}}
    <div class="flex items-center gap-5">
        {{-- Live indicator --}}
        <div class="hidden sm:flex items-center gap-2 bg-[var(--app-success-soft)] px-3 py-1.5 rounded-full border border-[var(--app-success-soft)]">
            <span class="w-2 h-2 rounded-full bg-[var(--app-success)] pulse-live"></span>
            <span class="text-xs font-bold text-[var(--app-success)] tracking-wide">LIVE</span>
        </div>

        <div class="w-px h-8 bg-[var(--app-border)] hidden sm:block"></div>

        {{-- Current Time --}}
        <div class="hidden md:block text-right">
            <p class="text-sm font-bold text-[var(--app-text)]" id="current-time">--:--:--</p>
            <p class="text-xs text-[var(--app-text-muted)] font-medium" id="current-date">Loading...</p>
        </div>

        {{-- Dark Mode Toggle --}}
        <button onclick="toggleDarkMode()" id="dark-mode-toggle"
                class="w-10 h-10 rounded-xl bg-[var(--app-surface-soft)] border border-[var(--app-border)] flex items-center justify-center text-[var(--app-text-secondary)] hover:text-[var(--app-primary)] hover:border-[var(--app-primary-soft)] transition-colors">
            <svg id="dark-mode-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
        </button>
    </div>
</header>

<script>
    function updateClock() {
        const now = new Date();
        const timeEl = document.getElementById('current-time');
        const dateEl = document.getElementById('current-date');
        if (timeEl) timeEl.textContent = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        if (dateEl) dateEl.textContent = now.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'short', year: 'numeric' });
    }
    updateClock();
    setInterval(updateClock, 1000);
</script>
