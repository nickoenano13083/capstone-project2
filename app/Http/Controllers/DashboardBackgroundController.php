<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DashboardBackgroundController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'background' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5120', // Max 5MB
        ]);

        // Delete old background if exists
        $oldBg = Auth::user()->dashboard_background;
        if ($oldBg && Storage::exists($oldBg)) {
            Storage::delete($oldBg);
        }

        // Store the new background
        $path = $request->file('background')->store('dashboard-backgrounds', 'public');
        
        // Save to user
        Auth::user()->update([
            'dashboard_background' => $path
        ]);

        return back()->with('success', 'Dashboard background updated successfully!');
    }

    public function remove()
    {
        $user = Auth::user();
        
        if ($user->dashboard_background && Storage::exists($user->dashboard_background)) {
            Storage::delete($user->dashboard_background);
            $user->update(['dashboard_background' => null]);
        }

        return back()->with('success', 'Dashboard background removed successfully!');
    }
}
