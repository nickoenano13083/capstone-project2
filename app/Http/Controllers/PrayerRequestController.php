<?php

namespace App\Http\Controllers;

use App\Models\PrayerRequest;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PrayerRequestController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->query('search', '');
        $memberId = $request->query('member_id');
        $status = $request->query('status');
        $dateRange = $request->query('date_range');
        $chapterId = $request->query('chapter_id');
        
        $query = PrayerRequest::with(['member.chapter', 'user.preferredChapter'])
            ->orderBy('created_at', 'desc');
            
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
        
        $prayerRequests = $query->paginate(10);
        
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
            'chapterId' => $chapterId
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
        ]);

        $prayerRequest = new PrayerRequest([
            'request' => $validated['request'],
            'status' => 'pending',
            'prayer_date' => now(),
            'is_anonymous' => $validated['is_anonymous'] ?? false,
            'user_id' => Auth::id(),
        ]);

        if (isset($validated['member_id'])) {
            $prayerRequest->member_id = $validated['member_id'];
        } elseif (Auth::user()->member) {
            $prayerRequest->member_id = Auth::user()->member->id;
        }

        $prayerRequest->save();

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
        
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'prayerRequest' => $prayerRequest->load(['member', 'user'])
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
        ]);

        $prayerRequest->update($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'message' => 'Prayer request updated successfully',
                'prayerRequest' => $prayerRequest->load(['member', 'user']),
            ]);
        }

        return redirect()->route('prayer-requests.index')
            ->with('success', 'Prayer request updated successfully');
    }

    public function destroy(PrayerRequest $prayerRequest)
    {
        $this->authorize('delete', $prayerRequest);
        
        $prayerRequest->delete();

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'message' => 'Prayer request deleted successfully'
            ]);
        }

        return redirect()->route('prayer-requests.index')
            ->with('success', 'Prayer request deleted successfully');
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
