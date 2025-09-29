<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Member;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ChapterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $chapters = Chapter::with('leader')
            ->when($search, function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('location', 'like', "%{$search}%");
            })
            ->when(auth()->check() && auth()->user()->role === 'Leader', function($query) {
                $leaderChapterIds = auth()->user()->ledChapters()->pluck('id');
                $query->whereIn('id', $leaderChapterIds);
            })
            ->paginate(10);
        return view('chapters.index', compact('chapters', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->check() || auth()->user()->role !== 'Admin') {
            abort(403, 'Only admins can create chapters.');
        }
        $members = Member::whereIn('role', ['Pastor', 'Leader', 'Admin'])->get();
        $users = User::whereIn('role', ['Admin', 'Leader'])->get();
        return view('chapters.create', compact('members', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->check() || auth()->user()->role !== 'Admin') {
            abort(403, 'Only admins can create chapters.');
        }
        $request->validate([
            'name' => 'required|string|max:100|unique:chapters',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'leader_id' => 'nullable|string',
            'leader_type' => 'nullable|in:member,user',
            'status' => 'required|in:active,inactive',
        ]);

        $data = $request->all();
        
        // Handle leader assignment
        if ($request->filled('leader_id') && $request->filled('leader_type')) {
            if ($request->leader_type === 'user') {
                $data['leader_type'] = 'App\Models\User';
            } else {
                $data['leader_type'] = 'App\Models\Member';
            }
        } else {
            $data['leader_id'] = null;
            $data['leader_type'] = null;
        }

        Chapter::create($data);

        return redirect()->route('chapters.index')
            ->with('success', 'Chapter created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Chapter $chapter)
    {
        if (auth()->check() && auth()->user()->role === 'Leader') {
            $leaderChapterIds = auth()->user()->ledChapters()->pluck('id');
            if (!$leaderChapterIds->contains($chapter->id)) {
                abort(403, 'Access denied.');
            }
        }
        $chapter->load('leader');
        return view('chapters.show', compact('chapter'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chapter $chapter)
    {
        if (auth()->check() && auth()->user()->role === 'Leader') {
            $leaderChapterIds = auth()->user()->ledChapters()->pluck('id');
            if (!$leaderChapterIds->contains($chapter->id)) {
                abort(403, 'Access denied.');
            }
        }
        $members = Member::whereIn('role', ['Pastor', 'Leader', 'Admin'])->get();
        $users = User::whereIn('role', ['Admin', 'Leader'])->get();
        return view('chapters.edit', compact('chapter', 'members', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chapter $chapter)
    {
        if (auth()->check() && auth()->user()->role === 'Leader') {
            $leaderChapterIds = auth()->user()->ledChapters()->pluck('id');
            if (!$leaderChapterIds->contains($chapter->id)) {
                abort(403, 'Access denied.');
            }
        }
        $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('chapters')->ignore($chapter->id)],
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'leader_id' => 'nullable|string',
            'leader_type' => 'nullable|in:member,user',
            'status' => 'required|in:active,inactive',
        ]);

        $data = $request->all();
        
        // Handle leader assignment
        if ($request->filled('leader_id') && $request->filled('leader_type')) {
            if ($request->leader_type === 'user') {
                $data['leader_type'] = 'App\Models\User';
            } else {
                $data['leader_type'] = 'App\Models\Member';
            }
        } else {
            $data['leader_id'] = null;
            $data['leader_type'] = null;
        }

        $chapter->update($data);

        return redirect()->route('chapters.index')
            ->with('success', 'Chapter updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chapter $chapter)
    {
        if (!auth()->check() || auth()->user()->role !== 'Admin') {
            abort(403, 'Only admins can delete chapters.');
        }
        $chapter->delete();

        return redirect()->route('chapters.index')
            ->with('success', 'Chapter deleted successfully.');
    }
}
