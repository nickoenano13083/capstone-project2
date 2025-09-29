<?php

namespace App\Http\Controllers;

use App\Models\InvitationCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class InvitationCodeController extends Controller
{
    /**
     * Display a listing of the invitation codes.
     */
    public function index()
    {
        $invitations = InvitationCode::with(['creator', 'user'])
            ->latest()
            ->paginate(20);

        return view('invitations.index', compact('invitations'));
    }

    /**
     * Show the form for creating a new invitation code.
     */
    public function create()
    {
        return view('invitations.create');
    }

    /**
     * Store a newly created invitation code in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => ['nullable', 'email', 'max:255'],
            'expires_in_days' => ['required', 'integer', 'min:1', 'max:365'],
            'count' => ['required', 'integer', 'min:1', 'max:20'],
        ]);

        $codes = [];
        
        for ($i = 0; $i < $validated['count']; $i++) {
            $code = InvitationCode::generate(
                userId: auth()->id(),
                email: $validated['email'],
                expiresInDays: $validated['expires_in_days']
            );
            
            $codes[] = $code;
        }

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Invitation codes generated successfully',
                'data' => $codes,
            ]);
        }

        return redirect()
            ->route('invitations.index')
            ->with('success', 'Invitation codes generated successfully');
    }

    /**
     * Display the specified invitation code.
     */
    public function show(InvitationCode $invitation)
    {
        $this->authorize('view', $invitation);
        
        return view('invitations.show', compact('invitation'));
    }

    /**
     * Revoke the specified invitation code.
     */
    public function revoke(InvitationCode $invitation)
    {
        $this->authorize('delete', $invitation);

        if ($invitation->used_at) {
            return back()->with('error', 'Cannot revoke an already used invitation code');
        }

        $invitation->update(['expires_at' => now()]);

        return back()->with('success', 'Invitation code has been revoked');
    }

    /**
     * Check if an invitation code is valid.
     */
    public function check(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'uppercase'],
            'email' => ['nullable', 'email'],
        ]);

        $invitation = InvitationCode::where('code', $validated['code'])->first();

        if (!$invitation) {
            return response()->json([
                'valid' => false,
                'message' => 'Invalid invitation code',
            ]);
        }

        if ($invitation->used_at) {
            return response()->json([
                'valid' => false,
                'message' => 'This invitation code has already been used',
            ]);
        }

        if ($invitation->expires_at && $invitation->expires_at->isPast()) {
            return response()->json([
                'valid' => false,
                'message' => 'This invitation code has expired',
            ]);
        }

        if ($invitation->email && $validated['email'] && $invitation->email !== $validated['email']) {
            return response()->json([
                'valid' => false,
                'message' => 'This invitation code is not valid for the provided email',
            ]);
        }

        return response()->json([
            'valid' => true,
            'message' => 'Valid invitation code',
            'data' => [
                'email' => $invitation->email,
                'expires_at' => $invitation->expires_at?->toDateTimeString(),
                'created_at' => $invitation->created_at->toDateTimeString(),
            ],
        ]);
    }
}
