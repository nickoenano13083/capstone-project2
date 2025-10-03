<?php

namespace App\Models;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'birthday',
        'age',
        'gender',
        'preferred_chapter_id',
        'profile_photo_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_seen' => 'datetime',
        ];
    }

    /**
     * Get the member associated with the user.
     */
    public function member(): HasOne
    {
        return $this->hasOne(Member::class);
    }

    /**
     * Get the prayer requests for the user.
     */
    public function prayerRequests(): HasMany
    {
        return $this->hasMany(PrayerRequest::class);
    }

    public function presence(): HasOne
    {
        return $this->hasOne(UserPresence::class);
    }

    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    /**
     * Get unread messages for the user
     */
    public function unreadMessages(): HasMany
    {
        return $this->receivedMessages()->whereNull('read_at');
    }

    /**
     * Get unread message count
     */
    public function getUnreadMessageCountAttribute(): int
    {
        return $this->unreadMessages()->count();
    }

    /**
     * Check if user is online
     */
    public function isOnline(): bool
    {
        return Cache::has('user-online-' . $this->id) && 
               $this->last_seen && 
               $this->last_seen->gt(now()->subMinutes(5));
    }

    /**
     * Get online status
     */
    public function getOnlineStatusAttribute(): string
    {
        return $this->presence?->online_status ?? 'Offline';
    }

    /**
     * Check if user is typing in a specific conversation
     */
    public function isTypingIn(string $conversationId): bool
    {
        return $this->presence?->is_typing && 
               $this->presence?->current_conversation_id === $conversationId;
    }

    /**
     * Scope to include the last message for each user
     */
    public function scopeWithLastMessage(Builder $query)
    {
        return $query->addSelect(['last_message' => Message::select('content')
            ->whereColumn('sender_id', 'users.id')
            ->orWhereColumn('receiver_id', 'users.id')
            ->latest()
            ->limit(1)
        ])->withCount(['unreadMessages' => function ($query) {
            $query->where('receiver_id', auth()->id());
        }]);
    }

    /**
     * Scope to include online status
     */
    public function scopeWithOnlineStatus($query)
    {
        return $query->addSelect([
            'online_status' => UserPresence::select('online_status')
                ->whereColumn('user_id', 'users.id')
                ->latest()
                ->limit(1)
        ])->withCasts(['online_status' => 'string']);
    }

    /**
     * Get all messages between the user and the authenticated user
     */
    public function messages()
    {
        return $this->hasMany(Message::class, 'sender_id')
            ->orWhere('receiver_id', $this->id);
    }

    /**
     * Get the user's messaging channel
     */
    public function getMessagingChannelAttribute(): string
    {
        return "user.{$this->id}.messages";
    }

    /**
     * Get the channels that model events should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [new PrivateChannel($this->messaging_channel)];
    }

    public function preferredChapter()
    {
        return $this->belongsTo(Chapter::class, 'preferred_chapter_id');
    }

    /**
     * Chapters led by this user (when chapter leader is a User)
     */
    public function ledChapters(): HasMany
    {
        return $this->hasMany(Chapter::class, 'leader_id')
            ->where('leader_type', 'App\\Models\\User');
    }

    /**
     * Notifications for this user
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Unread notifications for this user
     */
    public function unreadNotifications(): HasMany
    {
        return $this->hasMany(Notification::class)->where('is_read', false);
    }

    /**
     * Get the URL for the user's dashboard background image.
     *
     * @return string|null
     */
    public function getDashboardBackgroundUrlAttribute()
    {
        return $this->dashboard_background ? Storage::url($this->dashboard_background) : null;
    }

    /**
     * Get the URL to the user's profile photo.
     *
     * @return string
     */
    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo_path) {
            // Return a relative URL so it works across devices (e.g., mobile not resolving APP_URL)
            return '/storage/' . ltrim($this->profile_photo_path, '/');
        }

        // Default to showing the first letter of the name
        $name = trim(collect(explode(' ', $this->name))->map(function ($segment) {
            return mb_substr($segment, 0, 1);
        })->join(' '));

        return 'https://ui-avatars.com/api/?name='.urlencode($name).'&color=7F9CF5&background=EBF4FF';
    }

    /**
     * Check if the user has the specified role.
     *
     * @param string|array $role
     * @return bool
     */
    public function hasRole($role): bool
    {
        if (is_array($role)) {
            return in_array($this->role, $role);
        }
        
        return $this->role === $role;
    }

    /**
     * Check if the user is an admin.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role === 'Admin';
    }
    
    /**
     * Check if the user is a leader.
     *
     * @return bool
     */
    public function isLeader(): bool
    {
        return $this->role === 'Leader';
    }
}
