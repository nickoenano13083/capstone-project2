<x-app-layout>
    <!-- Font Awesome CDN for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Modern glassmorphism and gradient styles */
        .glass-card {
            background: rgba(255, 255, 255, 0.97);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .glass-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        }
        
        .stat-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
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
            background-color: #f9fafb;
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

            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                @php
                    $totalEvents = $events->total();
                    $totalPresent = $events->sum(function($event) {
                        return $event->attendance->where('status', 'present')->count();
                    });
                    $totalAbsent = $events->sum(function($event) {
                        return $event->attendance->where('status', 'absent')->count();
                    });
                    $attendanceRate = $totalEvents > 0 ? round(($totalPresent / ($totalPresent + $totalAbsent)) * 100) : 0;
                @endphp

                <div class="stat-card p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-indigo-100 text-indigo-600 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Events</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $totalEvents }}</p>
                        </div>
                    </div>
                </div>

                <div class="stat-card p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Present</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $totalPresent }}</p>
                        </div>
                    </div>
                </div>

                <div class="stat-card p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-red-100 text-red-600 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Absent</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $totalAbsent }}</p>
                        </div>
                    </div>
                </div>

                <div class="stat-card p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Attendance Rate</p>
                            <div class="flex items-center">
                                <p class="text-2xl font-semibold text-gray-900 mr-2">{{ $attendanceRate }}%</p>
                                <div class="h-2 flex-1 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-blue-500" style="width: {{ $attendanceRate }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
                        $attendancePercentage = $totalAttendees > 0 ? round(($presentCount / $totalAttendees) * 100) : 0;
                    @endphp

                    <div class="glass-card overflow-hidden">
                        <!-- Event Header -->
                        <div class="event-header bg-white px-6 py-4 border-b border-gray-200">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 bg-indigo-100 text-indigo-600 p-2 rounded-lg">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <a href="{{ route('events.show', $event) }}" class="text-lg font-semibold text-gray-900 hover:text-indigo-600 transition-colors duration-200 cursor-pointer">
                                                {{ $event->title }}
                                            </a>
                                            <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-4">
                                                <div class="flex items-center text-sm text-gray-500">
                                                    <svg class="flex-shrink-0 mr-1.5 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                                    </svg>
                                                    {{ $event->location }}
                                                </div>
                                                <div class="flex items-center text-sm text-gray-500 mt-1 sm:mt-0">
                                                    <svg class="flex-shrink-0 mr-1.5 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                                    </svg>
                                                    {{ \Carbon\Carbon::parse($event->date)->format('M d, Y') }} • {{ $event->time }}
                                                </div>
                                                @if($event->chapter)
                                                <div class="flex items-center text-sm text-gray-500 mt-1 sm:mt-0">
                                                    <svg class="flex-shrink-0 mr-1.5 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                    </svg>
                                                    {{ $event->chapter->name }}
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3 md:mt-0 flex items-center">
                                    <div class="text-right">
                                        <div class="text-sm font-medium text-gray-500">Attendance</div>
                                        <div class="flex items-center">
                                            <span class="text-lg font-semibold text-gray-900">{{ $presentCount }}/{{ $totalAttendees }}</span>
                                            <span class="ml-2 text-sm {{ $attendancePercentage >= 70 ? 'text-green-600' : ($attendancePercentage >= 40 ? 'text-yellow-600' : 'text-red-600') }}">
                                                ({{ $attendancePercentage }}%)
                                            </span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                            <div class="h-2 rounded-full {{ $attendancePercentage >= 70 ? 'bg-green-500' : ($attendancePercentage >= 40 ? 'bg-yellow-500' : 'bg-red-500') }}" 
                                                 style="width: {{ $attendancePercentage }}%">
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="ml-4 -mr-1 flex-shrink-0 flex items-center justify-center h-8 w-8 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500" aria-expanded="false" data-event-toggle="{{ $event->id }}">
                                        <span class="sr-only">View attendance</span>
                                        <svg class="h-5 w-5 transform transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Attendance Details -->
                        <div id="event-{{ $event->id }}" class="hidden transition-all duration-200 ease-in-out">
                            <div class="bg-gray-50 px-6 py-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Present Members -->
                                    <div>
                                        <div class="flex items-center justify-between mb-3">
                                            <h4 class="text-sm font-medium text-green-700 flex items-center">
                                                <svg class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                </svg>
                                                Present ({{ $presentCount }})
                                            </h4>
                                            @if(auth()->user()->can('create', [\App\Models\Attendance::class, $event]))
                                            <button type="button" class="text-xs text-indigo-600 hover:text-indigo-900 font-medium" data-add-attendance data-event-id="{{ $event->id }}">
                                                + Add Member
                                            </button>
                                            @endif
                                        </div>
                                        
                                        @if($presentCount > 0)
                                            <div class="space-y-2">
                                                @foreach($event->attendance->where('status', 'present') as $attendance)
                                                    <div class="flex items-center justify-between bg-white p-3 rounded-lg border border-gray-100 shadow-sm">
                                                        <div class="flex items-center">
                                                            <div class="member-avatar bg-green-100 text-green-800">
                                                                {{ substr($attendance->member->name, 0, 2) }}
                                                            </div>
                                                            <div class="ml-3">
                                                                <p class="text-sm font-medium text-gray-900">{{ $attendance->member->name }}</p>
                                                                @if($attendance->check_in_time)
                                                                <p class="text-xs text-gray-500">Checked in at {{ \Carbon\Carbon::parse($attendance->check_in_time)->format('g:i A') }}</p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="flex items-center space-x-2">
                                                            <span class="attendance-badge present-badge">Present</span>
                                                            @if(auth()->user()->can('update', $attendance))
                                                            <button type="button" class="text-gray-400 hover:text-indigo-600" data-edit-attendance data-id="{{ $attendance->id }}">
                                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                                </svg>
                                                            </button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="text-center py-4 bg-white rounded-lg border border-gray-100">
                                                <p class="text-sm text-gray-500">No members marked as present</p>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Absent Members -->
                                    <div>
                                        <div class="flex items-center justify-between mb-3">
                                            <h4 class="text-sm font-medium text-red-700 flex items-center">
                                                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                                Absent ({{ $absentCount }})
                                            </h4>
                                        </div>
                                        
                                        @if($absentCount > 0)
                                            <div class="space-y-2">
                                                @foreach($event->attendance->where('status', 'absent') as $attendance)
                                                    <div class="flex items-center justify-between bg-white p-3 rounded-lg border border-gray-100 shadow-sm">
                                                        <div class="flex items-center">
                                                            <div class="member-avatar bg-red-100 text-red-800">
                                                                {{ substr($attendance->member->name, 0, 2) }}
                                                            </div>
                                                            <div class="ml-3">
                                                                <p class="text-sm font-medium text-gray-900">{{ $attendance->member->name }}</p>
                                                                @if($attendance->notes)
                                                                <p class="text-xs text-gray-500">{{ Str::limit($attendance->notes, 30) }}</p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="flex items-center space-x-2">
                                                            <span class="attendance-badge absent-badge">Absent</span>
                                                            @if(auth()->user()->can('update', $attendance))
                                                            <button type="button" class="text-gray-400 hover:text-indigo-600" data-edit-attendance data-id="{{ $attendance->id }}">
                                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                                </svg>
                                                            </button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="text-center py-4 bg-white rounded-lg border border-gray-100">
                                                <p class="text-sm text-gray-500">No members marked as absent</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Quick Actions -->
                                @if(auth()->user()->can('create', [\App\Models\Attendance::class, $event]))
                                <div class="mt-6 pt-4 border-t border-gray-200">
                                    <div class="flex flex-wrap gap-3">
                                        <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" data-add-attendance data-event-id="{{ $event->id }}">
                                            <svg class="-ml-0.5 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                                            </svg>
                                            Add Attendance
                                        </button>
                                        <button type="button" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            <svg class="-ml-0.5 mr-2 h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd" />
                                            </svg>
                                            Export
                                        </button>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
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
            // Toggle event attendance details
            document.querySelectorAll('[data-event-toggle]').forEach(button => {
                button.addEventListener('click', function() {
                    const eventId = this.getAttribute('data-event-toggle');
                    const eventDetails = document.getElementById(`event-${eventId}`);
                    const icon = this.querySelector('svg');
                    
                    eventDetails.classList.toggle('hidden');
                    icon.classList.toggle('rotate-180');
                });
            });

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