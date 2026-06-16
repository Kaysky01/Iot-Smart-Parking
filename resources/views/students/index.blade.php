@extends('layouts.app')

@section('title', 'Students')
@section('page-title', 'Student Management')
@section('page-subtitle', 'Register and manage student accounts')

@section('content')
<div class="space-y-6">

    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="stat-card p-6 border-b-4 border-b-[var(--app-primary)]">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-semibold text-[var(--app-text-secondary)] uppercase tracking-wider">Total Students</p>
                    <p class="text-3xl font-extrabold text-[var(--app-text)] mt-2">{{ $students->total() }}</p>
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

    {{-- Two Column: Form + Table --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left: Create Student Form --}}
        <div class="lg:col-span-1">
            <div class="app-card p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-[var(--app-primary-soft)] text-[var(--app-primary)] flex items-center justify-center border border-[color-mix(in_srgb,var(--app-primary)_20%,transparent)]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="app-section-title text-lg">Register Student</h3>
                        <p class="text-sm text-[var(--app-text-muted)] mt-0.5">Create new student account</p>
                    </div>
                </div>

                <form id="student-form" class="space-y-4">
                    <div>
                        <label class="block text-[11px] font-bold text-[var(--app-text-secondary)] uppercase tracking-wider mb-1.5">NPM <span class="text-[var(--app-danger)]">*</span></label>
                        <input type="text" id="inp-npm" required placeholder="e.g. 24783072"
                               class="app-input">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-[var(--app-text-secondary)] uppercase tracking-wider mb-1.5">Name <span class="text-[var(--app-danger)]">*</span></label>
                        <input type="text" id="inp-name" required placeholder="Full name"
                               class="app-input">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-[var(--app-text-secondary)] uppercase tracking-wider mb-1.5">Password <span class="text-[var(--app-danger)]">*</span></label>
                        <input type="password" id="inp-password" required minlength="6" placeholder="Min 6 characters"
                               class="app-input">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-[var(--app-text-secondary)] uppercase tracking-wider mb-1.5">RFID UID</label>
                        <div class="flex gap-2">
                            <input type="text" id="inp-rfid" placeholder="Scan or type RFID..."
                                   class="app-input font-mono uppercase text-sm">
                            <button type="button" id="btn-scan-rfid"
                                    class="app-button-secondary px-3 flex items-center justify-center">
                                <svg class="w-5 h-5 text-[var(--app-primary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4"></path></svg>
                            </button>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[11px] font-bold text-[var(--app-text-secondary)] uppercase tracking-wider mb-1.5">Vehicle</label>
                            <select id="inp-vehicle" class="app-select">
                                <option value="">-- Select --</option>
                                <option value="motor">Motor</option>
                                <option value="mobil">Mobil</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-[var(--app-text-secondary)] uppercase tracking-wider mb-1.5">Plate</label>
                            <input type="text" id="inp-plate" placeholder="BE 1234 AB" style="text-transform:uppercase"
                                   class="app-input">
                        </div>
                    </div>
                    <div class="pt-3">
                        <button type="submit" id="btn-submit"
                                class="app-button-primary w-full py-3.5 font-bold tracking-wide flex items-center justify-center gap-2">
                            <span id="btn-submit-text">Register Student</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Right: Students Table --}}
        <div class="lg:col-span-2 app-card overflow-hidden h-fit">
            <div class="px-6 py-5 border-b border-[var(--app-border)] flex items-center justify-between">
                <div>
                    <h3 class="app-section-title text-lg">Registered Students</h3>
                    <p class="app-subtitle text-sm mt-0.5">All student accounts</p>
                </div>
                <div class="flex items-center gap-2 text-xs font-bold text-[var(--app-success)] bg-[var(--app-success-soft)] px-3 py-1.5 rounded-full">
                    <span class="w-2 h-2 rounded-full bg-[var(--app-success)] pulse-live"></span>
                    Auto-updating
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-[var(--app-surface-soft)] border-b border-[var(--app-border)]">
                            <th class="px-6 py-4 text-xs font-bold text-[var(--app-text-secondary)] uppercase tracking-wider">Student</th>
                            <th class="px-6 py-4 text-xs font-bold text-[var(--app-text-secondary)] uppercase tracking-wider">NPM</th>
                            <th class="px-6 py-4 text-xs font-bold text-[var(--app-text-secondary)] uppercase tracking-wider">RFID / Vehicle</th>
                            <th class="px-6 py-4 text-xs font-bold text-[var(--app-text-secondary)] uppercase tracking-wider">Balance</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-[var(--app-text-secondary)] uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody id="students-table-body" class="divide-y divide-[var(--app-border)]">
                        @forelse($students as $student)
                        <tr class="table-row-hover bg-[var(--app-surface)]" id="student-row-{{ $student->id }}">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-[var(--app-primary-soft)] flex items-center justify-center text-[var(--app-primary)] text-sm font-bold border border-[color-mix(in_srgb,var(--app-primary)_20%,transparent)]">
                                        {{ strtoupper(substr($student->name, 0, 1)) }}
                                    </div>
                                    <span class="font-bold text-[var(--app-text)]">{{ $student->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4"><span class="text-sm text-[var(--app-text)]">{{ $student->npm }}</span></td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-1">
                                    <code class="app-code w-fit text-xs font-mono bg-transparent border-0 p-0 text-[var(--app-primary)] font-semibold">{{ $student->rfid_uid ?? 'No RFID' }}</code>
                                    <span class="text-xs text-[var(--app-text-muted)]">
                                        @if($student->vehicle_type === 'motor') 🏍️ @elseif($student->vehicle_type === 'mobil') 🚗 @endif
                                        {{ $student->plate_number ?? 'No Vehicle' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-bold text-[var(--app-text)]">{{ $student->formatted_balance }}</td>
                            <td class="px-6 py-4 text-center">
                                <button onclick="deleteStudent({{ $student->id }}, '{{ addslashes($student->name) }}')"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-[var(--app-text-muted)] hover:text-[var(--app-danger)] hover:bg-[var(--app-danger-soft)] transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr id="empty-row">
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="app-empty-state max-w-sm mx-auto">
                                    <div class="app-empty-icon">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5z"></path></svg>
                                    </div>
                                    <p class="text-base font-bold text-[var(--app-text)] mb-1">No students registered yet</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($students->hasPages())
            <div class="px-6 py-4 border-t border-[var(--app-border)] bg-[var(--app-surface-soft)]">{{ $students->links() }}</div>
            @endif
        </div>
    </div>
</div>

{{-- ===== RFID SCAN MODAL ===== --}}
<div id="rfid-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="absolute inset-0 app-backdrop" onclick="closeRfidModal()"></div>
    <div class="app-modal relative w-full max-w-sm transform transition-all duration-300 scale-95 opacity-0" id="rfid-modal-card">
        <div class="p-8 text-center">
            <div class="w-20 h-20 rounded-full bg-[var(--app-primary-soft)] text-[var(--app-primary)] flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-[var(--app-text)] mb-2">Scan RFID Card</h3>
            <p class="text-sm text-[var(--app-text-muted)] mb-6">Tap the student's RFID card on the reader</p>
            <div class="flex items-center justify-center gap-2 mb-6">
                <span class="relative flex h-2.5 w-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[var(--app-primary)] opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-[var(--app-primary)]"></span>
                </span>
                <span class="text-sm font-bold text-[var(--app-primary)]" id="rfid-modal-status">Waiting for scan...</span>
            </div>
            <button onclick="closeRfidModal()" class="app-button-secondary w-full py-3 font-bold text-sm">Cancel</button>
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
                if (modalStatus) modalStatus.innerHTML = `<span class="text-[var(--app-success)]">✅ Card detected: ${e.rfid_uid}</span>`;
                setTimeout(closeRfidModal, 1500);
                if (window.showToast) window.showToast(`🎴 RFID Scanned: ${e.rfid_uid}`, 'success');
            });
    }
</script>
@endpush
