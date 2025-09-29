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
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                @if(auth()->user()->role === 'Member')
                    {{ __('My Prayer Request Details') }}
                @else
                    {{ __('Prayer Request Details') }}
                @endif
            </h2>
            <div class="flex space-x-2 desktop-only">
                @if(auth()->user()->role !== 'Member')
                    <a href="{{ route('prayer-requests.edit', $prayerRequest) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                        Edit
                    </a>
                @endif
                <a href="{{ route('prayer-requests.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mobile-container">
                <div class="p-6 text-gray-900 mobile-content-wrapper">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mobile-grid">
                        <!-- Member Information -->
                        @if(auth()->user()->role !== 'Member')
                            <div class="bg-gray-50 p-4 rounded-lg mobile-card">
                                <h3 class="text-lg font-semibold text-gray-800 mb-3 mobile-content-title">
                                    <i class="fas fa-user text-indigo-600"></i>
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
                        <div class="bg-gray-50 p-4 rounded-lg mobile-card">
                            <h3 class="text-lg font-semibold text-gray-800 mb-3 mobile-content-title">
                                <i class="fas fa-info-circle text-indigo-600"></i>
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
                                <div class="mobile-info-item">
                                    <span class="mobile-info-label">Prayer Date:</span>
                                    <span class="mobile-info-value">{{ $prayerRequest->prayer_date->format('F d, Y') }}</span>
                                </div>
                                <div class="mobile-info-item">
                                    <span class="mobile-info-label">Created:</span>
                                    <span class="mobile-info-value">{{ $prayerRequest->created_at->format('F d, Y \a\t g:i A') }}</span>
                                </div>
                                <div class="mobile-info-item">
                                    <span class="mobile-info-label">Last Updated:</span>
                                    <span class="mobile-info-value">{{ $prayerRequest->updated_at->format('F d, Y \a\t g:i A') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Prayer Request Content -->
                    <div class="mt-6 mobile-content-section">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3 mobile-content-title">
                            <i class="fas fa-praying-hands text-indigo-600"></i>
                            Prayer Request
                        </h3>
                        <div class="bg-white p-4 rounded-lg border border-gray-200">
                            <p class="mobile-content-text whitespace-pre-wrap">{{ $prayerRequest->request }}</p>
                        </div>
                    </div>

                    <!-- Response Section -->
                    @if($prayerRequest->response)
                        <div class="mt-6 mobile-content-section">
                            <h3 class="text-lg font-semibold text-gray-800 mb-3 mobile-content-title">
                                <i class="fas fa-reply text-green-600"></i>
                                Response
                            </h3>
                            <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                                <p class="mobile-content-text whitespace-pre-wrap">{{ $prayerRequest->response }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Desktop Action Buttons -->
                    @if(auth()->user()->role !== 'Member')
                        <div class="mt-6 flex justify-end space-x-3 desktop-actions">
                            <form action="{{ route('prayer-requests.destroy', $prayerRequest) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('Are you sure you want to delete this prayer request?')">
                                    Delete
                                </button>
                            </form>
                        </div>
                    @endif
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
            @if(auth()->user()->role !== 'Member')
                <a href="{{ route('prayer-requests.edit', $prayerRequest) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white">
                    <i class="fas fa-edit"></i>
                    Edit
                </a>
                <form action="{{ route('prayer-requests.destroy', $prayerRequest) }}" method="POST" class="flex-1" onsubmit="return confirm('Are you sure you want to delete this prayer request?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="mobile-delete-btn w-full">
                        <i class="fas fa-trash-alt"></i>
                        Delete
                    </button>
                </form>
            @endif
        </div>
    </div>
</x-app-layout>