<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Theme;
use Illuminate\Support\Facades\Auth;

class ThemeController extends Controller
{
    public function create()
    {
        if (!Auth::check() || Auth::user()->role !== 'Admin') {
            abort(403, 'Unauthorized');
        }
        return view('themes.create');
    }

    public function store(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'Admin') {
            abort(403, 'Unauthorized');
        }
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $month = now()->month;
        $year = now()->year;
        $imagePath = $request->file('image')->store('themes', 'public');
        // Remove existing theme for this month/year
        Theme::where('month', $month)->where('year', $year)->delete();
        Theme::create([
            'image_path' => $imagePath,
            'month' => $month,
            'year' => $year,
        ]);
        return redirect()->route('dashboard')->with('success', 'Theme image uploaded!');
    }
}
