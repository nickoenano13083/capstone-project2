<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display a listing of notifications for the authenticated user
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        $notifications = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('notifications.partials.notifications-list', [
                    'notifications' => $notifications
                ])->render(),
                'pagination' => $notifications->links()->toHtml(),
            ]);
        }

        return view('notifications.index', [
            'notifications' => $notifications,
            'unreadCount' => NotificationService::getUnreadCount($user)
        ]);
    }

    /**
     * Mark a notification as read
     */
    public function markAsRead(Notification $notification)
    {
        // Ensure the notification belongs to the authenticated user
        if ($notification->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        NotificationService::markAsRead($notification);

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back();
    }

    /**
     * Mark all notifications as read for the authenticated user
     */
    public function markAllAsRead()
    {
        NotificationService::markAllAsRead(auth()->user());

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back();
    }

    /**
     * Get unread notification count (for AJAX requests)
     */
    public function getUnreadCount()
    {
        $count = NotificationService::getUnreadCount(auth()->user());
        
        return response()->json(['count' => $count]);
    }

    /**
     * Get recent notifications (for dropdown/header)
     */
    public function getRecent(Request $request)
    {
        $limit = $request->get('limit', 5);
        
        $notifications = auth()->user()->notifications()
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        return response()->json([
            'notifications' => $notifications,
            'unreadCount' => NotificationService::getUnreadCount(auth()->user())
        ]);
    }
}