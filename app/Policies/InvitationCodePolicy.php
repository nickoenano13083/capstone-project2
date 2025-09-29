<?php

namespace App\Policies;

use App\Models\InvitationCode;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InvitationCodePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['admin', 'leader']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, InvitationCode $invitationCode): bool
    {
        return $user->hasRole('admin') || 
               $invitationCode->created_by === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['admin', 'leader']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, InvitationCode $invitationCode): bool
    {
        // Only allow deletion if the invitation is not used
        if ($invitationCode->used_at !== null) {
            return false;
        }

        return $user->hasRole('admin') || 
               $invitationCode->created_by === $user->id;
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, InvitationCode $invitationCode): bool
    {
        return $user->hasRole('admin');
    }
}
