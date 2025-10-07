<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Member Profile</h2>
                <p class="text-sm text-gray-500 mt-1">View and manage member details</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ url()->previous() }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back
                </a>
                @if(auth()->user()->role !== 'Member')
                    <a href="{{ route('members.edit', $member) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-yellow-500 to-yellow-600 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white hover:from-yellow-600 hover:to-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                        </svg>
                        Edit Profile
                    </a>
                    @if($member->is_archived)
                        <form action="{{ route('members.unarchive', $member) }}" method="POST" class="inline" onsubmit="return confirm('Unarchive this member?');">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white hover:from-green-600 hover:to-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V7a2 2 0 00-2-2h-6m8 8l-3 3m3-3l-3-3M4 7v10a2 2 0 002 2h10"></path>
                                </svg>
                                Unarchive
                            </button>
                        </form>
                    @else
                        <form action="{{ route('members.archive', $member) }}" method="POST" class="inline" onsubmit="return confirm('Archive this member? They will be moved to the archived section.');">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-gray-500 to-gray-600 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white hover:from-gray-600 hover:to-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v8m4-4H8m9 9a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2h10z"></path>
                                </svg>
                                Archive
                            </button>
                        </form>
                    @endif
                @endif
            </div>
        </div>
    </x-slot>

    <div class="dashboard-main-content p-6">
            <!-- Profile Header -->
            <div class="bg-gradient-to-br from-blue-400 to-indigo-100 overflow-hidden shadow-lg rounded-lg mb-8 border border-gray-200">
                <div class="px-6 py-8 sm:p-10">
                    <div class="flex flex-col sm:flex-row items-center sm:items-start space-y-6 sm:space-y-0 sm:space-x-8 mobile-center-info">
                        <div class="flex-shrink-0">
                            @php
                                $initials = implode('', array_map(function($name) {
                                    return strtoupper(substr(trim($name), 0, 1));
                                }, explode(' ', $member->name)));
                                $colors = ['from-blue-500 to-indigo-600', 'from-green-500 to-teal-600', 'from-purple-500 to-indigo-600', 'from-pink-500 to-rose-600'];
                                $colorIndex = abs(crc32($member->name)) % count($colors);
                                $gradient = $colors[$colorIndex];
                            @endphp
                            @if($member->user && $member->user->profile_photo_path)
                                <img src="{{ $member->user->profile_photo_url }}?t={{ optional($member->user->updated_at)->timestamp ?? time() }}" alt="{{ $member->name }}" class="h-24 w-24 rounded-full object-cover shadow-md">
                            @else
                                <div class="h-24 w-24 rounded-full bg-gradient-to-br {{ $gradient }} flex items-center justify-center text-white text-3xl font-bold shadow-md">
                                    {{ $initials }}
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <h1 class="text-2xl font-bold text-gray-900">{{ $member->name }}</h1>
                                    <div class="mt-1 flex items-center text-sm text-gray-600">
                                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                        <a href="mailto:{{ $member->email }}" class="text-blue-600 hover:text-blue-800 hover:underline">{{ $member->email }}</a>
                                    </div>
                                </div>
                                <div class="mt-3 sm:mt-0">
                                    @if($member->status === 'active')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <svg class="-ml-1 mr-1.5 h-2 w-2 text-green-500" fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3" />
                                        </svg>
                                        Active Member
                                    </span>
                                    @else
                                   
                                    @endif
                                </div>
                            </div>
<div class="mt-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 justify-center items-center mobile-center-info">                                @if($member->phone)
                                <div class="flex items-center bg-white p-3 rounded-lg shadow-sm border border-gray-100">
                                    <div class="p-2 rounded-full bg-blue-100 text-blue-600 mr-3">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Phone</p>
                                        <p class="text-sm font-medium text-gray-900">{{ $member->phone }}</p>
                                    </div>
                                </div>
                                @endif

                                @if($member->chapter)
                                <div class="flex items-center bg-white p-3 rounded-lg shadow-sm border border-gray-100 w-full max-w-xs mx-auto sm:mx-0">
                                    <div class="p-2 rounded-full bg-purple-100 text-purple-600 mr-3">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Chapter</p>
                                        <a href="{{ route('chapters.show', $member->chapter) }}" class="text-sm font-medium text-purple-600 hover:text-purple-800 hover:underline">
                                            {{ $member->chapter->name }}
                                        </a>
                                    </div>
                                </div>
                                @endif

                                <div class="flex items-center bg-white p-3 rounded-lg shadow-sm border border-gray-100 w-full max-w-xs mx-auto sm:mx-0">
                                    <div class="p-2 rounded-full bg-green-100 text-green-600 mr-3">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Member Since</p>
                                        <p class="text-sm font-medium text-gray-900">{{ $member->join_date->format('M d, Y') }}</p>
                                    </div>
                                </div>

                                @if($member->address)
                                <div class="flex items-center bg-white p-3 rounded-lg shadow-sm border border-gray-100">
                                    <div class="p-2 rounded-full bg-amber-100 text-amber-600 mr-3">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Address</p>
                                        <p class="text-sm font-medium text-gray-900 truncate max-w-[200px]" title="{{ $member->address }}">{{ $member->address }}</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Personal Information -->
                    <div class="bg-gradient-to-br from-blue-200 to-indigo-25 shadow rounded-lg overflow-hidden border border-blue-100">
                        <div class="px-6 py-5 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                <svg class="h-5 w-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Personal Information
                            </h3>
                        </div>
                        <div class="px-6 py-5">
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $member->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Email Address</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        <a href="mailto:{{ $member->email }}" class="text-blue-600 hover:text-blue-800 hover:underline">{{ $member->email }}</a>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Phone Number</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $member->phone ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Age</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $member->age ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Birthday</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        @if(!empty($member->birthday))
                                            {{ optional($member->birthday)->format('F d, Y') ?? \Carbon\Carbon::parse($member->birthday)->format('F d, Y') }}
                                        @else
                                            N/A
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Member Since</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $member->join_date->format('F d, Y') }}</dd>
                                </div>
                                @if($member->address)
                                <div class="sm:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500">Address</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $member->address }}</dd>
                                </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                    <!-- Attendance History -->
                    <div class="bg-gradient-to-br from-blue-400 to-indigo-100 shadow rounded-lg overflow-hidden border border-green-100">
                        <div class="px-6 py-5 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                    <svg class="h-5 w-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    Attendance History
                                </h3>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $member->attendance->count() }} {{ Str::plural('event', $member->attendance->count()) }}
                                </span>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($member->attendance as $attendance)
                                        @if($attendance->event)
                                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $attendance->event->title }}</div>
                                                <div class="text-xs text-gray-500">{{ $attendance->event->location }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $attendance->event->formatted_date ?? 'No date set' }}</div>
                                                <div class="text-xs text-gray-500">{{ $attendance->event->time ? \Carbon\Carbon::parse($attendance->event->time)->format('h:i A') : 'Time not set' }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($attendance->status === 'present')
                                                <span class="px-2.5 py-0.5 inline-flex items-center text-xs font-medium rounded-full bg-green-100 text-green-800">
                                                    <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-green-400" fill="currentColor" viewBox="0 0 8 8">
                                                        <circle cx="4" cy="4" r="3" />
                                                    </svg>
                                                    Present
                                                </span>
                                                @else
                                                <span class="px-2.5 py-0.5 inline-flex items-center text-xs font-medium rounded-full bg-red-100 text-red-800">
                                                    <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-red-400" fill="currentColor" viewBox="0 0 8 8">
                                                        <circle cx="4" cy="4" r="3" />
                                                    </svg>
                                                    Absent
                                                </span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endif
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-6 py-8 text-center text-sm text-gray-500">
                                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                                </svg>
                                                <h3 class="mt-2 text-sm font-medium text-gray-900">No attendance records</h3>
                                                <p class="mt-1 text-sm text-gray-500">This member hasn't attended any events yet.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <!-- Membership Status -->
                    <div class="bg-gradient-to-br from-purple-50 to-violet-50 shadow rounded-lg overflow-hidden border border-purple-100">
                        <div class="px-6 py-5 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                <svg class="h-5 w-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                                Membership Status
                            </h3>
                        </div>
                        <div class="px-6 py-5">
                            <div class="space-y-4">
                                <div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-medium text-gray-500">Status</span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $member->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-green-100 text-green-800' }}">
                                            {{ ucfirst($member->status) }}
                                        </span>
                                    </div>
                                    <div class="mt-1">
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="h-2 rounded-full {{ $member->status === 'active' ? 'bg-green-500' : 'bg-green-500' }}" style="width: {{ $member->status === 'active' ? '100%' : '30%' }}"></div>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-medium text-gray-500">Member Since</span>
                                        <span class="text-sm text-gray-900">{{ $member->join_date->format('M d, Y') }}</span>
                                    </div>
                                </div>
                                @if($member->chapter)
                                <div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-medium text-gray-500">Chapter</span>
                                        <a href="{{ route('chapters.show', $member->chapter) }}" class="text-sm text-blue-600 hover:text-blue-800 hover:underline">
                                            {{ $member->chapter->name }}
                                        </a>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Leadership Roles -->
                    @if($member->ledChapters->count() > 0)
                    <div class="bg-gradient-to-br from-cyan-50 to-sky-50 shadow rounded-lg overflow-hidden border border-cyan-100">
                        <div class="px-6 py-5 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                <svg class="h-5 w-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"></path>
                                </svg>
                                Leadership Roles
                            </h3>
                        </div>
                        <div class="px-6 py-5">
                            <ul class="space-y-4">
                                @foreach($member->ledChapters as $chapter)
                                <li class="relative bg-blue-50 rounded-lg p-4 hover:bg-blue-100 transition-colors duration-150">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1 min-w-0">
                                            <h4 class="text-sm font-medium text-blue-900 truncate">{{ $chapter->name }}</h4>
                                            <p class="text-xs text-blue-700 mt-1">{{ $chapter->location }}</p>
                                            <span class="mt-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $chapter->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ ucfirst($chapter->status) }}
                                            </span>
                                        </div>
                                        <a href="{{ route('chapters.show', $chapter) }}" class="ml-4 flex-shrink-0 text-blue-600 hover:text-blue-800">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    @endif

                    <!-- Quick Actions -->
                   <!-- Leadership Roles -->
                   @if($member->ledChapters->count() > 0)
                    <div class="bg-gradient-to-br from-cyan-50 to-sky-50 shadow rounded-lg overflow-hidden border border-cyan-100">
                        <div class="px-6 py-5 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                <svg class="h-5 w-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                </svg>
                                Quick Actions
                            </h3>
                        </div>
                        <div class="px-6 py-4 space-y-3">
                            <a href="{{ route('members.edit', $member) }}" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md transition-colors duration-150">
                                <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit Profile
                            </a>
                            <a href="#" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md transition-colors duration-150">
                                <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path>
                                </svg>
                                Send Message
                            </a>
                            <form action="{{ route('members.destroy', $member) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this member? This action cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full group flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 rounded-md transition-colors duration-150">
                                    <svg class="mr-3 h-5 w-5 text-red-400 group-hover:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Delete Member
                                </button>
                            </form>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
    </div>

    @push('styles')
    <style>
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
        
        /* Smooth transitions */
        .transition-colors {
            transition-property: background-color, border-color, color, fill, stroke;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }
        
        /* Card hover effect */
        .hover\:shadow-lg {
            transition: all 0.2s ease-in-out;
        }
        
        .hover\:shadow-lg:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        /* Mobile-specific centering for user information */
        @media (max-width: 640px) {
            .mobile-center-info .grid {
                justify-items: center;
            }
            
            .mobile-center-info > div {
                max-width: 280px;
                margin: 0 auto;
            }
        }
    </style>
    @endpush
</x-app-layout>