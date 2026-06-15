<?php

namespace App\Http\Controllers;

use App\Events\StudentCreatedEvent;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StudentController extends Controller
{
    /**
     * Show the student management page.
     */
    public function index()
    {
        $students = User::where('role', 'student')
            ->orderByDesc('id')
            ->paginate(20);

        return view('students.index', compact('students'));
    }

    /**
     * Create a new student.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'npm' => 'required|string|max:50|unique:users,npm',
            'password' => 'required|string|min:6',
            'rfid_uid' => 'nullable|string|max:255|unique:users,rfid_uid',
            'plate_number' => 'nullable|string|max:20',
            'vehicle_type' => 'nullable|string|in:motor,mobil',
        ]);

        $student = User::create([
            'name' => $validated['name'],
            'npm' => $validated['npm'],
            'email' => 'student_' . strtolower($validated['npm']) . '@parking.local',
            'password' => bcrypt($validated['password']),
            'rfid_uid' => $validated['rfid_uid'] ?? null,
            'plate_number' => $validated['plate_number'] ?? null,
            'vehicle_type' => $validated['vehicle_type'] ?? null,
            'balance' => config('parking.default_balance', 10000),
            'role' => 'student',
        ]);

        // Log activity
        ActivityLog::log(
            'registration',
            "Student registered: {$student->name} (NPM: {$student->npm})",
            $student->id,
            [
                'npm' => $student->npm,
                'rfid_uid' => $student->rfid_uid,
                'plate_number' => $student->plate_number,
                'vehicle_type' => $student->vehicle_type,
            ]
        );

        // Broadcast event
        try {
            broadcast(new StudentCreatedEvent($student));
        } catch (\Throwable $e) {
            Log::warning('Broadcasting StudentCreated skipped: ' . $e->getMessage());
        }

        return response()->json([
            'status' => 'success',
            'message' => "Mahasiswa \"{$student->name}\" berhasil didaftarkan.",
            'student' => $student,
        ]);
    }

    /**
     * Delete a student.
     */
    public function destroy(User $student): JsonResponse
    {
        if ($student->role !== 'student') {
            return response()->json(['message' => 'Hanya akun student yang dapat dihapus dari sini.'], 422);
        }

        if ($student->parkings()->where('status', 'IN')->exists()) {
            return response()->json(['message' => 'Mahasiswa sedang dalam sesi parkir aktif, tidak dapat dihapus.'], 422);
        }

        $name = $student->name;
        $student->transactions()->delete();
        $student->topups()->delete();
        $student->topupRequests()->delete();
        $student->parkings()->delete();
        $student->delete();

        return response()->json(['message' => "Mahasiswa \"{$name}\" berhasil dihapus."]);
    }

    /**
     * API: list students for real-time refresh.
     */
    public function apiList(): JsonResponse
    {
        $students = User::where('role', 'student')
            ->orderByDesc('id')
            ->get()
            ->map(fn($s) => [
                'id' => $s->id,
                'name' => $s->name,
                'npm' => $s->npm,
                'rfid_uid' => $s->rfid_uid,
                'plate_number' => $s->plate_number,
                'vehicle_type' => $s->vehicle_type,
                'balance' => $s->balance,
            ]);

        return response()->json($students);
    }
}
