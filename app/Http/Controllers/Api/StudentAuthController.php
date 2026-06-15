<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentAuthController extends Controller
{
    /**
     * Student login via NPM + Password.
     * POST /api/student/login
     */
    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'npm' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('npm', $validated['npm'])
            ->where('role', 'student')
            ->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'NPM atau password salah.',
            ], 401);
        }

        // Revoke existing tokens
        $user->tokens()->delete();

        // Create new token
        $token = $user->createToken('student-mobile-app')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login berhasil.',
            'token' => $token,
            'student' => [
                'id' => $user->id,
                'name' => $user->name,
                'npm' => $user->npm,
                'balance' => $user->balance,
                'rfid_uid' => $user->rfid_uid,
                'plate_number' => $user->plate_number,
                'vehicle_type' => $user->vehicle_type,
            ],
        ]);
    }

    /**
     * Student logout.
     * POST /api/student/logout
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logout berhasil.',
        ]);
    }
}
