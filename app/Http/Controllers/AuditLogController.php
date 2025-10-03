<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Chapter;
use App\Models\User;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->check() || !in_array(auth()->user()->role, ['Admin', 'Leader'])) {
            abort(403);
        }

        $query = AuditLog::with('user')->orderBy('created_at', 'desc');

        if ($request->filled('action')) {
            $query->where('action', $request->string('action'));
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->integer('user_id'));
        }

        if ($request->filled('chapter_id')) {
            $chapterId = (int) $request->input('chapter_id');
            $query->where('metadata->chapter_id', $chapterId);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        // If Leader, restrict to their chapters
        if (auth()->user()->role === 'Leader') {
            $leaderChapterIds = auth()->user()->ledChapters()->pluck('id');
            $query->whereIn('metadata->chapter_id', $leaderChapterIds);
        }

        $logs = $query->paginate(20)->withQueryString();

        $chaptersQuery = Chapter::query();
        if (auth()->user()->role === 'Leader') {
            $chaptersQuery->whereIn('id', auth()->user()->ledChapters()->pluck('id'));
        }
        $chapters = $chaptersQuery->orderBy('name')->get(['id','name']);
        $users = User::orderBy('name')->get(['id','name']);

        $availableActions = AuditLog::select('action')->distinct()->orderBy('action')->pluck('action');

        return view('admin.activity-log', [
            'logs' => $logs,
            'chapters' => $chapters,
            'users' => $users,
            'availableActions' => $availableActions,
            'filters' => $request->only(['action','user_id','chapter_id','date_from','date_to'])
        ]);
    }
}
