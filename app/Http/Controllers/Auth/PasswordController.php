<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Str;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => [
                'required',
                'confirmed',
                'different:current_password',
                Password::min(12)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
                    ->rules([
                        function (string $attribute, mixed $value, \Closure $fail) use ($request) {
                            $user = $request->user();
                            if (Str::contains(Str::lower($value), [
                                Str::lower($user->name),
                                Str::lower($user->email),
                                'password',
                                '123',
                                'qwerty',
                                'abc123',
                                'welcome',
                            ])) {
                                $fail('The :attribute is too predictable.');
                            }
                        },
                    ]),
            ],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }
}
