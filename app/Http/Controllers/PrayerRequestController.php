<?php

namespace App\Http\Controllers;

use App\Models\PrayerRequest;
use App\Models\Member;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Services\AuditService;

class PrayerRequestController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->query('search', '');
        $memberId = $request->query('member_id');
        $status = $request->query('status');
        $dateRange = $request->query('date_range');
        $archived = $request->boolean('archived');
        $chapterId = $request->query('chapter_id');
        $category = $request->query('category');
        
        $query = PrayerRequest::with(['member.chapter', 'user.preferredChapter'])
            ->orderBy('created_at', 'desc');
        
        if ($archived) {
            $query->onlyTrashed();
        }
            
        // Get members and chapters for filter dropdowns
        $members = collect();
        $chapters = collect();
        
        if ($user->can('manage', PrayerRequest::class)) {
            $memberQuery = Member::query();
            $chapterQuery = \App\Models\Chapter::query();
            
            // If user is a leader, only show members and chapters they manage
            if ($user->role === 'Leader') {
                $chapterIds = $user->ledChapters()->pluck('id');
                if ($user->member) {
                    $memberLedChapterIds = $user->member->ledChapters()->pluck('id');
                    $chapterIds = $chapterIds->merge($memberLedChapterIds);
                }
                $memberQuery->whereIn('chapter_id', $chapterIds);
                $chapterQuery->whereIn('id', $chapterIds);
            }
            
            $members = $memberQuery->orderBy('name')->get();
            $chapters = $chapterQuery->orderBy('name')->get();
        }
            
        // Apply chapter-based access control
        if ($user->role === 'Member') {
            // Members can only see their own requests
            $query->where('user_id', $user->id);
        } elseif ($user->role === 'Leader') {
            // Leaders can see requests from chapters they lead
            $ledChapterIds = $user->ledChapters()->pluck('id')->toArray();
            
            // Also check if user is a member who leads chapters
            if ($user->member) {
                $memberLedChapterIds = $user->member->ledChapters()->pluck('id')->toArray();
                $ledChapterIds = array_merge($ledChapterIds, $memberLedChapterIds);
            }
            
            if (!empty($ledChapterIds)) {
                $query->where(function($q) use ($ledChapterIds) {
                    $q->whereHas('member', function($memberQuery) use ($ledChapterIds) {
                        $memberQuery->whereIn('chapter_id', $ledChapterIds);
                    })->orWhereHas('user', function($userQuery) use ($ledChapterIds) {
                        $userQuery->whereIn('preferred_chapter_id', $ledChapterIds);
                    });
                });
            } else {
                // If leader has no chapters, show only their own requests
                $query->where('user_id', $user->id);
            }
        }
        
        // Apply filters
        if (!empty($memberId) && $memberId !== 'all' && $memberId !== '') {
            $query->where('member_id', $memberId);
        }
        
        if (!empty($status) && $status !== 'all' && $status !== '') {
            $query->where('status', $status);
        }
        
        // Apply category filter
        if (!empty($category)) {
            $query->where('category', $category);
        }

        if (!empty($dateRange) && $dateRange !== 'all' && $dateRange !== '') {
            $now = now();
            switch ($dateRange) {
                case 'today':
                    $query->whereDate('prayer_date', $now->toDateString());
                    break;
                case 'this_week':
                    $query->whereBetween('prayer_date', [
                        $now->startOfWeek()->toDateString(),
                        $now->endOfWeek()->toDateString()
                    ]);
                    break;
                case 'this_month':
                    $query->whereMonth('prayer_date', $now->month)
                          ->whereYear('prayer_date', $now->year);
                    break;
                case 'last_month':
                    $lastMonth = $now->subMonth();
                    $query->whereMonth('prayer_date', $lastMonth->month)
                          ->whereYear('prayer_date', $lastMonth->year);
                    break;
            }
        }
        
        // Apply chapter filter if selected
        if (!empty($chapterId) && $chapterId !== 'all') {
            $query->where(function($q) use ($chapterId) {
                $q->whereHas('member', function($memberQuery) use ($chapterId) {
                    $memberQuery->where('chapter_id', $chapterId);
                })->orWhereHas('user', function($userQuery) use ($chapterId) {
                    $userQuery->where('preferred_chapter_id', $chapterId);
                });
            });
        }
        
        // Add search functionality
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('request', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%")
                  ->orWhereHas('member', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        $prayerRequests = $query->paginate(10)->appends($request->query());
        
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'prayerRequests' => $prayerRequests,
                'canManage' => $user->can('manage', PrayerRequest::class),
            ]);
        }
        
        return view('prayer-requests.index', [
            'prayerRequests' => $prayerRequests,
            'canManage' => $user->can('manage', PrayerRequest::class),
            'search' => $search,
            'members' => $members,
            'selectedMember' => $memberId,
            'selectedStatus' => $status,
            'selectedDateRange' => $dateRange,
            'chapters' => $chapters,
            'chapterId' => $chapterId,
            'archived' => $archived,
            'selectedCategory' => $category,
        ]);
    }

    public function create()
    {
        $this->authorize('create', PrayerRequest::class);
        
        $user = auth()->user();
        $member = $user->member;
        
        $members = collect();
        if ($user->can('manage', PrayerRequest::class)) {
            $query = Member::query();
            
            if ($user->role === 'Leader') {
                $chapterIds = $user->ledChapters()->pluck('id');
                $query->whereIn('chapter_id', $chapterIds);
            }
            
            $members = $query->orderBy('name')->get();
        }
        
        return view('prayer-requests.create', [
            'member' => $member,
            'members' => $members
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'request' => 'required|string|max:1000',
            'is_anonymous' => 'sometimes|boolean',
            'member_id' => 'sometimes|exists:members,id',
            'category' => 'nullable|in:healing,family,work_school,deliverance,church,other',
        ]);

        $prayerRequest = new PrayerRequest([
            'request' => $validated['request'],
            'status' => 'pending',
            'prayer_date' => now(),
            'is_anonymous' => $validated['is_anonymous'] ?? false,
            'user_id' => Auth::id(),
        ]);

        if (isset($validated['category'])) {
            $prayerRequest->category = $validated['category'];
        }

        if (isset($validated['member_id'])) {
            $prayerRequest->member_id = $validated['member_id'];
        } elseif (Auth::user()->member) {
            $prayerRequest->member_id = Auth::user()->member->id;
        }

        $prayerRequest->save();

        // Audit: prayer request created
        $user = Auth::user();
        $chapter = $prayerRequest->member->chapter ?? $user->preferredChapter ?? null;
        AuditService::log(
            'prayer_request_created',
            'Prayer request created',
            [
                'prayer_request_id' => $prayerRequest->id,
                'member_id' => $prayerRequest->member_id,
                'chapter_id' => $chapter->id ?? null,
                'chapter_name' => $chapter->name ?? null,
                'status' => $prayerRequest->status,
                'category' => $prayerRequest->category ?? null,
            ],
            $user?->id
        );

        // Notify admins/leaders of the same chapter as the member/creator
        NotificationService::notifyPrayerRequestCreatedForAdmins($prayerRequest, Auth::user());

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'message' => 'Prayer request submitted successfully',
                'prayerRequest' => $prayerRequest->load(['member', 'user']),
            ], 201);
        }

        return redirect()->route('prayer-requests.index')
            ->with('success', 'Prayer request submitted successfully');
    }

    public function show(PrayerRequest $prayerRequest)
    {
        $this->authorize('view', $prayerRequest);
        
        // Eager load approver/responder and their chapters for display
        $prayerRequest->load(['member', 'user', 'approver.preferredChapter', 'responder.preferredChapter']);
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'prayerRequest' => $prayerRequest
            ]);
        }
        
        return view('prayer-requests.show', compact('prayerRequest'));
    }

    public function edit(PrayerRequest $prayerRequest)
    {
        $this->authorize('update', $prayerRequest);
        
        $user = auth()->user();
        $members = collect();
        
        // Only load members for admins/leaders
        if (in_array($user->role, ['Admin', 'Leader'])) {
            $query = Member::query();
            
            if ($user->role === 'Leader') {
                $chapterIds = $user->ledChapters()->pluck('id');
                $query->whereIn('chapter_id', $chapterIds);
            }
            
            $members = $query->orderBy('name')->get();
        }
        
        return view('prayer-requests.edit', [
            'prayerRequest' => $prayerRequest,
            'members' => $members,
        ]);
    }

    public function update(Request $request, PrayerRequest $prayerRequest)
    {
        $this->authorize('update', $prayerRequest);

        $validated = $request->validate([
            'status' => 'sometimes|in:pending,answered,declined',
            'response' => 'nullable|string|max:1000',
            'is_anonymous' => 'sometimes|boolean',
            'member_id' => 'sometimes|exists:members,id',
            'category' => 'sometimes|nullable|in:healing,family,work_school,deliverance,church,other',
        ]);

        // Check if status is changing to 'answered' and send notification
        $wasApproved = $prayerRequest->status === 'answered';
        $before = $prayerRequest->only(['status','response','is_anonymous','member_id','category']);
        $prayerRequest->update($validated);
        if (array_key_exists('response', $validated) && auth()->user()->can('manage', PrayerRequest::class)) {
            $prayerRequest->responded_by = Auth::id();
            $prayerRequest->save();
        }
        
        // If status changed to 'answered' and it wasn't already approved, send notification
        if (!$wasApproved && $prayerRequest->status === 'answered') {
            NotificationService::notifyPrayerRequestApproved($prayerRequest, Auth::user());
        }

        // Audit: prayer request updated
        $user = Auth::user();
        $chapter = $prayerRequest->member->chapter ?? $user->preferredChapter ?? null;
        AuditService::log(
            'prayer_request_updated',
            'Prayer request updated',
            [
                'prayer_request_id' => $prayerRequest->id,
                'member_id' => $prayerRequest->member_id,
                'chapter_id' => $chapter->id ?? null,
                'chapter_name' => $chapter->name ?? null,
                'before' => $before,
                'after' => $prayerRequest->only(['status','response','is_anonymous','member_id','category']),
            ],
            $user?->id
        );

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'message' => 'Prayer request updated successfully',
                'prayerRequest' => $prayerRequest->load(['member', 'user', 'responder.preferredChapter']),
            ]);
        }

        return redirect()->route('prayer-requests.index')
            ->with('success', 'Prayer request updated successfully');
    }

    public function destroy(PrayerRequest $prayerRequest)
    {
        $this->authorize('delete', $prayerRequest);
        
        // Audit: prayer request deleted
        $user = Auth::user();
        $chapter = $prayerRequest->member->chapter ?? $user->preferredChapter ?? null;
        AuditService::log(
            'prayer_request_deleted',
            'Prayer request deleted',
            [
                'prayer_request_id' => $prayerRequest->id,
                'member_id' => $prayerRequest->member_id,
                'chapter_id' => $chapter->id ?? null,
                'chapter_name' => $chapter->name ?? null,
            ],
            $user?->id
        );

        $prayerRequest->delete();

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'message' => 'Prayer request deleted successfully'
            ]);
        }

        return redirect()->route('prayer-requests.index')
            ->with('success', 'Prayer request deleted successfully');
    }

    public function approve(PrayerRequest $prayerRequest)
    {
        $this->authorize('manage', PrayerRequest::class);
        
        // Check if it was already approved to avoid duplicate notifications
        $wasApproved = $prayerRequest->status === 'answered';
        
        $prayerRequest->status = 'answered';
        $prayerRequest->approved_by = Auth::id();
        $prayerRequest->save();

        // Audit: prayer request approved
        $user = Auth::user();
        $chapter = $prayerRequest->member->chapter ?? $user->preferredChapter ?? null;
        AuditService::log(
            'prayer_request_approved',
            'Prayer request approved',
            [
                'prayer_request_id' => $prayerRequest->id,
                'member_id' => $prayerRequest->member_id,
                'chapter_id' => $chapter->id ?? null,
                'chapter_name' => $chapter->name ?? null,
            ],
            $user?->id
        );
        
        // Send notification if it wasn't already approved
        if (!$wasApproved) {
            NotificationService::notifyPrayerRequestApproved($prayerRequest, Auth::user());
        }
        
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'message' => 'Prayer request approved successfully',
                'prayerRequest' => $prayerRequest->load(['member', 'user', 'approver.preferredChapter']),
            ]);
        }
        
        return back()->with('success', 'Prayer request approved successfully');
    }

    public function decline(PrayerRequest $prayerRequest)
    {
        $this->authorize('manage', PrayerRequest::class);
        
        $prayerRequest->status = 'declined';
        $prayerRequest->save();

        // Audit: prayer request declined
        $user = Auth::user();
        $chapter = $prayerRequest->member->chapter ?? $user->preferredChapter ?? null;
        AuditService::log(
            'prayer_request_declined',
            'Prayer request declined',
            [
                'prayer_request_id' => $prayerRequest->id,
                'member_id' => $prayerRequest->member_id,
                'chapter_id' => $chapter->id ?? null,
                'chapter_name' => $chapter->name ?? null,
            ],
            $user?->id
        );
        
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'message' => 'Prayer request declined successfully',
                'prayerRequest' => $prayerRequest->load(['member', 'user']),
            ]);
        }
        
        return back()->with('success', 'Prayer request declined successfully');
    }

    public function getStats()
    {
        $user = Auth::user();
        
        $query = PrayerRequest::query();
        
        if ($user->role === 'Member') {
            $query->where('user_id', $user->id);
        }
        
        $total = $query->count();
        $answered = $query->clone()->where('status', 'answered')->count();
        $pending = $query->clone()->where('status', 'pending')->count();
        
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'total' => $total,
                'answered' => $answered,
                'pending' => $pending,
            ]);
        }
        
        return compact('total', 'answered', 'pending');
    }

    public function getRecentAnswered()
    {
        $user = Auth::user();
        
        $query = PrayerRequest::with(['member', 'user'])
            ->where('status', 'answered')
            ->orderBy('updated_at', 'desc')
            ->limit(5);
            
        // Apply chapter-based access control
        if ($user->role === 'Member') {
            // Members can only see their own requests
            $query->where('user_id', $user->id);
        } elseif ($user->role === 'Leader') {
            // Leaders can see requests from chapters they lead
            $ledChapterIds = $user->ledChapters()->pluck('id')->toArray();
            
            // Also check if user is a member who leads chapters
            if ($user->member) {
                $memberLedChapterIds = $user->member->ledChapters()->pluck('id')->toArray();
                $ledChapterIds = array_merge($ledChapterIds, $memberLedChapterIds);
            }
            
            if (!empty($ledChapterIds)) {
                $query->where(function($q) use ($ledChapterIds) {
                    $q->whereHas('member', function($memberQuery) use ($ledChapterIds) {
                        $memberQuery->whereIn('chapter_id', $ledChapterIds);
                    })->orWhereHas('user', function($userQuery) use ($ledChapterIds) {
                        $userQuery->whereIn('preferred_chapter_id', $ledChapterIds);
                    });
                });
            } else {
                // If leader has no chapters, show only their own requests
                $query->where('user_id', $user->id);
            }
        }
        // Admin role can see all requests (no additional filtering)
        
        $answeredPrayers = $query->get();
        
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'answeredPrayers' => $answeredPrayers,
            ]);
        }
        
        return $answeredPrayers;
    }
}
