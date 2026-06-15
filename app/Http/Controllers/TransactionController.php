<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Show the transactions page.
     */
    public function index()
    {
        $transactions = Transaction::with(['user', 'parking'])
            ->orderByDesc('id')
            ->paginate(20);

        return view('transactions.index', compact('transactions'));
    }

    /**
     * API endpoint for real-time transaction list refresh.
     */
    public function apiList(): JsonResponse
    {
        $transactions = Transaction::with(['user', 'parking'])
            ->orderByDesc('id')
            ->limit(50)
            ->get()
            ->map(fn($t) => [
                'id' => $t->id,
                'user' => [
                    'name' => $t->user->name,
                    'rfid_uid' => $t->user->rfid_uid,
                ],
                'parking_id' => $t->parking_id,
                'amount' => $t->amount,
                'remaining_balance' => $t->remaining_balance,
                'created_at' => $t->created_at->format('Y-m-d H:i:s'),
            ]);

        return response()->json($transactions);
    }

    /**
     * Delete a single transaction record.
     */
    public function destroy(Transaction $transaction): JsonResponse
    {
        $transaction->delete();

        return response()->json(['message' => 'Transaction record deleted successfully.']);
    }

    /**
     * Delete all transaction records.
     */
    public function destroyAll(): JsonResponse
    {
        $count = Transaction::count();
        Transaction::truncate();

        return response()->json(['message' => "Deleted {$count} transaction record(s) successfully."]);
    }
}
