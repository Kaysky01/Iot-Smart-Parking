<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\TopUp;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TopUpController extends Controller
{
    /**
     * Show the top-up management page.
     */
    public function index()
    {
        $topups = TopUp::with(['user', 'admin'])
            ->orderByDesc('id')
            ->paginate(20);

        $totalTopUpsToday = TopUp::whereDate('created_at', today())->sum('amount');
        $totalTopUpsCount = TopUp::whereDate('created_at', today())->count();
        $totalTopUpsAllTime = TopUp::sum('amount');

        return view('topups.index', compact(
            'topups',
            'totalTopUpsToday',
            'totalTopUpsCount',
            'totalTopUpsAllTime',
        ));
    }

    /**
     * Process a top-up for a user.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'rfid_uid' => 'nullable|string|max:255',
            'new_user_name' => 'nullable|string|max:255',
            'amount' => 'required|integer|min:1000|max:10000000',
            'method' => 'required|in:cash,transfer,qris,other',
            'notes' => 'nullable|string|max:500',
        ]);

        if (empty($validated['user_id']) && empty($validated['rfid_uid'])) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Please select a user or enter an RFID card.',
                ], 422);
            }
            return back()->withErrors(['user_id' => 'Please select a user or enter an RFID card.']);
        }

        $result = DB::transaction(function () use ($validated, $request) {
            $user = null;
            if (!empty($validated['user_id'])) {
                $user = User::lockForUpdate()->findOrFail($validated['user_id']);
            } else {
                // Find by RFID
                $user = User::where('rfid_uid', $validated['rfid_uid'])->lockForUpdate()->first();
                
                if (!$user) {
                    // Create new user on the fly
                    $name = !empty($validated['new_user_name']) ? $validated['new_user_name'] : 'User ' . $validated['rfid_uid'];
                    $user = User::create([
                        'name' => $name,
                        'email' => 'rfid_' . strtolower($validated['rfid_uid']) . '@parking.local',
                        'password' => bcrypt(\Illuminate\Support\Str::random(16)),
                        'rfid_uid' => $validated['rfid_uid'],
                        'balance' => 0, // starts with 0, topup will add to this
                        'role' => 'user',
                    ]);

                    // Log activity for auto registration
                    ActivityLog::log(
                        'registration',
                        "New user registered manually via top-up: {$name} (RFID: {$validated['rfid_uid']})",
                        $user->id,
                        ['rfid_uid' => $validated['rfid_uid'], 'initial_balance' => 0]
                    );
                }
            }

            $balanceBefore = $user->balance;
            $balanceAfter = $balanceBefore + $validated['amount'];

            // Update user balance
            $user->update(['balance' => $balanceAfter]);

            // Create top-up record
            $topup = TopUp::create([
                'user_id' => $user->id,
                'admin_id' => auth()->id(),
                'amount' => $validated['amount'],
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'method' => $validated['method'],
                'notes' => $validated['notes'] ?? null,
            ]);

            // Log activity
            ActivityLog::log(
                'topup',
                "Top-up Rp " . number_format($validated['amount'], 0, ',', '.') . " for {$user->name} via {$validated['method']}",
                $user->id,
                [
                    'topup_id' => $topup->id,
                    'amount' => $validated['amount'],
                    'balance_before' => $balanceBefore,
                    'balance_after' => $balanceAfter,
                    'method' => $validated['method'],
                    'admin_id' => auth()->id(),
                ]
            );

            // Broadcast the event
            event(new \App\Events\TopUpCreatedEvent($topup));

            return [
                'topup' => $topup,
                'user' => $user->fresh(),
            ];
        });

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Top-up berhasil!',
                'topup' => $result['topup'],
                'user' => $result['user'],
            ]);
        }

        return redirect()->route('topups.index')
            ->with('success', 'Top-up Rp ' . number_format($validated['amount'], 0, ',', '.') . ' berhasil untuk ' . $result['user']->name);
    }

    /**
     * Search users for top-up (AJAX).
     */
    public function searchUsers(Request $request): JsonResponse
    {
        $query = $request->get('q', '');

        $users = User::whereNotNull('rfid_uid')
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('rfid_uid', 'like', "%{$query}%");
            })
            ->limit(10)
            ->get()
            ->map(fn($u) => [
                'id' => $u->id,
                'name' => $u->name,
                'rfid_uid' => $u->rfid_uid,
                'balance' => $u->balance,
                'formatted_balance' => $u->formatted_balance,
            ]);

        return response()->json($users);
    }

    /**
     * API: list recent top-ups.
     */
    public function apiList(): JsonResponse
    {
        $topups = TopUp::with(['user', 'admin'])
            ->orderByDesc('id')
            ->limit(50)
            ->get()
            ->map(fn($t) => [
                'id' => $t->id,
                'user' => [
                    'name' => $t->user->name,
                    'rfid_uid' => $t->user->rfid_uid,
                ],
                'admin' => $t->admin ? $t->admin->name : '-',
                'amount' => $t->amount,
                'balance_before' => $t->balance_before,
                'balance_after' => $t->balance_after,
                'method' => $t->method,
                'notes' => $t->notes,
                'created_at' => $t->created_at->format('Y-m-d H:i:s'),
            ]);

        return response()->json($topups);
    }
}
