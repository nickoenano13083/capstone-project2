<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $search = $request->query('search');
        $status = $request->query('status', 'all');
        $sort = $request->query('sort', 'date_asc');
        $dateRange = $request->query('date_range', 'all');
        $chapter_id = $request->query('chapter_id');

        // Debug: Log user and request info
        \Log::info('EventController@index', [
            'user_id' => $user->id,
            'role' => $user->role,
            'request' => $request->all()
        ]);

        // Base query for all events
        $activeQuery = Event::with(['chapter', 'attendance']);
        $completedQuery = Event::with(['chapter', 'attendance']);

        // Apply search filter to both queries
        if ($search) {
            $searchTerm = "%{$search}%";
            $searchCallback = function($q) use ($searchTerm) {
                $q->where('title', 'like', $searchTerm)
                  ->orWhere('description', 'like', $searchTerm)
                  ->orWhere('location', 'like', $searchTerm);
            };
            $activeQuery->where($searchCallback);
            $completedQuery->where($searchCallback);
        }

        // Apply chapter filter if selected
        if ($chapter_id) {
            $activeQuery->where('chapter_id', $chapter_id);
            $completedQuery->where('chapter_id', $chapter_id);
        }

        // Apply status filter
        if ($status === 'ongoing') {
            $activeQuery->where('status', 'ongoing');
            $completedQuery->where('status', 'ongoing');
        } elseif ($status === 'upcoming') {
            $activeQuery->where('status', 'upcoming');
            $completedQuery->where('status', 'upcoming');
        } elseif ($status === 'completed') {
            $activeQuery->where('status', 'completed');
            $completedQuery->where('status', 'completed');
        } elseif ($status === 'cancelled') {
            $activeQuery->where('status', 'cancelled');
            $completedQuery->where('status', 'cancelled');
        } else {
            // Default: Show all statuses except completed in main view
            $activeQuery->where('status', '!=', 'completed');
            $completedQuery->where('status', 'completed');
        }

        // Apply role-based filtering to both queries
        foreach ([$activeQuery, $completedQuery] as $query) {
            if ($user->role === 'Admin') {
                // Admins see all events
                continue;
            } elseif (strtolower($user->role) === 'leader') {
                // Leaders see events from their led chapters and preferred chapter
                $ledChapters = $user->ledChapters->pluck('id')->toArray();
                $preferredChapterId = $user->preferred_chapter_id;
                
                $query->where(function($q) use ($ledChapters, $preferredChapterId) {
                    if (!empty($ledChapters)) {
                        $q->whereIn('chapter_id', $ledChapters);
                    }
                    if ($preferredChapterId) {
                        $q->orWhere('chapter_id', $preferredChapterId);
                    }
                });
            } elseif ($user->role === 'Member') {
                // Members see events from their chapter and preferred chapter
                $memberChapterIds = [];
                
                if ($user->member && $user->member->chapter_id) {
                    $memberChapterIds[] = $user->member->chapter_id;
                }
                
                if ($user->preferred_chapter_id && !in_array($user->preferred_chapter_id, $memberChapterIds)) {
                    $memberChapterIds[] = $user->preferred_chapter_id;
                }
                
                if (!empty($memberChapterIds)) {
                    $query->whereIn('chapter_id', $memberChapterIds);
                } else {
                    $query->where('id', '=', -1); // No chapters assigned
                }
            }
        }

        // Apply date range filter to active events
        $now = now();
        switch ($dateRange) {
            case 'today':
                $activeQuery->whereDate('date', $now->toDateString());
                break;
            case 'this_week':
                $activeQuery->whereBetween('date', [
                    $now->startOfWeek()->toDateTimeString(),
                    $now->endOfWeek()->toDateTimeString()
                ]);
                break;
            case 'this_month':
                $activeQuery->whereMonth('date', $now->month)
                      ->whereYear('date', $now->year);
                break;
            case 'upcoming':
                $activeQuery->where('date', '>=', $now->toDateTimeString());
                break;
            case 'past':
                $activeQuery->where('date', '<', $now->toDateTimeString());
                break;
        }

        // Apply sorting to active events
        switch ($sort) {
            case 'date_desc':
                $activeQuery->orderBy('date', 'desc');
                break;
            case 'title_asc':
                $activeQuery->orderBy('title', 'asc');
                break;
            case 'title_desc':
                $activeQuery->orderBy('title', 'desc');
                break;
            default: // date_asc
                $activeQuery->orderBy('date', 'asc');
                break;
        }

        // For completed events, always sort by date descending (most recent first)
        $completedQuery->orderBy('date', 'desc');

        // Paginate active events
        $perPage = $request->query('per_page', 10);
        $activeEvents = $activeQuery->paginate($perPage);
        $completedEvents = $completedQuery->take(10)->get();

        // Debug: Log the query results
        \Log::info('EventController@index - Query Results', [
            'active_events_count' => $activeEvents->count(),
            'completed_events_count' => $completedEvents->count(),
            'sql' => [
                'active' => $activeQuery->toSql(),
                'completed' => $completedQuery->toSql()
            ]
        ]);

        // Get all chapters for the filter dropdown
        $chapters = \App\Models\Chapter::orderBy('name')->get();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('events.partials.events-table', [
                    'events' => $activeEvents,
                    'user' => $user
                ])->render(),
                'pagination' => $activeEvents->links()->toHtml(),
                'count' => $activeEvents->count(),
                'completed_count' => $completedEvents->count()
            ]);
        }

        return view('events.index', [
            'events' => $activeEvents,
            'completedEvents' => $completedEvents,
            'search' => $search,
            'status' => $status,
            'sort' => $sort,
            'dateRange' => $dateRange,
            'chapter_id' => $chapter_id,
            'chapters' => $chapters,
            'role' => $user->role
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
       
        $chaptersQuery = \App\Models\Chapter::orderBy('name');
        $autoSelectChapter = null;
        $hideChapterSelection = false;
        
        if (auth()->check() && strtolower(auth()->user()->role) === 'leader') {
            $user = auth()->user();
            $leaderChapterIds = $user->ledChapters()->pluck('id')->toArray();
            
            // Include preferred chapter if exists
            if ($user->preferred_chapter_id) {
                $leaderChapterIds[] = $user->preferred_chapter_id;
            }
            
            // Remove duplicates
            $leaderChapterIds = array_unique($leaderChapterIds);
            
            if (!empty($leaderChapterIds)) {
                $chaptersQuery->whereIn('id', $leaderChapterIds);
                
                // Hide chapter selection for leaders and auto-select their first chapter
                $hideChapterSelection = true;
                $autoSelectChapter = $leaderChapterIds[0];
            }
        }
        
        $chapters = $chaptersQuery->get();
        
        return view('events.create', compact('chapters', 'autoSelectChapter', 'hideChapterSelection'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->check() || !in_array(auth()->user()->role, ['Admin', 'Leader'])) {
            abort(403, 'Unauthorized');
        }
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'time' => 'required|string',
            'location' => 'required|string|max:255',
            'status' => 'required|in:upcoming,ongoing,completed',
            'chapter_id' => 'required|exists:chapters,id',
        ]);

        if (auth()->check() && strtolower(auth()->user()->role) === 'leader') {
            $user = auth()->user();
            $leaderChapterIds = $user->ledChapters()->pluck('id')->toArray();
            
            // Include user's preferred chapter if they have one
            $allowedChapterIds = $leaderChapterIds;
            if ($user->preferred_chapter_id) {
                $allowedChapterIds[] = $user->preferred_chapter_id;
            }
            
            if (!in_array($validated['chapter_id'], $allowedChapterIds)) {
                abort(403, 'You can only create events for your chapters or preferred chapter.');
            }
        }

        // Check for duplicate events
        $existingEvent = Event::where('title', $validated['title'])
            ->where('date', $validated['date'])
            ->where('time', $validated['time'])
            ->where('chapter_id', $validated['chapter_id'])
            ->first();

        if ($existingEvent) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['title' => 'An event with the same title, date, time, and chapter already exists.']);
        }

        Event::create($validated);

        return redirect()->route('events.index')
            ->with('success', 'Event created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        $user = auth()->user();
        
        // For members, check if they have access to this event
        if ($user->role === 'Member') {
            $hasAccess = false;
            
            // Check if member is part of the event's chapter
            if ($user->member && $user->member->chapter_id === $event->chapter_id) {
                $hasAccess = true;
            }
            
            // Check if member has preferred chapter access
            if (!$hasAccess && $user->preferred_chapter_id === $event->chapter_id) {
                $hasAccess = true;
            }
            
            if (!$hasAccess) {
                abort(403, 'You do not have access to this event.');
            }
            
            // If member has access, show the event details which will include check-in options
            $event->load([
                'attendance' => function($query) use ($user) {
                    $query->where('member_id', $user->member?->id)
                          ->with(['member' => function($q) {
                              $q->select('id', 'name');
                          }]);
                }
            ]);
            
            return view('events.show', [
                'event' => $event,
                'isMemberView' => true
            ]);
        }
        
        // For non-members, check admin/leader access
        if (!in_array($user->role, ['Admin', 'Leader'])) {
            abort(403, 'Access denied. This area is restricted to administrators and leaders only.');
        }
        
        // For admins/leaders, load all attendance records
        $event->load([
            'attendance' => function($query) {
                $query->with(['member' => function($q) {
                    $q->select('id', 'name');
                }]);
            }
        ]);
        
        return view('events.show', [
            'event' => $event,
            'isAdminView' => true
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        if (!auth()->check() || !in_array(auth()->user()->role, ['Admin', 'Leader'])) {
            abort(403, 'Unauthorized');
        }
        if (auth()->check() && strtolower(auth()->user()->role) === 'leader') {
            $user = auth()->user();
            $leaderChapterIds = $user->ledChapters()->pluck('id')->toArray();
            
            // Include preferred chapter if exists
            if ($user->preferred_chapter_id) {
                $leaderChapterIds[] = $user->preferred_chapter_id;
            }
            
            // Remove duplicates
            $leaderChapterIds = array_unique($leaderChapterIds);
            
            if (!empty($leaderChapterIds) && !in_array($event->chapter_id, $leaderChapterIds)) {
                abort(403, 'Access denied.');
            } elseif (empty($leaderChapterIds)) {
                abort(403, 'Access denied.');
            }
        }
        
        $chaptersQuery = \App\Models\Chapter::orderBy('name');
        
        if (auth()->check() && strtolower(auth()->user()->role) === 'leader') {
            $user = auth()->user();
            $leaderChapterIds = $user->ledChapters()->pluck('id');
            
            // Include user's preferred chapter if they have one
            $chapterIds = $leaderChapterIds->toArray();
            if ($user->preferred_chapter_id && !in_array($user->preferred_chapter_id, $chapterIds)) {
                $chapterIds[] = $user->preferred_chapter_id;
            }
            
            $chaptersQuery->whereIn('id', $chapterIds);
        }
        $chapters = $chaptersQuery->get();

        return view('events.edit', compact('event', 'chapters'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        if (!auth()->check() || !in_array(auth()->user()->role, ['Admin', 'Leader'])) {
            abort(403, 'Unauthorized');
        }
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'time' => 'string',
            'location' => 'required|string|max:255',
            'status' => 'required|in:upcoming,ongoing,completed',
            'chapter_id' => 'required|exists:chapters,id',
        ]);

        if (auth()->check() && strtolower(auth()->user()->role) === 'leader') {
            $user = auth()->user();
            $leaderChapterIds = $user->ledChapters()->pluck('id')->toArray();
            
            // Include user's preferred chapter if they have one
            if ($user->preferred_chapter_id) {
                $leaderChapterIds[] = $user->preferred_chapter_id;
            }
            
            // Remove duplicates
            $leaderChapterIds = array_unique($leaderChapterIds);
            
            if (!in_array($validated['chapter_id'], $leaderChapterIds)) {
                abort(403, 'You can only update events for your chapters or preferred chapter.');
            }
        }

        $event->update($validated);

        return redirect()->route('events.index')
            ->with('success', 'Event updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        if (!auth()->check() || !in_array(auth()->user()->role, ['Admin', 'Leader'])) {
            abort(403, 'Unauthorized');
        }
        if (auth()->check() && strtolower(auth()->user()->role) === 'leader') {
            $user = auth()->user();
            $leaderChapterIds = $user->ledChapters()->pluck('id')->toArray();
            
            // Include preferred chapter if exists
            if ($user->preferred_chapter_id) {
                $leaderChapterIds[] = $user->preferred_chapter_id;
            }
            
            // Remove duplicates
            $leaderChapterIds = array_unique($leaderChapterIds);
            
            if (!empty($leaderChapterIds) && !in_array($event->chapter_id, $leaderChapterIds)) {
                abort(403, 'Access denied.');
            } elseif (empty($leaderChapterIds)) {
                abort(403, 'Access denied.');
            }
        }
        $event->delete();

        return redirect()->route('events.index')
            ->with('success', 'Event deleted successfully.');
    }

    /**
     * Display the QR scanner for event check-in
     */
    public function scan(Event $event)
    {
        $user = auth()->user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        $member = $user->member;
        
        if (!$member) {
            return redirect()->route('events.index')
                ->with('error', 'Member profile not found. Please contact an administrator.');
        }
        
        // For members, just verify they have a valid member profile
        if ($user->role === 'Member') {
            $hasCheckedIn = $this->hasCheckedIn($event, $member->id);
            
            return view('events.scan', [
                'event' => $event,
                'hasCheckedIn' => $hasCheckedIn,
                'checkInTime' => $hasCheckedIn ? 
                    \DB::table('attendance')
                        ->where('event_id', $event->id)
                        ->where('member_id', $member->id)
                        ->value('created_at') : null,
                'member' => $member
            ]);
        }
        
        // For Admins and Leaders, maintain the existing chapter access checks
        $hasAccess = false;
        
        // Check if member is part of the event's chapter
        if ($member->chapter_id === $event->chapter_id) {
            $hasAccess = true;
        }
        
        // Check if member has preferred chapter access
        if (!$hasAccess && $user->preferred_chapter_id === $event->chapter_id) {
            $hasAccess = true;
        }
        
        if (!$hasAccess) {
            return redirect()->route('events.show', $event)
                ->with('error', 'You do not have access to this event.');
        }
        
        $hasCheckedIn = $this->hasCheckedIn($event, $member->id);
        
        return view('events.scan', [
            'event' => $event,
            'hasCheckedIn' => $hasCheckedIn,
            'checkInTime' => $hasCheckedIn ? 
                \DB::table('attendance')
                    ->where('event_id', $event->id)
                    ->where('member_id', $member->id)
                    ->value('created_at') : null,
            'member' => $member
        ]);
    }
    
    /**
     * Handle event check-in via QR code or manual check-in
     */
    public function checkIn(Request $request, Event $event)
    {
        $qrData = $request->input('qr_data');
        
        if (empty($qrData)) {
            return response()->json([
                'success' => false,
                'message' => 'QR code data is required.'
            ], 400);
        }

        // Try to find member by QR code data
        $member = \App\Models\Member::where('qr_code', $qrData)->first();
        
        if (!$member) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid QR code. Please make sure you\'re using a valid check-in code.'
            ], 404);
        }

        // If user is authenticated, verify they have access to this member's data
        $user = auth()->user();
        if ($user) {
            // For members, they can only check themselves in
            if ($user->role === 'Member' && $user->member_id !== $member->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'You can only check in with your own QR code.'
                ], 403);
            }
            
            // For leaders/admins, check chapter access
            if (in_array($user->role, ['Leader', 'Admin'])) {
                $hasAccess = $member->chapter_id === $user->preferred_chapter_id || 
                            $user->chapters->contains('id', $member->chapter_id);
                
                if (!$hasAccess) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You do not have access to check in members from this chapter.'
                    ], 403);
                }
            }
        }

        // Check if member is already checked in
        $existingCheckIn = \DB::table('attendance')
            ->where('event_id', $event->id)
            ->where('member_id', $member->id)
            ->first();

        if ($existingCheckIn) {
            return response()->json([
                'success' => false,
                'message' => 'This member has already checked in to this event.',
                'check_in_time' => $existingCheckIn->created_at
            ]);
        }

        // Record the attendance
        $attendanceId = \DB::table('attendance')->insertGetId([
            'event_id' => $event->id,
            'member_id' => $member->id,
            'status' => 'present',
            'notes' => $request->input('notes', null),
            'attendance_date' => now()->toDateString(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        if (!$attendanceId) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to record attendance. Please try again.'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Check-in successful!',
            'member_name' => $member->first_name . ' ' . $member->last_name,
            'check_in_time' => now()
        ]);
    }

    /**
     * Check if the current member has already checked in to the event
     */
    private function hasCheckedIn(Event $event, $memberId)
    {
        return \DB::table('attendance')
            ->where('event_id', $event->id)
            ->where('member_id', $memberId)
            ->where('status', 'present')
            ->exists();
    }
}
