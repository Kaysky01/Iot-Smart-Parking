<?php

namespace App\Http\Controllers;

use App\Models\Parking;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ParkingController extends Controller
{
    /**
     * Show the parkings page.
     */
    public function index()
    {
        $parkings = Parking::with('user')
            ->orderByDesc('id')
            ->paginate(20);

        return view('parkings.index', compact('parkings'));
    }

    /**
     * API endpoint for real-time parking list refresh.
     */
    public function apiList(): JsonResponse
    {
        $parkings = Parking::with('user')
            ->orderByDesc('id')
            ->limit(50)
            ->get()
            ->map(fn($p) => [
                'id' => $p->id,
                'user' => [
                    'name' => $p->user->name,
                    'rfid_uid' => $p->user->rfid_uid,
                ],
                'entry_time' => $p->entry_time->format('Y-m-d H:i:s'),
                'exit_time' => $p->exit_time?->format('Y-m-d H:i:s'),
                'duration' => $p->duration,
                'cost' => $p->cost,
                'status' => $p->status,
            ]);

        return response()->json($parkings);
    }

    /**
     * Delete a single parking record.
     */
    public function destroy(Parking $parking): JsonResponse
    {
        if ($parking->status === 'IN') {
            return response()->json(['message' => 'Cannot delete an active parking record.'], 422);
        }

        optional($parking->transaction)->delete();
        $parking->delete();

        return response()->json(['message' => 'Parking record deleted successfully.']);
    }

    /**
     * Delete all completed parking records.
     */
    public function destroyAll(): JsonResponse
    {
        $deleted = Parking::where('status', 'OUT')->count();
        Parking::where('status', 'OUT')->each(function ($parking) {
            optional($parking->transaction)->delete();
            $parking->delete();
        });

        return response()->json(['message' => "Deleted {$deleted} parking record(s) successfully."]);
    }
}
