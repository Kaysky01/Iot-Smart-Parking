<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'npm',
        'email',
        'password',
        'rfid_uid',
        'plate_number',
        'vehicle_type',
        'balance',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'balance' => 'integer',
        ];
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is student.
     */
    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    /**
     * Check if user is display role.
     */
    public function isDisplay(): bool
    {
        return $this->role === 'display';
    }

    /**
     * Get all parkings for this user.
     */
    public function parkings(): HasMany
    {
        return $this->hasMany(Parking::class);
    }

    /**
     * Get all transactions for this user.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get all top-ups for this user.
     */
    public function topups(): HasMany
    {
        return $this->hasMany(TopUp::class);
    }

    /**
     * Get all top-up requests for this user.
     */
    public function topupRequests(): HasMany
    {
        return $this->hasMany(TopUpRequest::class);
    }

    /**
     * Get the active parking (status = IN) for this user.
     */
    public function activeParking()
    {
        return $this->hasOne(Parking::class)->where('status', 'IN')->latestOfMany();
    }

    /**
     * Format balance as IDR currency string.
     */
    public function getFormattedBalanceAttribute(): string
    {
        return 'Rp ' . number_format($this->balance, 0, ',', '.');
    }
}