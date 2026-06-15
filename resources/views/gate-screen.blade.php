<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gate Display — Smart Parking IoT</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; overflow: hidden; cursor: none; }
        .gate-bg { transition: background-color 0.8s ease; }
        .state-waiting { background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%); }
        .state-entry { background: linear-gradient(135deg, #1e3a5f 0%, #1e40af 50%, #1e3a8a 100%); }
        .state-exit { background: linear-gradient(135deg, #064e3b 0%, #059669 50%, #047857 100%); }
        .state-insufficient { background: linear-gradient(135deg, #7f1d1d 0%, #dc2626 50%, #991b1b 100%); }
        .fade-in { animation: fadeIn 0.5s ease forwards; }
        .fade-out { animation: fadeOut 0.4s ease forwards; }
        @keyframes fadeIn { from { opacity:0; transform:scale(0.95); } to { opacity:1; transform:scale(1); } }
        @keyframes fadeOut { from { opacity:1; transform:scale(1); } to { opacity:0; transform:scale(0.95); } }
        .icon-pulse { animation: iconPulse 2s ease-in-out infinite; }
        @keyframes iconPulse { 0%,100% { transform:scale(1); opacity:0.6; } 50% { transform:scale(1.1); opacity:1; } }
        .scan-ring { animation: scanRing 3s ease-in-out infinite; }
        @keyframes scanRing {
            0% { box-shadow: 0 0 0 0 rgba(59,130,246,0.4), 0 0 0 0 rgba(59,130,246,0.2); }
            50% { box-shadow: 0 0 0 20px rgba(59,130,246,0), 0 0 0 40px rgba(59,130,246,0); }
            100% { box-shadow: 0 0 0 0 rgba(59,130,246,0.4), 0 0 0 0 rgba(59,130,246,0.2); }
        }
        .balance-bar { height: 6px; border-radius: 999px; background: rgba(255,255,255,0.15); overflow: hidden; }
        .balance-fill { height: 100%; border-radius: 999px; transition: width 1s ease; }
        .clock-text { font-variant-numeric: tabular-nums; }
        .particles { position: fixed; inset: 0; pointer-events: none; z-index: 0; }
        .particle { position: absolute; width: 4px; height: 4px; background: rgba(255,255,255,0.1); border-radius: 50%; animation: float linear infinite; }
        @keyframes float {
            0% { transform: translateY(100vh) rotate(0deg); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translateY(-10vh) rotate(720deg); opacity: 0; }
        }
    </style>
</head>
<body class="gate-bg state-waiting" id="gate-body">

    {{-- Floating Particles --}}
    <div class="particles" id="particles"></div>

    {{-- Main Content --}}
    <div class="relative z-10 flex flex-col items-center justify-center min-h-screen p-8" id="gate-content">

        {{-- WAITING STATE (Default) --}}
        <div id="state-waiting" class="text-center fade-in">
            <div class="w-40 h-40 mx-auto mb-8 rounded-full bg-white/5 border-2 border-white/10 flex items-center justify-center scan-ring">
                <svg class="w-20 h-20 text-blue-400 icon-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                </svg>
            </div>
            <h1 class="text-6xl font-extrabold text-white tracking-tight mb-4">Tap Your Card</h1>
            <p class="text-2xl text-blue-300/70 font-medium">Place your RFID card on the reader</p>
            <div class="mt-10 flex items-center justify-center gap-3">
                <span class="w-3 h-3 rounded-full bg-blue-400 pulse-live"></span>
                <span class="text-sm font-semibold text-blue-300/50 uppercase tracking-widest">System Ready</span>
            </div>
            <div class="mt-6">
                <span class="text-sm text-slate-500">Active Vehicles: <strong class="text-blue-400" id="active-count">{{ $activeCount }}</strong></span>
            </div>
        </div>

        {{-- ENTRY STATE --}}
        <div id="state-entry" class="text-center hidden">
            <div class="w-36 h-36 mx-auto mb-8 rounded-full bg-white/10 flex items-center justify-center">
                <svg class="w-20 h-20 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                </svg>
            </div>
            <h1 class="text-5xl font-extrabold text-white tracking-tight mb-2">ENTRY SUCCESSFUL</h1>
            <p class="text-3xl text-blue-200 font-bold mb-2" id="entry-user-name"></p>
            <p class="text-lg text-blue-200/60 font-mono mb-6" id="entry-rfid"></p>
            <div class="inline-flex items-center gap-3 bg-white/10 backdrop-blur-sm px-8 py-4 rounded-2xl">
                <span class="text-lg text-blue-200/70">Balance</span>
                <span class="text-3xl font-extrabold text-white" id="entry-balance"></span>
            </div>
            <p class="text-xl text-blue-200/50 mt-6 font-medium">Welcome! Drive safely 🚗</p>
        </div>

        {{-- EXIT STATE (Access Granted) --}}
        <div id="state-exit" class="text-center hidden">
            <div class="w-36 h-36 mx-auto mb-8 rounded-full bg-white/10 flex items-center justify-center">
                <svg class="w-20 h-20 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-5xl font-extrabold text-white tracking-tight mb-2">ACCESS GRANTED</h1>
            <p class="text-3xl text-emerald-200 font-bold mb-6" id="exit-user-name"></p>
            <div class="grid grid-cols-2 gap-4 max-w-lg mx-auto mb-6">
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-5">
                    <p class="text-sm text-emerald-200/60 uppercase tracking-wider mb-1">Duration</p>
                    <p class="text-3xl font-extrabold text-white" id="exit-duration"></p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-5">
                    <p class="text-sm text-emerald-200/60 uppercase tracking-wider mb-1">Cost</p>
                    <p class="text-3xl font-extrabold text-white" id="exit-cost"></p>
                </div>
            </div>
            <div class="inline-flex items-center gap-3 bg-white/10 backdrop-blur-sm px-8 py-4 rounded-2xl mb-4">
                <span class="text-lg text-emerald-200/70">Remaining Balance</span>
                <span class="text-3xl font-extrabold text-white" id="exit-balance"></span>
            </div>
            <div class="balance-bar max-w-md mx-auto mt-4">
                <div class="balance-fill bg-emerald-400" id="exit-balance-bar" style="width:100%"></div>
            </div>
            <p class="text-xl text-emerald-200/50 mt-6 font-medium">Thank you! Have a safe drive 🏁</p>
        </div>

        {{-- INSUFFICIENT BALANCE STATE --}}
        <div id="state-insufficient" class="text-center hidden">
            <div class="w-36 h-36 mx-auto mb-8 rounded-full bg-white/10 flex items-center justify-center">
                <svg class="w-20 h-20 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <h1 class="text-5xl font-extrabold text-white tracking-tight mb-2">INSUFFICIENT BALANCE</h1>
            <p class="text-3xl text-red-200 font-bold mb-6" id="insuf-user-name"></p>
            <div class="grid grid-cols-2 gap-4 max-w-lg mx-auto mb-6">
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-5">
                    <p class="text-sm text-red-200/60 uppercase tracking-wider mb-1">Your Balance</p>
                    <p class="text-3xl font-extrabold text-white" id="insuf-balance"></p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-5">
                    <p class="text-sm text-red-200/60 uppercase tracking-wider mb-1">Required</p>
                    <p class="text-3xl font-extrabold text-white" id="insuf-cost"></p>
                </div>
            </div>
            <p class="text-xl text-red-200/50 mt-6 font-medium">Please top up your balance ⚠️</p>
        </div>
    </div>

    {{-- Clock --}}
    <div class="fixed bottom-8 left-1/2 -translate-x-1/2 z-20 text-center">
        <p class="text-4xl font-bold text-white/30 clock-text" id="gate-clock">--:--:--</p>
        <p class="text-sm text-white/15 mt-1" id="gate-date">Loading...</p>
    </div>

    {{-- Logo --}}
    <div class="fixed top-8 left-8 z-20 flex items-center gap-3">
        <div class="w-12 h-12 rounded-xl gradient-primary flex items-center justify-center shadow-lg">
            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
        </div>
        <div>
            <h2 class="text-white font-bold text-lg">Smart Parking</h2>
            <p class="text-blue-400/50 text-xs font-medium uppercase tracking-wider">Gate Display</p>
        </div>
    </div>

    {{-- Fullscreen Toggle --}}
    <button onclick="toggleFullscreen()" class="fixed top-8 right-8 z-20 w-10 h-10 rounded-xl bg-white/5 hover:bg-white/10 flex items-center justify-center text-white/30 hover:text-white/60 transition-all" id="fs-btn">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
        </svg>
    </button>

    <script>
        // ===== Particles =====
        (function() {
            const c = document.getElementById('particles');
            for (let i = 0; i < 30; i++) {
                const p = document.createElement('div');
                p.className = 'particle';
                p.style.left = Math.random() * 100 + '%';
                p.style.animationDuration = (8 + Math.random() * 15) + 's';
                p.style.animationDelay = (Math.random() * 10) + 's';
                p.style.width = p.style.height = (2 + Math.random() * 4) + 'px';
                c.appendChild(p);
            }
        })();

        // ===== Clock =====
        function updateGateClock() {
            const now = new Date();
            document.getElementById('gate-clock').textContent = now.toLocaleTimeString('id-ID', { hour:'2-digit', minute:'2-digit', second:'2-digit' });
            document.getElementById('gate-date').textContent = now.toLocaleDateString('id-ID', { weekday:'long', day:'numeric', month:'long', year:'numeric' });
        }
        updateGateClock();
        setInterval(updateGateClock, 1000);

        // ===== Fullscreen =====
        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(() => {});
            } else {
                document.exitFullscreen().catch(() => {});
            }
        }

        // ===== Format Currency =====
        function fmtIDR(v) { return 'Rp ' + new Intl.NumberFormat('id-ID').format(v); }

        // ===== Sound Effects =====
        function playSound(type) {
            try {
                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.connect(gain);
                gain.connect(ctx.destination);
                if (type === 'entry') { osc.frequency.value = 880; osc.type = 'sine'; }
                else if (type === 'exit') { osc.frequency.value = 1200; osc.type = 'sine'; }
                else { osc.frequency.value = 300; osc.type = 'square'; }
                gain.gain.setValueAtTime(0.3, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.4);
                osc.start(ctx.currentTime);
                osc.stop(ctx.currentTime + 0.4);
                if (type === 'exit') {
                    const o2 = ctx.createOscillator();
                    const g2 = ctx.createGain();
                    o2.connect(g2); g2.connect(ctx.destination);
                    o2.frequency.value = 1400; o2.type = 'sine';
                    g2.gain.setValueAtTime(0.3, ctx.currentTime + 0.15);
                    g2.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.5);
                    o2.start(ctx.currentTime + 0.15);
                    o2.stop(ctx.currentTime + 0.5);
                }
            } catch(e) {}
        }

        // ===== State Management =====
        let resetTimer = null;
        const states = ['state-waiting','state-entry','state-exit','state-insufficient'];
        const bgClasses = ['state-waiting','state-entry','state-exit','state-insufficient'];
        const body = document.getElementById('gate-body');

        function showState(state) {
            if (resetTimer) clearTimeout(resetTimer);
            states.forEach(s => {
                const el = document.getElementById(s);
                if (el) { el.classList.add('hidden'); el.classList.remove('fade-in'); }
            });
            bgClasses.forEach(c => body.classList.remove(c));

            const el = document.getElementById('state-' + state);
            if (el) {
                body.classList.add('state-' + state);
                el.classList.remove('hidden');
                void el.offsetWidth;
                el.classList.add('fade-in');
            }

            if (state !== 'waiting') {
                resetTimer = setTimeout(() => showState('waiting'), 8000);
            }
        }

        // ===== Real-time Listener =====
        document.addEventListener('DOMContentLoaded', () => {
            if (window.Echo) {
                window.Echo.channel('gate-screen')
                    .listen('.scan.result', (data) => {
                        if (data.type === 'entry') {
                            document.getElementById('entry-user-name').textContent = data.user_name;
                            document.getElementById('entry-rfid').textContent = data.rfid_uid;
                            document.getElementById('entry-balance').textContent = fmtIDR(data.balance);
                            playSound('entry');
                            showState('entry');
                        } else if (data.type === 'exit') {
                            document.getElementById('exit-user-name').textContent = data.user_name;
                            document.getElementById('exit-duration').textContent = data.duration + ' Jam';
                            document.getElementById('exit-cost').textContent = fmtIDR(data.cost);
                            document.getElementById('exit-balance').textContent = fmtIDR(data.balance);
                            const pct = Math.min(100, Math.max(5, (data.balance / 100000) * 100));
                            document.getElementById('exit-balance-bar').style.width = pct + '%';
                            playSound('exit');
                            showState('exit');
                        } else if (data.type === 'insufficient_balance') {
                            document.getElementById('insuf-user-name').textContent = data.user_name;
                            document.getElementById('insuf-balance').textContent = fmtIDR(data.balance);
                            document.getElementById('insuf-cost').textContent = fmtIDR(data.cost);
                            playSound('error');
                            showState('insufficient');
                        }
                    });

                window.Echo.channel('parking-channel')
                    .listen('.parking.entry', () => {
                        const el = document.getElementById('active-count');
                        if (el) el.textContent = parseInt(el.textContent || '0') + 1;
                    })
                    .listen('.parking.exit', () => {
                        const el = document.getElementById('active-count');
                        if (el) el.textContent = Math.max(0, parseInt(el.textContent || '0') - 1);
                    });
            }
        });

        // Auto fullscreen on first click
        document.addEventListener('click', function autoFs() {
            document.documentElement.requestFullscreen().catch(() => {});
            document.removeEventListener('click', autoFs);
        }, { once: true });
    </script>
</body>
</html>
