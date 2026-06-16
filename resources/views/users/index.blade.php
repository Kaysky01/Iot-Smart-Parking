@extends('layouts.app')

@section('title', 'Users')
@section('page-title', 'Users Management')
@section('page-subtitle', 'RFID-registered users & balances')

@section('content')
<div class="space-y-6">

    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="stat-card p-6 border-b-4 border-b-[var(--app-info)]">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-semibold text-[var(--app-text-secondary)] uppercase tracking-wider">Total Users</p>
                    <p class="text-3xl font-extrabold text-[var(--app-text)] mt-2">{{ $users->total() }}</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-[var(--app-info-soft)] flex items-center justify-center">
                    <svg class="w-6 h-6 text-[var(--app-info)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Users Table --}}
    <div class="app-card overflow-hidden">
        <div class="px-6 py-5 border-b border-[var(--app-border)] flex items-center justify-between">
            <div>
                <h3 class="app-section-title text-lg">Registered Users</h3>
                <p class="app-subtitle text-sm mt-0.5">All RFID-linked users</p>
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
                        <th class="px-6 py-4 text-xs font-bold text-[var(--app-text-secondary)] uppercase tracking-wider">User</th>
                        <th class="px-6 py-4 text-xs font-bold text-[var(--app-text-secondary)] uppercase tracking-wider">RFID UID</th>
                        <th class="px-6 py-4 text-xs font-bold text-[var(--app-text-secondary)] uppercase tracking-wider">Balance</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-[var(--app-text-secondary)] uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody id="users-table-body" class="divide-y divide-[var(--app-border)]">
                    @forelse($users as $user)
                    <tr class="table-row-hover bg-[var(--app-surface)]" id="user-row-{{ $user->id }}">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-[var(--app-info-soft)] flex items-center justify-center text-[var(--app-info)] text-sm font-bold shadow-sm border border-[color-mix(in_srgb,var(--app-info)_20%,transparent)]">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <span class="font-bold text-[var(--app-text)]">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <code class="app-code text-xs font-mono">{{ $user->rfid_uid }}</code>
                        </td>
                        <td class="px-6 py-4 font-bold text-[var(--app-text)]">
                            {{ $user->formatted_balance }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button
                                onclick="confirmDeleteUser({{ $user->id }}, '{{ addslashes($user->name) }}')"
                                title="Delete user"
                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-[var(--app-text-muted)] hover:text-[var(--app-danger)] hover:bg-[var(--app-danger-soft)] transition-colors duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-16 text-center">
                            <div class="app-empty-state max-w-sm mx-auto">
                                <div class="app-empty-icon">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                </div>
                                <p class="text-base font-bold text-[var(--app-text)] mb-1">No users registered yet</p>
                                <p class="text-sm text-[var(--app-text-secondary)]">Users are automatically registered when they first scan their RFID card</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-[var(--app-border)] bg-[var(--app-surface-soft)]">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div id="delete-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    {{-- Backdrop --}}
    <div class="absolute inset-0 app-backdrop" onclick="closeDeleteModal()"></div>

    {{-- Modal Card --}}
    <div class="app-modal relative w-full max-w-md transform transition-all duration-300 scale-95 opacity-0" id="delete-modal-card">
        <div class="p-8">
            {{-- Icon --}}
            <div class="flex items-center justify-center w-14 h-14 rounded-full bg-[var(--app-danger-soft)] mx-auto mb-5 text-[var(--app-danger)] border border-[color-mix(in_srgb,var(--app-danger)_20%,transparent)]">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2M3 12a9 9 0 1118 0 9 9 0 01-18 0z"/>
                </svg>
            </div>

            <h3 class="text-xl font-bold text-[var(--app-text)] text-center">Delete User?</h3>
            <p class="text-sm text-[var(--app-text-secondary)] text-center mt-2" id="modal-description">
                This user will be deleted along with all their parking records and transactions.
            </p>

            {{-- Warning --}}
            <div class="mt-5 p-3.5 rounded-xl bg-[var(--app-warning-soft)] border border-[color-mix(in_srgb,var(--app-warning)_30%,transparent)] flex items-start gap-3">
                <svg class="w-5 h-5 text-[var(--app-warning)] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
                <p class="text-xs text-[var(--app-text-secondary)] font-medium leading-relaxed">This action cannot be undone and will permanently remove all related data.</p>
            </div>

            <div class="flex gap-3 mt-8">
                <button onclick="closeDeleteModal()"
                    class="flex-1 px-4 py-3 text-sm font-bold text-[var(--app-text)] app-button-secondary">
                    Cancel
                </button>
                <button id="modal-confirm-btn"
                    class="flex-1 px-4 py-3 text-sm font-bold app-button-danger flex items-center justify-center gap-2">
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

    function confirmDeleteUser(id, name) {
        showModal(
            `User "${name}" will be deleted along with all their parking records and transactions.`,
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
                        if (window.showToast) window.showToast(data.message, 'success');
                    } else {
                        closeDeleteModal();
                        if (window.showToast) window.showToast(data.message || 'Failed to delete user.', 'error');
                    }
                } catch (e) {
                    closeDeleteModal();
                    if (window.showToast) window.showToast('Network error occurred.', 'error');
                }
            }
        );
    }
</script>
@endpush
@endsection
