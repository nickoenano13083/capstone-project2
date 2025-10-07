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
        
        .modern-badge {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }
        
        .table-row-hover {
            transition: all 0.3s ease;
        }
        
        .table-row-hover:hover {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.05), rgba(118, 75, 162, 0.05));
            transform: scale(1.01);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        
        .status-badge {
            padding: 0.35rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: capitalize;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        .status-pending {
            background: linear-gradient(135deg, #f6e05e, #ecc94b);
            color: #744210;
        }
        
        .status-in_progress {
            background: linear-gradient(135deg, #63b3ed, #4299e1);
            color: #1a365d;
        }
        
        .status-completed {
            background: linear-gradient(135deg, #68d391, #48bb78);
            color: #22543d;
        }
        
        .search-container {
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
        }
        
        .search-container:focus-within {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            transform: translateY(-2px);
        }
        
        /* Add transition for smooth rotation */
        .rotate-180 {
            transform: rotate(180deg);
            transition: transform 0.2s ease-in-out;
        }
        
        /* Ensure the chevron has a transform origin */
        #filtersChevron {
            transform-origin: center;
            transition: transform 0.2s ease-in-out;
        }

        /* Mobile Responsiveness Improvements */
        @media (max-width: 768px) {
            .glass-card {
                margin: 0 -1rem;
                border-radius: 0;
            }
            
            /* Mobile-optimized table */
            .mobile-table {
                display: block;
                width: 100%;
            }
            
            .mobile-table thead {
                display: none;
            }
            
            .mobile-table tbody {
                display: block;
            }
            
            .mobile-table tr {
                display: block;
                background: white;
                border: 1px solid #e5e7eb;
                border-radius: 0.5rem;
                margin-bottom: 1rem;
                padding: 1rem;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            }
            
            .mobile-table td {
                display: block;
                text-align: left !important;
                border: none;
                padding: 0.5rem 0;
                position: relative;
            }
            
            .mobile-table td:before {
                content: attr(data-label);
                font-weight: 600;
                color: #374151;
                display: block;
                margin-bottom: 0.25rem;
                font-size: 0.875rem;
            }
            
            .mobile-table td:last-child {
                border-bottom: none;
            }
            
            /* Mobile filters panel */
            .mobile-filters {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 50;
                display: none;
            }
            
            .mobile-filters.active {
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 1rem;
            }
            
            .mobile-filters-content {
                background: white;
                border-radius: 0.75rem;
                padding: 1.5rem;
                width: 100%;
                max-width: 400px;
                max-height: 80vh;
                overflow-y: auto;
            }
            
            .mobile-filters-header {
                display: flex;
                justify-content: between;
                align-items: center;
                margin-bottom: 1rem;
                padding-bottom: 1rem;
                border-bottom: 1px solid #e5e7eb;
            }
            
            .mobile-close-btn {
                background: #ef4444;
                color: white;
                border: none;
                border-radius: 50%;
                width: 2rem;
                height: 2rem;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                font-size: 1rem;
                margin-left: auto;
            }
            
            /* Mobile form elements */
            .mobile-form-group {
                margin-bottom: 1rem;
            }
            
            .mobile-form-group label {
                display: block;
                font-weight: 600;
                color: #374151;
                margin-bottom: 0.5rem;
                font-size: 0.875rem;
            }
            
            .mobile-form-group input,
            .mobile-form-group select {
                width: 100%;
                padding: 0.75rem;
                border: 1px solid #d1d5db;
                border-radius: 0.5rem;
                font-size: 1rem;
                min-height: 48px;
            }
            
            .mobile-form-group input:focus,
            .mobile-form-group select:focus {
                outline: none;
                border-color: #3b82f6;
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            }
            
            /* Mobile buttons */
            .mobile-btn {
                width: 100%;
                padding: 0.75rem 1rem;
                border-radius: 0.5rem;
                font-weight: 600;
                text-align: center;
                min-height: 48px;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 0.5rem;
            }
            
            .mobile-btn-primary {
                background: linear-gradient(135deg, #3b82f6, #1d4ed8);
                color: white;
                border: none;
            }
            
            .mobile-btn-secondary {
                background: #f3f4f6;
                color: #374151;
                border: 1px solid #d1d5db;
            }
            
            /* Mobile pagination */
            .mobile-pagination {
                display: flex;
                justify-content: center;
                gap: 0.5rem;
                flex-wrap: wrap;
            }
            
            .mobile-pagination a,
            .mobile-pagination span {
                min-width: 44px;
                min-height: 44px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 0.5rem;
                font-size: 0.875rem;
            }
            
            /* Mobile action buttons */
            .mobile-actions {
                display: flex;
                gap: 0.5rem;
                margin-top: 0.5rem;
            }
            
            .mobile-actions a,
            .mobile-actions button {
                flex: 1;
                padding: 0.5rem;
                border-radius: 0.375rem;
                font-size: 0.875rem;
                text-align: center;
                min-height: 40px;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.25rem;
            }
            
            .mobile-view-btn {
                background: #3b82f6;
                color: white;
                text-decoration: none;
            }
            
            .mobile-edit-btn {
                background: #f59e0b;
                color: white;
                text-decoration: none;
            }
            
            .mobile-delete-btn {
                background: #ef4444;
                color: white;
                border: none;
                cursor: pointer;
            }
            
            /* Hide desktop elements on mobile */
            .desktop-only {
                display: none;
            }
            
            /* Mobile header adjustments */
            .mobile-header {
                flex-direction: column;
                gap: 1rem;
                align-items: stretch;
            }
            
            .mobile-header h2 {
                font-size: 1.5rem;
                text-align: center;
            }
            
            .mobile-header .gradient-button {
                width: 100%;
                justify-content: center;
            }
        }
        
        @media (min-width: 769px) {
            .mobile-only {
                display: none;
            }
        }
    </style>

    <div class="py-6">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg glass-card">
                <div class="p-6 border-b border-gray-200">
                    <x-page-header :icon="'fas fa-hands-praying'" title="Prayer Requests" :subtitle="auth()->user()->role === 'Member' ? 'View and track your requests' : 'Manage and review prayer requests'">
                        @if(auth()->user()->role === 'Member')
                            <a href="{{ route('prayer-requests.create') }}" class="inline-flex items-center px-4 py-2 bg-white text-blue-600 text-sm font-medium rounded-lg shadow-md hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                New Prayer Request
                            </a>
                        @endif
                    </x-page-header>
                    <!-- Header and Search Bar -->
                    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
                        
                    </div>

                    @if(session('success'))
                        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-red-700">{{ session('error') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Search and Filters -->
                    <div class="mb-6">
                        <button id="filtersToggle" 
                                class="flex items-center text-sm font-medium text-blue-600 hover:text-blue-800 focus:outline-none">
                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                            <span>Filters</span>
                            <svg id="filtersChevron" class="w-4 h-4 ml-1 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Filters Panel -->
                    <div id="filtersPanel" class="mb-6 p-4 bg-gray-200 rounded-lg">
                        <form action="{{ route('prayer-requests.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <!-- Search Input -->
                            <div class="md:col-span-2">
                                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                                <div class="relative">
                                    <input type="text" 
                                           id="search"
                                           name="search" 
                                           value="{{ request('search') }}" 
                                           placeholder="Search prayer requests..." 
                                           
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 pl-10"> 
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                       
                                    </div>
                                </div>
                            </div>

                           
                            
                            <!-- Member Filter -->
                            @if(auth()->user()->can('manage', App\Models\PrayerRequest::class))
                                <div>
                                    <label for="member_id" class="block text-sm font-medium text-gray-700 mb-1">Member</label>
                                    <select name="member_id" id="member_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">All Members</option>
                                        @foreach($members as $member)
                                            <option value="{{ $member->id }}" {{ request('member_id') == $member->id ? 'selected' : '' }}>
                                                {{ $member->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <!-- Chapter Filter -->
                            @if(auth()->user()->can('manage', App\Models\PrayerRequest::class))
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
                            @endif

                            <!-- Status Filter -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select name="status" id="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">All Statuses</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="answered" {{ request('status') == 'answered' ? 'selected' : '' }}>Answered</option>
                                </select>
                            </div>

                            <!-- Category Filter -->
                            <div>
                                <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                                <select name="category" id="category" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">All Categories</option>
                                    <option value="healing" {{ request('category') == 'healing' ? 'selected' : '' }}>Healing</option>
                                    <option value="family" {{ request('category') == 'family' ? 'selected' : '' }}>Family</option>
                                    <option value="work_school" {{ request('category') == 'work_school' ? 'selected' : '' }}>Work/School</option>
                                    <option value="deliverance" {{ request('category') == 'deliverance' ? 'selected' : '' }}>Deliverance</option>
                                    <option value="church" {{ request('category') == 'church' ? 'selected' : '' }}>Church</option>
                                    <option value="other" {{ request('category') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            
                            <!-- Date Range Filter -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Date Range</label>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <select id="date_range" name="date_range" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">All Time</option>
                                        <option value="today" {{ request('date_range') === 'today' ? 'selected' : '' }}>Today</option>
                                        <option value="this_week" {{ request('date_range') === 'this_week' ? 'selected' : '' }}>This Week</option>
                                        <option value="this_month" {{ request('date_range') === 'this_month' ? 'selected' : '' }}>This Month</option>
                                        <option value="this_year" {{ request('date_range') === 'this_year' ? 'selected' : '' }}>This Year</option>
                                    </select>
                                    
                                    <label class="inline-flex items-center space-x-2">
                                        <input type="checkbox" name="archived" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" {{ request('archived') ? 'checked' : '' }}>
                                        <span class="text-sm text-gray-700">Include Archived</span>
                                    </label>

                                    <div class="flex items-center space-x-3">
                                        <button type="submit" 
                                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-medium text-sm rounded-lg shadow-md hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                            <i class="fas fa-filter mr-2"></i> Apply Filters
                                        </button>
                                        <a href="{{ route('prayer-requests.index') }}" 
                                           class="inline-flex items-center px-4 py-2 bg-green-100 border border-gray-300 text-gray-700 font-medium text-sm rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200">
                                            <i class="fas fa-undo mr-2"></i> Reset
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <script>
                        // Simple toggle for filters panel
                        document.addEventListener('DOMContentLoaded', function() {
                            const filtersToggle = document.getElementById('filtersToggle');
                            const filtersPanel = document.getElementById('filtersPanel');
                            const filtersChevron = document.getElementById('filtersChevron');
                            
                            // Check if any filters are active
                            const hasActiveFilters = window.location.search.includes('search=') || 
                                                  window.location.search.includes('status=') ||
                                                  window.location.search.includes('date_range=') ||
                                                  window.location.search.includes('member_id=');
                            
                            // Show panel if filters are active
                            if (hasActiveFilters && filtersPanel) {
                                filtersPanel.classList.remove('hidden');
                                if (filtersChevron) {
                                    filtersChevron.classList.add('rotate-180');
                                }
                            }
                            
                            // Toggle panel on button click
                            if (filtersToggle && filtersPanel && filtersChevron) {
                                filtersToggle.addEventListener('click', function(e) {
                                    e.preventDefault();
                                    filtersPanel.classList.toggle('hidden');
                                    filtersChevron.classList.toggle('rotate-180');
                                });
                            }
                        });
                    </script>

                    <!-- Prayer Requests Count -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4">
                        <p class="text-sm text-gray-600 mb-2 sm:mb-0">
                            Showing <span class="font-medium">{{ $prayerRequests->firstItem() }}</span> to 
                            <span class="font-medium">{{ $prayerRequests->lastItem() }}</span> of 
                            <span class="font-medium">{{ $prayerRequests->total() }}</span> prayer requests
                        </p>
                        
                    </div>

                    <!-- Prayer Requests Table -->
                    <div class="overflow-hidden rounded-xl border border-gray-200 shadow-sm">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 mobile-table">
                                <thead class="bg-gray-50">
                                    <tr>
                                        @if(auth()->user()->role !== 'Member')
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                <div class="flex items-center">
                                                    <i class="fas fa-user mr-2"></i>
                                                    Member
                                                </div>
                                            </th>
                                        @endif
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <div class="flex items-center">
                                                <i class="fas fa-pray mr-2"></i>
                                                Prayer Request
                                            </div>
                                        </th>
                                        @if(auth()->user()->role !== 'Member')
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                <div class="flex items-center">
                                                    <i class="far fa-calendar-alt mr-2"></i>
                                                    Prayer Date
                                                </div>
                                            </th>
                                        @endif
                                        @if(auth()->user()->role !== 'Member')
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                <div class="flex items-center">
                                                    <i class="fas fa-tag mr-2"></i>
                                                    Status
                                                </div>
                                            </th>
                                        @endif
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <div class="flex items-center">
                                                <i class="fas fa-info-circle mr-2"></i>
                                                Category
                                            </div>
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <div class="flex items-center justify-end">
                                                <i class="fas fa-cog mr-2"></i>
                                                Actions
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($prayerRequests as $request)
                                        <tr class="table-row-hover hover:bg-gray-50">
                                            @if(auth()->user()->role !== 'Member')
                                                <td class="px-6 py-4 whitespace-nowrap" data-label="Member">
                                                    <div class="flex items-center">
                                                        @if($request->member)
                                                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gradient-to-r from-indigo-500 to-purple-600 flex items-center justify-center text-white font-semibold">
                                                                {{ substr($request->member->name, 0, 1) }}
                                                            </div>
                                                            <div class="ml-4">
                                                                <div class="text-sm font-medium text-gray-900">{{ $request->member->name }}</div>
                                                                <div class="text-sm text-gray-500">{{ $request->member->email }}</div>
                                                            </div>
                                                        @else
                                                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center text-gray-600">
                                                                <i class="fas fa-user-secret"></i>
                                                            </div>
                                                            <div class="ml-4">
                                                                <div class="text-sm font-medium text-gray-500 italic">Anonymous</div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </td>
                                            @endif
                                            <td class="px-6 py-4" data-label="Prayer Request">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ Str::limit($request->request, 80) }}
                                                </div>
                                                @if($request->prayer_date->isToday())
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mt-1">
                                                        <i class="fas fa-calendar-day mr-1"></i> Today
                                                    </span>
                                                @endif
                                            </td>
                                            @if(auth()->user()->role !== 'Member')
                                                <td class="px-6 py-4 whitespace-nowrap" data-label="Prayer Date">
                                                    <div class="text-sm text-gray-900">
                                                        <i class="far fa-calendar-alt mr-1 text-indigo-500"></i> {{ $request->prayer_date->format('M d, Y') }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        <i class="far fa-clock mr-1 text-indigo-400"></i> {{ $request->prayer_date->format('h:i A') }}
                                                    </div>
                                                </td>
                                            @endif
                                            <td class="px-6 py-4 whitespace-nowrap" data-label="Status">
                                                @php
                                                    $statusClass = [
                                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                                        'in_progress' => 'bg-blue-100 text-blue-800',
                                                        'completed' => 'bg-green-100 text-green-800',
                                                        'answered' => 'bg-green-100 text-green-800'
                                                    ][$request->status] ?? 'bg-gray-100 text-gray-800';
                                                @endphp
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                                    {{ str_replace('_', ' ', ucfirst($request->status)) }}
                                                </span>
                                            </td>
                                            @if(auth()->user()->role !== 'Member')
                                                <td class="px-6 py-4 whitespace-nowrap" data-label="Category">
                                                    @php
                                                        $categoryMap = [
                                                            'healing' => ['Healing', 'bg-purple-100 text-purple-800'],
                                                            'family' => ['Family', 'bg-pink-100 text-pink-800'],
                                                            'work_school' => ['Work/School', 'bg-indigo-100 text-indigo-800'],
                                                            'deliverance' => ['Deliverance', 'bg-red-100 text-red-800'],
                                                            'church' => ['Church', 'bg-blue-100 text-blue-800'],
                                                            'other' => ['Other', 'bg-gray-100 text-gray-800'],
                                                        ];
                                                        [$label, $classes] = $categoryMap[$request->category] ?? [($request->category ?? 'Uncategorized'), 'bg-gray-100 text-gray-800'];
                                                    @endphp
                                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $classes }}">
                                                        {{ $label }}
                                                    </span>
                                                </td>
                                            @endif
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium" data-label="Actions">
                                                <div class="flex items-center justify-end space-x-2">
                                                    <a href="{{ route('prayer-requests.show', $request) }}" class="text-indigo-600 hover:text-indigo-900 mr-3 action-button" data-tooltip="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    
                                                    @can('delete', $request)
                                                        <form action="{{ route('prayer-requests.destroy', $request) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to archive this prayer request?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-900 action-button" data-tooltip="Archive">
                                                                <i class="fas fa-archive"></i>
                                                            </button>
                                                        </form>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="{{ auth()->user()->role === 'Member' ? '3' : '5' }}" class="px-6 py-12 text-center">
                                                <div class="flex flex-col items-center justify-center">
                                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                                        <i class="fas fa-praying-hands text-2xl text-gray-400"></i>
                                                    </div>
                                                    <h3 class="text-lg font-medium text-gray-900 mb-1">No prayer requests found</h3>
                                                    <p class="text-gray-500 mb-4">
                                                        @if(auth()->user()->role === 'Member')
                                                            Get started by creating a new prayer request.
                                                        @else
                                                            No prayer requests have been submitted yet.
                                                        @endif
                                                    </p>
                                                    @if(auth()->user()->role === 'Member')
                                                        <a href="{{ route('prayer-requests.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
                                                            <i class="fas fa-plus mr-2"></i> New Prayer Request
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        
                        <!-- Pagination -->
                        @if($prayerRequests->hasPages())
                            <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                                <div class="flex-1 flex justify-between sm:hidden">
                                    @if($prayerRequests->onFirstPage())
                                        <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-gray-100 cursor-not-allowed">
                                            Previous
                                        </span>
                                    @else
                                        <a href="{{ $prayerRequests->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                            Previous
                                        </a>
                                    @endif
                                    
                                    @if($prayerRequests->hasMorePages())
                                        <a href="{{ $prayerRequests->nextPageUrl() }}" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                            Next
                                        </a>
                                    @else
                                        <span class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-gray-100 cursor-not-allowed">
                                            Next
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                    <div>
                                        <p class="text-sm text-gray-700">
                                            Showing <span class="font-medium">{{ $prayerRequests->firstItem() }}</span> to 
                                            <span class="font-medium">{{ $prayerRequests->lastItem() }}</span> of 
                                            <span class="font-medium">{{ $prayerRequests->total() }}</span> results
                                        </p>
                                    </div>
                                    <div>
                                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                            @if($prayerRequests->onFirstPage())
                                                <span class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-300 cursor-not-allowed">
                                                    <span class="sr-only">Previous</span>
                                                    <i class="fas fa-chevron-left h-5 w-5"></i>
                                                </span>
                                            @else
                                                <a href="{{ $prayerRequests->previousPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                                    <span class="sr-only">Previous</span>
                                                    <i class="fas fa-chevron-left h-5 w-5"></i>
                                                </a>
                                            @endif
                                            
                                            @foreach($prayerRequests->getUrlRange(1, $prayerRequests->lastPage()) as $page => $url)
                                                @if($page == $prayerRequests->currentPage())
                                                    <span aria-current="page" class="z-10 bg-indigo-50 border-indigo-500 text-indigo-600 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                                        {{ $page }}
                                                    </span>
                                                @else
                                                    <a href="{{ $url }}" class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                                        {{ $page }}
                                                    </a>
                                                @endif
                                            @endforeach
                                            
                                            @if($prayerRequests->hasMorePages())
                                                <a href="{{ $prayerRequests->nextPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                                    <span class="sr-only">Next</span>
                                                    <i class="fas fa-chevron-right h-5 w-5"></i>
                                                </a>
                                            @else
                                                <span class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-300 cursor-not-allowed">
                                                    <span class="sr-only">Next</span>
                                                    <i class="fas fa-chevron-right h-5 w-5"></i>
                                                </span>
                                            @endif
                                        </nav>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            // Toggle filters panel
            const filtersPanel = document.getElementById('filtersPanel');
            const filtersToggle = document.getElementById('filtersToggle');
            if (filtersToggle) {
                filtersToggle.addEventListener('click', function() {
                    filtersPanel.classList.toggle('hidden');
                });
            }
            
            // Initialize tooltips
            const tooltipElements = document.querySelectorAll('[data-tooltip]');
            tooltipElements.forEach(element => {
                element.addEventListener('mouseenter', function() {
                    const tooltip = document.createElement('div');
                    tooltip.className = 'tooltip';
                    tooltip.textContent = this.getAttribute('data-tooltip');
                    document.body.appendChild(tooltip);
                    
                    const rect = this.getBoundingClientRect();
                    tooltip.style.top = `${rect.top - tooltip.offsetHeight - 10}px`;
                    tooltip.style.left = `${rect.left + (this.offsetWidth / 2) - (tooltip.offsetWidth / 2)}px`;
                    
                    this.addEventListener('mouseleave', function() {
                        tooltip.remove();
                    }, { once: true });
                });
            });
            
            // Add style for tooltips
            const style = document.createElement('style');
            style.textContent = `
                .tooltip {
                    position: fixed;
                    background: rgba(0, 0, 0, 0.8);
                    color: white;
                    padding: 4px 8px;
                    border-radius: 4px;
                    font-size: 12px;
                    pointer-events: none;
                    z-index: 1000;
                    white-space: nowrap;
                }
                .tooltip::after {
                    content: '';
                    position: absolute;
                    top: 100%;
                    left: 50%;
                    margin-left: -5px;
                    border-width: 5px;
                    border-style: solid;
                    border-color: rgba(0, 0, 0, 0.8) transparent transparent transparent;
                }
            `;
            document.head.appendChild(style);
        });
    </script>
    @endpush
</x-app-layout>