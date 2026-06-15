<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Show the users page.
     */
    public function index()
    {
        $users = User::whereNotNull('rfid_uid')
            ->orderByDesc('id')
            ->paginate(20);

        return view('users.index', compact('users'));
    }

    /**
     * API endpoint for real-time user list refresh.
     */
    public function apiList(): JsonResponse
    {
        $users = User::whereNotNull('rfid_uid')
            ->orderByDesc('id')
            ->get()
            ->map(fn($u) => [
                'id' => $u->id,
                'name' => $u->name,
                'rfid_uid' => $u->rfid_uid,
                'balance' => $u->balance,
            ]);

        return response()->json($users);
    }

    /**
     * Delete a single user along with their related data.
     */
    public function destroy(User $user): JsonResponse
    {
        // Prevent deleting admin or display accounts
        if (in_array($user->role, ['admin', 'display'])) {
            return response()->json(['message' => 'Akun admin/display tidak dapat dihapus.'], 422);
        }

        // Prevent deleting user with an active parking session
        if ($user->parkings()->where('status', 'IN')->exists()) {
            return response()->json(['message' => 'Pengguna sedang dalam sesi parkir aktif, tidak dapat dihapus.'], 422);
        }

        // Cascade delete related data
        $user->transactions()->delete();
        $user->topups()->delete();
        $user->parkings()->delete();
        $user->delete();

        return response()->json(['message' => "Pengguna \"{$user->name}\" berhasil dihapus."]);
    }
}
