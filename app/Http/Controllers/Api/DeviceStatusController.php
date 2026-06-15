<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class DeviceStatusController extends Controller
{
    /**
     * Get device status for the dashboard sidebar.
     *
     * GET /api/device-status
     */
    public function status(): JsonResponse
    {
        $printerOnline = $this->checkPrinterStatus();

        return response()->json([
            'printer' => [
                'online' => $printerOnline,
                'name'   => config('parking.printer_name', 'POS-58'),
                'mode'   => config('parking.print_mode', 'windows'),
            ],
        ]);
    }

    /**
     * Check if the thermal printer is reachable.
     */
    private function checkPrinterStatus(): bool
    {
        $mode = config('parking.print_mode', 'windows');

        // If disabled, report as "offline" (intentionally off)
        if ($mode === 'disabled') {
            return false;
        }

        // If ESC/POS library not installed
        if (!class_exists('Mike42\Escpos\Printer')) {
            return false;
        }

        try {
            if ($mode === 'file') {
                // File mode: always "online" if writable
                $path = storage_path('logs/receipt_latest.txt');
                return is_writable(dirname($path));
            }

            // Windows mode: try to open the printer connector
            $connectorClass = 'Mike42\Escpos\PrintConnectors\WindowsPrintConnector';
            $printerName    = config('parking.printer_name', 'POS-58');
            $connector      = new $connectorClass($printerName);
            $connector->finalize(); // close immediately
            return true;
        } catch (\Exception $e) {
            Log::debug('Printer status check failed: ' . $e->getMessage());
            return false;
        }
    }
}
