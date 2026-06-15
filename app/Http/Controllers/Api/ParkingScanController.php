<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ParkingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ParkingScanController extends Controller
{
    public function __construct(
        private ParkingService $parkingService,
    ) {}

    /**
     * Handle RFID scan from ESP32.
     *
     * POST /api/scan
     * Body: { "uid": "RFID_UID" }
     */
    public function scan(Request $request): JsonResponse
    {
        $request->validate([
            'uid' => 'required|string|max:255',
        ]);

        try {
            $result = $this->parkingService->handleScan($request->uid);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ], 500);
        }
    }
}
