<?php

namespace App\Http\Controllers\Api;

use App\Events\TopUpRequestCreatedEvent;
use App\Http\Controllers\Controller;
use App\Models\TopUpRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    /**
     * GET /api/student/profile
     */
    public function profile(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'npm' => $user->npm,
                'balance' => $user->balance,
                'rfid_uid' => $user->rfid_uid,
                'rfid_status' => $user->rfid_uid ? 'active' : 'inactive',
                'plate_number' => $user->plate_number,
                'vehicle_type' => $user->vehicle_type,
            ],
        ]);
    }

    /**
     * PATCH/PUT /api/student/profile
     */
    public function updateProfile(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:100'],
        ]);

        // Only update name, ignore all other fields as per security rule
        $user->update([
            'name' => $validated['name']
        ]);

        return response()->json([
            'message' => 'Nama berhasil diperbarui',
            'data' => [
                'profile' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'npm' => $user->npm,
                    'rfid_uid' => $user->rfid_uid,
                    'rfid_status' => $user->rfid_uid ? 'active' : 'inactive',
                    'plate_number' => $user->plate_number,
                    'vehicle_type' => $user->vehicle_type,
                ]
            ]
        ]);
    }

    /**
     * POST /api/student/change-password
     */
    public function changePassword(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user->update([
            'password' => \Illuminate\Support\Facades\Hash::make($validated['password'])
        ]);

        return response()->json([
            'message' => 'Password berhasil diperbarui'
        ]);
    }

    /**
     * GET /api/student/balance
     */
    public function balance(Request $request): JsonResponse
    {
        return response()->json([
            'balance' => $request->user()->balance,
        ]);
    }

    /**
     * GET /api/student/parking-history
     */
    public function parkingHistory(Request $request): JsonResponse
    {
        $parkings = $request->user()
            ->parkings()
            ->orderByDesc('id')
            ->paginate(15)
            ->through(fn($p) => [
                'id' => $p->id,
                'entry_time' => $p->entry_time->format('Y-m-d H:i:s'),
                'exit_time' => $p->exit_time?->format('Y-m-d H:i:s'),
                'duration' => $p->duration,
                'cost' => $p->cost,
                'status' => $p->status,
            ]);

        return response()->json($parkings);
    }

    /**
     * GET /api/student/transactions
     */
    public function transactions(Request $request): JsonResponse
    {
        $transactions = $request->user()
            ->transactions()
            ->with('parking')
            ->orderByDesc('id')
            ->paginate(15)
            ->through(fn($t) => [
                'id' => $t->id,
                'parking_id' => $t->parking_id,
                'amount' => $t->amount,
                'remaining_balance' => $t->remaining_balance,
                'created_at' => $t->created_at->format('Y-m-d H:i:s'),
            ]);

        return response()->json($transactions);
    }

    /**
     * GET /api/student/topups
     */
    public function topups(Request $request): JsonResponse
    {
        $topups = $request->user()
            ->topupRequests()
            ->orderByDesc('id')
            ->get()
            ->map(fn($t) => [
                'id' => $t->id,
                'amount' => $t->amount,
                'status' => $t->status,
                'payment_proof_url' => $t->payment_proof_url,
                'rejection_reason' => $t->rejection_reason,
                'created_at' => $t->created_at->toIso8601String(),
            ]);

        return response()->json([
            'data' => [
                'topups' => $topups
            ]
        ]);
    }

    /**
     * POST /api/student/topups
     */
    public function createTopup(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'amount' => ['required', 'integer', 'min:1000'],
            'payment_proof' => [
                'required',
                'file',
                'image',
                'mimes:jpg,jpeg,png',
                'max:5120',
            ],
        ]);

        $user = $request->user();
        $file = $request->file('payment_proof');
        
        // Save file as public with random uuid name
        $extension = $file->getClientOriginalExtension();
        $fileName = Str::uuid() . '.' . $extension;
        $path = $file->storeAs("topup-proofs/{$user->id}", $fileName, 'public');

        $topupRequest = TopUpRequest::create([
            'user_id' => $user->id,
            'amount' => $validated['amount'],
            'payment_proof_path' => $path,
            'status' => 'pending',
        ]);

        // Broadcast event
        try {
            broadcast(new TopUpRequestCreatedEvent($topupRequest));
        } catch (\Throwable $e) {
            Log::warning('Broadcasting TopUpRequestCreated skipped: ' . $e->getMessage());
        }

        return response()->json([
            'message' => 'Permintaan top-up berhasil dikirim',
            'data' => [
                'topup' => [
                    'id' => $topupRequest->id,
                    'amount' => $topupRequest->amount,
                    'status' => $topupRequest->status,
                    'payment_proof_url' => $topupRequest->payment_proof_url,
                    'created_at' => $topupRequest->created_at->toIso8601String(),
                ],
            ],
        ], 201);
    }
}
