{{-- ===== TOP BAR ===== --}}
<header class="h-20 bg-slate-900 border-b border-slate-800 flex items-center justify-between px-6 lg:px-8 flex-shrink-0">
    {{-- Left: Page Title --}}
    <div>
        <h2 class="text-xl font-bold text-white">@yield('page-title', 'Dashboard')</h2>
        <p class="text-sm text-slate-400 mt-0.5">@yield('page-subtitle', 'Smart Parking IoT System')</p>
    </div>

    {{-- Right: Actions --}}
    <div class="flex items-center gap-4">
        {{-- Live indicator --}}
        <div class="hidden sm:flex items-center gap-2 bg-emerald-900/30 px-3 py-1.5 rounded-full">
            <span class="w-2 h-2 rounded-full bg-emerald-500 pulse-live"></span>
            <span class="text-xs font-semibold text-emerald-400">LIVE</span>
        </div>

        {{-- Dark Mode Toggle --}}
        <button onclick="toggleDarkMode()" id="dark-mode-toggle"
                class="w-10 h-10 rounded-xl bg-slate-800 flex items-center justify-center text-slate-400 hover:bg-slate-700 transition-colors">
            <svg id="dark-mode-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
        </button>

        {{-- Current Time --}}
        <div class="hidden md:block text-right">
            <p class="text-sm font-semibold text-slate-300" id="current-time">--:--:--</p>
            <p class="text-xs text-slate-400" id="current-date">Loading...</p>
        </div>
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
