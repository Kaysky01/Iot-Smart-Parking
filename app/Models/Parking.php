<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Parking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'entry_time',
        'exit_time',
        'duration',
        'cost',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'entry_time' => 'datetime',
            'exit_time' => 'datetime',
            'duration' => 'integer',
            'cost' => 'integer',
        ];
    }

    /**
     * Get the user that owns this parking record.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the transaction for this parking.
     */
    public function transaction(): HasOne
    {
        return $this->hasOne(Transaction::class);
    }

    /**
     * Scope: only active parkings (IN).
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'IN');
    }

    /**
     * Scope: only completed parkings (OUT).
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'OUT');
    }

    /**
     * Scope: today's records.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('entry_time', today());
    }

    /**
     * Format cost as IDR currency string.
     */
    public function getFormattedCostAttribute(): string
    {
        if ($this->cost === null) return '-';
        return 'Rp ' . number_format($this->cost, 0, ',', '.');
    }

    /**
     * Format duration for display.
     */
    public function getFormattedDurationAttribute(): string
    {
        if ($this->duration === null) return '-';
        return $this->duration . ' Jam';
    }
}