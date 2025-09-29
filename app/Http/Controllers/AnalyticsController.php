<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Event;
use App\Models\Attendance;
use App\Models\PrayerRequest;
use App\Models\Chapter;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        // Members by gender
        $genderStats = [
            'male' => Member::where('gender', 'male')->count(),
            'female' => Member::where('gender', 'female')->count(),
            'other' => Member::where('gender', 'other')->orWhereNull('gender')->count(),
        ];

        // Members by age group
        $ageGroups = [
            '3-12' => 0,
            '13-25' => 0,
            '26-59' => 0,
            '60+' => 0
        ];
        $members = Member::whereNotNull('birthday')->get();
        foreach ($members as $member) {
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

        // Events per month (last 12 months)
        $eventsPerMonth = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $eventsPerMonth[] = [
                'month' => $date->format('M Y'),
                'count' => Event::whereYear('date', $date->year)->whereMonth('date', $date->month)->count(),
            ];
        }

        // Attendance trend (last 12 months)
        $attendanceTrend = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $attendanceTrend[] = [
                'month' => $date->format('M Y'),
                'count' => Attendance::whereYear('attendance_date', $date->year)->whereMonth('attendance_date', $date->month)->where('status', 'present')->count(),
            ];
        }

        // Prayer requests status
        $prayerStats = [
            'pending' => PrayerRequest::where('status', 'pending')->count(),
            'answered' => PrayerRequest::where('status', 'answered')->count(),
        ];

        // Members per chapter (top 7)
        $chapterStats = Chapter::withCount('members')->orderBy('members_count', 'desc')->take(7)->get()->map(function($c) {
            return [
                'name' => $c->name,
                'members' => $c->members_count
            ];
        });

        // Top 5 events by attendance
        $topEvents = Event::withCount(['attendance' => function($q) {
            $q->where('status', 'present');
        }])->orderBy('attendance_count', 'desc')->take(5)->get()->map(function($e) {
            return [
                'title' => $e->title,
                'attendance' => $e->attendance_count,
                'date' => $e->date->format('M d, Y')
            ];
        });

        $recentPrayerRequests = PrayerRequest::latest()->take(3)->get();
        $totalMembers = array_sum($genderStats);
        $totalEvents = collect($eventsPerMonth)->sum('count');
        $totalAttendance = collect($attendanceTrend)->sum('count');

        return view('analytics', [
            'genderStats' => $genderStats,
            'ageGroups' => $ageGroups,
            'eventsPerMonth' => $eventsPerMonth,
            'attendanceTrend' => $attendanceTrend,
            'prayerStats' => $prayerStats,
            'chapterStats' => $chapterStats,
            'topEvents' => $topEvents,
            'recentPrayerRequests' => $recentPrayerRequests,
            'totalMembers' => $totalMembers,
            'totalEvents' => $totalEvents,
            'totalAttendance' => $totalAttendance,
        ]);
    }
} 