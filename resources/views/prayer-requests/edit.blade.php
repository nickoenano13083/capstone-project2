<x-app-layout>
    <style>
        /* Mobile Responsiveness Improvements for Prayer Request Edit Page */
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
            
            /* Mobile grid layout */
            .mobile-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
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
            
            /* Mobile card styling */
            .mobile-card {
                padding: 1rem;
                margin-bottom: 1rem;
                border-radius: 0.5rem;
            }
            
            .mobile-card h3 {
                font-size: 1.125rem;
                font-weight: 600;
                color: #374151;
                margin-bottom: 0.75rem;
                display: flex;
                align-items: center;
                gap: 0.5rem;
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
            
            /* Mobile member info display */
            .mobile-member-info {
                background: #f9fafb;
                border: 1px solid #e5e7eb;
                border-radius: 0.5rem;
                padding: 1rem;
            }
            
            .mobile-member-info .member-detail {
                display: flex;
                flex-direction: column;
                gap: 0.25rem;
                padding: 0.5rem 0;
                border-bottom: 1px solid #e5e7eb;
            }
            
            .mobile-member-info .member-detail:last-child {
                border-bottom: none;
            }
            
            .mobile-member-info .member-label {
                font-weight: 600;
                color: #374151;
                font-size: 0.875rem;
            }
            
            .mobile-member-info .member-value {
                color: #111827;
                font-size: 1rem;
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
            
            .mobile-update-btn {
                background: #3b82f6;
                color: white;
            }
            
            .mobile-update-btn:hover {
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
            {{ __('Edit Prayer Request') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mobile-container">
                <div class="p-6 text-gray-900 mobile-content-wrapper">
                    <form action="{{ route('prayer-requests.update', $prayerRequest) }}" method="POST" class="mobile-form">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mobile-grid">
                            @if(auth()->user()->role === 'Member' && $prayerRequest->user_id === auth()->id())
                                <div class="bg-blue-50 p-4 rounded-lg col-span-2 mobile-info-banner">
                                    <p class="text-sm text-blue-700">You are editing your prayer request.</p>
                                </div>
                            @endif

                            <!-- Member Information -->
                            @if(in_array(auth()->user()->role, ['Admin', 'Leader']))
                                <div class="bg-gray-50 p-4 rounded-lg mobile-card">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-3">
                                        <i class="fas fa-user text-indigo-600"></i>
                                        Member Information
                                    </h3>
                                    <div class="mobile-member-info">
                                        <div class="member-detail">
                                            <span class="member-label">Name:</span>
                                            <span class="member-value">{{ $prayerRequest->member->name }}</span>
                                            <input type="hidden" name="member_id" value="{{ $prayerRequest->member_id }}">
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Prayer Request -->
                            <div class="bg-white p-4 rounded-lg mobile-card {{ in_array(auth()->user()->role, ['Admin', 'Leader']) ? 'md:col-span-1' : 'col-span-2' }}">
                                <h3 class="text-lg font-semibold text-gray-800 mb-3">
                                    <i class="fas fa-praying-hands text-indigo-600"></i>
                                    Prayer Request Details
                                </h3>
                                <div class="space-y-4">
                                    <div class="mobile-form-group">
                                        <x-input-label for="request" :value="__('Prayer Request')" />
                                        <textarea id="request" name="request" rows="4" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Enter the prayer request details..." required>{{ old('request', $prayerRequest->request) }}</textarea>
                                        <x-input-error :messages="$errors->get('request')" class="mt-2 mobile-error" />
                                    </div>

                                    <div class="mobile-form-group">
                                        <x-input-label for="prayer_date" :value="__('Prayer Date')" />
                                        <input type="date" id="prayer_date" name="prayer_date" value="{{ old('prayer_date', $prayerRequest->prayer_date->format('Y-m-d')) }}" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                        <x-input-error :messages="$errors->get('prayer_date')" class="mt-2 mobile-error" />
                                    </div>
                                </div>
                            </div>

                            @if(auth()->user()->role !== 'Member')
                                <!-- Status and Response -->
                                <div class="bg-gray-50 p-4 rounded-lg mobile-card">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-3">
                                        <i class="fas fa-cog text-indigo-600"></i>
                                        Administration
                                    </h3>
                                    <div class="space-y-4">
                                        <div class="mobile-form-group">
                                            <x-input-label for="status" :value="__('Status')" />
                                            <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                                <option value="pending" {{ old('status', $prayerRequest->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="answered" {{ old('status', $prayerRequest->status) == 'answered' ? 'selected' : '' }}>Answered</option>
                                            </select>
                                            <x-input-error :messages="$errors->get('status')" class="mt-2 mobile-error" />
                                        </div>

                                        <div class="mobile-form-group">
                                            <x-input-label for="response" :value="__('Response (Optional)')" />
                                            <textarea id="response" name="response" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Enter any response or notes...">{{ old('response', $prayerRequest->response) }}</textarea>
                                            <x-input-error :messages="$errors->get('response')" class="mt-2 mobile-error" />
                                        </div>
                                    </div>
                                </div>
                            @else
                                <!-- Hidden fields for members to maintain data integrity -->
                                <input type="hidden" name="status" value="{{ $prayerRequest->status }}">
                                <input type="hidden" name="response" value="{{ $prayerRequest->response }}">
                                @if($prayerRequest->member_id)
                                    <input type="hidden" name="member_id" value="{{ $prayerRequest->member_id }}">
                                @endif
                            @endif
                        </div>

                        <!-- Desktop Submit Buttons -->
                        <div class="mt-6 flex justify-end space-x-3 desktop-actions">
                            <x-secondary-button type="button" onclick="window.location='{{ route('prayer-requests.show', $prayerRequest) }}'" class="bg-gray-500 hover:bg-gray-600">
                                {{ __('Cancel') }}
                            </x-secondary-button>
                            <x-primary-button>
                                {{ __('Update Prayer Request') }}
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
            <button type="button" onclick="window.location='{{ route('prayer-requests.show', $prayerRequest) }}'" class="mobile-cancel-btn">
                <i class="fas fa-times"></i>
                Cancel
            </button>
            <button type="submit" form="mobile-form" class="mobile-update-btn">
                <i class="fas fa-save"></i>
                Update Request
            </button>
        </div>
    </div>

    <script>
        // Add form ID for mobile submit button
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form[action*="prayer-requests"]');
            if (form) {
                form.id = 'mobile-form';
            }
        });
    </script>
</x-app-layout>