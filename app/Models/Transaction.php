<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'parking_id',
        'amount',
        'remaining_balance',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'remaining_balance' => 'integer',
        ];
    }

    /**
     * Get the user for this transaction.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parking record for this transaction.
     */
    public function parking(): BelongsTo
    {
        return $this->belongsTo(Parking::class);
    }

    /**
     * Format amount as IDR.
     */
    public function getFormattedAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    /**
     * Format remaining balance as IDR.
     */
    public function getFormattedRemainingBalanceAttribute(): string
    {
        return 'Rp ' . number_format($this->remaining_balance, 0, ',', '.');
    }
}