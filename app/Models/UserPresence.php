<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class UserPresence extends Model
{
    use HasFactory;

    protected $table = 'user_presence';

    protected $fillable = [
        'user_id',
        'status',
        'last_seen_at',
        'last_activity_at',
        'current_conversation_id',
        'is_typing',
        'typing_started_at',
    ];

    protected $casts = [
        'is_typing' => 'boolean',
        'last_seen_at' => 'datetime',
        'last_activity_at' => 'datetime',
        'typing_started_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Update user activity
     */
    public function updateActivity(): void
    {
        $this->update([
            'last_activity_at' => now(),
            'status' => 'online',
        ]);
    }

    /**
     * Mark user as offline
     */
    public function markOffline(): void
    {
        $this->update([
            'status' => 'offline',
            'last_seen_at' => now(),
            'is_typing' => false,
            'typing_started_at' => null,
        ]);
    }

    /**
     * Start typing indicator
     */
    public function startTyping(string $conversationId): void
    {
        $this->update([
            'is_typing' => true,
            'typing_started_at' => now(),
            'current_conversation_id' => $conversationId,
        ]);
    }

    /**
     * Stop typing indicator
     */
    public function stopTyping(): void
    {
        $this->update([
            'is_typing' => false,
            'typing_started_at' => null,
            'current_conversation_id' => null,
        ]);
    }

    /**
     * Check if user is online (active within last 5 minutes)
     */
    public function isOnline(): bool
    {
        if (!$this->last_activity_at) {
            return false;
        }

        return $this->last_activity_at->diffInMinutes(now()) < 5;
    }

    /**
     * Get online status with time
     */
    public function getOnlineStatusAttribute(): string
    {
        if ($this->isOnline()) {
            return 'Online';
        }

        if ($this->last_seen_at) {
            return 'Last seen ' . $this->last_seen_at->diffForHumans();
        }

        return 'Offline';
    }
}
