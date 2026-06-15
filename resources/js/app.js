import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// ===== Initialize Laravel Reverb & Echo =====
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});

// ===== Notification Sound =====
const notificationSound = new Audio('data:audio/wav;base64,UklGRl9vT19teleXRlZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQ==');

function playNotificationSound() {
    try {
        // Create a simple beep using AudioContext
        const ctx = new (window.AudioContext || window.webkitAudioContext)();
        const osc = ctx.createOscillator();
        const gain = ctx.createGain();
        osc.connect(gain);
        gain.connect(ctx.destination);
        osc.frequency.value = 800;
        osc.type = 'sine';
        gain.gain.setValueAtTime(0.3, ctx.currentTime);
        gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.3);
        osc.start(ctx.currentTime);
        osc.stop(ctx.currentTime + 0.3);
    } catch (e) {
        // Audio not supported or blocked
    }
}

// ===== Toast Notification System =====
window.showToast = function (message, type = 'info', duration = 4000) {
    const container = document.getElementById('toast-container');
    if (!container) return;

    const icons = {
        success: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>`,
        info: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`,
        warning: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>`,
        error: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>`,
    };

    const colors = {
        success: 'bg-emerald-500',
        info: 'bg-blue-500',
        warning: 'bg-amber-500',
        error: 'bg-red-500',
    };

    const toast = document.createElement('div');
    toast.className = `toast-enter flex items-center gap-3 px-5 py-3.5 rounded-xl shadow-2xl text-white text-sm font-medium ${colors[type] || colors.info} mb-3 max-w-sm`;
    toast.innerHTML = `
        <span class="flex-shrink-0">${icons[type] || icons.info}</span>
        <span class="flex-1">${message}</span>
        <button onclick="this.parentElement.classList.replace('toast-enter','toast-exit');setTimeout(()=>this.parentElement.remove(),300)" class="flex-shrink-0 opacity-70 hover:opacity-100 transition-opacity">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    `;

    container.appendChild(toast);

    // Play notification sound
    playNotificationSound();

    // Auto remove
    setTimeout(() => {
        if (toast.parentElement) {
            toast.classList.replace('toast-enter', 'toast-exit');
            setTimeout(() => toast.remove(), 300);
        }
    }, duration);
};

// ===== Currency Formatter =====
window.formatIDR = function (amount) {
    return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
};

// ===== Date Formatter =====
window.formatDateTime = function (dateString) {
    if (!dateString) return '-';
    const d = new Date(dateString);
    return d.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};

// ===== Dark Mode Toggle =====
window.toggleDarkMode = function () {
    document.documentElement.classList.toggle('dark');
    const isDark = document.documentElement.classList.contains('dark');
    localStorage.setItem('darkMode', isDark ? 'true' : 'false');
    
    // Update icon
    const icon = document.getElementById('dark-mode-icon');
    if (icon) {
        icon.innerHTML = isDark
            ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>'
            : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>';
    }
};

// Initialize dark mode - dark by default
if (localStorage.getItem('darkMode') === 'false') {
    document.documentElement.classList.remove('dark');
} else {
    document.documentElement.classList.add('dark');
}

// ===== Real-time Event Listeners =====
document.addEventListener('DOMContentLoaded', () => {
    const currentPath = window.location.pathname;

    // ============================
    // DASHBOARD PAGE
    // ============================
    if (currentPath === '/dashboard' || currentPath === '/') {
        // Load initial dashboard data
        loadDashboardData();

        window.Echo.channel('parking-channel')
            .listen('.parking.entry', (e) => {
                window.showToast(`🚗 Vehicle Entered: ${e.parking.user.name}`, 'success');
                loadDashboardData();
                prependParkingRow(e.parking);
            })
            .listen('.parking.exit', (e) => {
                window.showToast(`✅ Vehicle Exited: ${e.parking.user.name} • Cost: ${formatIDR(e.parking.cost)}`, 'info');
                loadDashboardData();
                updateParkingRow(e.parking);
            });

        window.Echo.channel('transaction-channel')
            .listen('.transaction.created', (e) => {
                // Revenue will be updated via loadDashboardData
            });
    }

    // ============================
    // USERS PAGE
    // ============================
    if (currentPath === '/users') {
        window.Echo.channel('parking-channel')
            .listen('.parking.entry', (e) => {
                window.showToast(`👤 New scan: ${e.parking.user.name}`, 'info');
                refreshUsersTable();
            });
    }

    // ============================
    // PARKINGS PAGE
    // ============================
    if (currentPath === '/parkings') {
        window.Echo.channel('parking-channel')
            .listen('.parking.entry', (e) => {
                window.showToast(`🚗 Vehicle Entered: ${e.parking.user.name}`, 'success');
                refreshParkingsTable();
            })
            .listen('.parking.exit', (e) => {
                window.showToast(`✅ Vehicle Exited: ${e.parking.user.name}`, 'info');
                refreshParkingsTable();
            });
    }

    // ============================
    // TRANSACTIONS PAGE
    // ============================
    if (currentPath === '/transactions') {
        window.Echo.channel('transaction-channel')
            .listen('.transaction.created', (e) => {
                window.showToast(`💰 New Transaction: ${formatIDR(e.transaction.amount)}`, 'success');
                refreshTransactionsTable();
            });
    }

    // ============================
    // TOP UP REQUESTS PAGE
    // ============================
    if (currentPath === '/topup-requests') {
        window.Echo.channel('topup-requests-channel')
            .listen('.topup-request.created', (e) => {
                window.showToast(`📥 New top-up request: ${formatIDR(e.topup_request.amount)} from ${e.topup_request.user.name}`, 'info');
                setTimeout(() => location.reload(), 1500);
            })
            .listen('.topup-request.approved', (e) => {
                window.showToast(`✅ Top-up approved for ${e.topup_request.user.name}`, 'success');
            })
            .listen('.topup-request.rejected', (e) => {
                window.showToast(`❌ Top-up rejected for ${e.topup_request.user.name}`, 'warning');
            });
    }

    // ============================
    // STUDENTS PAGE
    // ============================
    if (currentPath === '/students') {
        window.Echo.channel('student-channel')
            .listen('.student.created', (e) => {
                window.showToast(`🎓 New student registered: ${e.student.name}`, 'success');
                setTimeout(() => location.reload(), 1500);
            });
    }

    // ============================
    // GLOBAL: Notify admin on any page about new top-up requests
    // ============================
    window.Echo.channel('topup-requests-channel')
        .listen('.topup-request.created', (e) => {
            if (currentPath !== '/topup-requests') {
                window.showToast(`📥 New top-up request from ${e.topup_request.user.name}: ${formatIDR(e.topup_request.amount)}`, 'info');
            }
        });
});

// ===== Dashboard Data Loader =====
function loadDashboardData() {
    fetch('/api/dashboard')
        .then(r => r.json())
        .then(data => {
            animateCounter('stat-total-today', data.total_today);
            animateCounter('stat-active-in', data.active_in);

            const revEl = document.getElementById('stat-revenue');
            if (revEl) revEl.textContent = formatIDR(data.revenue);
        })
        .catch(err => console.warn('Dashboard data fetch failed:', err));
}

function animateCounter(elementId, targetValue) {
    const el = document.getElementById(elementId);
    if (!el) return;

    const current = parseInt(el.textContent) || 0;
    const diff = targetValue - current;
    if (diff === 0) return;

    const steps = 20;
    const increment = diff / steps;
    let step = 0;

    const timer = setInterval(() => {
        step++;
        el.textContent = Math.round(current + increment * step);
        if (step >= steps) {
            el.textContent = targetValue;
            clearInterval(timer);
        }
    }, 30);
}

// ===== Dashboard Table Helpers =====
function prependParkingRow(parking) {
    const tbody = document.getElementById('parking-table-body');
    if (!tbody) return;

    const row = document.createElement('tr');
    row.id = `parking-row-${parking.id}`;
    row.className = 'table-row-hover row-highlight border-b border-slate-100 dark:border-slate-700';
    row.innerHTML = `
        <td class="px-6 py-4">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full gradient-primary flex items-center justify-center text-white text-sm font-bold">
                    ${parking.user.name.charAt(0).toUpperCase()}
                </div>
                <div>
                    <div class="font-semibold text-slate-800 dark:text-slate-200">${parking.user.name}</div>
                    <div class="text-xs text-slate-400">${parking.user.rfid_uid}</div>
                </div>
            </div>
        </td>
        <td class="px-6 py-4 text-slate-600 dark:text-slate-300">${formatDateTime(parking.entry_time)}</td>
        <td class="px-6 py-4 text-slate-400">-</td>
        <td class="px-6 py-4 text-slate-400">-</td>
        <td class="px-6 py-4 text-slate-400">-</td>
        <td class="px-6 py-4"><span class="badge-in">● IN</span></td>
    `;
    tbody.prepend(row);

    // Remove last row if more than 20
    while (tbody.children.length > 20) {
        tbody.removeChild(tbody.lastChild);
    }
}

function updateParkingRow(parking) {
    const row = document.getElementById(`parking-row-${parking.id}`);
    if (row) {
        row.className = 'table-row-hover row-highlight border-b border-slate-100 dark:border-slate-700';
        const cells = row.querySelectorAll('td');
        if (cells.length >= 6) {
            cells[2].innerHTML = `<span class="text-slate-600 dark:text-slate-300">${formatDateTime(parking.exit_time)}</span>`;
            cells[3].innerHTML = `<span class="text-slate-600 dark:text-slate-300">${parking.duration} Jam</span>`;
            cells[4].innerHTML = `<span class="font-semibold text-slate-800 dark:text-slate-200">${formatIDR(parking.cost)}</span>`;
            cells[5].innerHTML = '<span class="badge-out">● OUT</span>';
        }
    }
}

// ===== Page Refresh Helpers (for Users, Parkings, Transactions pages) =====
function refreshUsersTable() {
    fetch('/api/users')
        .then(r => r.json())
        .then(users => {
            const tbody = document.getElementById('users-table-body');
            if (!tbody) return;
            tbody.innerHTML = '';
            users.forEach(u => {
                const tr = document.createElement('tr');
                tr.className = 'table-row-hover border-b border-slate-100 dark:border-slate-700 row-highlight';
                tr.innerHTML = `
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full gradient-info flex items-center justify-center text-white text-sm font-bold">${u.name.charAt(0).toUpperCase()}</div>
                            <span class="font-semibold text-slate-800 dark:text-slate-200">${u.name}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4"><code class="bg-slate-100 dark:bg-slate-700 px-2 py-1 rounded text-sm font-mono text-slate-700 dark:text-slate-300">${u.rfid_uid}</code></td>
                    <td class="px-6 py-4 font-semibold text-slate-800 dark:text-slate-200">${formatIDR(u.balance)}</td>
                `;
                tbody.appendChild(tr);
            });
        })
        .catch(err => console.warn('Users refresh failed:', err));
}

function refreshParkingsTable() {
    fetch('/api/parkings')
        .then(r => r.json())
        .then(parkings => {
            const tbody = document.getElementById('parkings-table-body');
            if (!tbody) return;
            tbody.innerHTML = '';
            parkings.forEach(p => {
                const tr = document.createElement('tr');
                tr.className = 'table-row-hover border-b border-slate-100 dark:border-slate-700 row-highlight';
                tr.innerHTML = `
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full gradient-primary flex items-center justify-center text-white text-sm font-bold">${p.user.name.charAt(0).toUpperCase()}</div>
                            <div>
                                <div class="font-semibold text-slate-800 dark:text-slate-200">${p.user.name}</div>
                                <div class="text-xs text-slate-400">${p.user.rfid_uid}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-slate-600 dark:text-slate-300">${formatDateTime(p.entry_time)}</td>
                    <td class="px-6 py-4 text-slate-600 dark:text-slate-300">${p.exit_time ? formatDateTime(p.exit_time) : '-'}</td>
                    <td class="px-6 py-4 text-slate-600 dark:text-slate-300">${p.duration ? p.duration + ' Jam' : '-'}</td>
                    <td class="px-6 py-4 font-semibold text-slate-800 dark:text-slate-200">${p.cost ? formatIDR(p.cost) : '-'}</td>
                    <td class="px-6 py-4">${p.status === 'IN' ? '<span class="badge-in">● IN</span>' : '<span class="badge-out">● OUT</span>'}</td>
                `;
                tbody.appendChild(tr);
            });
        })
        .catch(err => console.warn('Parkings refresh failed:', err));
}

function refreshTransactionsTable() {
    fetch('/api/transactions')
        .then(r => r.json())
        .then(transactions => {
            const tbody = document.getElementById('transactions-table-body');
            if (!tbody) return;
            tbody.innerHTML = '';
            transactions.forEach(t => {
                const tr = document.createElement('tr');
                tr.className = 'table-row-hover border-b border-slate-100 dark:border-slate-700 row-highlight';
                tr.innerHTML = `
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full gradient-success flex items-center justify-center text-white text-sm font-bold">${t.user.name.charAt(0).toUpperCase()}</div>
                            <div>
                                <div class="font-semibold text-slate-800 dark:text-slate-200">${t.user.name}</div>
                                <div class="text-xs text-slate-400">${t.user.rfid_uid}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-slate-600 dark:text-slate-300">#${t.parking_id}</td>
                    <td class="px-6 py-4 font-semibold text-emerald-600">${formatIDR(t.amount)}</td>
                    <td class="px-6 py-4 text-slate-600 dark:text-slate-300">${formatIDR(t.remaining_balance)}</td>
                    <td class="px-6 py-4 text-slate-500 dark:text-slate-400">${formatDateTime(t.created_at)}</td>
                `;
                tbody.appendChild(tr);
            });
        })
        .catch(err => console.warn('Transactions refresh failed:', err));
}

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */

import './echo';
