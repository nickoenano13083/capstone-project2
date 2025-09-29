<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function store(Request $request)
    {
        // Only allow admins
        if (Auth::user()->role !== 'Admin') {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        Announcement::create([
            'title' => $validated['title'],
            'body' => $validated['body'],
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('dashboard')->with('success', 'Announcement posted successfully!');
    }

    public function update(Request $request, Announcement $announcement)
    {
        if (Auth::user()->role !== 'Admin') {
            abort(403, 'Unauthorized action.');
        }
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);
        $announcement->update($validated);
        return redirect()->route('dashboard')->with('success', 'Announcement updated successfully!');
    }

    public function destroy(Announcement $announcement)
    {
        if (Auth::user()->role !== 'Admin') {
            abort(403, 'Unauthorized action.');
        }
        $announcement->delete();
        return redirect()->route('dashboard')->with('success', 'Announcement deleted successfully!');
    }
}
