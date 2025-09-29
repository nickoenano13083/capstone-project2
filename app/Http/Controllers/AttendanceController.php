<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Event;
use App\Models\Member;
use App\Models\QrCode;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $user = auth()->user();
        $chapterId = $request->input('chapter_id');
        
        // Debug: Log user info
        \Log::info('User Info', [
            'user_id' => $user->id,
            'role' => $user->role,
            'member_id' => $user->member?->id
        ]);
        
        // Get events with attendance data
        $eventsQuery = Event::with(['attendance' => function($query) use ($user) {
            // For members, only load their own attendance records
            if ($user->role === 'Member') {
                $query->whereHas('member', function($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            }
            $query->with('member');
        }])
        ->whereHas('attendance', function($query) use ($user) {
            // For members, only show events where they have an attendance record
            if ($user->role === 'Member') {
                $query->whereHas('member', function($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            }
        })
        ->when($search, function($query) use ($search, $user) {
            $query->where(function($q) use ($search, $user) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
                
                // Only search member names for non-members (admins/leaders)
                if ($user->role !== 'Member') {
                    $q->orWhereHas('attendance.member', function($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
                }
            });
        });

        // Apply chapter filter if selected
        if (!empty($chapterId) && $chapterId !== 'all') {
            $eventsQuery->where('chapter_id', $chapterId);
        }

        // Apply role-based filtering for non-members
        if ($user->role === 'Leader') {
            $leaderChapterIds = $user->ledChapters()->pluck('id')->toArray();
            
            // Include preferred chapter if exists
            if ($user->preferred_chapter_id) {
                $leaderChapterIds[] = $user->preferred_chapter_id;
            }
            
            // Remove duplicates and filter events
            $leaderChapterIds = array_unique($leaderChapterIds);
            
            if (!empty($leaderChapterIds)) {
                $eventsQuery->whereIn('chapter_id', $leaderChapterIds);
            } else {
                $eventsQuery->whereRaw('1 = 0');
            }
        }

        // Get chapters for the filter dropdown
        $chapters = \App\Models\Chapter::orderBy('name')->get();

        $events = $eventsQuery->orderBy('date', 'desc')->paginate(10);
        
        // Debug: Log the events and their attendance data
        \Log::info('Events with attendance', [
            'events_count' => $events->count(),
            'events' => $events->map(function($event) {
                return [
                    'event_id' => $event->id,
                    'title' => $event->title,
                    'attendance_count' => $event->attendance->count(),
                    'attendance' => $event->attendance->map(function($att) {
                        return [
                            'id' => $att->id,
                            'member_id' => $att->member_id,
                            'status' => $att->status,
                            'time_in' => $att->time_in,
                            'member' => $att->member ? ['id' => $att->member->id, 'name' => $att->member->name] : null
                        ];
                    })
                ];
            })
        ]);

        return view('attendance.index', [
            'events' => $events,
            'search' => $search,
            'chapters' => $chapters,
            'selectedChapter' => $chapterId
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->check() || !in_array(auth()->user()->role, ['Admin', 'Leader'])) {
            abort(403, 'Unauthorized');
        }
        $membersQuery = Member::orderBy('name');
        $eventsQuery = Event::orderBy('date', 'desc');
        if (auth()->check() && auth()->user()->role === 'Leader') {
            $leaderChapterIds = auth()->user()->ledChapters()->pluck('id');
            $membersQuery->whereIn('chapter_id', $leaderChapterIds);
            $eventsQuery->whereIn('chapter_id', $leaderChapterIds);
        }
        $members = $membersQuery->get();
        $events = $eventsQuery->get();
        
        return view('attendance.create', compact('members', 'events'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->check() || !in_array(auth()->user()->role, ['Admin', 'Leader'])) {
            abort(403, 'Unauthorized');
        }
        $request->validate([
            'member_id' => 'required|exists:members,id',
            'event_id' => 'required|exists:events,id',
            'status' => 'required|in:present,absent',
            'notes' => 'nullable|string',
        ]);

        // Check if attendance already exists for this member and event on the same date
        $existingAttendance = Attendance::where('member_id', $request->member_id)
            ->where('event_id', $request->event_id)
            ->whereDate('attendance_date', $request->input('attendance_date', today()))
            ->first();

        if ($existingAttendance) {
            return back()->withErrors(['member_id' => 'Attendance already recorded for this member on this date.']);
        }

        Attendance::create([
            'member_id' => $request->member_id,
            'event_id' => $request->event_id,
            'attendance_date' => $request->input('attendance_date', today()),
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return redirect()->route('attendance.index')->with('success', 'Attendance recorded successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $attendanceRecord = Attendance::with(['member', 'event', 'qrCode', 'scannedBy'])->findOrFail($id);
        $user = auth()->user();
        
        // Access control based on user role
        if ($user->role === 'Member') {
            // Members can only view their own attendance records
            if (!$user->member || $attendanceRecord->member_id !== $user->member->id) {
                abort(403, 'You can only view your own attendance records.');
            }
        } elseif ($user->role === 'Leader') {
            // Leaders can view attendance records from their chapters
            $leaderChapterIds = $user->ledChapters()->pluck('id')->toArray();
            
            // Include preferred chapter if exists
            if ($user->preferred_chapter_id) {
                $leaderChapterIds[] = $user->preferred_chapter_id;
            }
            
            $leaderChapterIds = array_unique($leaderChapterIds);
            
            if (!empty($leaderChapterIds) && !in_array($attendanceRecord->member->chapter_id, $leaderChapterIds)) {
                abort(403, 'Access denied.');
            }
        }
        // Admins can view all records (no additional restrictions)
        
        return view('attendance.show', compact('attendanceRecord'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (!auth()->check() || !in_array(auth()->user()->role, ['Admin', 'Leader'])) {
            abort(403, 'Unauthorized');
        }
        $attendanceRecord = Attendance::findOrFail($id);
        $user = auth()->user();
        
        if ($user->role === 'Leader') {
            $leaderChapterIds = $user->ledChapters()->pluck('id')->toArray();
            
            // Include preferred chapter if exists
            if ($user->preferred_chapter_id) {
                $leaderChapterIds[] = $user->preferred_chapter_id;
            }
            
            $leaderChapterIds = array_unique($leaderChapterIds);
            
            if (!in_array($attendanceRecord->member->chapter_id, $leaderChapterIds)) {
                abort(403, 'Access denied.');
            }
        }

        $membersQuery = Member::orderBy('name');
        $eventsQuery = Event::orderBy('date', 'desc');
        
        if ($user->role === 'Leader') {
            $membersQuery->whereIn('chapter_id', $leaderChapterIds);
            $eventsQuery->whereIn('chapter_id', $leaderChapterIds);
        }
        
        $members = $membersQuery->get();
        $events = $eventsQuery->get();
        
        return view('attendance.edit', compact('attendanceRecord', 'members', 'events'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (!auth()->check() || !in_array(auth()->user()->role, ['Admin', 'Leader'])) {
            abort(403, 'Unauthorized');
        }
        
        $attendanceRecord = Attendance::findOrFail($id);
        $user = auth()->user();
        
        if ($user->role === 'Leader') {
            $leaderChapterIds = $user->ledChapters()->pluck('id')->toArray();
            
            // Include preferred chapter if exists
            if ($user->preferred_chapter_id) {
                $leaderChapterIds[] = $user->preferred_chapter_id;
            }
            
            $leaderChapterIds = array_unique($leaderChapterIds);
            
            if (!in_array($attendanceRecord->member->chapter_id, $leaderChapterIds)) {
                abort(403, 'Access denied.');
            }
        }
        
        $request->validate([
            'member_id' => 'required|exists:members,id',
            'event_id' => 'required|exists:events,id',
            'status' => 'required|in:present,absent',
            'notes' => 'nullable|string',
        ]);

        // Check if attendance already exists for this member and event on the same date
        $existingAttendance = Attendance::where('member_id', $request->member_id)
            ->where('event_id', $request->event_id)
            ->where('id', '!=', $id)
            ->whereDate('attendance_date', $request->input('attendance_date', $attendanceRecord->attendance_date))
            ->first();

        if ($existingAttendance) {
            return back()->withErrors(['member_id' => 'Attendance already recorded for this member on this date.']);
        }

        $attendanceRecord->update([
            'member_id' => $request->member_id,
            'event_id' => $request->event_id,
            'attendance_date' => $request->input('attendance_date', $attendanceRecord->attendance_date),
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return redirect()->route('attendance.index')->with('success', 'Attendance updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!auth()->check() || !in_array(auth()->user()->role, ['Admin', 'Leader'])) {
            abort(403, 'Unauthorized');
        }
        
        $attendanceRecord = Attendance::findOrFail($id);
        $user = auth()->user();
        
        if ($user->role === 'Leader') {
            $leaderChapterIds = $user->ledChapters()->pluck('id')->toArray();
            
            // Include preferred chapter if exists
            if ($user->preferred_chapter_id) {
                $leaderChapterIds[] = $user->preferred_chapter_id;
            }
            
            $leaderChapterIds = array_unique($leaderChapterIds);
            
            if (!in_array($attendanceRecord->member->chapter_id, $leaderChapterIds)) {
                abort(403, 'Access denied.');
            }
        }
        
        $attendanceRecord->delete();
        
        return redirect()->route('attendance.index')
            ->with('success', 'Attendance record deleted successfully!');
    }

    /**
     * Get real-time attendance data for dashboard
     */
    public function getRealTimeAttendance(Request $request)
    {
        $event_id = $request->input('event_id');
        $date = $request->input('date', today());

        $query = Attendance::with(['member', 'event'])
            ->whereDate('attendance_date', $date);

        if ($event_id) {
            $query->where('event_id', $event_id);
        }

        $attendances = $query->orderBy('scanned_at', 'desc')->get();

        return response()->json([
            'attendances' => $attendances,
            'total_present' => $attendances->where('status', 'present')->count(),
            'total_absent' => $attendances->where('status', 'absent')->count(),
        ]);
    }

    /**
     * Show QR code management for an event
     */
    public function qrManagement(Event $event)
    {
        if (!in_array(auth()->user()->role, ['Admin', 'Leader'])) {
            abort(403, 'Unauthorized');
        }

        $activeQrCode = $event->activeQrCode();
        $qrCodes = $event->qrCodes()->orderBy('created_at', 'desc')->get();
        
        return view('attendance.qr-management', compact('event', 'activeQrCode', 'qrCodes'));
    }
}
