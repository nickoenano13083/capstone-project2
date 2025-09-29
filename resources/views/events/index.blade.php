<x-app-layout>
    <!-- Font Awesome CDN for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Modern glassmorphism and gradient styles */
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            width: 100%;
            max-width: none;
            margin: 0;
            margin-top: 10px;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .gradient-button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s ease;
        }
        
        .gradient-button:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        
        .modern-input {
            transition: all 0.3s ease;
            border: 2px solid #e5e7eb;
        }
        
        .modern-input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            transform: translateY(-1px);
        }
        
        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        .action-button {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .action-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }
        
        .action-button:hover::before {
            left: 100%;
        }
        
        /* Modern card grid */
        .event-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(255, 255, 255, 0.7) 100%);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .event-card:hover {
            background: linear-gradient(135deg, rgba(255, 255, 255, 1) 0%, rgba(255, 255, 255, 0.95) 100%);
            transform: translateY(-12px) scale(1.02);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        }
        
        /* Status badges */
        .status-badge {
            @apply px-3 py-1 rounded-full text-xs font-medium;
        }
        
        .status-upcoming {
            @apply bg-blue-100 text-blue-800 border border-blue-200;
        }
        
        .status-ongoing {
            @apply bg-green-100 text-green-800 border border-green-200;
        }
        
        .status-completed {
            @apply bg-gray-100 text-gray-800 border border-gray-200;
        }
        
        .status-cancelled {
            @apply bg-red-100 text-red-800 border border-red-200;
        }
        
        .animate-ping {
            animation: ping 1.5s cubic-bezier(0, 0, 0.2, 1) infinite;
        }
        
        @keyframes ping {
            75%, 100% {
                transform: scale(2);
                opacity: 0;
            }
        }
    </style>

    <div class="py-6">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                    <!-- Header and Search Bar -->
                    <div class="flex flex-col space-y-4 mb-6">
                        <div class="flex flex-col md:flex-row md:items-center justify-between">
                            <h2 class="text-2xl font-bold text-gray-800">Events Management</h2>
                            @if(in_array(auth()->user()->role ?? 'Guest', ['Admin', 'Leader']))
                                <a href="{{ route('events.create') }}" 
                                   class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white text-sm font-medium rounded-lg shadow-md hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200">
                                    <i class="fas fa-plus mr-2"></i>
                                    Add Event
                                </a>
                            @endif
                        </div>

                        <form action="{{ route('events.index') }}" method="GET" id="searchForm" class="relative w-full">
                            <div class="relative flex w-full">
                                <div class="relative flex-1">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <input 
                                        type="text" 
                                        name="search" 
                                        id="eventSearch"
                                        value="{{ request('search') }}"
                                        placeholder="Search events by title, description, or location..." 
                                        class="block w-full pl-10 pr-12 py-3 border border-gray-300 rounded-l-lg bg-white text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base transition duration-150 ease-in-out"
                                        autocomplete="off"
                                    >
                                    @if(request('search'))
                                    <button type="button" 
                                            onclick="document.getElementById('eventSearch').value = ''; this.form.submit()"
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                    @endif
                                </div>
                                <button type="submit" 
                                        class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium text-sm rounded-r-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150 ease-in-out">
                                    Search
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Filters Toggle Button -->
                    <div class="mb-4">
                        <button id="filtersToggle" 
                                class="flex items-center text-sm font-medium text-blue-600 hover:text-blue-800 focus:outline-none">
                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                            Filters
                            <svg id="filtersToggleIcon" class="w-4 h-4 ml-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Filters Panel -->
                    <div id="filtersPanel" class="hidden mb-6 p-4 bg-gray-200 rounded-lg">
                        <form id="filtersForm" action="{{ route('events.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <!-- Status Filter -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select name="status" id="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">All Status</option>
                                    <option value="upcoming" {{ request('status') === 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                    <option value="ongoing" {{ request('status') === 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                                    
                                </select>
                            </div>

                            <!-- Date Range Filter -->
                            <div>
                                <label for="date_range" class="block text-sm font-medium text-gray-700 mb-1">Date Range</label>
                                <select name="date_range" id="date_range" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="all" {{ request('date_range') === 'all' ? 'selected' : '' }}>All Dates</option>
                                    <option value="today" {{ request('date_range') === 'today' ? 'selected' : '' }}>Today</option>
                                    <option value="this_week" {{ request('date_range') === 'this_week' ? 'selected' : '' }}>This Week</option>
                                    <option value="this_month" {{ request('date_range') === 'this_month' ? 'selected' : '' }}>This Month</option>
                                    <option value="upcoming" {{ request('date_range') === 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                    <option value="past" {{ request('date_range') === 'past' ? 'selected' : '' }}>Past</option>
                                </select>
                            </div>

                            <!-- Chapter Filter -->
                            <div>
                                <label for="chapter_id" class="block text-sm font-medium text-gray-700 mb-1">Chapter</label>
                                <select name="chapter_id" id="chapter_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">All Chapters</option>
                                    @foreach($chapters as $chapter)
                                        <option value="{{ $chapter->id }}" {{ request('chapter_id') == $chapter->id ? 'selected' : '' }}>
                                            {{ $chapter->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Sort By Filter -->
                            <div>
                                <label for="sort" class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                                <select name="sort" id="sort" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="date_asc" {{ request('sort') === 'date_asc' ? 'selected' : '' }}>Date (Oldest First)</option>
                                    <option value="date_desc" {{ request('sort') === 'date_desc' || !request('sort') ? 'selected' : '' }}>Date (Newest First)</option>
                                    <option value="title_asc" {{ request('sort') === 'title_asc' ? 'selected' : '' }}>Title (A-Z)</option>
                                    <option value="title_desc" {{ request('sort') === 'title_desc' ? 'selected' : '' }}>Title (Z-A)</option>
                                </select>
                            </div>

                            <!-- View Toggle -->
                            <div class="flex items-end space-x-3">
                                <div class="flex-1">
                                    <label for="view" class="block text-sm font-medium text-gray-700 mb-1">View</label>
                                    <div class="flex rounded-md shadow-sm">
                                        <button type="button" 
                                                onclick="setViewMode('table')" 
                                                class="px-4 py-2 text-sm font-medium rounded-l-md border border-r-0 {{ request('view', 'table') === 'table' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }}">
                                            <i class="fas fa-table"></i>
                                        </button>
                                        <button type="button" 
                                                onclick="setViewMode('grid')" 
                                                class="px-4 py-2 text-sm font-medium rounded-r-md border {{ request('view') === 'grid' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }}">
                                            <i class="fas fa-th"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="flex items-end space-x-3">
                                    <button type="submit" 
                                            class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-gray-700 font-medium text-sm rounded-lg shadow-md hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 transform hover:-translate-y-0.5">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                                        </svg>
                                        Apply Filters
                                    </button>
                                    <a href="{{ route('events.index') }}" 
                                       class="inline-flex items-center px-4 py-2.5 border border-gray-300 text-gray-700 font-medium text-sm rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300 transition-all duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                        </svg>
                                        Reset
                                    </a>
                                </div>
                            </div>

                            <!-- Hidden fields to maintain other filter values -->
                            @if(request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif
                        </form>
                    </div>

                    <!-- Events Count -->
                    <div class="flex justify-between items-center mb-4">
                        <p class="text-sm text-gray-600">
                            Showing {{ $events->firstItem() ?? 0 }} to {{ $events->lastItem() ?? 0 }} of {{ $events->total() }} events
                        </p>
                    </div>

                    <!-- Table View -->
                    <div id="table-view" class="overflow-hidden rounded-lg border border-gray-200 {{ request('view', 'table') === 'table' ? '' : 'hidden' }}">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Title
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Date & Time
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Location
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($events as $event)
                                    @php
                                        $statusClass = [
                                            'upcoming' => 'bg-blue-100 text-blue-800 border border-blue-200',
                                            'ongoing' => 'bg-green-100 text-green-800 border border-green-200',
                                            'completed' => 'bg-gray-100 text-gray-800 border border-gray-200',
                                            'cancelled' => 'bg-red-100 text-red-800 border border-red-200'
                                        ][$event->status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                    <i class="fas fa-calendar-day text-blue-600"></i>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $event->title }}</div>
                                                    <div class="text-xs text-gray-500">{{ $event->chapter->name ?? 'No Chapter' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $event->date ? $event->date->format('M j, Y') : 'No date set' }}</div>
                                            <div class="text-xs text-gray-500">
                                                {{ $event->time ? \Carbon\Carbon::parse($event->time)->format('g:i A') : 'All Day' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $event->location }}</div>
                                            <div class="text-xs text-gray-500">{{ $event->address }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-3 py-1 inline-flex text-xs font-medium rounded-full {{ $statusClass }} transition-colors duration-200">
                                                <span class="relative flex h-2 w-2 mr-2">
                                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75 {{ str_replace('text-', 'bg-', explode(' ', $statusClass)[0]) }}"></span>
                                                    <span class="relative inline-flex rounded-full h-2 w-2 {{ str_replace('text-', 'bg-', explode(' ', $statusClass)[0]) }}"></span>
                                                </span>
                                                {{ ucfirst($event->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end space-x-2">
                                                @if(auth()->user()->role === 'Member')
                                                <a href="{{ route('events.check-in', $event) }}" 
                                                   class="text-white bg-blue-500 hover:bg-blue-600 p-2 rounded-full transition-colors duration-200 ease-in-out"
                                                   data-tooltip="Check In to Event"
                                                   data-tooltip-position="top">
                                                    <i class="fas fa-qrcode w-4 h-4"></i>
                                                </a>
                                                @else
                                                <a href="{{ route('events.show', $event) }}" 
                                                   class="text-white bg-blue-500 hover:bg-blue-600 p-2 rounded-full transition-colors duration-200 ease-in-out"
                                                   data-tooltip="View Event"
                                                   data-tooltip-position="top">
                                                    <i class="fas fa-eye w-4 h-4"></i>
                                                </a>
                                                @endif
                                                @if(in_array(auth()->user()->role, ['Admin', 'Leader']))
                                                <a href="{{ route('events.edit', $event) }}" 
                                                   class="text-white bg-yellow-500 hover:bg-yellow-600 p-2 rounded-full transition-colors duration-200 ease-in-out"
                                                   data-tooltip="Edit Event"
                                                   data-tooltip-position="top">
                                                    <i class="fas fa-edit w-4 h-4"></i>
                                                </a>
                                                <form action="{{ route('events.destroy', $event) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this event? This action cannot be undone.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="text-white bg-red-500 hover:bg-red-600 p-2 rounded-full transition-colors duration-200 ease-in-out"
                                                            data-tooltip="Delete Event"
                                                            data-tooltip-position="top">
                                                        <i class="fas fa-trash w-4 h-4"></i>
                                                    </button>
                                                </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                            No events found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Grid View -->
                    <div id="grid-view" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 {{ request('view') === 'grid' ? '' : 'hidden' }}">
                        @forelse($events as $event)
                            @php
                                $statusClass = [
                                    'upcoming' => 'bg-blue-100 text-blue-800 border border-blue-200',
                                    'ongoing' => 'bg-green-100 text-green-800 border border-green-200',
                                    'completed' => 'bg-gray-100 text-gray-800 border border-gray-200',
                                    'cancelled' => 'bg-red-100 text-red-800 border border-red-200'
                                ][$event->status] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <div class="event-card rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-shadow duration-300">
                                <div class="p-6">
                                    <div class="flex justify-between items-start mb-4">
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $event->title }}</h3>
                                        <span class="px-3 py-1 inline-flex text-xs font-medium rounded-full {{ $statusClass }} transition-colors duration-200">
                                            <span class="relative flex h-2 w-2 mr-2">
                                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75 {{ str_replace('text-', 'bg-', explode(' ', $statusClass)[0]) }}"></span>
                                                <span class="relative inline-flex rounded-full h-2 w-2 {{ str_replace('text-', 'bg-', explode(' ', $statusClass)[0]) }}"></span>
                                            </span>
                                            {{ ucfirst($event->status) }}
                                        </span>
                                    </div>
                                    
                                    <div class="flex items-center text-sm text-gray-600 mb-3">
                                        <i class="far fa-calendar-alt mr-2 text-blue-500"></i>
                                        <span>{{ $event->date ? $event->date->format('M j, Y') : 'No date set' }}</span>
                                        @if($event->time)
                                            <span class="mx-1">•</span>
                                            <span>{{ \Carbon\Carbon::parse($event->time)->format('g:i A') }}</span>
                                        @else
                                            <span class="ml-1">(All Day)</span>
                                        @endif
                                    </div>
                                    
                                    <div class="flex items-start text-sm text-gray-600 mb-4">
                                        <i class="fas fa-map-marker-alt mt-1 mr-2 text-red-500"></i>
                                        <div>
                                            <div class="font-medium">{{ $event->location }}</div>
                                            @if($event->address)
                                                <div class="text-xs text-gray-500">{{ $event->address }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    @if($event->description)
                                        <p class="text-sm text-gray-600 mb-4 line-clamp-2">
                                            {{ $event->description }}
                                        </p>
                                    @endif
                                    
                                    <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-100">
                                        <div class="text-sm text-gray-500">
                                            <i class="fas fa-users mr-1"></i>
                                            {{ $event->attendance_count ?? 0 }} attending
                                        </div>
                                        <div class="flex space-x-2">
                                            <a href="{{ route('events.show', $event) }}" 
                                               class="px-3 py-1.5 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                                                View
                                            </a>
                                            @if(in_array(auth()->user()->role, ['Admin', 'Leader']))
                                                <a href="{{ route('events.edit', $event) }}" 
                                                   class="px-3 py-1.5 bg-indigo-600 text-white text-xs font-medium rounded hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors duration-200">
                                                    Edit
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-12">
                                <div class="mx-auto w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                                    <i class="fas fa-calendar-day text-gray-400 text-2xl"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-1">No events found</h3>
                                <p class="text-gray-500">Try adjusting your search or filter to find what you're looking for.</p>
                                @if(in_array(auth()->user()->role ?? 'Guest', ['Admin', 'Leader']))
                                    <div class="mt-6">
                                        <a href="{{ route('events.create') }}" 
                                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 transform hover:-translate-y-0.5">
                                            <i class="fas fa-plus -ml-1 mr-2"></i>
                                            Create Event
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if($events->hasPages())
                        <div class="mt-6">
                            {{ $events->withQueryString()->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function setViewMode(mode) {
            // Update URL with view parameter
            const url = new URL(window.location.href);
            url.searchParams.set('view', mode);
            window.history.pushState({}, '', url);
            
            // Toggle views
            document.getElementById('table-view').classList.toggle('hidden', mode !== 'table');
            document.getElementById('grid-view').classList.toggle('hidden', mode !== 'grid');
            
            // Update active button state
            document.querySelectorAll('[data-view-toggle]').forEach(btn => {
                btn.classList.toggle('bg-gray-100', btn.dataset.viewToggle === mode);
            });
        }
        
        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function() {
            const tooltips = document.querySelectorAll('[data-tooltip]');
            
            tooltips.forEach(tooltip => {
                const tooltipText = tooltip.getAttribute('data-tooltip');
                const tooltipElement = document.createElement('div');
                
                tooltipElement.className = 'hidden absolute z-50 px-2 py-1 text-xs text-white bg-gray-900 rounded';
                tooltipElement.textContent = tooltipText;
                
                tooltip.parentNode.style.position = 'relative';
                tooltip.parentNode.appendChild(tooltipElement);
                
                tooltip.addEventListener('mouseenter', () => {
                    tooltipElement.classList.remove('hidden');
                    
                    // Position tooltip
                    const rect = tooltip.getBoundingClientRect();
                    tooltipElement.style.top = `${rect.top - 30}px`;
                    tooltipElement.style.left = `${rect.left + (rect.width / 2) - (tooltipElement.offsetWidth / 2)}px`;
                });
                
                tooltip.addEventListener('mouseleave', () => {
                    tooltipElement.classList.add('hidden');
                });
            });
        });

        // Toggle filters panel
        document.getElementById('filtersToggle').addEventListener('click', () => {
            document.getElementById('filtersPanel').classList.toggle('hidden');
            document.getElementById('filtersToggleIcon').classList.toggle('rotate-180');
        });
    </script>
    @endpush
</x-app-layout>