<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class InvitationCode extends Model
{
    protected $fillable = [
        'code',
        'created_by',
        'used_by',
        'email',
        'expires_at',
        'used_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
    ];

    /**
     * Generate a new invitation code
     */
    public static function generate(int $userId, ?string $email = null, ?int $expiresInDays = 30): self
    {
        return static::create([
            'code' => Str::upper(Str::random(8)),
            'created_by' => $userId,
            'email' => $email,
            'expires_at' => now()->addDays($expiresInDays),
        ]);
    }

    /**
     * Check if the code is valid
     */
    public function isValid(): bool
    {
        return !$this->isUsed() && !$this->isExpired();
    }

    /**
     * Check if the invitation code has been used
     */
    public function isUsed(): bool
    {
        return $this->used_at !== null;
    }

    /**
     * Check if the invitation code has expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Mark the code as used
     */
    public function markAsUsed(int $userId): bool
    {
        return $this->update([
            'used_by' => $userId,
            'used_at' => now(),
        ]);
    }

    /**
     * Scope a query to only include valid codes
     */
    public function scopeValid($query)
    {
        return $query->whereNull('used_at')
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }

    /**
     * Get the creator of the invitation code
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who used this code
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'used_by');
    }
}
