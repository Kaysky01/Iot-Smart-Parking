@extends('layouts.app')

@section('title', 'Users')
@section('page-title', 'Users Management')
@section('page-subtitle', 'RFID-registered users & balances')

@section('content')
<div class="space-y-6">

    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="stat-card bg-surface dark:bg-slate-800 rounded-2xl shadow-md border border-slate-100 dark:border-slate-700 p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Users</p>
                    <p class="text-3xl font-extrabold text-slate-800 dark:text-white mt-2">{{ $users->total() }}</p>
                </div>
                <div class="w-12 h-12 rounded-2xl gradient-info flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Users Table --}}
    <div class="bg-surface dark:bg-slate-800 rounded-2xl shadow-md border border-slate-100 dark:border-slate-700 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-slate-800 dark:text-white">Registered Users</h3>
                <p class="text-sm text-slate-400 mt-0.5">All RFID-linked users</p>
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
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">User</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">RFID UID</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Balance</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody id="users-table-body" class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($users as $user)
                    <tr class="table-row-hover" id="user-row-{{ $user->id }}">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full gradient-info flex items-center justify-center text-white text-sm font-bold">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <code class="bg-slate-100 dark:bg-slate-700 px-3 py-1.5 rounded-lg text-sm font-mono text-slate-700 dark:text-slate-300">{{ $user->rfid_uid }}</code>
                        </td>
                        <td class="px-6 py-4 font-semibold text-slate-800 dark:text-slate-200">
                            {{ $user->formatted_balance }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button
                                onclick="confirmDeleteUser({{ $user->id }}, '{{ addslashes($user->name) }}')"
                                title="Hapus pengguna ini"
                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-all duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-slate-300 dark:text-slate-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                <p class="text-slate-400 font-medium">No users registered yet</p>
                                <p class="text-sm text-slate-400 mt-1">Users are automatically registered when they first scan their RFID card</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div id="delete-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeDeleteModal()"></div>

    {{-- Modal Card --}}
    <div class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700 w-full max-w-md transform transition-all duration-300 scale-95 opacity-0" id="delete-modal-card">
        <div class="p-6">
            {{-- Icon --}}
            <div class="flex items-center justify-center w-14 h-14 rounded-2xl bg-red-100 dark:bg-red-900/30 mx-auto mb-5">
                <svg class="w-7 h-7 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2M3 12a9 9 0 1118 0 9 9 0 01-18 0z"/>
                </svg>
            </div>

            <h3 class="text-lg font-bold text-slate-800 dark:text-white text-center">Hapus Pengguna?</h3>
            <p class="text-sm text-slate-500 dark:text-slate-400 text-center mt-2" id="modal-description">
                Pengguna ini akan dihapus beserta semua riwayat parkir dan transaksinya.
            </p>

            {{-- Warning --}}
            <div class="mt-4 p-3 rounded-xl bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 flex items-start gap-2.5">
                <svg class="w-4 h-4 text-amber-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
                <p class="text-xs text-amber-700 dark:text-amber-400 font-medium">Tindakan ini tidak dapat dibatalkan dan akan menghapus semua data terkait pengguna.</p>
            </div>

            <div class="flex gap-3 mt-5">
                <button onclick="closeDeleteModal()"
                    class="flex-1 px-4 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 rounded-xl transition-all duration-200">
                    Batal
                </button>
                <button id="modal-confirm-btn"
                    class="flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-xl transition-all duration-200 flex items-center justify-center gap-2">
                    <span id="modal-confirm-text">Hapus</span>
                    <svg id="modal-spinner" class="hidden w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const CSRF_TOKEN = '{{ csrf_token() }}';
    let pendingAction = null;

    function showModal(description, onConfirm) {
        document.getElementById('modal-description').textContent = description;
        pendingAction = onConfirm;

        const modal = document.getElementById('delete-modal');
        const card  = document.getElementById('delete-modal-card');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        requestAnimationFrame(() => {
            card.classList.remove('scale-95', 'opacity-0');
            card.classList.add('scale-100', 'opacity-100');
        });
    }

    function closeDeleteModal() {
        const modal = document.getElementById('delete-modal');
        const card  = document.getElementById('delete-modal-card');
        card.classList.add('scale-95', 'opacity-0');
        card.classList.remove('scale-100', 'opacity-100');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            pendingAction = null;
            document.getElementById('modal-spinner').classList.add('hidden');
            document.getElementById('modal-confirm-text').textContent = 'Hapus';
            document.getElementById('modal-confirm-btn').disabled = false;
        }, 200);
    }

    document.getElementById('modal-confirm-btn').addEventListener('click', async () => {
        if (!pendingAction) return;
        document.getElementById('modal-spinner').classList.remove('hidden');
        document.getElementById('modal-confirm-text').textContent = 'Menghapus...';
        document.getElementById('modal-confirm-btn').disabled = true;
        await pendingAction();
    });

    function confirmDeleteUser(id, name) {
        showModal(
            `Pengguna "${name}" akan dihapus beserta semua riwayat parkir dan transaksinya.`,
            async () => {
                try {
                    const res = await fetch(`/users/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': CSRF_TOKEN,
                            'Accept': 'application/json'
                        }
                    });
                    const data = await res.json();

                    if (res.ok) {
                        const row = document.getElementById(`user-row-${id}`);
                        if (row) {
                            row.style.transition = 'opacity 0.3s, transform 0.3s';
                            row.style.opacity = '0';
                            row.style.transform = 'translateX(20px)';
                            setTimeout(() => row.remove(), 300);
                        }
                        closeDeleteModal();
                        showToast(data.message, 'success');
                    } else {
                        closeDeleteModal();
                        showToast(data.message || 'Gagal menghapus pengguna.', 'error');
                    }
                } catch (e) {
                    closeDeleteModal();
                    showToast('Terjadi kesalahan jaringan.', 'error');
                }
            }
        );
    }

    function showToast(message, type = 'success') {
        const colors = { success: 'bg-emerald-600', error: 'bg-red-600' };
        const icons = {
            success: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>',
            error:   '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>'
        };
        const toast = document.createElement('div');
        toast.className = `fixed bottom-6 right-6 z-[9999] flex items-center gap-3 px-5 py-3.5 rounded-xl text-white text-sm font-semibold shadow-xl ${colors[type]} transform translate-y-4 opacity-0 transition-all duration-300`;
        toast.innerHTML = `
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                ${icons[type]}
            </svg>
            <span>${message}</span>`;
        document.body.appendChild(toast);
        requestAnimationFrame(() => toast.classList.remove('translate-y-4', 'opacity-0'));
        setTimeout(() => {
            toast.classList.add('translate-y-4', 'opacity-0');
            setTimeout(() => toast.remove(), 300);
        }, 3500);
    }
</script>
@endpush
@endsection
