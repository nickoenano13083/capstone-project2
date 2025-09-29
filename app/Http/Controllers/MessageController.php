<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use App\Models\Chapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\NewMessage;

class MessageController extends Controller
{
    /**
     * Display the messaging interface with users list
     */
    public function index(Request $request)
    {
        $query = User::where('id', '!=', auth()->id())
            ->withLastMessage()
            ->with(['member', 'presence', 'preferredChapter']);

        // For regular members, only show users who have messaged them or they have messaged
        if (!Auth::user()->isAdmin()) {
            $query->where(function($q) {
                $q->whereHas('sentMessages', function($sentQuery) {
                    $sentQuery->where('receiver_id', Auth::id());
                })->orWhereHas('receivedMessages', function($receivedQuery) {
                    $receivedQuery->where('sender_id', Auth::id());
                });
            });
        }

        // Apply chapter filter if user is admin and chapter is specified
        if (Auth::user()->isAdmin() && $request->has('chapter_id')) {
            $chapterId = $request->input('chapter_id');
            if ($chapterId) {
                $query->where('preferred_chapter_id', $chapterId);
            }
        }

        $chatUsers = $query->get();
        
        // Get all chapters for admin filter dropdown
        $chapters = Auth::user()->isAdmin() ? Chapter::all() : collect();

        // Calculate online users count
        $onlineUsersCount = $chatUsers->filter(function ($user) {
            return $user->isOnline();
        })->count();

        return view('messages.index', compact('chatUsers', 'chapters', 'onlineUsersCount'));
    }

    /**
     * Get messages between authenticated user and specified user
     */
    public function getMessages($userId)
    {
        try {
            $offset = request()->query('offset', 0);
            $perPage = 20; // Number of messages per page
            
            $messages = Message::where(function($query) use ($userId) {
                $query->where('sender_id', Auth::id())
                      ->where('receiver_id', $userId);
            })->orWhere(function($query) use ($userId) {
                $query->where('sender_id', $userId)
                      ->where('receiver_id', Auth::id());
            })
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->skip($offset * $perPage)
            ->take($perPage)
            ->get();

            return response()->json($messages);
            
        } catch (\Exception $e) {
            \Log::error('Error loading messages: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to load messages',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new message
     */
    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'content' => 'required|string'
        ]);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $validated['receiver_id'],
            'content' => $validated['content']
        ]);

        // Load relationships for the response
        $message->load(['sender', 'receiver']);

        broadcast(new NewMessage($message))->toOthers();

        return response()->json($message);
    }

    /**
     * Mark messages as read
     */
    public function markAsRead($userId)
    {
        Message::where('sender_id', $userId)
              ->where('receiver_id', Auth::id())
              ->whereNull('read_at')
              ->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }

    /**
     * Search users available for messaging
     */
    public function searchUsers(Request $request)
    {
        $search = $request->query('q', '');
        
        // Debug: Log the search term
        \Log::info('Search term received: ' . $search);
        
        $query = User::where('id', '!=', auth()->id())
            ->with(['member', 'presence', 'preferredChapter']);
        
        // Apply search filter if provided
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }
        
        // Debug: Log the SQL query
        \Log::info('SQL Query: ' . $query->toSql());
        
        // For regular members, show all users (not just those with message history)
        // This allows them to start new conversations
        if (!Auth::user()->isAdmin()) {
            // No additional filtering for members - they can see all users to start new conversations
        }
        
        // Apply chapter filter if user is admin and chapter is specified
        if (Auth::user()->isAdmin() && $request->has('chapter_id')) {
            $chapterId = $request->input('chapter_id');
            if ($chapterId) {
                $query->where('preferred_chapter_id', $chapterId);
            }
        }
        
        $users = $query->orderBy('name')->limit(20)->get();
        
        // Debug: Log the number of results
        \Log::info('Number of users found: ' . $users->count());
        
        return response()->json($users);
    }

    /**
     * Update user's last activity timestamp
     */
    public function updateActivity()
    {
        auth()->user()->update(['last_activity' => now()]);
        return response()->json(['success' => true]);
    }
}