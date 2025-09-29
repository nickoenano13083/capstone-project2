<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function myQrCode()
    {
        $member = \App\Models\Member::where('user_id', auth()->id())->first();
        
        if (!$member) {
            return redirect()->route('profile.edit')
                ->with('error', 'Member profile not found. Please complete your profile first.');
        }
        
        // Ensure the member has a QR code
        if (empty($member->qr_code)) {
            $member->qr_code = (string) \Illuminate\Support\Str::uuid();
            $member->save();
        }
        
        return view('profile.my-qr-code', compact('member'));
    }

    /**
     * Update the user's personal information.
     */
    public function updatePersonalInfo(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'birthday' => ['nullable', 'date', 'before:today'],
            'age' => ['nullable', 'integer', 'min:1', 'max:120'],
            'gender' => ['nullable', 'in:Male,Female'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        $user = $request->user();
        $user->fill($validated);
        $user->save();

        return Redirect::route('profile.edit')
            ->with('status', 'personal-info-updated');
    }

    /**
     * Update the user's profile picture.
     */
    public function updateProfilePicture(Request $request): RedirectResponse
    {
        \Log::info('Profile picture upload started', ['user_id' => $request->user()->id]);
        
        $request->validate([
            'profile_picture' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
        ]);

        $user = $request->user();
        
        try {
            // Delete old profile picture if exists
            if ($user->profile_photo_path) {
                \Log::info('Deleting old profile picture', ['path' => $user->profile_photo_path]);
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            // Store the new profile picture
            $image = $request->file('profile_picture');
            $filename = 'profile-photos/' . Str::uuid() . '.' . $image->getClientOriginalExtension();
            
            \Log::info('Processing new profile picture', ['filename' => $filename]);
            
            // Ensure the directory exists
            if (!Storage::disk('public')->exists('profile-photos')) {
                Storage::disk('public')->makeDirectory('profile-photos');
            }
            
            // Store the file
            $path = $image->storeAs('public/profile-photos', basename($filename));
            
            // Get the relative path (without 'public/' prefix)
            $relativePath = str_replace('public/', '', $path);
            
            \Log::info('Profile picture saved', [
                'full_path' => storage_path('app/' . $path),
                'public_path' => public_path('storage/' . $relativePath),
                'url' => Storage::url($relativePath)
            ]);

            // Update user's profile photo path
            $user->profile_photo_path = $relativePath;
            $user->save();

            return back()->with('status', 'profile-picture-updated');
            
        } catch (\Exception $e) {
            \Log::error('Error uploading profile picture', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Failed to upload profile picture. Please try again.');
        }
    }
}
