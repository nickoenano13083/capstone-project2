<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Models\Event;
use App\Models\Chapter;
use App\Models\PrayerRequest;

class NotificationService
{
    /**
     * Send event creation notification to members of the same chapter
     */
    public static function notifyEventCreated(Event $event, User $admin): void
    {
        // Get all members from the same chapter as the event
        $chapterId = $event->chapter_id;
        
        // Get all users who are members of this chapter or have this as preferred chapter
        $members = User::where('role', 'Member')
            ->where(function($query) use ($chapterId) {
                $query->whereHas('member', function($q) use ($chapterId) {
                    $q->where('chapter_id', $chapterId);
                })
                ->orWhere('preferred_chapter_id', $chapterId);
            })
            ->get();

        foreach ($members as $member) {
            Notification::create([
                'user_id' => $member->id,
                'type' => 'event_created',
                'title' => 'New Event Created',
                'message' => "A new event '{$event->title}' has been created for your chapter.",
                'data' => [
                    'event_id' => $event->id,
                    'event_title' => $event->title,
                    'event_date' => $event->date ? $event->date->setTimezone('Asia/Manila')->format('Y-m-d') : null,
                    'event_time' => $event->time,
                    'event_location' => $event->location,
                    'chapter_id' => $chapterId,
                    'admin_name' => $admin->name,
                ],
            ]);
        }
    }

    /**
     * Send event update notification to members of the same chapter
     */
    public static function notifyEventUpdated(Event $event, User $admin): void
    {
        // Get all members from the same chapter as the event
        $chapterId = $event->chapter_id;
        
        // Get all users who are members of this chapter or have this as preferred chapter
        $members = User::where('role', 'Member')
            ->where(function($query) use ($chapterId) {
                $query->whereHas('member', function($q) use ($chapterId) {
                    $q->where('chapter_id', $chapterId);
                })
                ->orWhere('preferred_chapter_id', $chapterId);
            })
            ->get();

        foreach ($members as $member) {
            Notification::create([
                'user_id' => $member->id,
                'type' => 'event_updated',
                'title' => 'Event Updated',
                'message' => "The event '{$event->title}' has been updated.",
                'data' => [
                    'event_id' => $event->id,
                    'event_title' => $event->title,
                    'event_date' => $event->date ? $event->date->setTimezone('Asia/Manila')->format('Y-m-d') : null,
                    'event_time' => $event->time,
                    'event_location' => $event->location,
                    'chapter_id' => $chapterId,
                    'admin_name' => $admin->name,
                ],
            ]);
        }
    }

    /**
     * Get unread notification count for a user
     */
    public static function getUnreadCount(User $user): int
    {
        return $user->unreadNotifications()->count();
    }

    /**
     * Mark all notifications as read for a user
     */
    public static function markAllAsRead(User $user): void
    {
        $user->unreadNotifications()->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    /**
     * Send prayer request approval notification to the member who submitted the request
     */
    public static function notifyPrayerRequestApproved(PrayerRequest $prayerRequest, User $admin): void
    {
        // Only send notification if the prayer request belongs to a user
        if (!$prayerRequest->user_id) {
            return;
        }

        // Only send notification if the prayer request is actually approved
        if ($prayerRequest->status !== 'answered') {
            return;
        }

        // Get the user who submitted the prayer request
        $user = $prayerRequest->user;
        if (!$user) {
            return;
        }

        // Check if notification already exists to avoid duplicates
        $existingNotification = Notification::where('user_id', $user->id)
            ->where('type', 'prayer_request_approved')
            ->where('data->prayer_request_id', $prayerRequest->id)
            ->first();

        if ($existingNotification) {
            return;
        }

        // Create notification for the user
        Notification::create([
            'user_id' => $user->id,
            'type' => 'prayer_request_approved',
            'title' => 'Prayer Request Approved',
            'message' => "Your prayer request has been approved and answered by the admin.",
            'data' => [
                'prayer_request_id' => $prayerRequest->id,
                'prayer_request' => $prayerRequest->request,
                'response' => $prayerRequest->response,
                'admin_name' => $admin->name,
                'approved_at' => now()->toISOString(),
            ],
        ]);
    }

    /**
     * Mark a specific notification as read
     */
    public static function markAsRead(Notification $notification): void
    {
        $notification->markAsRead();
    }

    /**
     * Notify Admins/Leaders of the same chapter when a member creates a prayer request
     */
    public static function notifyPrayerRequestCreatedForAdmins(PrayerRequest $prayerRequest, User $creator): void
    {
        // Determine the chapter to route by: prefer member's chapter, fallback to creator's preferred chapter
        $chapterId = null;
        if ($prayerRequest->member && $prayerRequest->member->chapter_id) {
            $chapterId = $prayerRequest->member->chapter_id;
        } elseif ($creator && $creator->preferred_chapter_id) {
            $chapterId = $creator->preferred_chapter_id;
        }

        if (!$chapterId) {
            return; // No routing chapter; skip notifications
        }

        // Get Admins assigned to this chapter via preferred_chapter_id
        $admins = User::where('role', 'Admin')
            ->where('preferred_chapter_id', $chapterId)
            ->get();

        // Get Leaders who lead this chapter (either via user->ledChapters or member->ledChapters)
        $leaders = User::where('role', 'Leader')
            ->where(function($q) use ($chapterId) {
                $q->whereHas('ledChapters', function($c) use ($chapterId) {
                    $c->where('id', $chapterId);
                })
                ->orWhereHas('member.ledChapters', function($c) use ($chapterId) {
                    $c->where('id', $chapterId);
                });
            })
            ->get();

        $recipients = $admins->merge($leaders)->unique('id');

        foreach ($recipients as $recipient) {
            // Prevent duplicates for the same prayer request and recipient
            $existing = Notification::where('user_id', $recipient->id)
                ->where('type', 'prayer_request_created')
                ->where('data->prayer_request_id', $prayerRequest->id)
                ->first();
            if ($existing) {
                continue;
            }

            Notification::create([
                'user_id' => $recipient->id,
                'type' => 'prayer_request_created',
                'title' => 'New Prayer Request',
                'message' => ($prayerRequest->member?->name ?? $creator->name) . ' submitted a new prayer request.',
                'data' => [
                    'prayer_request_id' => $prayerRequest->id,
                    'member_name' => $prayerRequest->member?->name,
                    'user_name' => $creator->name,
                    'category' => $prayerRequest->category,
                    'chapter_id' => $chapterId,
                ],
            ]);
        }
    }
}
