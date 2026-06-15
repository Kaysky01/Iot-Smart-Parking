@extends('layouts.app')

@section('title', 'Students')
@section('page-title', 'Student Management')
@section('page-subtitle', 'Register and manage student accounts')

@section('content')
<div class="space-y-6">

    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="stat-card bg-slate-800 rounded-2xl shadow-md border border-slate-700 p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider">Total Students</p>
                    <p class="text-3xl font-extrabold text-white mt-2">{{ $students->total() }}</p>
                </div>
                <div class="w-12 h-12 rounded-2xl gradient-primary flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Two Column: Form + Table --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left: Create Student Form --}}
        <div class="lg:col-span-1">
            <div class="bg-slate-800 rounded-2xl shadow-md border border-slate-700 p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl gradient-primary flex items-center justify-center shadow-md">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-white">Register Student</h3>
                        <p class="text-sm text-slate-400">Create new student account</p>
                    </div>
                </div>

                <form id="student-form" class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">NPM <span class="text-red-400">*</span></label>
                        <input type="text" id="inp-npm" required placeholder="e.g. 24783072"
                               class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-700 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Name <span class="text-red-400">*</span></label>
                        <input type="text" id="inp-name" required placeholder="Full name"
                               class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-700 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Password <span class="text-red-400">*</span></label>
                        <input type="password" id="inp-password" required minlength="6" placeholder="Min 6 characters"
                               class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-700 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">RFID UID</label>
                        <div class="flex gap-2">
                            <input type="text" id="inp-rfid" placeholder="Scan or type RFID UID"
                                   class="flex-1 px-4 py-3 rounded-xl bg-slate-900 border border-slate-700 text-white font-mono uppercase placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                            <button type="button" id="btn-scan-rfid"
                                    class="px-4 py-3 rounded-xl gradient-primary text-white text-xs font-bold hover:opacity-90 transition-all whitespace-nowrap flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4"></path></svg>
                                Scan RFID
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Plate Number</label>
                        <input type="text" id="inp-plate" placeholder="e.g. BE 1234 AB" style="text-transform:uppercase"
                               class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-700 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Vehicle Type</label>
                        <select id="inp-vehicle"
                                class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-700 text-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                            <option value="">-- Select --</option>
                            <option value="motor">🏍️ Motor</option>
                            <option value="mobil">🚗 Mobil</option>
                        </select>
                    </div>
                    <button type="submit" id="btn-submit"
                            class="w-full py-4 rounded-xl gradient-primary hover:opacity-95 text-white font-bold tracking-wide shadow-lg shadow-blue-500/20 flex items-center justify-center gap-2 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span id="btn-submit-text">Register Student</span>
                    </button>
                </form>
            </div>
        </div>

        {{-- Right: Students Table --}}
        <div class="lg:col-span-2 bg-slate-800 rounded-2xl shadow-md border border-slate-700 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-700 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-white">Registered Students</h3>
                    <p class="text-sm text-slate-400 mt-0.5">All student accounts</p>
                </div>
                <div class="flex items-center gap-2 text-xs font-semibold text-emerald-400 bg-emerald-900/30 px-3 py-1.5 rounded-full">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 pulse-live"></span>
                    Auto-updating
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-slate-700/50">
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Student</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">NPM</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">RFID</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Vehicle</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Balance</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-slate-400 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody id="students-table-body" class="divide-y divide-slate-700">
                        @forelse($students as $student)
                        <tr class="table-row-hover" id="student-row-{{ $student->id }}">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full gradient-primary flex items-center justify-center text-white text-sm font-bold">
                                        {{ strtoupper(substr($student->name, 0, 1)) }}
                                    </div>
                                    <span class="font-semibold text-slate-200">{{ $student->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4"><code class="bg-slate-700 px-3 py-1.5 rounded-lg text-sm font-mono text-blue-400">{{ $student->npm }}</code></td>
                            <td class="px-6 py-4"><code class="bg-slate-700 px-3 py-1.5 rounded-lg text-sm font-mono text-slate-300">{{ $student->rfid_uid ?? '-' }}</code></td>
                            <td class="px-6 py-4 text-sm text-slate-300">
                                @if($student->vehicle_type === 'motor') 🏍️ @elseif($student->vehicle_type === 'mobil') 🚗 @endif
                                {{ $student->plate_number ?? '-' }}
                            </td>
                            <td class="px-6 py-4 font-semibold text-slate-200">{{ $student->formatted_balance }}</td>
                            <td class="px-6 py-4 text-center">
                                <button onclick="deleteStudent({{ $student->id }}, '{{ addslashes($student->name) }}')"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-900/20 transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr id="empty-row">
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-slate-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5z"></path></svg>
                                    <p class="text-slate-400 font-medium">No students registered yet</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($students->hasPages())
            <div class="px-6 py-4 border-t border-slate-700">{{ $students->links() }}</div>
            @endif
        </div>
    </div>
</div>

{{-- ===== RFID SCAN MODAL ===== --}}
<div id="rfid-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeRfidModal()"></div>
    <div class="relative bg-slate-800 rounded-2xl shadow-2xl border border-slate-700 w-full max-w-md transform transition-all duration-300 scale-95 opacity-0" id="rfid-modal-card">
        <div class="p-8 text-center">
            <div class="w-20 h-20 rounded-2xl gradient-primary flex items-center justify-center mx-auto mb-6 shadow-lg shadow-blue-500/30">
                <svg class="w-10 h-10 text-white animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">Scan RFID Card</h3>
            <p class="text-sm text-slate-400 mb-6">Tap the student's RFID card on the reader...</p>
            <div class="flex items-center justify-center gap-2 mb-4">
                <span class="relative flex h-3 w-3"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span><span class="relative inline-flex rounded-full h-3 w-3 bg-blue-500"></span></span>
                <span class="text-sm font-semibold text-blue-400" id="rfid-modal-status">Waiting for RFID scan...</span>
            </div>
            <button onclick="closeRfidModal()" class="mt-4 px-6 py-2.5 rounded-xl text-sm font-semibold text-slate-300 bg-slate-700 hover:bg-slate-600 transition-all">Cancel</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const CSRF = '{{ csrf_token() }}';

    // Form submission
    document.getElementById('student-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        const btn = document.getElementById('btn-submit');
        const btnText = document.getElementById('btn-submit-text');
        btn.disabled = true;
        btnText.textContent = 'Registering...';

        try {
            const res = await fetch('/students', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    npm: document.getElementById('inp-npm').value,
                    name: document.getElementById('inp-name').value,
                    password: document.getElementById('inp-password').value,
                    rfid_uid: document.getElementById('inp-rfid').value || null,
                    plate_number: document.getElementById('inp-plate').value || null,
                    vehicle_type: document.getElementById('inp-vehicle').value || null,
                })
            });
            const data = await res.json();
            if (res.ok) {
                if (window.showToast) window.showToast(data.message, 'success');
                document.getElementById('student-form').reset();
                document.getElementById('inp-rfid').value = '';
                setTimeout(() => location.reload(), 1000);
            } else {
                const errors = data.errors ? Object.values(data.errors).flat().join(', ') : data.message;
                if (window.showToast) window.showToast(errors, 'error');
            }
        } catch (err) {
            if (window.showToast) window.showToast('Network error', 'error');
        } finally {
            btn.disabled = false;
            btnText.textContent = 'Register Student';
        }
    });

    // Delete student
    async function deleteStudent(id, name) {
        if (!confirm(`Delete student "${name}"?`)) return;
        try {
            const res = await fetch(`/students/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
            });
            const data = await res.json();
            if (res.ok) {
                const row = document.getElementById(`student-row-${id}`);
                if (row) { row.style.opacity = '0'; setTimeout(() => row.remove(), 300); }
                if (window.showToast) window.showToast(data.message, 'success');
            } else {
                if (window.showToast) window.showToast(data.message, 'error');
            }
        } catch (e) {
            if (window.showToast) window.showToast('Network error', 'error');
        }
    }

    // RFID Scan Modal
    document.getElementById('btn-scan-rfid').addEventListener('click', openRfidModal);

    function openRfidModal() {
        const modal = document.getElementById('rfid-modal');
        const card = document.getElementById('rfid-modal-card');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        requestAnimationFrame(() => { card.classList.remove('scale-95', 'opacity-0'); card.classList.add('scale-100', 'opacity-100'); });
    }

    function closeRfidModal() {
        const modal = document.getElementById('rfid-modal');
        const card = document.getElementById('rfid-modal-card');
        card.classList.add('scale-95', 'opacity-0');
        card.classList.remove('scale-100', 'opacity-100');
        setTimeout(() => { modal.classList.add('hidden'); modal.classList.remove('flex'); }, 200);
    }

    // Listen for RFID scan via WebSocket
    if (window.Echo) {
        window.Echo.channel('gate-screen')
            .listen('.scan.result', (e) => {
                const rfidInput = document.getElementById('inp-rfid');
                rfidInput.value = e.rfid_uid;
                const modalStatus = document.getElementById('rfid-modal-status');
                if (modalStatus) modalStatus.innerHTML = `<span class="text-emerald-400">✅ Card detected: ${e.rfid_uid}</span>`;
                setTimeout(closeRfidModal, 1500);
                if (window.showToast) window.showToast(`🎴 RFID Scanned: ${e.rfid_uid}`, 'success');
            });
    }
</script>
@endpush
