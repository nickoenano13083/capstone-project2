<x-app-layout>
    <!-- Font Awesome CDN for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Modern glassmorphism and gradient styles */
        .glass-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .glass-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.1);
            border-color: #6366f1;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        }
        

        .member-avatar {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-weight: 600;
            color: white;
            text-transform: uppercase;
            font-size: 0.875rem;
        }

        .member-card {
            background: white;
            border: 1px solid #f1f5f9;
            transition: all 0.2s ease;
        }

        .member-card:hover {
            background-color: #f8fafc;
            border-color: #e2e8f0;
        }

        .attendance-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
            text-transform: capitalize;
        }

        .present-badge {
            background-color: #dcfce7;
            color: #166534;
        }

        .absent-badge {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .event-header {
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .event-header:hover {
            background-color: #f8fafc;
        }

        .event-icon {
            background: #6366f1;
            transition: all 0.2s ease;
        }

        .glass-card:hover .event-icon {
            background: #4f46e5;
        }

        .attendance-badge {
            background: #10b981;
            color: white;
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.2s ease;
        }


        .search-input {
            transition: all 0.3s ease;
            border: 2px solid #e5e7eb;
        }

        .search-input:focus {
            border-color: #8b5cf6;
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
        }

        .loading-spinner {
            width: 1.5rem;
            height: 1.5rem;
            border: 3px solid rgba(139, 92, 246, 0.1);
            border-radius: 50%;
            border-top-color: #8b5cf6;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .fade-enter-active, .fade-leave-active {
            transition: opacity 0.3s ease;
        }
        .fade-enter-from, .fade-leave-to {
            opacity: 0;
        }
    </style>

    <div class="py-6">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-8">
                <x-page-header :icon="'fas fa-clipboard-check'" title="Attendance Records" subtitle="View and manage attendance across all events" />
            </div>


            <!-- Search and Filters -->
            <div class="glass-card p-6 mb-6">
                <form action="{{ route('attendance.index') }}" method="GET" class="space-y-4">
                    <div class="flex flex-col md:flex-row gap-4">
                        <!-- Search Input -->
                        <div class="flex-1">
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search Events</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                                       class="search-input block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                                       placeholder="Search events...">
                            </div>
                        </div>

                        <!-- Chapter Filter -->
                        @if(auth()->user()->role !== 'Member')
                        <div class="w-full md:w-64">
                            <label for="chapter_id" class="block text-sm font-medium text-gray-700 mb-1">Filter by Chapter</label>
                            <select name="chapter_id" id="chapter_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-lg">
                                <option value="all" {{ request('chapter_id') === 'all' ? 'selected' : '' }}>All Chapters</option>
                                @foreach(\App\Models\Chapter::orderBy('name')->get() as $chapter)
                                    <option value="{{ $chapter->id }}" {{ request('chapter_id') == $chapter->id ? 'selected' : '' }}>{{ $chapter->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <!-- Date Range Filter -->
                        <div class="w-full md:w-64">
                            <label for="date_range" class="block text-sm font-medium text-gray-700 mb-1">Date Range</label>
                            <select name="date_range" id="date_range" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-lg">
                                <option value="all" {{ request('date_range') === 'all' ? 'selected' : '' }}>All Time</option>
                                <option value="today" {{ request('date_range') === 'today' ? 'selected' : '' }}>Today</option>
                                <option value="week" {{ request('date_range') === 'week' ? 'selected' : '' }}>This Week</option>
                                <option value="month" {{ request('date_range') === 'month' ? 'selected' : '' }}>This Month</option>
                                <option value="year" {{ request('date_range') === 'year' ? 'selected' : '' }}>This Year</option>
                            </select>
                        </div>

                        <!-- Status Filter -->
                        <div class="w-full md:w-48">
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" id="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-lg">
                                <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>All Statuses</option>
                                <option value="present" {{ request('status') === 'present' ? 'selected' : '' }}>Present</option>
                                <option value="absent" {{ request('status') === 'absent' ? 'selected' : '' }}>Absent</option>
                            </select>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 h-10">
                                <svg class="-ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 019 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                                </svg>
                                Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Events List -->
            <div class="space-y-6">
                @forelse($events as $event)
                    @php
                        $presentCount = $event->attendance->where('status', 'present')->count();
                        $absentCount = $event->attendance->where('status', 'absent')->count();
                        $totalAttendees = $presentCount + $absentCount;
                    @endphp

                    <div class="glass-card overflow-hidden">
                        <!-- Event Header -->
                        <a href="{{ route('attendance.event', $event) }}" class="event-header bg-white px-6 py-4 border-b border-gray-200 block">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0 event-icon text-white p-2 rounded-lg">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">
                                            {{ $event->title }}
                                        </h3>
                                        <div class="flex items-center space-x-4 text-sm text-gray-500 mt-1">
                                            <span>{{ $event->location }}</span>
                                            <span>•</span>
                                            <span>{{ \Carbon\Carbon::parse($event->date)->format('M d, Y') }} {{ $event->time }}</span>
                                            @if($event->chapter)
                                            <span>•</span>
                                            <span>{{ $event->chapter->name }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="attendance-badge">
                                    {{ $presentCount }}/{{ $totalAttendees }}
                                </div>
                            </div>
                        </a>

                    </div>
                @empty
                    <div class="text-center py-12 bg-white rounded-lg border-2 border-dashed border-gray-300">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No events found</h3>
                        <p class="mt-1 text-sm text-gray-500">There are no attendance records to display.</p>
                        @if(auth()->user()->can('create', \App\Models\Event::class))
                        <div class="mt-6">
                            <a href="{{ route('events.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 01-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                New Event
                            </a>
                        </div>
                        @endif
                    </div>
                @endforelse

                <!-- Pagination -->
                @if($events->hasPages())
                <div class="mt-8">
                    {{ $events->withQueryString()->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Handle search input debounce
            let searchTimeout;
            const searchInput = document.getElementById('search');
            
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    const form = this.closest('form');
                    
                    // Show loading state
                    const submitButton = form.querySelector('button[type="submit"]');
                    const originalButtonContent = submitButton.innerHTML;
                    submitButton.innerHTML = '<span class="loading-spinner"></span> Searching...';
                    submitButton.disabled = true;
                    
                    searchTimeout = setTimeout(() => {
                        form.submit();
                    }, 500);
                });
            }

            // Handle add attendance button
            document.querySelectorAll('[data-add-attendance]').forEach(button => {
                button.addEventListener('click', function() {
                    const eventId = this.getAttribute('data-event-id');
                    // Implement your add attendance modal or redirect logic here
                    console.log('Add attendance for event:', eventId);
                });
            });

            // Handle edit attendance button
            document.querySelectorAll('[data-edit-attendance]').forEach(button => {
                button.addEventListener('click', function() {
                    const attendanceId = this.getAttribute('data-id');
                    // Implement your edit attendance modal or redirect logic here
                    console.log('Edit attendance:', attendanceId);
                });
            });
        });
    </script>
    @endpush
</x-app-layout>