<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Ensure your account is using a strong, unique password.') }}
        </p>
        
        <div class="mt-2 text-sm text-gray-600 bg-gray-50 p-3 rounded-md">
            <p class="font-medium">{{ __('Password must contain:') }}</p>
            <ul class="list-disc list-inside space-y-1 mt-1">
                <li class="password-requirement" data-requirement="length">{{ __('At least 12 characters') }}</li>
                <li class="password-requirement" data-requirement="uppercase">{{ __('At least one uppercase letter') }}</li>
                <li class="password-requirement" data-requirement="lowercase">{{ __('At least one lowercase letter') }}</li>
                <li class="password-requirement" data-requirement="number">{{ __('At least one number') }}</li>
                <li class="password-requirement" data-requirement="special">{{ __('At least one special character') }}</li>
            </ul>
        </div>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6" id="password-update-form">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('Current Password')" />
            <x-text-input 
                id="update_password_current_password" 
                name="current_password" 
                type="password" 
                class="mt-1 block w-full" 
                autocomplete="current-password" 
                required
            />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('New Password')" />
            <x-text-input 
                id="update_password_password" 
                name="password" 
                type="password" 
                class="mt-1 block w-full" 
                autocomplete="new-password" 
                required
                minlength="12"
                pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\w\s]).{12,}$"
                title="Password must be at least 12 characters long and include uppercase, lowercase, number, and special character"
                oninput="validatePassword(this.value)"
            />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
            <div id="password-strength" class="mt-2 text-sm"></div>
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm New Password')" />
            <x-text-input 
                id="update_password_password_confirmation" 
                name="password_confirmation" 
                type="password" 
                class="mt-1 block w-full" 
                autocomplete="new-password" 
                required
                oninput="checkPasswordMatch()"
            />
            <div id="password-match" class="mt-2 text-sm"></div>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button type="submit" id="submit-button">{{ __('Update Password') }}</x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 5000)"
                    class="text-sm text-green-600"
                >{{ __('Password updated successfully!') }}</p>
            @endif
        </div>
    </form>
</section>

@push('scripts')
<script>
function validatePassword(password) {
    const requirements = {
        length: password.length >= 12,
        uppercase: /[A-Z]/.test(password),
        lowercase: /[a-z]/.test(password),
        number: /\d/.test(password),
        special: /[!@#$%^&*(),.?":{}|<>]/.test(password),
    };

    // Update requirement indicators
    Object.entries(requirements).forEach(([key, met]) => {
        const element = document.querySelector(`.password-requirement[data-requirement="${key}"]`);
        if (element) {
            element.className = `password-requirement ${met ? 'text-green-600' : 'text-gray-400'}`;
            element.innerHTML = met 
                ? `✓ ${element.textContent.replace('✓ ', '').replace('✗ ', '')}` 
                : `✗ ${element.textContent.replace('✓ ', '').replace('✗ ', '')}`;
        }
    });

    // Update password strength indicator
    const strength = calculateStrength(password);
    const strengthElement = document.getElementById('password-strength');
    
    if (password.length === 0) {
        strengthElement.textContent = '';
        strengthElement.className = 'mt-2 text-sm';
    } else {
        const strengthText = ['Very Weak', 'Weak', 'Moderate', 'Strong', 'Very Strong'][strength.index];
        strengthElement.textContent = `Strength: ${strengthText}${strength.feedback ? ` (${strength.feedback})` : ''}`;
        strengthElement.className = `mt-2 text-sm ${strength.class}`;
    }
    
    // Also check password match
    checkPasswordMatch();
    
    return Object.values(requirements).every(Boolean);
}

function checkPasswordMatch() {
    const password = document.getElementById('update_password_password').value;
    const confirmPassword = document.getElementById('update_password_password_confirmation').value;
    const matchElement = document.getElementById('password-match');
    
    if (confirmPassword.length === 0) {
        matchElement.textContent = '';
        matchElement.className = 'mt-2 text-sm';
        return;
    }
    
    if (password === confirmPassword) {
        matchElement.textContent = '✓ Passwords match';
        matchElement.className = 'mt-2 text-sm text-green-600';
    } else {
        matchElement.textContent = '✗ Passwords do not match';
        matchElement.className = 'mt-2 text-sm text-red-600';
    }
}

function calculateStrength(password) {
    let score = 0;
    let feedback = [];
    
    // Length check
    if (password.length >= 20) score += 3;
    else if (password.length >= 15) score += 2;
    else if (password.length >= 12) score += 1;
    
    // Character diversity
    const hasLower = /[a-z]/.test(password);
    const hasUpper = /[A-Z]/.test(password);
    const hasNumber = /\d/.test(password);
    const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(password);
    
    if (hasLower && hasUpper) score += 1;
    if (hasNumber) score += 1;
    if (hasSpecial) score += 1;
    
    // Check for common patterns
    const commonPatterns = [
        '123', 'abc', 'qwe', 'asd', 'zxc', 'qaz', 'wsx', 'edc', 'rfv', 'tgb', 'yhn', 'ujm', 'ik', 'ol', 'p;', 
        'password', 'admin', 'welcome', 'qwerty', 'letmein', 'monkey', 'dragon', 'baseball', 'football', 'iloveyou',
        'trustno1', 'sunshine', 'master', 'superman', 'starwars', 'pokemon', 'letmein', 'access', 'shadow'
    ];
    
    const isCommon = commonPatterns.some(pattern => 
        password.toLowerCase().includes(pattern) || 
        password.toLowerCase().split('').reverse().join('').includes(pattern)
    );
    
    if (isCommon) {
        score = Math.max(0, score - 2);
        feedback.push('Avoid common words and patterns');
    }
    
    // Cap the score
    score = Math.min(4, Math.max(0, score));
    
    // Determine strength class
    const strengthClasses = [
        'text-red-600', 'text-orange-500', 'text-yellow-500', 'text-blue-500', 'text-green-600'
    ];
    
    return {
        index: score,
        class: strengthClasses[score],
        feedback: feedback.length > 0 ? feedback.join(', ') : ''
    };
}

// Initial validation on page load
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('update_password_password');
    const confirmInput = document.getElementById('update_password_password_confirmation');
    
    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            validatePassword(this.value);
        });
    }
    
    if (confirmInput) {
        confirmInput.addEventListener('input', function() {
            checkPasswordMatch();
        });
    }
});
</script>
<style>
.password-requirement {
    transition: color 0.3s ease;
    display: flex;
    align-items: center;
}

.password-requirement:before {
    content: '•';
    margin-right: 0.5rem;
    font-weight: bold;
}

.password-requirement.text-green-600:before {
    content: '✓';
    color: #10B981;
}

.password-requirement.text-gray-400:before {
    content: '✗';
    color: #9CA3AF;
}

/* Add some spacing between the bullet and text */
.password-requirement {
    margin-left: -1.25rem;
    padding-left: 1.25rem;
}
</style>
@endpush
