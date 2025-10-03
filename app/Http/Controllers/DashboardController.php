<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Event;
use App\Models\Theme;
use App\Models\DashboardImage;
use App\Models\BibleVerse;
use App\Models\Attendance;
use App\Models\Chapter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Services\AuditService;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $unreadMessages = \App\Models\Message::where('is_read', false)->latest()->take(5)->get();
        $unreadCount = $unreadMessages->count();

        $pendingPrayerRequests = collect();
        $pendingCount = 0;
        $answeredPrayerRequests = collect();
        $answeredCount = 0;

        if ($user->role === 'Admin') {
            $pendingPrayerRequests = \App\Models\PrayerRequest::where('status', 'pending')->latest()->take(5)->get();
            $pendingCount = $pendingPrayerRequests->count();
        } elseif ($user->role === 'Member') {
            $answeredPrayerRequests = \App\Models\PrayerRequest::where('user_id', $user->id)
                ->where('status', 'answered')
                ->where('notified', false)
                ->latest()->take(5)->get();
            $answeredCount = $answeredPrayerRequests->count();
        }

        // Calculate total notifications
        $totalNotifications = $unreadCount + $pendingCount + $answeredCount;

        $search = request('search');
        $members = collect();
        $events = collect();
        $chapters = collect();
        $prayerRequests = collect();
        
        if ($search) {
            // Search members
            $members = \App\Models\Member::where(function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
            })->limit(10)->get();
            
            // Search events
            $events = \App\Models\Event::where(function($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhere('location', 'like', "%{$search}%");
            })->limit(10)->get();
            
            // Search chapters
            $chapters = \App\Models\Chapter::where('name', 'like', "%{$search}%")
                ->limit(10)->get();
            
            // Search prayer requests (only for admins or if user has prayer requests)
            if ($user->role === 'Admin') {
                $prayerRequests = \App\Models\PrayerRequest::where(function($query) use ($search) {
                    $query->where('request', 'like', "%{$search}%")
                          ->orWhere('status', 'like', "%{$search}%")
                          ->orWhereHas('member', function($q) use ($search) {
                              $q->where('name', 'like', "%{$search}%");
                          })
                          ->orWhereHas('user', function($q) use ($search) {
                              $q->where('name', 'like', "%{$search}%");
                          });
                })->limit(10)->get();
            } elseif ($user->role === 'Member') {
                $prayerRequests = \App\Models\PrayerRequest::where('user_id', $user->id)
                    ->where(function($query) use ($search) {
                        $query->where('request', 'like', "%{$search}%")
                              ->orWhere('status', 'like', "%{$search}%");
                    })->limit(10)->get();
            }
        }

        // Get analytics data
        $totalMembers = \App\Models\Member::count();
        $totalEvents = \App\Models\Event::count();
        
        $upcomingEvents = \App\Models\Event::where('date', '>=', now()->startOfMonth())
            ->where('date', '<=', now()->endOfMonth())
            ->count();
            
        // Calculate attendance rate for last month
        $lastMonthStart = now()->subMonth()->startOfMonth();
        $lastMonthEnd = now()->subMonth()->endOfMonth();
        
        $totalEventsLastMonth = \App\Models\Event::whereBetween('date', [$lastMonthStart, $lastMonthEnd])->count();
        $totalAttendance = \App\Models\Attendance::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->count();
        
        $attendanceRate = $totalEventsLastMonth > 0 
            ? round(($totalAttendance / ($totalEventsLastMonth * $totalMembers)) * 100, 1) 
            : 0;
            
        // Get prayer requests for current month
        $monthlyPrayerRequests = \App\Models\PrayerRequest::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Get a random Bible verse for the prayer banner
        $bibleVerse = BibleVerse::latest()->first();
        $prayerVerse = $bibleVerse ? $bibleVerse->verse : null;
        $prayerReference = $bibleVerse ? $bibleVerse->reference : null;
        
        // Fetch latest announcements
        $announcements = \App\Models\Announcement::with('user')->latest()->take(5)->get();
        
        // Analytics data
        $analyticsData = $this->getAnalyticsData();
        
        $newSignups = \App\Models\Member::where('join_date', '>=', now()->subDays(30))
            ->orderBy('join_date', 'desc')
            ->get();

        $totalAdmins = \App\Models\User::where('role', 'Admin')->count();
        $monthlyAttendanceCount = \App\Models\Attendance::whereYear('attendance_date', now()->year)
            ->whereMonth('attendance_date', now()->month)
            ->where('status', 'present')
            ->count();
        $totalMessages = 6;
        // Events widget: filter for Members by their chapter, otherwise show general upcoming
        if ($user->role === 'Member') {
            $memberChapterId = $user->member->chapter_id ?? $user->preferred_chapter_id;
            $eventsForWidget = \App\Models\Event::query()
                ->when($memberChapterId, function ($query) use ($memberChapterId) {
                    $query->where('chapter_id', $memberChapterId);
                })
                ->where(function ($query) {
                    // upcoming by date if available; fallback to status
                    $query->whereDate('date', '>=', now()->toDateString())
                          ->orWhere('status', 'upcoming');
                })
                ->orderBy('date', 'asc')
                ->take(2)
                ->get();
            // Next service for member's chapter
            $nextService = \App\Models\Event::query()
                ->when($memberChapterId, function ($query) use ($memberChapterId) {
                    $query->where('chapter_id', $memberChapterId);
                })
                ->whereDate('date', '>=', now()->toDateString())
                ->orderBy('date', 'asc')
                ->first();
        } else {
            $eventsForWidget = \App\Models\Event::where(function ($query) {
                    $query->whereDate('date', '>=', now()->toDateString())
                          ->orWhere('status', 'upcoming');
                })
                ->orderBy('date', 'asc')
                ->take(2)
                ->get();
            $nextService = null;
        }
        $theme = Theme::where('month', now()->month)->where('year', now()->year)->first();
        $themeImage = $theme ? asset('storage/' . $theme->image_path) : null;
        $dashboardImage = DashboardImage::latest()->first();
        $dashboardImageUrl = $dashboardImage ? asset('storage/' . $dashboardImage->image_path) : null;

        // Get gender statistics
        $genderStats = [
            'male' => \App\Models\Member::where('gender', 'Male')->count(),
            'female' => \App\Models\Member::where('gender', 'Female')->count(),
        ];

        // Get age group statistics
        $ageGroups = [
            '3-12' => 0,
            '13-25' => 0,
            '26-59' => 0,
            '60+' => 0
        ];
        $membersWithAge = \App\Models\Member::whereNotNull('birthday')->get();
        foreach ($membersWithAge as $member) {
            $age = $member->birthday->age;
            if ($age >= 3 && $age <= 12) {
                $ageGroups['3-12']++;
            } elseif ($age >= 13 && $age <= 25) {
                $ageGroups['13-25']++;
            } elseif ($age >= 26 && $age <= 59) {
                $ageGroups['26-59']++;
            } else {
                $ageGroups['60+']++;
            }
        }

        // Get prayer request statistics
        $prayerStats = [
            'pending' => \App\Models\PrayerRequest::where('status', 'pending')->count(),
            'answered' => \App\Models\PrayerRequest::where('status', 'answered')->count(),
        ];

        return view('dashboard', compact(
            'totalMembers', 
            'totalEvents', 
            'totalMessages', 
            'eventsForWidget', 
            'themeImage', 
            'dashboardImage', 
            'prayerVerse', 
            'prayerReference',
            'dashboardImageUrl',
            'analyticsData',
            'upcomingEvents',
            'monthlyAttendanceCount',
            'unreadMessages',
            'pendingPrayerRequests',
            'pendingCount',
            'answeredPrayerRequests',
            'answeredCount',
            'members',
            'events',
            'chapters',
            'prayerRequests',
            'search',
            'newSignups',
            'totalAdmins',
            'announcements',
            'attendanceRate',
            'monthlyPrayerRequests',
            'totalNotifications',
            'genderStats',
            'ageGroups',
            'prayerStats',
            'nextService'
        ));
    }

    private function getAnalyticsData()
    {
        // Monthly attendance data for the last 6 months
        $monthlyAttendance = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthName = $date->format('M Y');
            $attendanceCount = Attendance::whereYear('attendance_date', $date->year)
                ->whereMonth('attendance_date', $date->month)
                ->where('status', 'present')
                ->count();
            
            $monthlyAttendance[] = [
                'month' => $monthName,
                'attendance' => $attendanceCount
            ];
        }

        // Member demographics
        $memberDemographics = [
            'gender' => [
                'male' => Member::where('gender', 'male')->count(),
                'female' => Member::where('gender', 'female')->count(),
                'other' => Member::where('gender', 'other')->orWhereNull('gender')->count(),
            ],
            'age_groups' => $this->getAgeGroups(),
            'new_members_this_month' => Member::whereMonth('join_date', now()->month)
                ->whereYear('join_date', now()->year)
                ->count(),
            'total_chapters' => Chapter::count(),
        ];

        // Top events by attendance
        $topEvents = Event::withCount(['attendance' => function($query) {
            $query->where('status', 'present');
        }])
        ->orderBy('attendance_count', 'desc')
        ->take(5)
        ->get()
        ->map(function($event) {
            return [
                'title' => $event->title,
                'attendance' => $event->attendance_count,
                'date' => $event->date->format('M d, Y')
            ];
        });

        // Weekly attendance trend (last 4 weeks)
        $weeklyAttendance = [];
        for ($i = 3; $i >= 0; $i--) {
            $weekStart = now()->subWeeks($i)->startOfWeek();
            $weekEnd = now()->subWeeks($i)->endOfWeek();
            $attendanceCount = Attendance::whereBetween('attendance_date', [$weekStart, $weekEnd])
                ->where('status', 'present')
                ->count();
            
            $weeklyAttendance[] = [
                'week' => 'Week ' . (4 - $i),
                'attendance' => $attendanceCount
            ];
        }

        // Chapter distribution
        $chapterDistribution = Chapter::withCount('members')
            ->orderBy('members_count', 'desc')
            ->take(5)
            ->get()
            ->map(function($chapter) {
                return [
                    'name' => $chapter->name,
                    'members' => $chapter->members_count
                ];
            });

        return [
            'monthly_attendance' => $monthlyAttendance,
            'demographics' => $memberDemographics,
            'top_events' => $topEvents,
            'weekly_attendance' => $weeklyAttendance,
            'chapter_distribution' => $chapterDistribution,
        ];
    }

    private function getAgeGroups()
    {
        $members = Member::whereNotNull('birthday')->get();
        $ageGroups = [
            '18-25' => 0,
            '26-35' => 0,
            '36-45' => 0,
            '46-55' => 0,
            '56-65' => 0,
            '65+' => 0
        ];

        foreach ($members as $member) {
            $age = $member->birthday->age;
            if ($age >= 18 && $age <= 25) {
                $ageGroups['18-25']++;
            } elseif ($age >= 26 && $age <= 35) {
                $ageGroups['26-35']++;
            } elseif ($age >= 36 && $age <= 45) {
                $ageGroups['36-45']++;
            } elseif ($age >= 46 && $age <= 55) {
                $ageGroups['46-55']++;
            } elseif ($age >= 56 && $age <= 65) {
                $ageGroups['56-65']++;
            } else {
                $ageGroups['65+']++;
            }
        }

        return $ageGroups;
    }

    public function updateBibleVerse(Request $request)
    {
        if (Auth::user()->role !== 'Admin') {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'verse' => 'required|string',
            'reference' => 'required|string|max:255',
        ]);

        // Capture previous verse for audit trail
        $previous = BibleVerse::find(1);

        BibleVerse::updateOrCreate(
            ['id' => 1], // Assuming we always update the same row
            [
                'verse' => $request->verse,
                'reference' => $request->reference,
            ]
        );

        // Audit log with chapter context
        $user = Auth::user();
        $chapter = $user->member->chapter ?? $user->preferredChapter ?? null;
        AuditService::log(
            'bible_verse_updated',
            'Bible verse updated on dashboard',
            [
                'chapter_id' => $chapter->id ?? null,
                'chapter_name' => $chapter->name ?? null,
                'verse_before' => $previous->verse ?? null,
                'verse_after' => $request->verse,
                'reference_before' => $previous->reference ?? null,
                'reference_after' => $request->reference,
            ],
            $user->id
        );

        return redirect()->route('dashboard')->with('success', 'Bible verse updated successfully.');
    }

    public function uploadDashboardImage(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'Admin') {
            abort(403);
        }

        try {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
                'caption' => 'nullable|string|max:255',
                'title' => 'nullable|string|max:255',
            ]);

            // Delete existing dashboard image if it exists
            $existingImage = DashboardImage::latest()->first();
            if ($existingImage) {
                // Delete the old image file
                if ($existingImage->image_path && Storage::disk('public')->exists($existingImage->image_path)) {
                    Storage::disk('public')->delete($existingImage->image_path);
                }
                $existingImage->delete();
            }

            // Store the new image
            $path = $request->file('image')->store('dashboard_images', 'public');
            
            // Create new dashboard image record
            $dashboardImage = DashboardImage::create([
                'image_path' => $path,
                'caption' => $request->input('caption'),
                'title' => $request->input('title'),
            ]);

            // Verify the image was stored correctly
            if (!Storage::disk('public')->exists($path)) {
                throw new \Exception('Image failed to store properly');
            }

            return redirect()->route('dashboard')->with('success', 'Dashboard image uploaded successfully.');
        } catch (\Exception $e) {
            \Log::error('Dashboard image upload failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to upload dashboard image. Please try again.');
        }
    }

    public function editDashboardImage(DashboardImage $dashboardImage)
    {
        if (!Auth::check() || Auth::user()->role !== 'Admin') {
            abort(403);
        }
        
        try {
            if (!$dashboardImage->image_path || !Storage::disk('public')->exists($dashboardImage->image_path)) {
                throw new \Exception('Dashboard image not found');
            }
            
            return view('dashboard', [
                'dashboardImage' => $dashboardImage,
                'editMode' => true,
                'totalMembers' => \App\Models\Member::count(),
                'totalEvents' => \App\Models\Event::count(),
                'totalMessages' => 6,
                'events' => \App\Models\Event::orderBy('date', 'asc')->take(2)->get(),
                'themeImage' => (Theme::where('month', now()->month)->where('year', now()->year)->first()) ? asset('storage/' . Theme::where('month', now()->month)->where('year', now()->year)->first()->image_path) : null,
                'dashboardImageUrl' => asset('storage/' . $dashboardImage->image_path)
            ]);
        } catch (\Exception $e) {
            \Log::error('Dashboard image edit failed: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Failed to load dashboard image for editing.');
        }
    }

    public function updateDashboardImage(Request $request, DashboardImage $dashboardImage)
    {
        if (!Auth::check() || Auth::user()->role !== 'Admin') {
            abort(403);
        }
        try {
            $request->validate([
                'caption' => 'nullable|string|max:255',
                'title' => 'nullable|string|max:255',
            ]);
            $dashboardImage->caption = $request->input('caption');
            $dashboardImage->title = $request->input('title');
            $dashboardImage->save();
            return redirect()->route('dashboard')->with('success', 'Dashboard image updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Dashboard image update failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update dashboard image.');
        }
    }

    public function deleteDashboardImage(DashboardImage $dashboardImage)
    {
        if (!Auth::check() || Auth::user()->role !== 'Admin') {
            abort(403);
        }
        try {
            // Delete the image file
            if ($dashboardImage->image_path && Storage::disk('public')->exists($dashboardImage->image_path)) {
                Storage::disk('public')->delete($dashboardImage->image_path);
            }
            $dashboardImage->delete();
            return redirect()->route('dashboard')->with('success', 'Dashboard image deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Dashboard image delete failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete dashboard image.');
        }
    }

    public function deleteBibleVerse()
    {
        if (Auth::user()->role !== 'Admin') {
            abort(403, 'Unauthorized action.');
        }

        // Capture for audit before delete
        $previous = BibleVerse::latest()->first();

        BibleVerse::truncate(); // This will delete all records

        // Audit log
        $user = Auth::user();
        $chapter = $user->member->chapter ?? $user->preferredChapter ?? null;
        AuditService::log(
            'bible_verse_deleted',
            'Bible verse removed from dashboard',
            [
                'chapter_id' => $chapter->id ?? null,
                'chapter_name' => $chapter->name ?? null,
                'verse_before' => $previous->verse ?? null,
                'reference_before' => $previous->reference ?? null,
            ],
            $user->id
        );

        return redirect()->route('dashboard')->with('success', 'Bible verse removed successfully.');
    }

    public function markNotificationsRead()
    {
        \App\Models\Message::where('is_read', false)->update(['is_read' => true]);
        if (auth()->user()->role === 'Member') {
            \App\Models\PrayerRequest::where('user_id', auth()->id())
                ->where('status', 'answered')
                ->where('notified', false)
                ->update(['notified' => true]);
        }
        return response()->json(['success' => true]);
    }
}