<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Profile Settings') }}
            </h2>
            <div class="text-sm text-gray-500">
                Member since {{ $user->created_at->format('F Y') }}
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    <!-- Profile Header -->
                    <div class="flex flex-col md:flex-row items-center md:items-start gap-6 mb-8">
                        <form id="profile-picture-form" action="{{ route('profile.picture.update') }}" method="POST" enctype="multipart/form-data" class="relative group">
                            @csrf
                            @method('PUT')
                            <div class="w-24 h-24 rounded-full bg-indigo-100 flex items-center justify-center text-3xl font-bold text-indigo-600 overflow-hidden">
                                @if($user->profile_photo_path)
                                    <img src="{{ $user->profile_photo_url }}?t={{ optional($user->updated_at)->timestamp ?? time() }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-indigo-100 text-indigo-600 text-3xl font-bold">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <label for="profile_picture" class="absolute bottom-0 right-0 bg-white p-1.5 rounded-full shadow-md group-hover:bg-gray-50 transition cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <input id="profile_picture" name="profile_picture" type="file" class="hidden" accept="image/*" onchange="this.form.submit()" />
                            </label>
                        </form>
                        <div class="text-center md:text-left">
                            <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
                            <p class="text-gray-600">{{ $user->email }}</p>
                            @if($user->email_verified_at)
                                <span class="inline-flex items-center mt-1 text-sm text-green-600">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    Verified Account
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Tabs -->
                    <div class="border-b border-gray-200 mb-8">
                        <nav class="-mb-px flex space-x-8" id="profile-tabs" role="tablist">
                            <button type="button" data-tab="profile" class="profile-tab whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-indigo-500 text-indigo-600">
                                <span class="flex items-center">
                                    <svg class="mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Profile
                                </span>
                            </button>
                            <button type="button" data-tab="security" class="profile-tab whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                                <span class="flex items-center">
                                    <svg class="mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                    Security
                                </span>
                            </button>
                            <button type="button" data-tab="danger" class="profile-tab whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                                <span class="flex items-center">
                                    <svg class="mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    Danger Zone
                                </span>
                            </button>
                        </nav>
                    </div>

                    <!-- Tab Content -->
                    <!-- Profile Tab -->
                    <div id="profile-content" class="tab-pane">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Personal Information -->
                            <div class="md:col-span-2">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h3>
                                <div class="bg-gray-50 p-6 rounded-lg">
                                    @include('profile.partials.update-profile-information-form')
                                </div>
                            </div>

                            <!-- Additional Information -->
                            <div class="md:col-span-2">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Additional Information</h3>
                                <div class="bg-gray-50 p-6 rounded-lg">
                                    <form method="POST" action="{{ route('profile.personal-info.update') }}">
                                        @csrf
                                        @method('patch')
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <x-input-label for="birthday" :value="__('Birthday')" />
                                                <x-text-input id="birthday" name="birthday" type="date" class="mt-1 block w-full" :value="old('birthday', $user->birthday)" />
                                                <x-input-error class="mt-2" :messages="$errors->get('birthday')" />
                                            </div>

                                            <div>
                                                <x-input-label for="age" :value="__('Age')" />
                                                <x-text-input id="age" name="age" type="number" min="1" max="120" class="mt-1 block w-full" :value="old('age', $user->age)" />
                                                <x-input-error class="mt-2" :messages="$errors->get('age')" />
                                            </div>

                                            <div>
                                                <x-input-label for="gender" :value="__('Gender')" />
                                                <select id="gender" name="gender" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                                    <option value="">{{ __('Select Gender') }}</option>
                                                    <option value="Male" {{ old('gender', $user->gender) === 'Male' ? 'selected' : '' }}>{{ __('Male') }}</option>
                                                    <option value="Female" {{ old('gender', $user->gender) === 'Female' ? 'selected' : '' }}>{{ __('Female') }}</option>
                                                </select>
                                                <x-input-error class="mt-2" :messages="$errors->get('gender')" />
                                            </div>

                                            <div class="md:col-span-2">
                                                <x-input-label for="address" :value="__('Address')" />
                                                <textarea id="address" name="address" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('address', $user->address) }}</textarea>
                                                <x-input-error class="mt-2" :messages="$errors->get('address')" />
                                            </div>

                                            <div class="md:col-span-2 flex justify-end">
                                                <x-primary-button>{{ __('Save Changes') }}</x-primary-button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Security Tab -->
                    <div id="security-content" class="tab-pane" style="display: none;">
                        <div class="bg-gray-50 p-6 rounded-lg">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>

                    <!-- Danger Zone Tab -->
                    <div id="danger-content" class="tab-pane" style="display: none;">
                        <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-lg">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">Danger Zone</h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <p>These actions are irreversible. Please be certain.</p>
                                    </div>
                                    <div class="mt-4">
                                        @include('profile.partials.delete-user-form')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            // Get all tab buttons and content panes
                            const tabButtons = document.querySelectorAll('.profile-tab');
                            const tabPanes = document.querySelectorAll('.tab-pane');
                            
                            // Set initial active tab from URL hash
                            const hash = window.location.hash.substring(1);
                            const initialTab = ['profile', 'security', 'danger'].includes(hash) ? hash : 'profile';
                            
                            // Show initial tab
                            showTab(initialTab);
                            
                            // Add click event listeners to tab buttons
                            tabButtons.forEach(button => {
                                button.addEventListener('click', function() {
                                    const tabName = this.getAttribute('data-tab');
                                    showTab(tabName);
                                    // Update URL hash without page reload
                                    window.history.pushState(null, '', '#' + tabName);
                                });
                            });
                            
                            // Handle browser back/forward buttons
                            window.addEventListener('popstate', function() {
                                const newHash = window.location.hash.substring(1);
                                if (['profile', 'security', 'danger'].includes(newHash)) {
                                    showTab(newHash);
                                }
                            });
                            
                            // Function to show a specific tab
                            function showTab(tabName) {
                                // Hide all tab panes
                                tabPanes.forEach(pane => {
                                    pane.style.display = 'none';
                                });
                                
                                // Remove active class from all tab buttons
                                tabButtons.forEach(button => {
                                    button.classList.remove('border-indigo-500', 'text-indigo-600');
                                    button.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
                                });
                                
                                // Show the selected tab pane
                                const activePane = document.getElementById(tabName + '-content');
                                if (activePane) {
                                    activePane.style.display = 'block';
                                }
                                
                                // Add active class to the clicked tab button
                                const activeButton = document.querySelector(`.profile-tab[data-tab="${tabName}"]`);
                                if (activeButton) {
                                    activeButton.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
                                    activeButton.classList.add('border-indigo-500', 'text-indigo-600');
                                }
                            }
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    @endpush
</x-app-layout>
