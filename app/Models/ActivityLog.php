<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'type',
        'description',
        'metadata',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
        ];
    }

    /**
     * Get the user associated with this log.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Create a log entry.
     */
    public static function log(string $type, string $description, ?int $userId = null, ?array $metadata = null): self
    {
        return self::create([
            'type' => $type,
            'description' => $description,
            'user_id' => $userId,
            'metadata' => $metadata,
        ]);
    }
}
