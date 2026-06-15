<?php

namespace App\Services;

use Carbon\Carbon;

class PaymentService
{
    /**
     * Calculate parking cost based on duration.
     * First hour = Rp 2.000
     * Each subsequent hour = Rp 1.000/hour
     * Duration is always rounded up.
     */
    public function calculateCost(Carbon $entryTime, Carbon $exitTime): array
    {
        $totalMinutes = $entryTime->diffInMinutes($exitTime);
        $durationHours = (int) ceil($totalMinutes / 60);

        // Minimum 1 hour
        if ($durationHours < 1) {
            $durationHours = 1;
        }

        // First hour = 2000, additional hours = 1000 each
        $cost = 2000 + (($durationHours - 1) * 1000);

        return [
            'duration' => $durationHours,
            'cost' => $cost,
            'minutes' => $totalMinutes,
        ];
    }

    /**
     * Check if user has sufficient balance.
     */
    public function hasSufficientBalance(int $balance, int $cost): bool
    {
        return $balance >= $cost;
    }
}