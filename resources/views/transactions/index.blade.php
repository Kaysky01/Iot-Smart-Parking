@extends('layouts.app')

@section('title', 'Transactions')
@section('page-title', 'Transaction History')
@section('page-subtitle', 'Payment records & balance changes')

@section('content')
<div class="space-y-6">

    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="stat-card p-6 border-b-4 border-b-[var(--app-success)]">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-semibold text-[var(--app-text-secondary)] uppercase tracking-wider">Total Transactions</p>
                    <p class="text-3xl font-extrabold text-[var(--app-text)] mt-2">{{ $transactions->total() }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-[var(--app-success-soft)] flex items-center justify-center">
                    <svg class="w-6 h-6 text-[var(--app-success)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Transactions Table --}}
    <div class="app-card overflow-hidden">
        <div class="px-6 py-5 border-b border-[var(--app-border)] flex items-center justify-between flex-wrap gap-3">
            <div>
                <h3 class="app-section-title text-lg">All Transactions</h3>
                <p class="app-subtitle text-sm mt-0.5">Complete payment history</p>
            </div>
            <div class="flex items-center gap-3 flex-wrap">
                <div class="flex items-center gap-2 text-xs font-bold text-[var(--app-success)] bg-[var(--app-success-soft)] px-3 py-1.5 rounded-full border border-[color-mix(in_srgb,var(--app-success)_20%,transparent)]">
                    <span class="w-2 h-2 rounded-full bg-[var(--app-success)] pulse-live"></span>
                    Auto-updating
                </div>
                <button
                    onclick="confirmDeleteAllTransactions()"
                    class="app-button-danger px-4 py-2 text-sm font-bold flex items-center gap-2 shadow-none border border-[color-mix(in_srgb,var(--app-danger)_50%,transparent)] hover:opacity-90">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Delete All
                </button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-[var(--app-surface-soft)] border-b border-[var(--app-border)]">
                        <th class="px-6 py-4 text-xs font-bold text-[var(--app-text-secondary)] uppercase tracking-wider">User</th>
                        <th class="px-6 py-4 text-xs font-bold text-[var(--app-text-secondary)] uppercase tracking-wider">Parking ID</th>
                        <th class="px-6 py-4 text-xs font-bold text-[var(--app-text-secondary)] uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-4 text-xs font-bold text-[var(--app-text-secondary)] uppercase tracking-wider">Remaining Balance</th>
                        <th class="px-6 py-4 text-xs font-bold text-[var(--app-text-secondary)] uppercase tracking-wider">Date</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-[var(--app-text-secondary)] uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody id="transactions-table-body" class="divide-y divide-[var(--app-border)]">
                    @forelse($transactions as $transaction)
                    <tr class="table-row-hover bg-[var(--app-surface)]" id="transaction-row-{{ $transaction->id }}">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-[var(--app-success-soft)] text-[var(--app-success)] flex items-center justify-center text-sm font-bold border border-[color-mix(in_srgb,var(--app-success)_20%,transparent)]">
                                    {{ strtoupper(substr($transaction->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-bold text-[var(--app-text)]">{{ $transaction->user->name }}</div>
                                    <div class="text-xs text-[var(--app-text-muted)] mt-0.5">{{ $transaction->user->rfid_uid }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="app-code text-xs font-mono">#{{ $transaction->parking_id }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-bold text-[var(--app-success)]">{{ $transaction->formatted_amount }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-[var(--app-text-secondary)]">
                            {{ $transaction->formatted_remaining_balance }}
                        </td>
                        <td class="px-6 py-4 text-sm text-[var(--app-text-secondary)]">
                            {{ $transaction->created_at->format('d M Y, H:i') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button
                                onclick="confirmDeleteTransaction({{ $transaction->id }})"
                                title="Delete transaction"
                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-[var(--app-text-muted)] hover:text-[var(--app-danger)] hover:bg-[var(--app-danger-soft)] transition-colors duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="app-empty-state max-w-sm mx-auto">
                                <div class="app-empty-icon">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <p class="text-base font-bold text-[var(--app-text)] mb-1">No transactions yet</p>
                                <p class="text-sm text-[var(--app-text-secondary)]">Transactions are created when vehicles exit the parking</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($transactions->hasPages())
        <div class="px-6 py-4 border-t border-[var(--app-border)] bg-[var(--app-surface-soft)]">
            {{ $transactions->links() }}
        </div>
        @endif
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div id="delete-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="absolute inset-0 app-backdrop" onclick="closeDeleteModal()"></div>
    <div class="app-modal relative w-full max-w-md transform transition-all duration-300 scale-95 opacity-0" id="delete-modal-card">
        <div class="p-8">
            <div class="flex items-center justify-center w-14 h-14 rounded-full bg-[var(--app-danger-soft)] text-[var(--app-danger)] border border-[color-mix(in_srgb,var(--app-danger)_20%,transparent)] mx-auto mb-5">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-[var(--app-text)] text-center" id="modal-title">Delete Transaction?</h3>
            <p class="text-sm text-[var(--app-text-secondary)] text-center mt-2" id="modal-description">This action cannot be undone.</p>
            <div class="flex gap-3 mt-8">
                <button onclick="closeDeleteModal()" class="flex-1 px-4 py-3 text-sm font-bold text-[var(--app-text)] app-button-secondary">Cancel</button>
                <button id="modal-confirm-btn" class="flex-1 px-4 py-3 text-sm font-bold app-button-danger flex items-center justify-center gap-2">
                    <span id="modal-confirm-text">Delete</span>
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
            document.getElementById('modal-confirm-text').textContent = 'Delete';
            document.getElementById('modal-confirm-btn').disabled = false;
        }, 200);
    }

    document.getElementById('modal-confirm-btn').addEventListener('click', async () => {
        if (!pendingAction) return;
        document.getElementById('modal-spinner').classList.remove('hidden');
        document.getElementById('modal-confirm-text').textContent = 'Deleting...';
        document.getElementById('modal-confirm-btn').disabled = true;
        await pendingAction();
    });

    function confirmDeleteTransaction(id) {
        showModal(
            'Delete Transaction?',
            'This transaction data will be deleted permanently.',
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
                        if(window.showToast) window.showToast(data.message, 'success');
                    } else {
                        closeDeleteModal();
                        if(window.showToast) window.showToast(data.message || 'Failed to delete.', 'error');
                    }
                } catch (e) {
                    closeDeleteModal();
                    if(window.showToast) window.showToast('Network error.', 'error');
                }
            }
        );
    }

    function confirmDeleteAllTransactions() {
        showModal(
            'Delete All Transactions?',
            'All transaction history will be permanently deleted. This action cannot be undone.',
            async () => {
                try {
                    const res = await fetch(`/transactions/all`, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' }
                    });
                    const data = await res.json();
                    closeDeleteModal();
                    if (res.ok) {
                        if(window.showToast) window.showToast(data.message, 'success');
                        setTimeout(() => window.location.reload(), 800);
                    } else {
                        if(window.showToast) window.showToast(data.message || 'Failed to delete.', 'error');
                    }
                } catch (e) {
                    closeDeleteModal();
                    if(window.showToast) window.showToast('Network error.', 'error');
                }
            }
        );
    }
</script>
@endpush
@endsection
