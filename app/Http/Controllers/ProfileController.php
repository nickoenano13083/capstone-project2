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
        // Normalize phone to digits only before validating
        $request->merge([
            'phone' => preg_replace('/[^0-9]/', '', (string) $request->input('phone')),
        ]);

        $validated = $request->validate([
            'birthday' => ['nullable', 'date', 'before:today'],
            'age' => ['nullable', 'integer', 'min:1', 'max:120'],
            'gender' => ['nullable', 'in:Male,Female'],
            'address' => ['nullable', 'string', 'max:500'],
            'phone' => ['nullable', 'regex:/^\\d{11}$/', 'unique:members,phone,' . $request->user()->member?->id],
        ]);

        $user = $request->user();
        
        // Keep age consistent: compute from birthday when provided
        if (!empty($validated['birthday'])) {
            $validated['age'] = \Carbon\Carbon::parse($validated['birthday'])->age;
        }
        
        // Update user record (excluding phone field)
        $userData = collect($validated)->except('phone')->toArray();
        $user->fill($userData);
        $user->save();

        // Update member record if it exists (including phone field)
        if ($user->member) {
            $user->member->fill($validated);
            $user->member->save();
        }

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
            
            // Store the file on the public disk so it's web-accessible via /storage
            $stored = Storage::disk('public')->putFileAs('profile-photos', $image, basename($filename));
            
            // The stored path is already relative to the public disk root
            $relativePath = $stored; // e.g. 'profile-photos/uuid.ext'
            
            \Log::info('Profile picture saved', [
                'full_path' => storage_path('app/public/' . $relativePath),
                'public_path' => public_path('storage/' . $relativePath),
                'url' => Storage::disk('public')->url($relativePath)
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
