<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Chapter;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $query = User::with(['member.chapter', 'preferredChapter'])->orderBy('created_at', 'desc');

        $filterChapterId = $request->input('chapter_id');
        if ($filterChapterId) {
            $query->where(function($q) use ($filterChapterId) {
                $q->whereHas('member', function ($memberQ) use ($filterChapterId) {
                    $memberQ->where('chapter_id', $filterChapterId);
                })->orWhere('preferred_chapter_id', $filterChapterId);
            });
        }

        if (auth()->user()->role === 'Leader') {
            $leaderChapterIds = auth()->user()->ledChapters()->pluck('id');
            $query->where(function($q) use ($leaderChapterIds) {
                $q->whereHas('member', function ($memberQ) use ($leaderChapterIds) {
                    $memberQ->whereIn('chapter_id', $leaderChapterIds);
                })->orWhereIn('preferred_chapter_id', $leaderChapterIds);
            });
        }

        $users = $query->paginate(10);

        $chaptersQuery = Chapter::query();

        if (auth()->user()->role === 'Leader') {
            $leaderChapterIds = auth()->user()->ledChapters()->pluck('id');
            $chaptersQuery->whereIn('id', $leaderChapterIds);
        }

        $chapters = $chaptersQuery->orderBy('name')->get(['id','name']);

        return view('admin.users.index', [
            'users' => $users,
            'chapters' => $chapters,
            'filterChapterId' => $filterChapterId,
        ]);
    }

    public function updateChapter(Request $request, $id)
    {
        $request->validate([
            'chapter_id' => 'nullable|exists:chapters,id',
        ]);

        $user = User::with('member')->findOrFail($id);

        if (auth()->user()->role === 'Leader') {
            $leaderChapterIds = auth()->user()->ledChapters()->pluck('id')->toArray();
            $targetChapterId = $request->input('chapter_id');
            if ($targetChapterId && !in_array($targetChapterId, $leaderChapterIds)) {
                abort(403, 'You can only assign users to chapters you lead.');
            }
        }

        $chapterId = $request->input('chapter_id') ?: null;

        // Update both member.chapter_id and user.preferred_chapter_id
        if ($user->member) {
            $user->member->chapter_id = $chapterId;
            $user->member->save();
        }
        
        // Always update the user's preferred chapter
        $user->preferred_chapter_id = $chapterId;
        $user->save();

        // If the user is a Leader and a chapter was selected, ensure they're set as leader for that chapter when unassigned
        if ($chapterId && $user->role === 'Leader') {
            $chapter = Chapter::find($chapterId);
            if ($chapter && (!$chapter->leader_id || $chapter->leader_type !== 'App\\Models\\Member')) {
                // Ensure member record exists and is saved before assignment
                $user->load('member'); // Refresh the relationship
                if ($user->member && $user->member->exists) {
                    $chapter->leader_id = $user->member->id;
                    $chapter->leader_type = 'App\\Models\\Member';
                    $chapter->save();
                }
            }
        }

        return redirect()->back()->with('success', 'User chapter updated successfully.');
    }

    /**
     * Impersonate a user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function impersonate($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent impersonating users with higher or equal privileges
        if (auth()->user()->role === 'Admin') {
            if ($user->role === 'Admin' && $user->id !== auth()->id()) {
                return redirect()->back()->with('error', 'You cannot impersonate another admin.');
            }
        } elseif (auth()->user()->role === 'Leader') {
            if (in_array($user->role, ['Admin', 'Leader'])) {
                return redirect()->back()->with('error', 'You can only impersonate regular members.');
            }
            
            // Leaders can only impersonate members from their chapters
            $leaderChapterIds = auth()->user()->ledChapters()->pluck('id')->toArray();
            $userChapterId = $user->member->chapter_id ?? $user->preferred_chapter_id;
            
            if (!in_array($userChapterId, $leaderChapterIds)) {
                return redirect()->back()->with('error', 'You can only impersonate members from your chapters.');
            }
        } else {
            return redirect()->back()->with('error', 'You do not have permission to impersonate users.');
        }
        
        // Store the original user ID in the session
        session()->put('impersonated_by', auth()->id());
        
        // Log in as the user
        auth()->loginUsingId($user->id);
        
        return redirect()->route('dashboard')->with('success', 'You are now impersonating ' . $user->name);
    }

    /**
     * Stop impersonating a user and return to the original admin user.
     *
     * @return \Illuminate\Http\Response
     */
    public function stopImpersonate()
    {
        if (!session()->has('impersonated_by')) {
            return redirect()->route('dashboard')->with('error', 'Not currently impersonating any user.');
        }

        $adminId = session()->pull('impersonated_by');
        $admin = User::findOrFail($adminId);
        
        // Log back in as the original admin
        auth()->login($admin);
        
        return redirect()->route('admin.users.index')->with('success', 'You have stopped impersonating and are now back as ' . $admin->name);
    }

    public function assignLeader(Request $request, $id)
    {
        if (!auth()->check() || auth()->user()->role !== 'Admin') {
            abort(403, 'Only admins can assign chapter leaders.');
        }

        $request->validate([
            'leader_chapter_id' => 'required|exists:chapters,id',
        ]);

        $user = User::findOrFail($id);
        $chapter = Chapter::findOrFail($request->input('leader_chapter_id'));

        // Check if the chapter already has a leader
        if ($chapter->leader_id && $chapter->leader_type === 'App\\Models\\Member') {
            $currentLeader = \App\Models\Member::find($chapter->leader_id);
            if ($currentLeader && $currentLeader->user_id !== $user->id) {
                return redirect()->back()->with('error', 'This chapter already has a leader assigned. Please remove the current leader first.');
            }
        }

        // Assign the user as leader for the chapter
        $user->load('member'); // Refresh the relationship
        if ($user->member && $user->member->exists) {
            $chapter->leader_id = $user->member->id;
            $chapter->leader_type = 'App\\Models\\Member';
            $chapter->save();
        } else {
            return redirect()->back()->with('error', 'Cannot assign leadership: User must have a member record first.');
        }

        // Ensure the user has Leader role
        if ($user->role !== 'Leader') {
            $user->role = 'Leader';
            $user->save();
        }

        // Update user's preferred chapter
        $user->preferred_chapter_id = $chapter->id;
        $user->save();

        return redirect()->back()->with('success', 'User assigned as leader for the selected chapter.');
    }

    public function assignLeaderAndRole(Request $request, $id)
    {
        if (!auth()->check() || auth()->user()->role !== 'Admin') {
            abort(403, 'Only admins can assign chapter leaders.');
        }

        $request->validate([
            'leader_chapter_id' => 'required|exists:chapters,id',
        ]);

        $user = User::findOrFail($id);
        $chapter = Chapter::findOrFail($request->input('leader_chapter_id'));

        // Check if the chapter already has a leader
        if ($chapter->leader_id && $chapter->leader_type === 'App\\Models\\Member') {
            $currentLeader = \App\Models\Member::find($chapter->leader_id);
            if ($currentLeader && $currentLeader->user_id !== $user->id) {
                return redirect()->back()->with('error', 'This chapter already has a leader assigned. Please remove the current leader first.');
            }
        }

        // Assign the user as leader for the chapter
        if ($user->member) {
            $chapter->leader_id = $user->member->id;
            $chapter->leader_type = 'App\\Models\\Member';
            $chapter->save();
        }

        // Set user role to Leader
        $user->role = 'Leader';
        $user->save();

        // Update user's preferred chapter
        $user->preferred_chapter_id = $chapter->id;
        $user->save();

        return redirect()->back()->with('success', "User {$user->name} has been assigned as Leader for {$chapter->name}.");
    }

    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|in:Admin,Leader,Member'
        ]);

        $user = User::with('member')->findOrFail($id);

        if (auth()->user()->role === 'Leader') {
            $leaderChapterIds = auth()->user()->ledChapters()->pluck('id');
            $isInScope = ($user->member && $leaderChapterIds->contains($user->member->chapter_id)) || 
                         ($user->preferred_chapter_id && $leaderChapterIds->contains($user->preferred_chapter_id));
            if (!$isInScope || $request->role === 'Admin') {
                abort(403, 'Leaders can only manage users in their chapters and cannot assign Admin.');
            }
        }

        $user->update(['role' => $request->role]);

        // If promoting to Leader, do not automatically assign chapter leadership
        if ($request->role === 'Leader') {
            // Temporarily disabled automatic chapter leader assignment to prevent foreign key constraint errors
            /*
            $chapterId = $user->member?->chapter_id ?? $user->preferred_chapter_id;
            
            if ($chapterId) {
                // User has a chapter, assign them as leader if unassigned
                $chapter = Chapter::find($chapterId);
                if ($chapter && (!$chapter->leader_id || $chapter->leader_type !== 'App\\Models\\Member')) {
                    // Ensure member record exists and is saved before assignment
                    $user->load('member'); // Refresh the relationship
                    if ($user->member && $user->member->exists) {
                        $chapter->leader_id = $user->member->id;
                        $chapter->leader_type = 'App\\Models\\Member';
                        $chapter->save();
                    }
                }
            } else {
                // User doesn't have a chapter, find an unassigned chapter or create one
                $unassignedChapter = Chapter::whereNull('leader_id')->first();
                if ($unassignedChapter) {
                    // Ensure member record exists before assignment
                    $user->load('member');
                    if ($user->member && $user->member->exists) {
                        $unassignedChapter->leader_id = $user->member->id;
                        $unassignedChapter->leader_type = 'App\\Models\\Member';
                        $unassignedChapter->save();
                        
                        // Update user's preferred chapter
                        $user->preferred_chapter_id = $unassignedChapter->id;
                        $user->save();
                    }
                } else {
                    // No unassigned chapters, redirect back with warning
                    return redirect()->back()->with('warning', 'User promoted to Leader but no unassigned chapters available. Please assign them to a specific chapter.');
                }
            }
            */
        }

        return redirect()->back()->with('success', 'User role updated successfully.');
    }

    public function removeLeader(Request $request, $id)
    {
        if (!auth()->check() || auth()->user()->role !== 'Admin') {
            abort(403, 'Only admins can remove chapter leaders.');
        }

        $request->validate([
            'chapter_id' => 'required|exists:chapters,id',
        ]);

        $chapter = Chapter::findOrFail($request->input('chapter_id'));
        
        if ($chapter->leader_id && $chapter->leader_type === 'App\\Models\\User') {
            $leader = User::find($chapter->leader_id);
            if ($leader) {
                // Remove leader role if they don't lead any other chapters
                $otherChaptersLed = Chapter::where('leader_id', $leader->id)
                    ->where('leader_type', 'App\\Models\\User')
                    ->where('id', '!=', $chapter->id)
                    ->count();
                
                if ($otherChaptersLed === 0) {
                    $leader->role = 'Member';
                    $leader->save();
                }
            }
            
            // Clear the chapter's leader
            $chapter->leader_id = null;
            $chapter->leader_type = null;
            $chapter->save();
        }

        return redirect()->back()->with('success', 'Leader removed from chapter successfully.');
    }

    /**
     * Deactivate a user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deactivate($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent deactivating self or other admins
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot deactivate your own account.');
        }
        
        if ($user->role === 'Admin' && auth()->user()->role !== 'Admin') {
            return redirect()->back()->with('error', 'Only admins can deactivate other admin accounts.');
        }
        
        $user->is_active = false;
        $user->save();
        
        return redirect()->back()->with('success', 'User has been deactivated successfully.');
    }
    
    /**
     * Activate a user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function activate($id)
    {
        $user = User::findOrFail($id);
        
        // Only admins can activate users
        if (auth()->user()->role !== 'Admin') {
            return redirect()->back()->with('error', 'You do not have permission to activate users.');
        }
        
        $user->is_active = true;
        $user->save();
        
        return redirect()->back()->with('success', 'User has been activated successfully.');
    }

    /**
     * Remove the specified user from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent deleting self
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }
        
        // Only admins can delete other admins
        if ($user->role === 'Admin' && auth()->user()->role !== 'Admin') {
            return redirect()->back()->with('error', 'Only admins can delete admin accounts.');
        }
        
        try {
            // Delete related records first to maintain referential integrity
            \DB::beginTransaction();
            
            // Delete member record if exists
            if ($user->member) {
                $user->member->delete();
            }
            
            // Remove from any chapters they lead
            Chapter::where('leader_id', $user->member?->id)
                  ->where('leader_type', 'App\\Models\\Member')
                  ->update([
                      'leader_id' => null,
                      'leader_type' => null
                  ]);
            
            // Delete the user
            $user->delete();
            
            \DB::commit();
            
            return redirect()->route('admin.users.index')
                           ->with('success', 'User deleted successfully.');
                           
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Error deleting user: ' . $e->getMessage());
            return redirect()->back()
                           ->with('error', 'An error occurred while deleting the user.');
        }
    }

    /**
     * Store a newly created Member user.
     */
    public function store(Request $request)
    {
        $request->merge([
            'name' => preg_replace('/\s+/', ' ', trim((string) $request->input('name'))),
            'email' => strtolower(trim((string) $request->input('email'))),
        ]);

        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:users,name'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'preferred_chapter_id' => ['nullable', 'exists:chapters,id'],
        ]);

        $chapterId = $request->input('preferred_chapter_id');

        // Leaders can only create users within chapters they lead
        if (auth()->user()->role === 'Leader') {
            if (!$chapterId) {
                abort(403, 'Leaders must select a chapter for the new member.');
            }
            $leaderChapterIds = auth()->user()->ledChapters()->pluck('id')->toArray();
            if (!in_array($chapterId, $leaderChapterIds)) {
                abort(403, 'You can only create members in your chapters.');
            }
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'Member',
            'preferred_chapter_id' => $chapterId,
        ]);

        // Mirror to members table
        Member::create([
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $request->input('phone', ''),
            'address' => $request->input('address', ''),
            'join_date' => now()->toDateString(),
            'status' => 'Active',
            'role' => 'Member',
            'chapter_id' => $chapterId,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Member user created successfully.');
    }
}