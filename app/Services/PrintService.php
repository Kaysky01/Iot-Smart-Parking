<?php

namespace App\Services;

use App\Models\Parking;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class PrintService
{
    /**
     * Print parking receipt via ESC/POS thermal printer.
     * Fails gracefully if printer is not connected.
     *
     * Set PARKING_PRINT_MODE in .env:
     *   windows  → WindowsPrintConnector (default, production)
     *   file     → FilePrintConnector (dev/testing, saves to storage/logs/receipt.txt)
     *   disabled → skip printing entirely
     */
    public function printReceipt(Parking $parking, User $user): void
    {
        $mode = config('parking.print_mode', 'windows');

        // Skip printing if explicitly disabled
        if ($mode === 'disabled') {
            Log::info('Printing disabled via config.', ['parking_id' => $parking->id]);
            return;
        }

        // Check if the escpos-php library is available
        if (!class_exists('Mike42\Escpos\Printer')) {
            Log::info('ESC/POS library not installed. Receipt printing skipped.', [
                'parking_id' => $parking->id,
            ]);
            return;
        }

        try {
            $printerClass = 'Mike42\Escpos\Printer';
            $connector    = $this->buildConnector($mode);
            $printer      = new $printerClass($connector);

            // ── Header ────────────────────────────────────────────
            $printer->setJustification($printerClass::JUSTIFY_CENTER);
            $printer->setTextSize(2, 2);
            $printer->text("SMART PARKING\n");
            $printer->setTextSize(1, 1);
            $printer->text("IoT Parking System\n");
            $printer->setJustification($printerClass::JUSTIFY_LEFT);
            $printer->text("\n");
            $printer->text("Name    : " . $user->name . "\n");
            $printer->text("Entry   : " . $parking->entry_time->format('d/m/Y H:i:s') . "\n");
            $printer->text("Exit    : " . $parking->exit_time->format('d/m/Y H:i:s') . "\n");
            $printer->text("Duration: " . $parking->duration . " Hour\n");
            $printer->text("Cost    : Rp " . number_format($parking->cost, 0, ',', '.') . "\n");
            $printer->text("Balance : Rp " . number_format($user->balance, 0, ',', '.') . "\n");
            $printer->setJustification($printerClass::JUSTIFY_CENTER);

            $printer->feed(1);
            $printer->text("Thank you!\n");
            $printer->text("Have a safe drive\n");
            $printer->feed(1);
            $printer->cut();
            $printer->close();

            Log::info('Receipt printed successfully.', [
                'parking_id' => $parking->id,
                'mode'       => $mode,
            ]);

        } catch (\Exception $e) {
            Log::warning('Printer error (non-critical): ' . $e->getMessage(), [
                'parking_id'   => $parking->id,
                'mode'         => $mode,
                'printer_name' => config('parking.printer_name'),
                'hint'         => 'Pastikan nama printer di .env (PARKING_PRINTER_NAME) sama persis dengan nama di Windows Devices & Printers.',
            ]);
        }
    }

    /**
     * Build the appropriate print connector based on mode.
     */
    private function buildConnector(string $mode): object
    {
        return match ($mode) {
            'file' => new ('Mike42\Escpos\PrintConnectors\FilePrintConnector')(
                storage_path('logs/receipt_latest.txt')
            ),
            default => new ('Mike42\Escpos\PrintConnectors\WindowsPrintConnector')(
                config('parking.printer_name', 'POS-58')
            ),
        };
    }
}