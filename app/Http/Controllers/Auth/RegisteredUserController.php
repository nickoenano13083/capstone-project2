<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\InvitationCode;
use App\Models\Member;
use App\Models\User;
use App\Rules\Password as PasswordRule;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $whitelist = [
            'Sorsogon City Chapter',
            'Gubat Chapter',
            'Bulan Chapter',
            'Pilar Chapter',
            'Casiguran Chapter',
            'Bulusan Chapter',
        ];

        $preferred = \App\Models\Chapter::whereIn('name', $whitelist)
            ->orderBy('name')
            ->get(['id','name']);

        $chapters = $preferred->count() > 0
            ? $preferred
            : \App\Models\Chapter::orderBy('name')->get(['id','name']);

        return view('auth.register', [
            'chapters' => $chapters,
            'showInvitationField' => true,
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Normalize inputs to reduce duplicates caused by spacing/case
        $request->merge([
            'name' => preg_replace('/\s+/', ' ', trim((string) $request->input('name'))),
            'email' => strtolower(trim((string) $request->input('email'))),
        ]);

        // Normalize phone to digits only before validating
        $request->merge([
            'phone' => preg_replace('/[^0-9]/', '', (string) $request->input('phone')),
        ]);

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', 'unique:users,name'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', new PasswordRule()],
            'preferred_chapter_id' => ['required', 'exists:chapters,id'],
            'phone' => ['required', 'regex:/^\\d{11}$/', 'unique:members,phone'],
            'birthday' => [
                'required', 
                'date', 
                'before_or_equal:' . now()->subYears(3)->format('Y-m-d'),
                'after_or_equal:' . now()->subYears(120)->format('Y-m-d')
            ],
            // age will be computed from birthday; keep optional if present but not trusted
            'age' => ['nullable', 'integer', 'min:3', 'max:120'],
            'address' => ['required', 'string', 'max:500'],
            'gender' => ['required', 'in:Male,Female'],
            'invitation_code' => ['sometimes', 'required', 'exists:invitation_codes,code'],
        ], [
            'birthday.before_or_equal' => 'You must be at least 3 years old to register.',
            'age.min' => 'You must be at least 3 years old to register.'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Get the invitation code before validation removes it
        $invitation = InvitationCode::where('code', $request->input('invitation_code'))->first();
        
        // Compute age from birthday for consistency
        $computedAge = \Carbon\Carbon::parse($request->input('birthday'))->age;

        // Create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'preferred_chapter_id' => $request->input('preferred_chapter_id'),
            'birthday' => $request->input('birthday'),
            'age' => $computedAge,
            'address' => $request->input('address'),
            'gender' => $request->input('gender'),
        ]);

        // Create member record
        $member = new Member([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->input('phone'),
            'chapter_id' => $request->input('preferred_chapter_id'),
            'birthday' => $request->input('birthday'),
            'age' => $computedAge,
            'address' => $request->input('address'),
            'gender' => $request->input('gender'),
            'join_date' => now(),
            'user_id' => $user->id,
        ]);
        $member->save();

        // Mark the invitation as used
        if ($invitation) {
            $invitation->markAsUsed($user->id);
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
