<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use App\Services\AuditService;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        // Auto-complete events that have passed their date or end time
        try {
            $now = now();
            $today = $now->toDateString();
            
            // Get all non-completed events
            $eventsToUpdate = \DB::table('events')
                ->whereNotIn('status', ['completed', 'cancelled'])
                ->get();
            
            $updatedCount = 0;
            foreach ($eventsToUpdate as $event) {
                $shouldComplete = false;
                $eventDate = $event->date instanceof \Carbon\Carbon ? $event->date->format('Y-m-d') : $event->date;
                
                // Check if event date has passed
                if ($eventDate < $today) {
                    $shouldComplete = true;
                }
                // If event is today, check if end time has passed
                elseif ($eventDate == $today && !empty($event->end_time)) {
                    $dateString = $eventDate;
                    $endTime = $event->end_time;
                    
                    // Handle both H:i and H:i:s formats
                    if (strlen($endTime) == 5) { // H:i format
                        $endDateTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $dateString . ' ' . $endTime);
                    } else { // H:i:s format
                        $endDateTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $dateString . ' ' . $endTime);
                    }
                    
                    if ($now->greaterThan($endDateTime)) {
                        $shouldComplete = true;
                    }
                }
                
                if ($shouldComplete) {
                    \DB::table('events')
                        ->where('id', $event->id)
                        ->update(['status' => 'completed', 'updated_at' => $now]);
                    $updatedCount++;
                }
            }
            
            if ($updatedCount > 0) {
                \Log::info("Auto-completed {$updatedCount} events due to past date or end time");
            }
        } catch (\Throwable $e) {
            \Log::warning('Failed to auto-complete events', ['error' => $e->getMessage()]);
        }
        $search = $request->query('search');
        $status = $request->query('status', 'all');
        $sort = $request->query('sort', 'date_asc');
        $dateRange = $request->query('date_range', 'all');
        $chapter_id = $request->query('chapter_id');
        $showArchived = $request->query('archived', false);

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
        } elseif ($status === 'all') {
            // Show all active events (upcoming and ongoing) in main view
            $activeQuery->whereIn('status', ['upcoming', 'ongoing']);
            $completedQuery->whereIn('status', ['completed', 'cancelled']);
        } else {
            // Default: Show only upcoming events in main view
            $activeQuery->where('status', 'upcoming');
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

        // Apply archive filter
        if ($showArchived) {
            // Show only archived events
            $activeQuery->where('archived', true);
            $completedQuery->where('archived', true);
        } else {
            // Show only non-archived events
            $activeQuery->where('archived', false);
            $completedQuery->where('archived', false);
        }

        // Apply date range filter to both active and completed events
        $now = now();
        $today = $now->toDateString();
        
        foreach ([$activeQuery, $completedQuery] as $query) {
            switch ($dateRange) {
                case 'today':
                    $query->whereDate('date', $today);
                    break;
                case 'this_week':
                    $query->whereBetween('date', [
                        $now->startOfWeek()->toDateString(),
                        $now->endOfWeek()->toDateString()
                    ]);
                    break;
                case 'this_month':
                    $query->whereMonth('date', $now->month)
                          ->whereYear('date', $now->year);
                    break;
                case 'upcoming':
                    $query->where('date', '>=', $today);
                    break;
                case 'past':
                    $query->where('date', '<', $today);
                    break;
                case 'all':
                default:
                    // No date filtering for 'all' or default
                    break;
            }
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
            'role' => $user->role,
            'showArchived' => $showArchived
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:time',
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

        // Auto-complete if event date has passed or end time has passed
        $eventDate = $validated['date'] instanceof \Carbon\Carbon ? $validated['date']->format('Y-m-d') : $validated['date'];
        $today = now()->toDateString();
        
        if ($eventDate < $today) {
            // Event date has passed
            $validated['status'] = 'completed';
            \Log::info('Event auto-completed due to past date');
        } elseif ($eventDate == $today && !empty($validated['end_time'])) {
            // Event is today, check if end time has passed
            $dateString = $eventDate;
            $endTime = $validated['end_time'];
            
            // Handle both H:i and H:i:s formats
            if (strlen($endTime) == 5) { // H:i format
                $endAt = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $dateString . ' ' . $endTime);
            } else { // H:i:s format
                $endAt = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $dateString . ' ' . $endTime);
            }
            
            $now = now();
            
            // Debug logging
            \Log::info('Event creation auto-completion check', [
                'event_date' => $validated['date'],
                'event_time' => $validated['time'],
                'end_time' => $validated['end_time'],
                'end_datetime' => $endAt->toDateTimeString(),
                'current_time' => $now->toDateTimeString(),
                'is_past' => $now->greaterThan($endAt)
            ]);
            
            if ($now->greaterThan($endAt)) {
                $validated['status'] = 'completed';
                \Log::info('Event auto-completed due to past end time');
            }
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            
            // Ensure the directory exists
            $directory = storage_path('app/public/event-images');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            
            // Move the file
            $image->move($directory, $imageName);
            $validated['image'] = 'event-images/' . $imageName;
        }

        $event = Event::create($validated);

        // Audit: event created
        $user = auth()->user();
        $chapter = $event->chapter ?? null;
        AuditService::log(
            'event_created',
            'Event created',
            [
                'event_id' => $event->id,
                'title' => $event->title,
                'date' => (string) $event->date,
                'chapter_id' => $event->chapter_id,
                'chapter_name' => $chapter->name ?? null,
            ],
            $user?->id
        );

        // Send notification to members of the same chapter
        if (in_array(auth()->user()->role, ['Admin', 'Leader'])) {
            NotificationService::notifyEventCreated($event, auth()->user());
        }

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
        // Disallow editing completed events
        if ($event->status === 'completed') {
            return redirect()->route('events.index')->with('error', 'Completed events cannot be edited.');
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:time',
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

        // Auto-complete if event date has passed or end time has passed
        $eventDate = $validated['date'] instanceof \Carbon\Carbon ? $validated['date']->format('Y-m-d') : $validated['date'];
        $today = now()->toDateString();
        
        if ($eventDate < $today) {
            // Event date has passed
            $validated['status'] = 'completed';
        } elseif ($eventDate == $today && !empty($validated['end_time'])) {
            // Event is today, check if end time has passed
            $dateString = $eventDate;
            $endTime = $validated['end_time'];
            
            // Handle both H:i and H:i:s formats
            if (strlen($endTime) == 5) { // H:i format
                $endAt = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $dateString . ' ' . $endTime);
            } else { // H:i:s format
                $endAt = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $dateString . ' ' . $endTime);
            }
            
            if (now()->greaterThan($endAt)) {
                $validated['status'] = 'completed';
            }
        }

        // Prevent editing a completed event back to non-completed
        if ($event->status === 'completed') {
            $validated['status'] = 'completed';
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($event->image) {
                $oldImagePath = storage_path('app/public/' . $event->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            
            // Ensure the directory exists
            $directory = storage_path('app/public/event-images');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            
            // Move the file
            $image->move($directory, $imageName);
            $validated['image'] = 'event-images/' . $imageName;
        }

        // Capture before state
        $before = [
            'title' => $event->title,
            'date' => (string) $event->date,
            'time' => $event->time,
            'end_time' => $event->end_time,
            'location' => $event->location,
            'status' => $event->status,
            'chapter_id' => $event->chapter_id,
        ];

        $event->update($validated);

        // Audit: event updated
        $user = auth()->user();
        $chapter = $event->chapter ?? null;
        AuditService::log(
            'event_updated',
            'Event updated',
            [
                'event_id' => $event->id,
                'chapter_id' => $event->chapter_id,
                'chapter_name' => $chapter->name ?? null,
                'before' => $before,
                'after' => [
                    'title' => $event->title,
                    'date' => (string) $event->date,
                    'time' => $event->time,
                    'end_time' => $event->end_time,
                    'location' => $event->location,
                    'status' => $event->status,
                    'chapter_id' => $event->chapter_id,
                ],
            ],
            $user?->id
        );

        // Send notification to members of the same chapter
        if (in_array(auth()->user()->role, ['Admin', 'Leader'])) {
            NotificationService::notifyEventUpdated($event, auth()->user());
        }

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
        // Check if event is already archived (permanent deletion)
        if ($event->archived) {
            // Permanently delete the event and all related data
            $event->attendance()->delete(); // Delete all attendance records
            $event->qrCodes()->delete(); // Delete all QR codes
            $event->delete(); // Delete the event itself
            
            // Audit: event permanently deleted
            $user = auth()->user();
            $chapter = $event->chapter ?? null;
            AuditService::log(
                'event_deleted',
                'Event permanently deleted',
                [
                    'event_id' => $event->id,
                    'title' => $event->title,
                    'chapter_id' => $event->chapter_id,
                    'chapter_name' => $chapter->name ?? null,
                ],
                $user?->id
            );

            return redirect()->route('events.index', ['archived' => true])
                ->with('success', 'Event permanently deleted successfully.');
        } else {
            // Archive the event instead of deleting
            $event->update(['archived' => true]);

            // Audit: event archived
            $user = auth()->user();
            $chapter = $event->chapter ?? null;
            AuditService::log(
                'event_archived',
                'Event archived',
                [
                    'event_id' => $event->id,
                    'title' => $event->title,
                    'chapter_id' => $event->chapter_id,
                    'chapter_name' => $chapter->name ?? null,
                ],
                $user?->id
            );

            return redirect()->route('events.index')
                ->with('success', 'Event archived successfully.');
        }
    }

    /**
     * Restore an archived event
     */
    public function restore(Event $event)
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
        
        // Restore the event
        $event->update(['archived' => false]);

        // Audit: event restored
        $user = auth()->user();
        $chapter = $event->chapter ?? null;
        AuditService::log(
            'event_restored',
            'Event restored',
            [
                'event_id' => $event->id,
                'title' => $event->title,
                'chapter_id' => $event->chapter_id,
                'chapter_name' => $chapter->name ?? null,
            ],
            $user?->id
        );

        return redirect()->route('events.index', ['archived' => true])
            ->with('success', 'Event restored successfully.');
    }

    /**
     * Display member's personal QR code for event check-in
     */
    public function memberQrCode(Event $event)
    {
        $user = auth()->user();
        
        // Only members can access this page
        if ($user->role !== 'Member') {
            abort(403, 'Access denied. This page is for members only.');
        }
        
        // Check if member has access to this event
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
        
        // Check if member is already checked in
        $hasCheckedIn = $this->hasCheckedIn($event, $user->member->id);
        
        return view('events.member-qr', [
            'event' => $event,
            'member' => $user->member,
            'hasCheckedIn' => $hasCheckedIn,
            'checkInTime' => $hasCheckedIn ? 
                \DB::table('attendance')
                    ->where('event_id', $event->id)
                    ->where('member_id', $user->member->id)
                    ->value('created_at') : null,
        ]);
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
