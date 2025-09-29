<?php

namespace App\Policies;

use App\Models\User;
use App\Models\PrayerRequest;
use Illuminate\Auth\Access\HandlesAuthorization;

class PrayerRequestPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view prayer requests
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PrayerRequest $prayerRequest): bool
    {
        // Admins can view all prayer requests
        if ($user->role === 'Admin') {
            return true;
        }
        
        // Users can always view their own prayer requests
        if ($user->id === $prayerRequest->user_id) {
            return true;
        }
        
        // Leaders can view requests from chapters they lead
        if ($user->role === 'Leader') {
            $ledChapterIds = $user->ledChapters()->pluck('id')->toArray();
            
            // Also check if user is a member who leads chapters
            if ($user->member) {
                $memberLedChapterIds = $user->member->ledChapters()->pluck('id')->toArray();
                $ledChapterIds = array_merge($ledChapterIds, $memberLedChapterIds);
            }
            
            // Check if prayer request is from a chapter this leader manages
            if (!empty($ledChapterIds)) {
                // Check if the prayer request member belongs to a led chapter
                if ($prayerRequest->member && in_array($prayerRequest->member->chapter_id, $ledChapterIds)) {
                    return true;
                }
                
                // Check if the prayer request user's preferred chapter is led by this leader
                if ($prayerRequest->user && in_array($prayerRequest->user->preferred_chapter_id, $ledChapterIds)) {
                    return true;
                }
            }
        }
        
        // Members can view requests from their preferred chapter
        if ($user->role === 'Member' && $user->preferred_chapter_id) {
            // Check if prayer request member belongs to the same chapter
            if ($prayerRequest->member && $prayerRequest->member->chapter_id === $user->preferred_chapter_id) {
                return true;
            }
            
            // Check if prayer request user has the same preferred chapter
            if ($prayerRequest->user && $prayerRequest->user->preferred_chapter_id === $user->preferred_chapter_id) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // All authenticated users can create prayer requests
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PrayerRequest $prayerRequest): bool
    {
        // Users can update their own prayer requests or admins/leaders can update any
        return $user->id === $prayerRequest->user_id || 
               in_array($user->role, ['Admin', 'Leader']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PrayerRequest $prayerRequest): bool
    {
        // Only admins can delete prayer requests
        return $user->role === 'Admin';
    }

    /**
     * Determine whether the user can manage prayer requests.
     */
    public function manage(User $user): bool
    {
        return in_array($user->role, ['Admin', 'Leader']);
    }
}
