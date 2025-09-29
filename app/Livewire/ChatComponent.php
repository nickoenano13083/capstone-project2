<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\On;
use App\Models\Message;
use App\Models\User;
use App\Models\UserPresence;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ChatComponent extends Component
{
    use WithFileUploads;

    public $selectedUserId = null;
    public $newMessage = '';
    public $isTyping = false;
    public $attachment = null;
    public $searchQuery = '';
    
    public $users = [];
    public $messages = [];
    public $selectedUser = null;
    
    public $showFileUpload = false;
    public $uploadProgress = 0;

    protected $listeners = [
        'echo:chat,MessageSent' => 'handleNewMessage',
        'echo:presence,UserStatusChanged' => 'handleUserStatusChange',
        'echo:typing,UserTyping' => 'handleUserTyping',
        'message-sent' => 'handleNewMessage',
        'user-status-change' => 'handleUserStatusChange',
        'user-typing' => 'handleUserTyping',
    ];

    public function mount()
    {
        $this->loadUsers();
        $this->initializePresence();
    }

    public function loadUsers()
    {
        $this->users = User::with(['member', 'presence'])
            ->where('id', '!=', Auth::id())
            ->orderBy('name')
            ->get()
            ->map(function ($user) {
                $user->online = $user->isOnline();
                $user->unread_count = $user->unread_message_count;
                return $user;
            });
    }

    public function initializePresence()
    {
        $presence = UserPresence::firstOrCreate(
            ['user_id' => Auth::id()],
            [
                'status' => 'online',
                'last_activity_at' => now(),
            ]
        );
        
        $presence->updateActivity();
    }

    public function selectUser($userId)
    {
        $this->selectedUserId = $userId;
        $this->selectedUser = User::with('member')->find($userId);
        $this->loadMessages();
        $this->markMessagesAsRead();
        
        // Update presence
        $this->updatePresence();
    }

    public function loadMessages()
    {
        if (!$this->selectedUserId) return;

        $this->messages = Message::with(['sender.member', 'receiver.member'])
            ->where(function ($query) {
                $query->where('sender_id', Auth::id())
                      ->where('receiver_id', $this->selectedUserId);
            })
            ->orWhere(function ($query) {
                $query->where('sender_id', $this->selectedUserId)
                      ->where('receiver_id', Auth::id());
            })
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function markMessagesAsRead()
    {
        Message::where('sender_id', $this->selectedUserId)
               ->where('receiver_id', Auth::id())
               ->where('is_read', false)
               ->update([
                   'is_read' => true,
                   'read_at' => now(),
                   'read_by' => Auth::id(),
               ]);
    }

    public function sendMessage()
    {
        if (empty($this->newMessage) && !$this->attachment) return;

        $messageData = [
            'sender_id' => Auth::id(),
            'receiver_id' => $this->selectedUserId,
            'content' => $this->newMessage,
            'message_type' => 'text',
        ];

        // Handle file attachment
        if ($this->attachment) {
            $messageData = $this->handleFileUpload($messageData);
        }

        $message = Message::create($messageData);

        // Load relationships for the response
        $message->load(['sender.member', 'receiver.member']);

        // Reset form immediately for better UX
        $this->newMessage = '';
        $this->attachment = null;
        $this->showFileUpload = false;
        $this->uploadProgress = 0;

        // Reload messages to show the new message
        $this->loadMessages();

        // Stop typing indicator
        $this->stopTyping();

        // Dispatch event for real-time updates
        $this->dispatch('message-sent', $message->id);

        // Broadcast to other users via Pusher (if configured)
        if (config('broadcasting.default') === 'pusher') {
            broadcast(new \App\Events\MessageSent($message));
        }
    }

    protected function handleFileUpload($messageData)
    {
        $fileName = $this->attachment->getClientOriginalName();
        $fileSize = $this->attachment->getSize();
        $fileType = $this->attachment->getMimeType();
        
        // Determine message type
        $messageType = 'file';
        if (Str::startsWith($fileType, 'image/')) {
            $messageType = 'image';
        }

        // Store file
        $path = $this->attachment->store('chat-attachments', 'public');

        $messageData['attachment_path'] = $path;
        $messageData['attachment_name'] = $fileName;
        $messageData['attachment_type'] = $fileType;
        $messageData['attachment_size'] = $fileSize;
        $messageData['message_type'] = $messageType;
        $messageData['content'] = $fileName; // Use filename as content for file messages

        return $messageData;
    }

    public function startTyping()
    {
        if (!$this->selectedUserId) return;

        $this->isTyping = true;
        
        $presence = UserPresence::firstOrCreate(
            ['user_id' => Auth::id()],
            ['status' => 'online', 'last_activity_at' => now()]
        );
        
        $presence->startTyping($this->selectedUserId . '_' . Auth::id());

        // Broadcast typing event
        $this->dispatch('user-typing', [
            'userId' => Auth::id(),
            'conversationId' => $this->selectedUserId . '_' . Auth::id(),
        ]);
    }

    public function stopTyping()
    {
        $this->isTyping = false;
        
        $presence = UserPresence::where('user_id', Auth::id())->first();
        if ($presence) {
            $presence->stopTyping();
        }

        // Broadcast stop typing event
        $this->dispatch('user-stopped-typing', [
            'userId' => Auth::id(),
            'conversationId' => $this->selectedUserId . '_' . Auth::id(),
        ]);
    }

    public function updatePresence()
    {
        $presence = UserPresence::where('user_id', Auth::id())->first();
        if ($presence) {
            $presence->updateActivity();
        }
    }

    public function searchUsers()
    {
        if (empty($this->searchQuery)) {
            $this->loadUsers();
            return;
        }

        $this->users = User::with(['member', 'presence'])
            ->where('id', '!=', Auth::id())
            ->where('name', 'like', '%' . $this->searchQuery . '%')
            ->orderBy('name')
            ->get()
            ->map(function ($user) {
                $user->online = $user->isOnline();
                $user->unread_count = $user->unread_message_count;
                return $user;
            });
    }

    #[On('message-sent')]
    public function handleNewMessage($messageId)
    {
        // Reload messages if we're in the right conversation
        if ($this->selectedUserId) {
            $this->loadMessages();
        }
        
        // Reload users to update unread counts
        $this->loadUsers();
    }

    #[On('user-status-change')]
    public function handleUserStatusChange($data)
    {
        // Reload users to update online status
        $this->loadUsers();
    }

    #[On('user-typing')]
    public function handleUserTyping($data)
    {
        // Handle typing indicator from other users
        // This will be implemented in the view
    }

    // Test method to verify component is working
    public function testComponent()
    {
        $this->dispatch('test-event', 'Component is working!');
        return 'Component test successful';
    }

    public function render()
    {
        return view('livewire.chat-component');
    }

    public function dehydrate()
    {
        // Update presence when component is destroyed
        $presence = UserPresence::where('user_id', Auth::id())->first();
        if ($presence) {
            $presence->updateActivity();
        }
    }
}
