<?php

namespace App\Http\Controllers;

use App\Events\TopUpApprovedEvent;
use App\Events\TopUpRejectedEvent;
use App\Models\ActivityLog;
use App\Models\TopUp;
use App\Models\TopUpRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TopUpRequestController extends Controller
{
    /**
     * Show the top-up requests management page.
     */
    public function index()
    {
        $requests = TopUpRequest::with(['user', 'approver'])
            ->orderByDesc('id')
            ->paginate(20);

        $pendingCount = TopUpRequest::pending()->count();
        $approvedCount = TopUpRequest::approved()->count();
        $rejectedCount = TopUpRequest::rejected()->count();
        $pendingTotal = TopUpRequest::pending()->sum('amount');

        return view('topup-requests.index', compact(
            'requests',
            'pendingCount',
            'approvedCount',
            'rejectedCount',
            'pendingTotal',
        ));
    }

    /**
     * Approve a top-up request.
     */
    public function approve(Request $request, TopUpRequest $topupRequest): JsonResponse
    {
        try {
            $result = DB::transaction(function () use ($topupRequest) {
                // Lock the record for update
                $lockedRequest = TopUpRequest::where('id', $topupRequest->id)->lockForUpdate()->first();

                if (!$lockedRequest || !$lockedRequest->isPending()) {
                    throw new \Exception('ALREADY_PROCESSED', 409);
                }

                $user = $lockedRequest->user;
                // Lock the user record for balance update
                $user = \App\Models\User::where('id', $user->id)->lockForUpdate()->first();

                $balanceBefore = $user->balance;
                $balanceAfter = $balanceBefore + $lockedRequest->amount;

                // Update user balance
                $user->update(['balance' => $balanceAfter]);

                // Update request status
                $lockedRequest->update([
                    'status' => 'approved',
                    'approved_by' => auth()->id(),
                    'approved_at' => now(),
                ]);

                // Create a record in topups table (for consistency with existing system)
                $topup = TopUp::create([
                    'user_id' => $user->id,
                    'admin_id' => auth()->id(),
                    'amount' => $lockedRequest->amount,
                    'balance_before' => $balanceBefore,
                    'balance_after' => $balanceAfter,
                    'method' => 'other',
                    'notes' => 'Approved from mobile top-up request #' . $lockedRequest->id,
                ]);

                // Log activity
                ActivityLog::log(
                    'topup',
                    "Top-up request #" . $lockedRequest->id . " approved: Rp " . number_format($lockedRequest->amount, 0, ',', '.') . " for {$user->name}",
                    $user->id,
                    [
                        'topup_request_id' => $lockedRequest->id,
                        'topup_id' => $topup->id,
                        'amount' => $lockedRequest->amount,
                        'balance_before' => $balanceBefore,
                        'balance_after' => $balanceAfter,
                        'admin_id' => auth()->id(),
                    ]
                );

                // Notify User
                if ($user) {
                    $user->notify(new \App\Notifications\TopUpApprovedNotification($lockedRequest));
                }

                return $lockedRequest->fresh(['user', 'approver']);
            });

            if (!$result) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Request ini sudah diproses sebelumnya.',
                ], 409);
            }
        } catch (\Exception $e) {
            if ($e->getMessage() === 'ALREADY_PROCESSED') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Request ini sudah diproses sebelumnya.',
                ], 409);
            }
            throw $e;
        }

        // Broadcast events
        try {
            broadcast(new TopUpApprovedEvent($result));
            broadcast(new \App\Events\TopUpCreatedEvent(
                TopUp::with(['user', 'admin'])->where('notes', 'like', '%request #' . $topupRequest->id)->latest()->first()
            ));
        } catch (\Throwable $e) {
            Log::warning('Broadcasting TopUpApproved skipped: ' . $e->getMessage());
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Top-up request berhasil disetujui.',
            'data' => $result,
        ]);
    }

    /**
     * Reject a top-up request.
     */
    public function reject(Request $request, TopUpRequest $topupRequest): JsonResponse
    {
        $validated = $request->validate([
            'rejection_reason' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            $result = DB::transaction(function () use ($topupRequest, $validated) {
                // Lock the record for update
                $lockedRequest = TopUpRequest::where('id', $topupRequest->id)->lockForUpdate()->first();

                if (!$lockedRequest || !$lockedRequest->isPending()) {
                    throw new \Exception('ALREADY_PROCESSED', 409);
                }

                $lockedRequest->update([
                    'status' => 'rejected',
                    'approved_by' => auth()->id(),
                    'approved_at' => now(),
                    'rejection_reason' => $validated['rejection_reason'] ?? null,
                ]);

                $lockedRequest->load(['user', 'approver']);

                // Log activity
                ActivityLog::log(
                    'topup',
                    "Top-up request #" . $lockedRequest->id . " rejected for {$lockedRequest->user->name}",
                    $lockedRequest->user_id,
                    [
                        'topup_request_id' => $lockedRequest->id,
                        'amount' => $lockedRequest->amount,
                        'admin_id' => auth()->id(),
                        'rejection_reason' => $validated['rejection_reason'] ?? null,
                    ]
                );

                // Notify User
                if ($lockedRequest->user) {
                    $lockedRequest->user->notify(new \App\Notifications\TopUpRejectedNotification($lockedRequest));
                }

                return $lockedRequest;
            });
        } catch (\Exception $e) {
            if ($e->getMessage() === 'ALREADY_PROCESSED') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Request ini sudah diproses sebelumnya.',
                ], 409);
            }
            throw $e;
        }

        // Broadcast event
        try {
            broadcast(new TopUpRejectedEvent($result));
        } catch (\Throwable $e) {
            Log::warning('Broadcasting TopUpRejected skipped: ' . $e->getMessage());
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Top-up request berhasil ditolak.',
            'data' => $result,
        ]);
    }

    /**
     * API: list recent top-up requests.
     */
    public function apiList(): JsonResponse
    {
        $requests = TopUpRequest::with(['user', 'approver'])
            ->orderByDesc('id')
            ->limit(50)
            ->get()
            ->map(fn($r) => [
                'id' => $r->id,
                'user' => [
                    'id' => $r->user->id,
                    'name' => $r->user->name,
                    'npm' => $r->user->npm,
                ],
                'amount' => $r->amount,
                'status' => $r->status,
                'payment_proof_url' => $r->payment_proof_url,
                'rejection_reason' => $r->rejection_reason,
                'approver' => $r->approver?->name,
                'approved_at' => $r->approved_at?->format('Y-m-d H:i:s'),
                'created_at' => $r->created_at->format('Y-m-d H:i:s'),
            ]);

        return response()->json($requests);
    }

    /**
     * Delete a top-up request (Admin).
     */
    public function destroy(TopUpRequest $topupRequest): JsonResponse
    {
        // Delete the proof image from storage if exists
        if ($topupRequest->payment_proof_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($topupRequest->payment_proof_path);
        }

        $topupRequest->delete();

        // Log activity for deletion
        ActivityLog::log(
            'topup',
            "Top-up request #" . $topupRequest->id . " deleted by admin",
            $topupRequest->user_id,
            [
                'topup_request_id' => $topupRequest->id,
                'amount' => $topupRequest->amount,
                'admin_id' => auth()->id(),
            ]
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Top-up request berhasil dihapus.'
        ]);
    }
}
