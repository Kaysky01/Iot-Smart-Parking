<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TopUp extends Model
{
    use HasFactory;

    protected $table = 'topups';

    protected $fillable = [
        'user_id',
        'admin_id',
        'amount',
        'balance_before',
        'balance_after',
        'method',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'balance_before' => 'integer',
            'balance_after' => 'integer',
        ];
    }

    /**
     * Get the user who received the top-up.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin who processed the top-up.
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Format amount as IDR.
     */
    public function getFormattedAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    /**
     * Format balance before as IDR.
     */
    public function getFormattedBalanceBeforeAttribute(): string
    {
        return 'Rp ' . number_format($this->balance_before, 0, ',', '.');
    }

    /**
     * Format balance after as IDR.
     */
    public function getFormattedBalanceAfterAttribute(): string
    {
        return 'Rp ' . number_format($this->balance_after, 0, ',', '.');
    }

    /**
     * Get method label with proper casing.
     */
    public function getMethodLabelAttribute(): string
    {
        return match ($this->method) {
            'cash' => 'Cash',
            'transfer' => 'Transfer',
            'qris' => 'QRIS',
            'other' => 'Other',
            default => ucfirst($this->method),
        };
    }
}
