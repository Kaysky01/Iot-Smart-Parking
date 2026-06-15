<?php

namespace App\Services;

use App\Events\ParkingEntryEvent;
use App\Events\ParkingExitEvent;
use App\Events\ScanResultEvent;
use App\Events\TransactionCreatedEvent;
use App\Models\ActivityLog;
use App\Models\Parking;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ParkingService
{
    public function __construct(
        private PaymentService $paymentService,
        private PrintService $printService,
    ) {}

    /**
     * Handle an RFID scan from ESP32.
     * Auto-registers unknown cards, then processes entry or exit.
     */
    public function handleScan(string $uid): array
    {
        return DB::transaction(function () use ($uid) {
            // 1. Find or auto-register user
            $user = $this->findOrCreateUser($uid);

            // 2. Check for active parking
            $activeParking = Parking::where('user_id', $user->id)
                ->where('status', 'IN')
                ->lockForUpdate()
                ->first();

            // 3. Route to entry or exit
            if (!$activeParking) {
                return $this->processEntry($user);
            }

            return $this->processExit($user, $activeParking);
        });
    }

    /**
     * Find user by RFID UID or create a new one.
     */
    private function findOrCreateUser(string $uid): User
    {
        $user = User::where('rfid_uid', $uid)->first();

        if (!$user) {
            $baseName = 'New User';
            $existingNames = User::where('name', 'like', $baseName . '%')->pluck('name');
            
            $maxNumber = 0;
            foreach ($existingNames as $name) {
                if (preg_match('/^New User\s+(\d+)$/i', $name, $matches)) {
                    $num = (int)$matches[1];
                    if ($num > $maxNumber) {
                        $maxNumber = $num;
                    }
                }
            }
            $nextNumber = $maxNumber + 1;
            $newUserName = $baseName . ' ' . $nextNumber;

            $user = User::create([
                'name' => $newUserName,
                'email' => 'rfid_' . Str::lower($uid) . '@parking.local',
                'password' => bcrypt(Str::random(16)),
                'rfid_uid' => $uid,
                'balance' => config('parking.default_balance', 10000),
                'role' => 'user',
            ]);

            ActivityLog::log(
                'registration',
                "New user auto-registered with RFID: {$uid} as {$newUserName}",
                $user->id,
                ['rfid_uid' => $uid, 'initial_balance' => $user->balance]
            );
        }

        return $user;
    }

    /**
     * Process vehicle entry.
     */
    private function processEntry(User $user): array
    {
        $parking = Parking::create([
            'user_id' => $user->id,
            'entry_time' => now(),
            'status' => 'IN',
        ]);

        $parking->load('user');

        // Broadcast entry event (for admin dashboard)
        $this->safeBroadcast(new ParkingEntryEvent($parking));

        // Broadcast gate screen event
        $this->safeBroadcast(new ScanResultEvent([
            'type' => 'entry',
            'user_name' => $user->name,
            'rfid_uid' => $user->rfid_uid,
            'balance' => $user->balance,
            'entry_time' => $parking->entry_time->format('d/m/Y H:i:s'),
            'message' => 'Entry Successful',
        ]));

        // Log activity
        ActivityLog::log(
            'entry',
            "Vehicle entered - {$user->name} (RFID: {$user->rfid_uid})",
            $user->id,
            ['parking_id' => $parking->id]
        );

        return [
            'status' => 'entry',
            'balance' => $user->balance,
        ];
    }

    /**
     * Process vehicle exit with payment.
     */
    private function processExit(User $user, Parking $activeParking): array
    {
        $exitTime = now();

        // Calculate cost
        $pricing = $this->paymentService->calculateCost(
            $activeParking->entry_time,
            $exitTime
        );

        $cost = $pricing['cost'];
        $duration = $pricing['duration'];

        // Validate balance
        if (!$this->paymentService->hasSufficientBalance($user->balance, $cost)) {
            // Broadcast insufficient balance to gate screen
            $this->safeBroadcast(new ScanResultEvent([
                'type' => 'insufficient_balance',
                'user_name' => $user->name,
                'rfid_uid' => $user->rfid_uid,
                'balance' => $user->balance,
                'cost' => $cost,
                'message' => 'Insufficient Balance',
            ]));

            return [
                'status' => 'insufficient_balance',
                'cost' => $cost,
                'balance' => $user->balance,
            ];
        }

        // Deduct balance
        $user->decrement('balance', $cost);
        $user->refresh();

        // Update parking record
        $activeParking->update([
            'exit_time' => $exitTime,
            'duration' => $duration,
            'cost' => $cost,
            'status' => 'OUT',
        ]);

        // Create transaction record
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'parking_id' => $activeParking->id,
            'amount' => $cost,
            'remaining_balance' => $user->balance,
        ]);

        // Print receipt (graceful failure)
        $this->printService->printReceipt($activeParking, $user);

        // Broadcast events (for admin dashboard)
        $this->safeBroadcast(new ParkingExitEvent($activeParking));
        $this->safeBroadcast(new TransactionCreatedEvent($transaction));

        // Broadcast gate screen event
        $this->safeBroadcast(new ScanResultEvent([
            'type' => 'exit',
            'user_name' => $user->name,
            'rfid_uid' => $user->rfid_uid,
            'balance' => $user->balance,
            'cost' => $cost,
            'duration' => $duration,
            'entry_time' => $activeParking->entry_time->format('d/m/Y H:i:s'),
            'exit_time' => $activeParking->exit_time->format('d/m/Y H:i:s'),
            'message' => 'Access Granted',
        ]));

        // Log activity
        ActivityLog::log(
            'exit',
            "Vehicle exited - {$user->name}, Cost: Rp " . number_format($cost, 0, ',', '.'),
            $user->id,
            [
                'parking_id' => $activeParking->id,
                'cost' => $cost,
                'duration' => $duration,
                'remaining_balance' => $user->balance,
            ]
        );

        return [
            'status' => 'success',
            'cost' => $cost,
            'balance' => $user->balance,
        ];
    }

    /**
     * Broadcast an event gracefully — skip if driver not configured.
     */
    private function safeBroadcast(object $event): void
    {
        try {
            broadcast($event);
        } catch (\Throwable $e) {
            Log::warning('Broadcasting skipped: ' . $e->getMessage());
        }
    }
}