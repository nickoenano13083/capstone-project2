<x-app-layout>
    <style>
        /* Mobile Responsiveness Improvements for Prayer Request Show Page */
        @media (max-width: 768px) {
            /* Mobile header adjustments */
            .mobile-header {
                flex-direction: column;
                gap: 1rem;
                align-items: stretch;
            }
            
            .mobile-header h2 {
                font-size: 1.25rem;
                text-align: center;
                line-height: 1.4;
            }
            
            .mobile-header .flex {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .mobile-header .flex a,
            .mobile-header .flex button {
                width: 100%;
                justify-content: center;
                min-height: 48px;
                padding: 0.75rem 1rem;
                font-size: 0.875rem;
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
            
            /* Mobile card styling */
            .mobile-card {
                padding: 1rem;
                margin-bottom: 1rem;
            }
            
            /* Mobile information display */
            .mobile-info-item {
                display: flex;
                flex-direction: column;
                gap: 0.25rem;
                padding: 0.75rem 0;
                border-bottom: 1px solid #e5e7eb;
            }
            
            .mobile-info-item:last-child {
                border-bottom: none;
            }
            
            .mobile-info-label {
                font-weight: 600;
                color: #374151;
                font-size: 0.875rem;
                text-transform: uppercase;
                letter-spacing: 0.05em;
            }
            
            .mobile-info-value {
                color: #111827;
                font-size: 1rem;
                line-height: 1.5;
            }
            
            /* Mobile status badge */
            .mobile-status-badge {
                align-self: flex-start;
                margin-top: 0.25rem;
            }
            
            /* Mobile content sections */
            .mobile-content-section {
                background: #f9fafb;
                border-radius: 0.5rem;
                padding: 1rem;
                margin: 1rem 0;
            }
            
            .mobile-content-title {
                font-size: 1.125rem;
                font-weight: 600;
                color: #374151;
                margin-bottom: 0.75rem;
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }
            
            .mobile-content-text {
                color: #111827;
                line-height: 1.6;
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
            }
            
            .mobile-delete-btn {
                background: #ef4444;
                color: white;
                border: none;
                cursor: pointer;
            }
            
            .mobile-delete-btn:hover {
                background: #dc2626;
            }
            
            /* Add bottom padding to prevent content from being hidden behind fixed actions */
            .mobile-content-wrapper {
                padding-bottom: 5rem;
            }
            
            /* Hide desktop actions on mobile */
            .desktop-actions {
                display: none;
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
        <div class="flex justify-between items-center mobile-header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center gap-3">
                @if(auth()->user()->role === 'Member')
                    {{ __('My Prayer Request Details') }}
                @else
                    {{ __('Prayer Request Details') }}
                @endif
                @if($prayerRequest->status !== 'answered')
                    <span class="ml-2 px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full border
                        {{ $prayerRequest->status === 'pending' ? 'bg-yellow-50 text-yellow-700 border-yellow-200' : ($prayerRequest->status === 'in_progress' ? 'bg-blue-50 text-blue-700 border-blue-200' : 'bg-green-50 text-green-700 border-green-200') }}">
                        <i class="fas fa-circle mr-1 text-[8px]"></i>
                        {{ ucfirst(str_replace('_', ' ', $prayerRequest->status)) }}
                    </span>
                @endif
            </h2>
            <div class="flex space-x-2 desktop-only">
                <a href="{{ route('prayer-requests.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="w-full max-w-none mx-0 px-0">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mobile-container">
                <div class="p-6 text-gray-900 mobile-content-wrapper">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mobile-grid">
                        <!-- Member Information -->
                        @if(auth()->user()->role !== 'Member')
                            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm mobile-card">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4 mobile-content-title flex items-center gap-2">
                                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-indigo-50 text-indigo-600">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    Member Information
                                </h3>
                                <div class="space-y-2">
                                    <div class="mobile-info-item">
                                        <span class="mobile-info-label">Name:</span>
                                        <span class="mobile-info-value">{{ $prayerRequest->member->name }}</span>
                                    </div>
                                    <div class="mobile-info-item">
                                        <span class="mobile-info-label">Email:</span>
                                        <span class="mobile-info-value">{{ $prayerRequest->member->email }}</span>
                                    </div>
                                    <div class="mobile-info-item">
                                        <span class="mobile-info-label">Phone:</span>
                                        <span class="mobile-info-value">{{ $prayerRequest->member->phone ?? 'Not provided' }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Request Information -->
                        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm mobile-card">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 mobile-content-title flex items-center gap-2">
                                <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-indigo-50 text-indigo-600">
                                    <i class="fas fa-info-circle"></i>
                                </span>
                                Request Information
                            </h3>
                            <div class="space-y-2">
                                <div class="mobile-info-item">
                                    <span class="mobile-info-label">Status:</span>
                                    <span class="mobile-info-value">
                                        <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full mobile-status-badge {{ $prayerRequest->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : ($prayerRequest->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                            {{ ucfirst(str_replace('_', ' ', $prayerRequest->status)) }}
                                        </span>
                                    </span>
                                </div>
                                @if($prayerRequest->approved_by || $prayerRequest->responded_by)
                                    <div class="mobile-info-item">
                                        <span class="mobile-info-label">Admin Actions:</span>
                                        <div class="mobile-info-value space-y-1">
                                            @if($prayerRequest->approved_by && $prayerRequest->approver)
                                                <div class="text-sm text-gray-700">
                                                    <i class="fas fa-user-check text-green-600 mr-1"></i>
                                                    Approved by: <span class="font-medium">{{ $prayerRequest->approver->name }}</span>
                                                    @if($prayerRequest->approver->preferred_chapter_id && $prayerRequest->approver->preferredChapter)
                                                        <span class="text-gray-500">(Chapter: {{ $prayerRequest->approver->preferredChapter->name }})</span>
                                                    @endif
                                                </div>
                                            @endif
                                            @if($prayerRequest->responded_by && $prayerRequest->responder)
                                                <div class="text-sm text-gray-700">
                                                    <i class="fas fa-reply text-indigo-600 mr-1"></i>
                                                    Responded by: <span class="font-medium">{{ $prayerRequest->responder->name }}</span>
                                                    @if($prayerRequest->responder->preferred_chapter_id && $prayerRequest->responder->preferredChapter)
                                                        <span class="text-gray-500">(Chapter: {{ $prayerRequest->responder->preferredChapter->name }})</span>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                                @if($prayerRequest->category)
                                    <div class="mobile-info-item">
                                        <span class="mobile-info-label">Category:</span>
                                        <span class="mobile-info-value">
                                            @php
                                                $categoryMap = [
                                                    'healing' => ['Healing', 'bg-purple-100 text-purple-800'],
                                                    'family' => ['Family', 'bg-pink-100 text-pink-800'],
                                                    'work_school' => ['Work/School', 'bg-indigo-100 text-indigo-800'],
                                                    'deliverance' => ['Deliverance', 'bg-red-100 text-red-800'],
                                                    'church' => ['Church', 'bg-blue-100 text-blue-800'],
                                                    'other' => ['Other', 'bg-gray-100 text-gray-800'],
                                                ];
                                                [$label, $classes] = $categoryMap[$prayerRequest->category] ?? [$prayerRequest->category, 'bg-gray-100 text-gray-800'];
                                            @endphp
                                            <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full {{ $classes }}">
                                                <i class="fas fa-tag mr-1"></i>
                                                {{ $label }}
                                            </span>
                                        </span>
                                    </div>
                                @endif
                                <div class="mobile-info-item">
                                    <span class="mobile-info-label">Prayer Date:</span>
                                    <span class="mobile-info-value">{{ $prayerRequest->prayer_date->format('F d, Y') }}</span>
                                </div>
                                <div class="mobile-info-item">
                                    <span class="mobile-info-label">Created:</span>
                                    <span class="mobile-info-value">{{ $prayerRequest->created_at->format('F d, Y \\a\\t g:i A') }}</span>
                                </div>
                                <div class="mobile-info-item">
                                    <span class="mobile-info-label">Last Updated:</span>
                                    <span class="mobile-info-value">{{ $prayerRequest->updated_at->format('F d, Y \\a\\t g:i A') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Prayer Request and Response (aligned to cards above) -->
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-yellow-50 p-5 rounded-xl border border-yellow-200 shadow-sm">
                            <h3 class="text-lg font-semibold text-gray-800 mb-3 mobile-content-title flex items-center gap-2">
                                <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-indigo-50 text-indigo-600">
                                    <i class="fas fa-praying-hands"></i>
                                </span>
                                Prayer Request
                            </h3>
                            <p class="mobile-content-text whitespace-pre-wrap">{{ $prayerRequest->request }}</p>
                        </div>

                        @if($prayerRequest->response)
                            <div class="bg-green-50 p-5 rounded-xl border border-green-200 shadow-sm">
                                <h3 class="text-lg font-semibold text-gray-800 mb-3 mobile-content-title flex items-center gap-2">
                                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-green-50 text-green-600">
                                        <i class="fas fa-reply"></i>
                                    </span>
                                    Response
                                </h3>
                                <p class="mobile-content-text whitespace-pre-wrap">{{ $prayerRequest->response }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Desktop Action Buttons -->
                    @can('manage', App\Models\PrayerRequest::class)
                        <div class="mt-6 flex justify-end space-x-3 desktop-actions">
                            @if($prayerRequest->status !== 'answered')
                                <form action="{{ route('prayer-requests.approve', $prayerRequest) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('Mark this prayer request as approved?')">
                                        Approved
                                    </button>
                                </form>
                            @endif
                            @can('delete', $prayerRequest)
                                <form action="{{ route('prayer-requests.destroy', $prayerRequest) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('Are you sure you want to archive this prayer request?')">
                                        Archive
                                    </button>
                                </form>
                            @endcan
                        </div>
                    @endcan

                    @can('manage', App\Models\PrayerRequest::class)
                        <div class="mt-6 mobile-content-section">
                            <h3 class="text-lg font-semibold text-gray-800 mb-3 mobile-content-title flex items-center gap-2">
                                <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-indigo-50 text-indigo-600">
                                    <i class="fas fa-comment-dots"></i>
                                </span>
                                Admin Response
                            </h3>
                            <form action="{{ route('prayer-requests.update', $prayerRequest) }}" method="POST" class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                                @csrf
                                @method('PUT')
                                <label for="response" class="block text-sm font-medium text-gray-700 mb-2">Response (visible to the requester)</label>
                                <textarea id="response" name="response" rows="4" class="w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Write your response here...">{{ old('response', $prayerRequest->response) }}</textarea>
                                <div class="mt-3 flex items-center justify-between">
                                    <div class="text-sm text-gray-500">
                                        For: <span class="font-medium text-gray-700">{{ $prayerRequest->member->name ?? ($prayerRequest->user->name ?? 'Unknown') }}</span>
                                    </div>
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm rounded-lg shadow-sm">
                                        <i class="fas fa-save mr-2"></i> Save Response
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Action Buttons -->
    <div class="mobile-actions mobile-only">
        <div class="flex">
            <a href="{{ route('prayer-requests.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white">
                <i class="fas fa-arrow-left"></i>
                Back to List
            </a>
            @can('manage', App\Models\PrayerRequest::class)
                @if($prayerRequest->status !== 'answered')
                    <form action="{{ route('prayer-requests.approve', $prayerRequest) }}" method="POST" class="flex-1" onsubmit="return confirm('Mark this prayer request as approved?')">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white rounded w-full">
                            <i class="fas fa-check"></i>
                            Approved
                        </button>
                    </form>
                @endif
                @can('delete', $prayerRequest)
                    <form action="{{ route('prayer-requests.destroy', $prayerRequest) }}" method="POST" class="flex-1" onsubmit="return confirm('Are you sure you want to archive this prayer request?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="mobile-delete-btn w-full">
                            <i class="fas fa-archive"></i>
                            Archive
                        </button>
                    </form>
                @endcan
            @endcan
        </div>
    </div>
</x-app-layout>