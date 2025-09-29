<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class UserStatusChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $status;
    public $lastSeen;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, string $status, $lastSeen = null)
    {
        $this->user = $user;
        $this->status = $status;
        $this->lastSeen = $lastSeen;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel
     */
    public function broadcastOn()
    {
        return new Channel('presence');
    }

    public function broadcastWith()
    {
        return [
            'user_id' => $this->user->id,
            'status' => $this->status,
            'last_seen' => $this->lastSeen,
            'online_status' => $this->user->online_status,
        ];
    }
}
