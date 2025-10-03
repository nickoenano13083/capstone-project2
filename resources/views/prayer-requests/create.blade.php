<x-app-layout>
    <style>
        /* Mobile Responsiveness Improvements for Prayer Request Create Page */
        @media (max-width: 768px) {
            /* Mobile header adjustments */
            .mobile-header h2 {
                font-size: 1.25rem;
                text-align: center;
                line-height: 1.4;
            }
            
            /* Mobile container adjustments */
            .mobile-container {
                margin: 0 -1rem;
                border-radius: 0;
            }
            
            /* Mobile form styling */
            .mobile-form {
                padding: 1rem;
            }
            
            .mobile-form-group {
                margin-bottom: 1.5rem;
            }
            
            .mobile-form-group label {
                display: block;
                font-weight: 600;
                color: #374151;
                margin-bottom: 0.5rem;
                font-size: 0.875rem;
            }
            
            .mobile-form-group input,
            .mobile-form-group select,
            .mobile-form-group textarea {
                width: 100%;
                padding: 0.75rem;
                border: 1px solid #d1d5db;
                border-radius: 0.5rem;
                font-size: 1rem;
                min-height: 48px;
                background: white;
            }
            
            .mobile-form-group input:focus,
            .mobile-form-group select:focus,
            .mobile-form-group textarea:focus {
                outline: none;
                border-color: #3b82f6;
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            }
            
            .mobile-form-group textarea {
                min-height: 120px;
                resize: vertical;
            }
            
            /* Mobile info banner */
            .mobile-info-banner {
                margin: 1rem;
                padding: 1rem;
                border-radius: 0.5rem;
                border-left: 4px solid #3b82f6;
                background: #eff6ff;
            }
            
            .mobile-info-banner p {
                margin: 0;
                color: #1e40af;
                font-size: 0.875rem;
                line-height: 1.5;
            }
            
            .mobile-info-banner .font-medium {
                font-weight: 600;
            }
            
            /* Mobile action buttons */
            .mobile-actions {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                background: white;
                border-top: 1px solid #e5e7eb;
                padding: 1rem;
                box-shadow: 0 -4px 6px -1px rgba(0, 0, 0, 0.1);
                z-index: 10;
            }
            
            .mobile-actions .flex {
                gap: 0.75rem;
            }
            
            .mobile-actions button,
            .mobile-actions a {
                flex: 1;
                min-height: 48px;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
                font-weight: 600;
                border-radius: 0.5rem;
                font-size: 0.875rem;
                text-decoration: none;
                border: none;
                cursor: pointer;
            }
            
            .mobile-cancel-btn {
                background: #6b7280;
                color: white;
            }
            
            .mobile-cancel-btn:hover {
                background: #4b5563;
            }
            
            .mobile-submit-btn {
                background: #3b82f6;
                color: white;
            }
            
            .mobile-submit-btn:hover {
                background: #2563eb;
            }
            
            /* Add bottom padding to prevent content from being hidden behind fixed actions */
            .mobile-content-wrapper {
                padding-bottom: 5rem;
            }
            
            /* Hide desktop actions on mobile */
            .desktop-actions {
                display: none;
            }
            
            /* Error messages styling */
            .mobile-error {
                color: #dc2626;
                font-size: 0.875rem;
                margin-top: 0.25rem;
            }
        }
        
        @media (min-width: 769px) {
            .mobile-only {
                display: none;
            }
            
            .desktop-only {
                display: block;
            }
        }
        
        @media (max-width: 768px) {
            .mobile-only {
                display: block;
            }
            
            .desktop-only {
                display: none;
            }
        }
    </style>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight mobile-header">
            @if(auth()->user()->role === 'Member')
                {{ __('Send Prayer Request') }}
            @else
                {{ __('Create Prayer Request') }}
            @endif
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mobile-container">
                <div class="p-6 text-gray-900 mobile-content-wrapper">
                    @if(auth()->user()->role === 'Member' && $member)
                        <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded mb-4 mobile-info-banner">
                            <p class="font-medium">Sending Prayer Request</p>
                            <p class="text-sm">You are sending this prayer request as: <strong>{{ $member->name }}</strong></p>
                        </div>
                    @endif

                    <form action="{{ route('prayer-requests.store') }}" method="POST" class="mobile-form">
                        @csrf

                        <!-- Member Selection (only for Admin/Leader) -->
                        @if(auth()->user()->role !== 'Member')
                            <div class="mb-4 mobile-form-group">
                                <x-input-label for="member_id" :value="__('Member')" />
                                <select id="member_id" name="member_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Select a member</option>
                                    @foreach($members as $member)
                                        <option value="{{ $member->id }}" {{ old('member_id') == $member->id ? 'selected' : '' }}>
                                            {{ $member->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('member_id')" class="mt-2 mobile-error" />
                            </div>
                        @endif

                        <!-- Prayer Request -->
                        <div class="mb-4 mobile-form-group">
                            <x-input-label for="request" :value="__('Prayer Request')" />
                            <textarea id="request" name="request" rows="4" maxlength="1000" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Enter the prayer request details..." required>{{ old('request') }}</textarea>
                            <div class="flex items-center justify-between mt-2 text-xs text-gray-500">
                                <div class="flex items-center gap-2">
                                    <span>Suggestions:</span>
                                    <button type="button" class="px-2 py-1 rounded-full bg-indigo-50 text-indigo-600 hover:bg-indigo-100 border border-indigo-100 transition" data-prayer-template="Please pray for healing and full recovery.">Healing</button>
                                    <button type="button" class="px-2 py-1 rounded-full bg-indigo-50 text-indigo-600 hover:bg-indigo-100 border border-indigo-100 transition" data-prayer-template="Praying for God’s provision and financial breakthrough.">Provision</button>
                                    <button type="button" class="px-2 py-1 rounded-full bg-indigo-50 text-indigo-600 hover:bg-indigo-100 border border-indigo-100 transition" data-prayer-template="Seeking guidance and wisdom for decisions ahead.">Guidance</button>
                                </div>
                                <span id="request-char-count">0 / 1000</span>
                            </div>
                            <x-input-error :messages="$errors->get('request')" class="mt-2 mobile-error" />
                        </div>

                        <!-- Category -->
                        <div class="mb-4 mobile-form-group">
                            <x-input-label for="category" :value="__('Category')" />
                            <select id="category" name="category" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">Select a category (optional)</option>
                                <option value="healing" {{ old('category') == 'healing' ? 'selected' : '' }}>Healing</option>
                                <option value="family" {{ old('category') == 'family' ? 'selected' : '' }}>Family</option>
                                <option value="work_school" {{ old('category') == 'work_school' ? 'selected' : '' }}>Work/School</option>
                                <option value="deliverance" {{ old('category') == 'deliverance' ? 'selected' : '' }}>Deliverance</option>
                                <option value="church" {{ old('category') == 'church' ? 'selected' : '' }}>Church</option>
                                <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            <x-input-error :messages="$errors->get('category')" class="mt-2 mobile-error" />
                        </div>

                        @if(auth()->user()->role !== 'Member')
                            <!-- Status -->
                            <div class="mb-4 mobile-form-group">
                                <x-input-label for="status" :value="__('Status')" />
                                <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="answered" {{ old('status') == 'answered' ? 'selected' : '' }}>Answered</option>
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2 mobile-error" />
                            </div>

                            <!-- Response -->
                            <div class="mb-4 mobile-form-group">
                                <x-input-label for="response" :value="__('Response (Optional)')" />
                                <textarea id="response" name="response" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Enter any response or notes...">{{ old('response') }}</textarea>
                                <x-input-error :messages="$errors->get('response')" class="mt-2 mobile-error" />
                            </div>
                        @endif

                        <!-- Prayer Date -->
                        <div class="mb-4 mobile-form-group">
                            <x-input-label for="prayer_date" :value="__('Prayer Date')" />
                            <input type="date" id="prayer_date" name="prayer_date" value="{{ old('prayer_date', date('Y-m-d')) }}" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                            <x-input-error :messages="$errors->get('prayer_date')" class="mt-2 mobile-error" />
                        </div>

                        <!-- Desktop Submit Buttons -->
                        <div class="flex items-center justify-end mt-4 desktop-actions">
                            <x-secondary-button type="button" onclick="window.location='{{ route('prayer-requests.index') }}'" class="mr-3">
                                {{ __('Cancel') }}
                            </x-secondary-button>
                            <x-primary-button>
                                @if(auth()->user()->role === 'Member')
                                    {{ __('Send Prayer Request') }}
                                @else
                                    {{ __('Create Prayer Request') }}
                                @endif
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Action Buttons -->
    <div class="mobile-actions mobile-only">
        <div class="flex">
            <button type="button" onclick="window.location='{{ route('prayer-requests.index') }}'" class="mobile-cancel-btn">
                <i class="fas fa-times"></i>
                Cancel
            </button>
            <button type="submit" form="mobile-form" class="mobile-submit-btn">
                <i class="fas fa-paper-plane"></i>
                @if(auth()->user()->role === 'Member')
                    Send Request
                @else
                    Create Request
                @endif
            </button>
        </div>
    </div>

    <script>
        // Add form ID for mobile submit button + enhance textarea with counter and templates
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form[action="{{ route('prayer-requests.store') }}"]');
            if (form) {
                form.id = 'mobile-form';
            }

            const textarea = document.getElementById('request');
            const counter = document.getElementById('request-char-count');
            const maxLen = parseInt(textarea.getAttribute('maxlength') || '1000', 10);

            function updateCount() {
                const length = textarea.value.length;
                counter.textContent = `${length} / ${maxLen}`;
                if (length > maxLen) counter.style.color = '#dc2626'; else counter.style.color = '#6b7280';
            }

            textarea.addEventListener('input', updateCount);
            updateCount();

            document.querySelectorAll('[data-prayer-template]').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const template = this.getAttribute('data-prayer-template') || '';
                    if (!template) return;
                    const current = textarea.value.trim();
                    const separator = current ? (current.endsWith('.') ? '\n\n' : '\n\n') : '';
                    const next = current ? (current + separator + template) : template;
                    textarea.value = next.slice(0, maxLen);
                    textarea.dispatchEvent(new Event('input'));
                    textarea.focus();
                });
            });
        });
    </script>
</x-app-layout>