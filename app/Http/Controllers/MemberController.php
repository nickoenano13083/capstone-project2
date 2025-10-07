<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Event;
use Illuminate\Http\Response;
use Carbon\Carbon;
use App\Services\AuditService;

class MemberController extends Controller
{
    private function getLeaderChapterIds()
    {
        $user = auth()->user();
        if (!$user) {
            return collect();
        }
        $query = \App\Models\Chapter::query()
            ->where(function($q) use ($user) {
                $q->where(function($qq) use ($user) {
                    $qq->where('leader_id', $user->id)
                       ->where('leader_type', 'App\\Models\\User');
                });
                if ($user->member) {
                    $q->orWhere(function($qq) use ($user) {
                        $qq->where('leader_id', $user->member->id)
                           ->where('leader_type', 'App\\Models\\Member');
                    });
                }
            });
        return $query->pluck('id');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $role = $request->input('role');
        $gender = $request->input('gender');
        $chapter_id = $request->input('chapter_id');
        $join_date_from = $request->input('join_date_from');
        $join_date_to = $request->input('join_date_to');
        $age_group = $request->input('age_group');
        $show_archived = $request->boolean('show_archived', false);

        $leaderChapterIds = collect();
        if (auth()->check() && auth()->user()->role === 'Leader') {
            $leaderChapterIds = $this->getLeaderChapterIds();
        }

        $members = Member::query()
            ->when($show_archived, function($query) {
                $query->where('is_archived', true);
            }, function($query) {
                $query->where('is_archived', false);
            })
            ->when($search, function($query) use ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->when($status, function($query) use ($status) {
                $query->whereRaw('LOWER(status) = ?', [strtolower($status)]);
            })
            ->when($role, function($query) use ($role) {
                $query->where('role', $role);
            })
            ->when($gender, function($query) use ($gender) {
                $query->where('gender', $gender);
            })
            ->when($chapter_id, function($query) use ($chapter_id) {
                $query->where(function($q) use ($chapter_id) {
                    $q->where('chapter_id', $chapter_id)
                      ->orWhereHas('user', function($uq) use ($chapter_id) {
                          $uq->where('preferred_chapter_id', $chapter_id);
                      });
                });
            })
            ->when($join_date_from, function($query) use ($join_date_from) {
                $query->whereDate('join_date', '>=', $join_date_from);
            })
            ->when($join_date_to, function($query) use ($join_date_to) {
                $query->whereDate('join_date', '<=', $join_date_to);
            })
            ->when($age_group, function($query) use ($age_group) {
                $today = now();
                
                if ($age_group === '60+') {
                    // For seniors (60+ years old)
                    $minBirthDate = $today->copy()->subYears(120)->toDateString(); // 120 years as upper bound
                    $maxBirthDate = $today->copy()->subYears(60)->toDateString();
                    $query->whereBetween('birthday', [$minBirthDate, $maxBirthDate])
                          ->whereNotNull('birthday');
                } else {
                    // For other age groups (format: 'min-max')
                    $range = array_map('intval', explode('-', $age_group));
                    
                    if (count($range) === 2) {
                        $minAge = $range[0];
                        $maxAge = $range[1];
                        
                        // Calculate the date range for the age group
                        $minBirthDate = $today->copy()->subYears($maxAge + 1)->addDay()->toDateString();
                        $maxBirthDate = $today->copy()->subYears($minAge)->toDateString();
                        
                        $query->whereBetween('birthday', [$minBirthDate, $maxBirthDate])
                              ->whereNotNull('birthday');
                    }
                }
            })
            ->when(auth()->check() && auth()->user()->role === 'Leader', function($query) use ($leaderChapterIds) {
                $query->where(function($q) use ($leaderChapterIds) {
                    $q->whereIn('chapter_id', $leaderChapterIds)
                      ->orWhereHas('user', function($uq) use ($leaderChapterIds) {
                          $uq->whereIn('preferred_chapter_id', $leaderChapterIds);
                      });
                });
            })
            ->with(['chapter', 'user'])
            ->orderBy('is_archived')
            ->orderBy('name')
            ->paginate(10)
            ->appends($request->except('page'));

        $chaptersQuery = \App\Models\Chapter::orderBy('name');
        if (auth()->check() && auth()->user()->role === 'Leader') {
            $chaptersQuery->whereIn('id', $leaderChapterIds);
        }
        $chapters = $chaptersQuery->get();
        $statuses = ['Active', 'Inactive','Transfer', 'Work', 'Deceased'];
        $roles = ['Admin', 'Leader', 'Member', 'Guest'];
        $genders = ['Male', 'Female', 'Other'];
        $ageGroups = [
            '3-12' => 'Kids (3-12)',
            '13-25' => 'Youth (13-25)',
            '26-59' => 'Adults (26-59)',
            '60+' => 'Seniors (60+)',
        ];

        $usersWithoutMember = collect();
        if (auth()->check() && auth()->user()->role === 'Leader') {
            $usersQuery = \App\Models\User::query()
                ->whereIn('preferred_chapter_id', $leaderChapterIds)
                ->whereDoesntHave('member')
                ->with('preferredChapter');
            if ($search) {
                $usersQuery->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }
            if ($chapter_id) {
                $usersQuery->where('preferred_chapter_id', $chapter_id);
            }
            $usersWithoutMember = $usersQuery->get();
        }

        return view('members.index', compact('members', 'search', 'status', 'role', 'gender', 'chapter_id', 'join_date_from', 'join_date_to', 'age_group', 'chapters', 'statuses', 'roles', 'genders', 'ageGroups', 'usersWithoutMember', 'show_archived'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->check() || !in_array(auth()->user()->role, ['Admin', 'Leader'])) {
            abort(403, 'Unauthorized');
        }
        $chaptersQuery = \App\Models\Chapter::orderBy('name');
        if (auth()->user()->role === 'Leader') {
            $leaderChapterIds = auth()->user()->ledChapters()->pluck('id');
            $chaptersQuery->whereIn('id', $leaderChapterIds);
        }
        $chapters = $chaptersQuery->get();
        $statuses = ['Active', 'Inactive', 'Pending', 'Transfer', 'Work', 'Deceased'];
        $roles = ['Admin', 'Leader', 'Member', 'Guest'];
        $genders = ['Male', 'Female', 'Other'];
        return view('members.create', compact('chapters', 'statuses', 'roles', 'genders'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->check() || !in_array(auth()->user()->role, ['Admin', 'Leader'])) {
            abort(403, 'Unauthorized');
        }
        // Normalize phone to digits only before validating
        $request->merge([
            'phone' => preg_replace('/[^0-9]/', '', (string) $request->input('phone')),
        ]);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:members,email',
            'phone' => 'required|regex:/^\d{11}$/|unique:members,phone',
            'address' => 'required|string|max:255',
            'join_date' => 'required|date',
            'chapter_id' => 'nullable|exists:chapters,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        if (auth()->user()->role === 'Leader' && $request->filled('chapter_id')) {
            $leaderChapterIds = $this->getLeaderChapterIds()->toArray();
            if (!in_array($request->chapter_id, $leaderChapterIds)) {
                abort(403, 'You can only add members to your chapters.');
            }
        }

        $member = Member::create($request->all());

        // Audit: member created
        $user = auth()->user();
        $chapter = $member->chapter ?? null;
        AuditService::log(
            'member_created',
            'Member created',
            [
                'member_id' => $member->id,
                'member_name' => $member->name,
                'chapter_id' => $member->chapter_id,
                'chapter_name' => $chapter->name ?? null,
            ],
            $user?->id
        );

        return redirect()->route('members.index')
            ->with('success', 'Member added successfully.');
    }

    /**
     * Store a newly created member via API.
     */
    public function apiStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:members,email',
        ]);

        $member = \App\Models\Member::create($validated);

        return response()->json([
            'message' => 'Member created successfully',
            'member' => $member,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Member $member)
    {
        if (auth()->check() && auth()->user()->role === 'Leader') {
            $leaderChapterIds = $this->getLeaderChapterIds();
            $member->load('user');
            $memberPreferredChapterId = optional($member->user)->preferred_chapter_id;
            if (!$leaderChapterIds->contains($member->chapter_id) && !$leaderChapterIds->contains($memberPreferredChapterId)) {
                abort(403, 'Access denied.');
            }
        }
        $member->load(['chapter', 'attendance.event', 'user']);
        return view('members.show', compact('member'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Member $member)
    {
        if (auth()->check() && auth()->user()->role === 'Leader') {
            $leaderChapterIds = $this->getLeaderChapterIds();
            $member->load('user');
            $memberPreferredChapterId = optional($member->user)->preferred_chapter_id;
            if (!$leaderChapterIds->contains($member->chapter_id) && !$leaderChapterIds->contains($memberPreferredChapterId)) {
                abort(403, 'Access denied.');
            }
        }

        $chaptersQuery = \App\Models\Chapter::orderBy('name');
        if (auth()->check() && auth()->user()->role === 'Leader') {
            $chaptersQuery->whereIn('id', $leaderChapterIds);
        }
        $chapters = $chaptersQuery->get();
        $statuses = ['Active', 'Inactive', 'Pending', 'Transfer', 'Work', 'Deceased'];
        $roles = ['Admin', 'Leader', 'Member', 'Guest'];
        $genders = ['Male', 'Female', 'Other'];
        return view('members.edit', compact('member', 'chapters', 'statuses', 'roles', 'genders'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Member $member)
    {
        if (auth()->check() && auth()->user()->role === 'Leader') {
            $leaderChapterIds = $this->getLeaderChapterIds();
            $member->load('user');
            $memberPreferredChapterId = optional($member->user)->preferred_chapter_id;
            if (!$leaderChapterIds->contains($member->chapter_id) && !$leaderChapterIds->contains($memberPreferredChapterId)) {
                abort(403, 'Access denied.');
            }
        }
        // Normalize phone to digits only before validating
        $request->merge([
            'phone' => preg_replace('/[^0-9]/', '', (string) $request->input('phone')),
        ]);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:members,email,' . $member->id,
            'phone' => 'required|regex:/^\d{11}$/|unique:members,phone,' . $member->id,
            'address' => 'required|string|max:255',
            'join_date' => 'required|date',
            'status' => 'required|in:Active,Inactive,Transfer,Work,Deceased',
            'role' => 'required|in:Admin,Leader,Member,Guest',
            'gender' => 'nullable|in:Male,Female,Other',
            'chapter_id' => 'nullable|exists:chapters,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $before = $member->only(['name','email','phone','address','status','role','gender','chapter_id']);
        $member->update($request->all());

        // Audit: member updated
        $user = auth()->user();
        $chapter = $member->chapter ?? null;
        AuditService::log(
            'member_updated',
            'Member updated',
            [
                'member_id' => $member->id,
                'chapter_id' => $member->chapter_id,
                'chapter_name' => $chapter->name ?? null,
                'before' => $before,
                'after' => $member->only(['name','email','phone','address','status','role','gender','chapter_id']),
            ],
            $user?->id
        );

        return redirect()->route('members.index')
            ->with('success', 'Member updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Member $member)
    {
        if (auth()->check() && auth()->user()->role === 'Leader') {
            $leaderChapterIds = $this->getLeaderChapterIds();
            $member->load('user');
            $memberPreferredChapterId = optional($member->user)->preferred_chapter_id;
            if (!$leaderChapterIds->contains($member->chapter_id) && !$leaderChapterIds->contains($memberPreferredChapterId)) {
                abort(403, 'Access denied.');
            }
        }
        // Audit: member deleted
        $user = auth()->user();
        $chapter = $member->chapter ?? null;
        AuditService::log(
            'member_deleted',
            'Member deleted',
            [
                'member_id' => $member->id,
                'member_name' => $member->name,
                'chapter_id' => $member->chapter_id,
                'chapter_name' => $chapter->name ?? null,
            ],
            $user?->id
        );

        $member->delete();

        return redirect()->route('members.index')
            ->with('success', 'Member deleted successfully.');
    }

    /**
     * Show the form for checking in a member to an event
     */
    public function showCheckInForm(Member $member)
    {
        $events = Event::where('status', 'active')
            ->where('date', '>=', now())
            ->orderBy('date')
            ->get();

        return view('members.check-in', [
            'member' => $member,
            'events' => $events
        ]);
    }

    /**
     * Process the member check-in form submission
     */
    public function processCheckIn(Request $request, Member $member)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'status' => 'required|in:present,absent',
            'notes' => 'nullable|string|max:500'
        ]);

        $event = Event::findOrFail($request->event_id);

        // Check if already checked in
        $existingCheckIn = \DB::table('attendance')
            ->where('event_id', $event->id)
            ->where('member_id', $member->id)
            ->first();

        if ($existingCheckIn) {
            return redirect()->back()
                ->with('error', 'Member is already checked in to this event.');
        }

        // Record attendance
        \DB::table('attendance')->insert([
            'event_id' => $event->id,
            'member_id' => $member->id,
            'status' => $request->status,
            'notes' => $request->notes,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('members.show', $member)
            ->with('success', 'Member successfully checked in to the event.');
    }

    /**
     * Archive the specified member.
     */
    public function archive(Member $member)
    {
        if (!auth()->check() || !in_array(auth()->user()->role, ['Admin', 'Leader'])) {
            abort(403, 'Unauthorized action.');
        }

        if (auth()->user()->role === 'Leader') {
            $leaderChapterIds = $this->getLeaderChapterIds();
            if (!$leaderChapterIds->contains($member->chapter_id)) {
                abort(403, 'You can only archive members from your chapters');
            }
        }

        $member->update(['is_archived' => true]);

        // Audit: member archived
        $user = auth()->user();
        $chapter = $member->chapter ?? null;
        AuditService::log(
            'member_archived',
            'Member archived',
            [
                'member_id' => $member->id,
                'member_name' => $member->name,
                'chapter_id' => $member->chapter_id,
                'chapter_name' => $chapter->name ?? null,
            ],
            $user?->id
        );
        return redirect()->route('members.index')->with('success', 'Member archived successfully.');
    }

    /**
     * Unarchive the specified member.
     */
    public function unarchive(Member $member)
    {
        if (!auth()->check() || !in_array(auth()->user()->role, ['Admin', 'Leader'])) {
            abort(403, 'Unauthorized action.');
        }

        if (auth()->user()->role === 'Leader') {
            $leaderChapterIds = $this->getLeaderChapterIds();
            if (!$leaderChapterIds->contains($member->chapter_id)) {
                abort(403, 'You can only unarchive members from your chapters');
            }
        }

        $member->update(['is_archived' => false]);

        // Audit: member unarchived
        $user = auth()->user();
        $chapter = $member->chapter ?? null;
        AuditService::log(
            'member_unarchived',
            'Member unarchived',
            [
                'member_id' => $member->id,
                'member_name' => $member->name,
                'chapter_id' => $member->chapter_id,
                'chapter_name' => $chapter->name ?? null,
            ],
            $user?->id
        );
        return redirect()->route('members.index', ['show_archived' => true])->with('success', 'Member unarchived successfully.');
    }

    /**
     * Download members list as formatted CSV (like print dialog)
     */
    public function download(Request $request)
    {
        if (!auth()->check() || !in_array(auth()->user()->role, ['Admin', 'Leader'])) {
            abort(403, 'Unauthorized action.');
        }

        // Apply the same filters as the index method
        $search = $request->input('search');
        $status = $request->input('status');
        $role = $request->input('role');
        $gender = $request->input('gender');
        $chapter_id = $request->input('chapter_id');
        $join_date_from = $request->input('join_date_from');
        $join_date_to = $request->input('join_date_to');
        $age_group = $request->input('age_group');
        $show_archived = $request->boolean('show_archived', false);

        $leaderChapterIds = collect();
        if (auth()->user()->role === 'Leader') {
            $leaderChapterIds = $this->getLeaderChapterIds();
        }

        $query = Member::with(['chapter', 'user']);

        // Apply chapter-based access control
        if (auth()->user()->role === 'Leader') {
            if ($leaderChapterIds->isEmpty()) {
                $query->whereRaw('1 = 0'); // No results if leader has no chapters
            } else {
                $query->whereIn('chapter_id', $leaderChapterIds);
            }
        }

        // Apply filters
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($role) {
            $query->where('role', $role);
        }

        if ($gender) {
            $query->where('gender', $gender);
        }

        if ($chapter_id) {
            $query->where('chapter_id', $chapter_id);
        }

        if ($join_date_from) {
            $query->whereDate('join_date', '>=', $join_date_from);
        }

        if ($join_date_to) {
            $query->whereDate('join_date', '<=', $join_date_to);
        }

        if ($age_group) {
            switch ($age_group) {
                case '0-17':
                    $query->where('age', '>=', 0)->where('age', '<=', 17);
                    break;
                case '18-30':
                    $query->where('age', '>=', 18)->where('age', '<=', 30);
                    break;
                case '31-50':
                    $query->where('age', '>=', 31)->where('age', '<=', 50);
                    break;
                case '51+':
                    $query->where('age', '>=', 51);
                    break;
            }
        }

        // Apply archived filter
        if ($show_archived) {
            $query->where('is_archived', true);
        } else {
            $query->where('is_archived', false);
        }

        $members = $query->orderBy('name')->get();

        // Generate formatted CSV content with header information
        $csvContent = '';
        
        // Add header information
        $csvContent .= "CHURCH MEMBERS LIST\n";
        $csvContent .= "Generated on: " . now()->format('M d, Y \a\t g:i A') . "\n";
        $csvContent .= "Total Members: " . $members->count() . "\n";
        
        // Add filter information if any filters are applied
        $filters = [];
        if ($search) $filters[] = "Search: \"$search\"";
        if ($status) $filters[] = "Status: " . ucfirst($status);
        if ($role) $filters[] = "Role: " . ucfirst($role);
        if ($gender) $filters[] = "Gender: " . ucfirst($gender);
        if ($age_group) $filters[] = "Age Group: $age_group";
        if ($join_date_from) $filters[] = "From: " . \Carbon\Carbon::parse($join_date_from)->format('M d, Y');
        if ($join_date_to) $filters[] = "To: " . \Carbon\Carbon::parse($join_date_to)->format('M d, Y');
        if ($show_archived) $filters[] = "Archived Members";
        
        if (!empty($filters)) {
            $csvContent .= "Applied Filters: " . implode(' | ', $filters) . "\n";
        }
        
        $csvContent .= "\n"; // Empty line
        
        // Add CSV headers
        $csvContent .= "No,Name,Email,Phone,Address,Chapter,Status,Role,Gender,Age,Join Date,Birthday\n";
        
        // Add member data
        foreach ($members as $index => $member) {
            $csvContent .= sprintf(
                "%d,\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\"\n",
                $index + 1,
                $member->name,
                $member->email,
                $member->phone ?? 'N/A',
                $member->address ?? 'N/A',
                $member->chapter ? $member->chapter->name : 'N/A',
                ucfirst($member->status),
                ucfirst($member->role),
                $member->gender ? ucfirst($member->gender) : 'N/A',
                $member->age ?? 'N/A',
                $member->join_date ? $member->join_date->format('M d, Y') : 'N/A',
                $member->birthday ? $member->birthday->format('M d, Y') : 'N/A'
            );
        }
        
        // Add footer
        $csvContent .= "\n";
        $csvContent .= "This document was generated from the Church Management System\n";
        $csvContent .= "Page generated on " . now()->format('M d, Y \a\t g:i A') . "\n";

        // Generate filename with timestamp
        $filename = 'members_list_' . now()->format('Y-m-d_H-i-s') . '.csv';

        return response($csvContent)
            ->header('Content-Type', 'text/csv; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }
}
