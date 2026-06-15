@extends('layouts.app')

@section('title', 'Top Up Requests')
@section('page-title', 'Top Up Requests')
@section('page-subtitle', 'Student balance top-up approval workflow')

@section('content')
<div class="space-y-6">

    {{-- ===== STATS CARDS ===== --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        {{-- Pending --}}
        <div class="stat-card relative overflow-hidden bg-slate-800 rounded-2xl shadow-md border border-slate-700 p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider">Pending</p>
                    <p class="text-3xl font-extrabold text-amber-400 mt-2" id="stat-pending">{{ $pendingCount }}</p>
                    <p class="text-xs text-slate-500 mt-1">Awaiting approval</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-500/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="absolute -bottom-4 -right-4 w-24 h-24 rounded-full bg-amber-900/20 opacity-50"></div>
        </div>

        {{-- Approved --}}
        <div class="stat-card relative overflow-hidden bg-slate-800 rounded-2xl shadow-md border border-slate-700 p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider">Approved</p>
                    <p class="text-3xl font-extrabold text-emerald-400 mt-2" id="stat-approved">{{ $approvedCount }}</p>
                    <p class="text-xs text-slate-500 mt-1">Total approved</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-500/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
            <div class="absolute -bottom-4 -right-4 w-24 h-24 rounded-full bg-emerald-900/20 opacity-50"></div>
        </div>

        {{-- Rejected --}}
        <div class="stat-card relative overflow-hidden bg-slate-800 rounded-2xl shadow-md border border-slate-700 p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider">Rejected</p>
                    <p class="text-3xl font-extrabold text-red-400 mt-2" id="stat-rejected">{{ $rejectedCount }}</p>
                    <p class="text-xs text-slate-500 mt-1">Total rejected</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-red-500/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
            </div>
            <div class="absolute -bottom-4 -right-4 w-24 h-24 rounded-full bg-red-900/20 opacity-50"></div>
        </div>

        {{-- Pending Total Amount --}}
        <div class="stat-card relative overflow-hidden bg-slate-800 rounded-2xl shadow-md border border-slate-700 p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-400 uppercase tracking-wider">Pending Amount</p>
                    <p class="text-3xl font-extrabold text-blue-400 mt-2" id="stat-pending-amount">Rp {{ number_format($pendingTotal, 0, ',', '.') }}</p>
                    <p class="text-xs text-slate-500 mt-1">Total value pending</p>
                </div>
                <div class="w-12 h-12 rounded-2xl gradient-primary flex items-center justify-center shadow-lg shadow-blue-500/20">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="absolute -bottom-4 -right-4 w-24 h-24 rounded-full bg-blue-900/20 opacity-50"></div>
        </div>
    </div>

    {{-- ===== REQUESTS TABLE ===== --}}
    <div class="bg-slate-800 rounded-2xl shadow-md border border-slate-700 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-700 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-white">Top Up Requests</h3>
                <p class="text-sm text-slate-400 mt-0.5">Approve or reject student balance requests</p>
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
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Student</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">NPM</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Proof</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-slate-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="requests-table-body" class="divide-y divide-slate-700">
                    @forelse($requests as $req)
                    <tr class="table-row-hover" id="request-row-{{ $req->id }}">
                        <td class="px-6 py-4 text-sm text-slate-400">
                            {{ $req->created_at->format('d M Y, H:i') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full gradient-primary flex items-center justify-center text-white text-sm font-bold">
                                    {{ strtoupper(substr($req->user->name, 0, 1)) }}
                                </div>
                                <span class="font-semibold text-slate-200">{{ $req->user->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <code class="bg-slate-700 px-3 py-1.5 rounded-lg text-sm font-mono text-slate-300">{{ $req->user->npm ?? '-' }}</code>
                        </td>
                        <td class="px-6 py-4 font-bold text-emerald-400">
                            {{ $req->formatted_amount }}
                        </td>
                        <td class="px-6 py-4 text-sm">
                            @if($req->payment_proof_path)
                                <button onclick="viewProof('{{ $req->payment_proof_url }}', '{{ addslashes($req->user->name) }}')"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 hover:text-blue-300 font-semibold text-xs transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    View Proof
                                </button>
                            @else
                                <span class="text-slate-500">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4" id="status-cell-{{ $req->id }}">
                            @if($req->status === 'pending')
                                <span class="px-3 py-1.5 rounded-full text-xs font-semibold bg-amber-900/30 text-amber-400 border border-amber-800/50">⏳ Pending</span>
                            @elseif($req->status === 'approved')
                                <span class="px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-900/30 text-emerald-400 border border-emerald-800/50">✅ Approved</span>
                            @else
                                <span class="px-3 py-1.5 rounded-full text-xs font-semibold bg-red-900/30 text-red-400 border border-red-800/50">❌ Rejected</span>
                                @if($req->rejection_reason)
                                    <p class="text-[11px] text-red-400/80 mt-1 max-w-[150px] truncate" title="{{ $req->rejection_reason }}">Reason: {{ $req->rejection_reason }}</p>
                                @endif
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center" id="actions-cell-{{ $req->id }}">
                            @if($req->status === 'pending')
                                <div class="flex items-center justify-center gap-2">
                                    <button onclick="approveRequest({{ $req->id }})"
                                        class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-bold text-emerald-400 bg-emerald-900/20 hover:bg-emerald-900/40 border border-emerald-800/40 transition-all duration-200">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Approve
                                    </button>
                                    <button onclick="openRejectModal({{ $req->id }})"
                                        class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-bold text-red-400 bg-red-900/20 hover:bg-red-900/40 border border-red-800/40 transition-all duration-200">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        Reject
                                    </button>
                                </div>
                            @else
                                <span class="text-xs text-slate-500">
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
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-slate-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <p class="text-slate-400 font-medium">No top-up requests yet</p>
                                <p class="text-sm text-slate-500 mt-1">Requests from mobile app will appear here</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($requests->hasPages())
        <div class="px-6 py-4 border-t border-slate-700">
            {{ $requests->links() }}
        </div>
        @endif
    </div>
</div>

{{-- ===== VIEW PROOF MODAL ===== --}}
<div id="proof-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closeProofModal()"></div>
    <div class="relative bg-slate-800 rounded-2xl shadow-2xl border border-slate-700 w-full max-w-2xl transform transition-all duration-300 scale-95 opacity-0" id="proof-modal-card">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-white" id="proof-modal-title">Payment Proof</h3>
                <button onclick="closeProofModal()" class="text-slate-400 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="rounded-xl overflow-hidden bg-slate-900 border border-slate-700 flex items-center justify-center max-h-[500px]">
                <img id="proof-modal-img" src="" alt="Payment Proof" class="max-w-full max-h-[500px] object-contain">
            </div>
        </div>
    </div>
</div>

{{-- ===== REJECT REASON MODAL ===== --}}
<div id="reject-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeRejectModal()"></div>
    <div class="relative bg-slate-800 rounded-2xl shadow-2xl border border-slate-700 w-full max-w-md transform transition-all duration-300 scale-95 opacity-0" id="reject-modal-card">
        <div class="p-6">
            <h3 class="text-lg font-bold text-white mb-2">Reject Top-Up Request</h3>
            <p class="text-sm text-slate-400 mb-4">Please provide a reason for rejecting this top-up request (optional).</p>
            <input type="hidden" id="reject-request-id">
            <div class="mb-5">
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Rejection Reason</label>
                <textarea id="reject-reason" rows="3" placeholder="e.g. Bukti transfer tidak jelas / nominal tidak sesuai"
                          class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-700 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all"></textarea>
            </div>
            <div class="flex justify-end gap-3">
                <button onclick="closeRejectModal()" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-300 bg-slate-700 hover:bg-slate-600 transition-all">Cancel</button>
                <button onclick="submitReject()" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-red-600 hover:bg-red-500 transition-all shadow-lg shadow-red-600/20">Reject Request</button>
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
                // Update status badge
                const statusCell = document.getElementById(`status-cell-${id}`);
                if (statusCell) {
                    statusCell.innerHTML = '<span class="px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-900/30 text-emerald-400 border border-emerald-800/50">✅ Approved</span>';
                }
                // Update actions
                const actionsCell = document.getElementById(`actions-cell-${id}`);
                if (actionsCell) {
                    actionsCell.innerHTML = '<span class="text-xs text-slate-500">Just now</span>';
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
                    statusCell.innerHTML = `<span class="px-3 py-1.5 rounded-full text-xs font-semibold bg-red-900/30 text-red-400 border border-red-800/50">❌ Rejected</span>` + 
                        (reason ? `<p class="text-[11px] text-red-400/80 mt-1 max-w-[150px] truncate" title="${reason}">Reason: ${reason}</p>` : '');
                }
                const actionsCell = document.getElementById(`actions-cell-${id}`);
                if (actionsCell) {
                    actionsCell.innerHTML = '<span class="text-xs text-slate-500">Just now</span>';
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
        document.getElementById('proof-modal-title').textContent = `Bukti Transfer - ${studentName}`;
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
