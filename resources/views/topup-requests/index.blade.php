@extends('layouts.app')

@section('title', 'Top Up Requests')
@section('page-title', 'Top Up Requests')
@section('page-subtitle', 'Student balance top-up approval workflow')

@section('content')
<div class="space-y-6">

    {{-- ===== STATS CARDS ===== --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        {{-- Pending --}}
        <div class="stat-card p-6 border-b-4 border-b-[var(--app-warning)]">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-semibold text-[var(--app-text-secondary)] uppercase tracking-wider">Pending</p>
                    <p class="text-3xl font-extrabold text-[var(--app-text)] mt-2" id="stat-pending">{{ $pendingCount }}</p>
                    <p class="text-[11px] font-medium text-[var(--app-text-muted)] mt-1">Awaiting approval</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-[var(--app-warning-soft)] flex items-center justify-center">
                    <svg class="w-6 h-6 text-[var(--app-warning)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Approved --}}
        <div class="stat-card p-6 border-b-4 border-b-[var(--app-success)]">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-semibold text-[var(--app-text-secondary)] uppercase tracking-wider">Approved</p>
                    <p class="text-3xl font-extrabold text-[var(--app-text)] mt-2" id="stat-approved">{{ $approvedCount }}</p>
                    <p class="text-[11px] font-medium text-[var(--app-text-muted)] mt-1">Total approved</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-[var(--app-success-soft)] flex items-center justify-center">
                    <svg class="w-6 h-6 text-[var(--app-success)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Rejected --}}
        <div class="stat-card p-6 border-b-4 border-b-[var(--app-danger)]">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-semibold text-[var(--app-text-secondary)] uppercase tracking-wider">Rejected</p>
                    <p class="text-3xl font-extrabold text-[var(--app-text)] mt-2" id="stat-rejected">{{ $rejectedCount }}</p>
                    <p class="text-[11px] font-medium text-[var(--app-text-muted)] mt-1">Total rejected</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-[var(--app-danger-soft)] flex items-center justify-center">
                    <svg class="w-6 h-6 text-[var(--app-danger)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Pending Total Amount --}}
        <div class="stat-card p-6 border-b-4 border-b-[var(--app-primary)]">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-semibold text-[var(--app-text-secondary)] uppercase tracking-wider">Pending Value</p>
                    <p class="text-3xl font-extrabold text-[var(--app-text)] mt-2" id="stat-pending-amount">Rp {{ number_format($pendingTotal, 0, ',', '.') }}</p>
                    <p class="text-[11px] font-medium text-[var(--app-text-muted)] mt-1">Total value pending</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-[var(--app-primary-soft)] flex items-center justify-center">
                    <svg class="w-6 h-6 text-[var(--app-primary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== REQUESTS TABLE ===== --}}
    <div class="app-card overflow-hidden">
        <div class="px-6 py-5 border-b border-[var(--app-border)] flex items-center justify-between">
            <div>
                <h3 class="app-section-title text-lg">Top Up Requests</h3>
                <p class="app-subtitle text-sm mt-0.5">Approve or reject student balance requests</p>
            </div>
            <div class="flex items-center gap-2 text-xs font-bold text-[var(--app-success)] bg-[var(--app-success-soft)] px-3 py-1.5 rounded-full border border-[color-mix(in_srgb,var(--app-success)_20%,transparent)]">
                <span class="w-2 h-2 rounded-full bg-[var(--app-success)] pulse-live"></span>
                Auto-updating
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-[var(--app-surface-soft)] border-b border-[var(--app-border)]">
                        <th class="px-6 py-4 text-xs font-bold text-[var(--app-text-secondary)] uppercase tracking-wider">Date</th>
                        <th class="px-6 py-4 text-xs font-bold text-[var(--app-text-secondary)] uppercase tracking-wider">Student</th>
                        <th class="px-6 py-4 text-xs font-bold text-[var(--app-text-secondary)] uppercase tracking-wider">NPM</th>
                        <th class="px-6 py-4 text-xs font-bold text-[var(--app-text-secondary)] uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-4 text-xs font-bold text-[var(--app-text-secondary)] uppercase tracking-wider">Proof</th>
                        <th class="px-6 py-4 text-xs font-bold text-[var(--app-text-secondary)] uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-[var(--app-text-secondary)] uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="requests-table-body" class="divide-y divide-[var(--app-border)]">
                    @forelse($requests as $req)
                    <tr class="table-row-hover bg-[var(--app-surface)]" id="request-row-{{ $req->id }}">
                        <td class="px-6 py-4 text-sm font-medium text-[var(--app-text-secondary)]">
                            {{ $req->created_at->format('d M Y, H:i') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-[var(--app-primary-soft)] text-[var(--app-primary)] flex items-center justify-center text-sm font-bold border border-[color-mix(in_srgb,var(--app-primary)_20%,transparent)]">
                                    {{ strtoupper(substr($req->user->name, 0, 1)) }}
                                </div>
                                <span class="font-bold text-[var(--app-text)]">{{ $req->user->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-[var(--app-text)]">{{ $req->user->npm ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4 font-bold text-[var(--app-success)]">
                            {{ $req->formatted_amount }}
                        </td>
                        <td class="px-6 py-4 text-sm">
                            @if($req->payment_proof_path)
                                <button onclick="viewProof('{{ $req->payment_proof_url }}', '{{ addslashes($req->user->name) }}')"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-[var(--app-primary-softer)] text-[var(--app-primary)] hover:bg-[var(--app-primary-soft)] font-bold text-xs transition-colors border border-[color-mix(in_srgb,var(--app-primary)_20%,transparent)]">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    View Proof
                                </button>
                            @else
                                <span class="text-[var(--app-text-muted)]">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4" id="status-cell-{{ $req->id }}">
                            @if($req->status === 'pending')
                                <span class="app-badge-warning">⏳ Pending</span>
                            @elseif($req->status === 'approved')
                                <span class="app-badge-success">✅ Approved</span>
                            @else
                                <span class="app-badge-danger">❌ Rejected</span>
                                @if($req->rejection_reason)
                                    <p class="text-[10px] text-[var(--app-danger)] font-medium mt-1.5 max-w-[150px] truncate" title="{{ $req->rejection_reason }}">Reason: {{ $req->rejection_reason }}</p>
                                @endif
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center" id="actions-cell-{{ $req->id }}">
                            @if($req->status === 'pending')
                                <div class="flex items-center justify-center gap-2">
                                    <button onclick="approveRequest({{ $req->id }})"
                                        class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-bold text-[var(--app-success)] bg-[var(--app-success-soft)] hover:brightness-95 transition-all border border-[color-mix(in_srgb,var(--app-success)_20%,transparent)]">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Approve
                                    </button>
                                    <button onclick="openRejectModal({{ $req->id }})"
                                        class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-bold text-[var(--app-danger)] bg-[var(--app-danger-soft)] hover:brightness-95 transition-all border border-[color-mix(in_srgb,var(--app-danger)_20%,transparent)]">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        Reject
                                    </button>
                                </div>
                            @else
                                <span class="text-[11px] font-medium text-[var(--app-text-muted)]">
                                    {{ $req->approver?->name ?? '-' }}
                                    @if($req->approved_at)
                                        <br>{{ $req->approved_at->format('d M H:i') }}
                                    @endif
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <div class="app-empty-state max-w-sm mx-auto">
                                <div class="app-empty-icon">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                                <p class="text-base font-bold text-[var(--app-text)] mb-1">No top-up requests yet</p>
                                <p class="text-sm text-[var(--app-text-secondary)]">Requests from mobile app will appear here</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($requests->hasPages())
        <div class="px-6 py-4 border-t border-[var(--app-border)] bg-[var(--app-surface-soft)]">
            {{ $requests->links() }}
        </div>
        @endif
    </div>
</div>

{{-- ===== VIEW PROOF MODAL ===== --}}
<div id="proof-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="absolute inset-0 app-backdrop" onclick="closeProofModal()"></div>
    <div class="app-modal relative w-full max-w-2xl transform transition-all duration-300 scale-95 opacity-0" id="proof-modal-card">
        <div class="p-6 border-b border-[var(--app-border)] flex justify-between items-center bg-[var(--app-surface-soft)] rounded-t-[24px]">
            <h3 class="text-lg font-bold text-[var(--app-text)]" id="proof-modal-title">Payment Proof</h3>
            <button onclick="closeProofModal()" class="text-[var(--app-text-muted)] hover:text-[var(--app-danger)] transition-colors p-1 bg-transparent border-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-6 flex items-center justify-center bg-[var(--app-surface)]">
            <img id="proof-modal-img" src="" alt="Payment Proof" class="max-w-full max-h-[500px] object-contain rounded-xl border border-[var(--app-border)] shadow-sm">
        </div>
    </div>
</div>

{{-- ===== REJECT REASON MODAL ===== --}}
<div id="reject-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="absolute inset-0 app-backdrop" onclick="closeRejectModal()"></div>
    <div class="app-modal relative w-full max-w-md transform transition-all duration-300 scale-95 opacity-0" id="reject-modal-card">
        <div class="p-8">
            <h3 class="text-xl font-bold text-[var(--app-text)] mb-2">Reject Top-Up Request</h3>
            <p class="text-sm text-[var(--app-text-secondary)] mb-6">Please provide a reason for rejecting this top-up request (optional).</p>
            <input type="hidden" id="reject-request-id">
            <div class="mb-8">
                <label class="block text-[11px] font-bold text-[var(--app-text-secondary)] uppercase tracking-wider mb-2">Rejection Reason</label>
                <textarea id="reject-reason" rows="3" placeholder="e.g. Bukti transfer tidak jelas / nominal tidak sesuai"
                          class="app-textarea resize-none"></textarea>
            </div>
            <div class="flex gap-3">
                <button onclick="closeRejectModal()" class="flex-1 px-4 py-3 text-sm font-bold text-[var(--app-text)] app-button-secondary">Cancel</button>
                <button onclick="submitReject()" class="flex-1 px-4 py-3 text-sm font-bold app-button-danger">Reject Request</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const CSRF = '{{ csrf_token() }}';

    async function approveRequest(id) {
        if (!confirm('Approve this top-up request?')) return;

        try {
            const res = await fetch(`/topup-requests/${id}/approve`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                }
            });
            const data = await res.json();
            if (res.ok) {
                const statusCell = document.getElementById(`status-cell-${id}`);
                if (statusCell) {
                    statusCell.innerHTML = '<span class="app-badge-success">✅ Approved</span>';
                }
                const actionsCell = document.getElementById(`actions-cell-${id}`);
                if (actionsCell) {
                    actionsCell.innerHTML = '<span class="text-[11px] font-medium text-[var(--app-text-muted)]">Just now</span>';
                }
                if (window.showToast) window.showToast('✅ Top-up request approved!', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                if (window.showToast) window.showToast(data.message || 'Failed to approve', 'error');
            }
        } catch (e) {
            if (window.showToast) window.showToast('Network error', 'error');
        }
    }

    // Reject Flow
    function openRejectModal(id) {
        document.getElementById('reject-request-id').value = id;
        document.getElementById('reject-reason').value = '';

        const modal = document.getElementById('reject-modal');
        const card = document.getElementById('reject-modal-card');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        requestAnimationFrame(() => {
            card.classList.remove('scale-95', 'opacity-0');
            card.classList.add('scale-100', 'opacity-100');
        });
    }

    function closeRejectModal() {
        const modal = document.getElementById('reject-modal');
        const card = document.getElementById('reject-modal-card');
        card.classList.add('scale-95', 'opacity-0');
        card.classList.remove('scale-100', 'opacity-100');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 200);
    }

    async function submitReject() {
        const id = document.getElementById('reject-request-id').value;
        const reason = document.getElementById('reject-reason').value;

        closeRejectModal();

        try {
            const res = await fetch(`/topup-requests/${id}/reject`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    rejection_reason: reason
                })
            });
            const data = await res.json();
            if (res.ok) {
                const statusCell = document.getElementById(`status-cell-${id}`);
                if (statusCell) {
                    statusCell.innerHTML = `<span class="app-badge-danger">❌ Rejected</span>` +
                        (reason ? `<p class="text-[10px] text-[var(--app-danger)] font-medium mt-1.5 max-w-[150px] truncate" title="${reason}">Reason: ${reason}</p>` : '');
                }
                const actionsCell = document.getElementById(`actions-cell-${id}`);
                if (actionsCell) {
                    actionsCell.innerHTML = '<span class="text-[11px] font-medium text-[var(--app-text-muted)]">Just now</span>';
                }
                if (window.showToast) window.showToast('❌ Top-up request rejected.', 'warning');
                setTimeout(() => location.reload(), 1000);
            } else {
                if (window.showToast) window.showToast(data.message || 'Failed to reject', 'error');
            }
        } catch (e) {
            if (window.showToast) window.showToast('Network error', 'error');
        }
    }

    // View Proof Modal Flow
    function viewProof(url, studentName) {
        document.getElementById('proof-modal-title').textContent = `Transfer Proof - ${studentName}`;
        document.getElementById('proof-modal-img').src = url;

        const modal = document.getElementById('proof-modal');
        const card = document.getElementById('proof-modal-card');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        requestAnimationFrame(() => {
            card.classList.remove('scale-95', 'opacity-0');
            card.classList.add('scale-100', 'opacity-100');
        });
    }

    function closeProofModal() {
        const modal = document.getElementById('proof-modal');
        const card = document.getElementById('proof-modal-card');
        card.classList.add('scale-95', 'opacity-0');
        card.classList.remove('scale-100', 'opacity-100');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 200);
    }

    // Real-time updates
    if (window.Echo) {
        window.Echo.channel('topup-requests-channel')
            .listen('.topup-request.created', (e) => {
                if (window.showToast) {
                    window.showToast(`📥 New top-up request: ${window.formatIDR(e.topup_request.amount)} from ${e.topup_request.user.name}`, 'info');
                }
                // Reload page to show new request
                setTimeout(() => location.reload(), 1500);
            });
    }
</script>
@endpush
