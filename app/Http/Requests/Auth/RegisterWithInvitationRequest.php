<?php

namespace App\Http\Requests\Auth;

use App\Models\InvitationCode;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterWithInvitationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'invitation_code' => [
                'required',
                'string',
                'uppercase',
                'exists:invitation_codes,code',
                function ($attribute, $value, $fail) {
                    $invitation = InvitationCode::where('code', $value)->first();
                    
                    if ($invitation && !$invitation->isValid()) {
                        if ($invitation->used_at) {
                            $fail('This invitation code has already been used.');
                        } elseif ($invitation->expires_at && $invitation->expires_at->isPast()) {
                            $fail('This invitation code has expired.');
                        } else {
                            $fail('This invitation code is not valid.');
                        }
                    }
                    
                    // If email is specified in the invitation, it must match
                    if ($invitation && $invitation->email && $invitation->email !== $this->email) {
                        $fail('This invitation code is not valid for the provided email.');
                    }
                },
            ],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:users,email',
                'unique:members,email',
            ],
            'password' => ['required', 'confirmed', new \App\Rules\Password()],
            'birthday' => ['required', 'date', 'before:today'],
            'age' => ['required', 'integer', 'min:1', 'max:120'],
            'address' => ['required', 'string', 'max:500'],
            'gender' => ['required', 'in:Male,Female'],
            'preferred_chapter_id' => ['required', 'exists:chapters,id'],
        ];
    }

    /**
     * Get the validated data from the request.
     *
     * @param  string|null  $key
     * @param  mixed  $default
     * @return mixed
     */
    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);
        
        // Remove invitation_code from the validated data as it's not needed for user creation
        unset($validated['invitation_code']);
        
        return $validated;
    }
    
    /**
     * Get the invitation code model
     */
    public function getInvitation(): ?InvitationCode
    {
        return InvitationCode::where('code', $this->invitation_code)->first();
    }
}
