@extends('layouts.app')

@section('title', 'Transactions')
@section('page-title', 'Transaction History')
@section('page-subtitle', 'Payment records & balance changes')

@section('content')
<div class="space-y-6">

    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="stat-card bg-surface dark:bg-slate-800 rounded-2xl shadow-md border border-slate-100 dark:border-slate-700 p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Transactions</p>
                    <p class="text-3xl font-extrabold text-slate-800 dark:text-white mt-2">{{ $transactions->total() }}</p>
                </div>
                <div class="w-12 h-12 rounded-2xl gradient-success flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Transactions Table --}}
    <div class="bg-surface dark:bg-slate-800 rounded-2xl shadow-md border border-slate-100 dark:border-slate-700 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between flex-wrap gap-3">
            <div>
                <h3 class="text-lg font-bold text-slate-800 dark:text-white">All Transactions</h3>
                <p class="text-sm text-slate-400 mt-0.5">Complete payment history</p>
            </div>
            <div class="flex items-center gap-3 flex-wrap">
                <div class="flex items-center gap-2 text-xs font-semibold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/30 px-3 py-1.5 rounded-full">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 pulse-live"></span>
                    Auto-updating
                </div>
                <button
                    onclick="confirmDeleteAllTransactions()"
                    class="flex items-center gap-2 px-4 py-2 text-sm font-semibold text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/40 border border-red-200 dark:border-red-800 rounded-xl transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Hapus Semua
                </button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-700/50">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">User</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Parking ID</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Remaining Balance</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody id="transactions-table-body" class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($transactions as $transaction)
                    <tr class="table-row-hover" id="transaction-row-{{ $transaction->id }}">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full gradient-success flex items-center justify-center text-white text-sm font-bold">
                                    {{ strtoupper(substr($transaction->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-semibold text-slate-800 dark:text-slate-200">{{ $transaction->user->name }}</div>
                                    <div class="text-xs text-slate-400">{{ $transaction->user->rfid_uid }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="bg-slate-100 dark:bg-slate-700 px-2.5 py-1 rounded-lg text-sm font-mono text-slate-600 dark:text-slate-300">#{{ $transaction->parking_id }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-semibold text-emerald-600">{{ $transaction->formatted_amount }}</span>
                        </td>
                        <td class="px-6 py-4 text-slate-600 dark:text-slate-300">
                            {{ $transaction->formatted_remaining_balance }}
                        </td>
                        <td class="px-6 py-4 text-slate-500 dark:text-slate-400">
                            {{ $transaction->created_at->format('d M Y, H:i') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button
                                onclick="confirmDeleteTransaction({{ $transaction->id }})"
                                title="Hapus transaksi ini"
                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-all duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-slate-300 dark:text-slate-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <p class="text-slate-400 font-medium">No transactions yet</p>
                                <p class="text-sm text-slate-400 mt-1">Transactions are created when vehicles exit the parking</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($transactions->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700">
            {{ $transactions->links() }}
        </div>
        @endif
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div id="delete-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeDeleteModal()"></div>
    <div class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700 w-full max-w-md transform transition-all duration-300 scale-95 opacity-0" id="delete-modal-card">
        <div class="p-6">
            <div class="flex items-center justify-center w-14 h-14 rounded-2xl bg-red-100 dark:bg-red-900/30 mx-auto mb-5">
                <svg class="w-7 h-7 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-slate-800 dark:text-white text-center" id="modal-title">Hapus Transaksi?</h3>
            <p class="text-sm text-slate-500 dark:text-slate-400 text-center mt-2" id="modal-description">Tindakan ini tidak dapat dibatalkan.</p>
            <div class="flex gap-3 mt-6">
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

    function showModal(title, description, onConfirm) {
        document.getElementById('modal-title').textContent = title;
        document.getElementById('modal-description').textContent = description;
        pendingAction = onConfirm;
        const modal = document.getElementById('delete-modal');
        const card = document.getElementById('delete-modal-card');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        requestAnimationFrame(() => {
            card.classList.remove('scale-95', 'opacity-0');
            card.classList.add('scale-100', 'opacity-100');
        });
    }

    function closeDeleteModal() {
        const modal = document.getElementById('delete-modal');
        const card = document.getElementById('delete-modal-card');
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

    function confirmDeleteTransaction(id) {
        showModal(
            'Hapus Transaksi?',
            'Data transaksi ini akan dihapus secara permanen.',
            async () => {
                try {
                    const res = await fetch(`/transactions/${id}`, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' }
                    });
                    const data = await res.json();
                    if (res.ok) {
                        const row = document.getElementById(`transaction-row-${id}`);
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
                        showToast(data.message || 'Gagal menghapus.', 'error');
                    }
                } catch (e) {
                    closeDeleteModal();
                    showToast('Terjadi kesalahan.', 'error');
                }
            }
        );
    }

    function confirmDeleteAllTransactions() {
        showModal(
            'Hapus Semua Transaksi?',
            'Semua riwayat transaksi akan dihapus secara permanen. Tindakan ini tidak dapat dibatalkan.',
            async () => {
                try {
                    const res = await fetch(`/transactions/all`, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' }
                    });
                    const data = await res.json();
                    closeDeleteModal();
                    if (res.ok) {
                        showToast(data.message, 'success');
                        setTimeout(() => window.location.reload(), 800);
                    } else {
                        showToast(data.message || 'Gagal menghapus.', 'error');
                    }
                } catch (e) {
                    closeDeleteModal();
                    showToast('Terjadi kesalahan.', 'error');
                }
            }
        );
    }

    function showToast(message, type = 'success') {
        const colors = { success: 'bg-emerald-600', error: 'bg-red-600' };
        const toast = document.createElement('div');
        toast.className = `fixed bottom-6 right-6 z-[9999] flex items-center gap-3 px-5 py-3.5 rounded-xl text-white text-sm font-semibold shadow-xl ${colors[type]} transform translate-y-4 opacity-0 transition-all duration-300`;
        toast.innerHTML = `<svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            ${type === 'success'
                ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>'
                : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>'}
        </svg><span>${message}</span>`;
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
