<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center"> 
        
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Attendance Details') }}
            </h2>
            @if(in_array(auth()->user()->role, ['Admin', 'Leader']))
                <div class="flex gap-4">
                <a href="{{ url()->previous() }}" class="inline-flex items-left px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back
                    </a>
                   
                    <a href="{{ route('attendance.edit', $attendanceRecord) }}" style="display: inline-flex; align-items: center; padding: 0.5rem 1rem; background-color: #2563eb; color: white; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; border-radius: 0.375rem; text-decoration: none;">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Attendance
                    </a>
                    <form action="{{ route('attendance.destroy', $attendanceRecord) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150" 
                                onclick="return confirm('Are you sure you want to delete this attendance record?')">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Delete
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-6">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                        <!-- Member Card -->
                        <div class="w-full md:w-1/3">
                            <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
                                <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 text-gray-700">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            <div class="h-16 w-16 rounded-full bg-white bg-opacity-20 flex items-center justify-center text-2xl font-bold text-gray-700">
                                                {{ substr($attendanceRecord->member->name, 0, 1) }}
                                            </div>
                                        </div>
                                        <div>
                                            <h3 class="text-xl font-semibold">{{ $attendanceRecord->member->name }}</h3>
                                            @if($attendanceRecord->member->email)
                                                <p class="text-blue-100 text-sm">{{ $attendanceRecord->member->email }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="p-6">
                                    <dl class="space-y-4">
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Member Since</dt>
                                            <dd class="mt-1 text-sm text-gray-900">{{ $attendanceRecord->member->created_at->format('M d, Y') }}</dd>
                                        </div>
                                        @if($attendanceRecord->member->phone)
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Phone</dt>
                                            <dd class="mt-1 text-sm text-gray-900">{{ $attendanceRecord->member->phone }}</dd>
                                        </div>
                                        @endif
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                                            <dd class="mt-1">
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $attendanceRecord->status === 'present' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ ucfirst($attendanceRecord->status) }}
                                                </span>
                                            </dd>
                                        </div>
                                    </dl>
                                </div>
                            </div>
                        </div>

                        <!-- Event and Attendance Details -->
                        <div class="flex-1">
                            <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
                                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                                    <h3 class="text-lg font-medium text-gray-900">Event Information</h3>
                                </div>
                                <div class="p-6">
                                    <h4 class="text-xl font-semibold text-gray-800 mb-2">{{ $attendanceRecord->event->title }}</h4>
                                    <p class="text-gray-600 mb-6">{{ $attendanceRecord->event->description ?? 'No description available' }}</p>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <h5 class="text-sm font-medium text-gray-500 mb-3">EVENT DETAILS</h5>
                                            <dl class="space-y-3">
                                                <div class="flex items-start">
                                                    <svg class="h-5 w-5 text-gray-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    <div>
                                                        <dt class="text-sm font-medium text-gray-500">Date</dt>
                                                        <dd class="text-sm text-gray-900">{{ $attendanceRecord->event->date->format('l, F j, Y') }}</dd>
                                                    </div>
                                                </div>
                                                <div class="flex items-start">
                                                    <svg class="h-5 w-5 text-gray-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <div>
                                                        <dt class="text-sm font-medium text-gray-500">Time</dt>
                                                        <dd class="text-sm text-gray-900">
                                                            {{ \Carbon\Carbon::parse($attendanceRecord->event->start_time)->format('g:i A') }}
                                                            @if($attendanceRecord->event->end_time)
                                                                - {{ \Carbon\Carbon::parse($attendanceRecord->event->end_time)->format('g:i A') }}
                                                            @endif
                                                        </dd>
                                                    </div>
                                                </div>
                                                @if($attendanceRecord->event->location)
                                                <div class="flex items-start">
                                                    <svg class="h-5 w-5 text-gray-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                    <div>
                                                        <dt class="text-sm font-medium text-gray-500">Location</dt>
                                                        <dd class="text-sm text-gray-900">{{ $attendanceRecord->event->location }}</dd>
                                                    </div>
                                                </div>
                                                @endif
                                            </dl>
                                        </div>
                                        
                                        <div>
                                            <h5 class="text-sm font-medium text-gray-500 mb-3">ATTENDANCE DETAILS</h5>
                                            <dl class="space-y-3">
                                                <div class="flex items-start">
                                                    <svg class="h-5 w-5 text-gray-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                                    </svg>
                                                    <div>
                                                        <dt class="text-sm font-medium text-gray-500">Recorded On</dt>
                                                        <dd class="text-sm text-gray-900">{{ $attendanceRecord->created_at->format('M j, Y \a\t g:i A') }}</dd>
                                                    </div>
                                                </div>
                                                @if($attendanceRecord->check_in_time)
                                                <div class="flex items-start">
                                                    <svg class="h-5 w-5 text-gray-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <div>
                                                        <dt class="text-sm font-medium text-gray-500">Checked In At</dt>
                                                        <dd class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($attendanceRecord->check_in_time)->format('g:i A') }}</dd>
                                                    </div>
                                                </div>
                                                @endif
                                                @if($attendanceRecord->notes)
                                                <div class="flex items-start">
                                                    <svg class="h-5 w-5 text-gray-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                    <div>
                                                        <dt class="text-sm font-medium text-gray-500">Notes</dt>
                                                        <dd class="text-sm text-gray-900">{{ $attendanceRecord->notes }}</dd>
                                                    </div>
                                                </div>
                                                @endif
                                            </dl>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            @if(in_array(auth()->user()->role, ['Admin', 'Leader']))
                            <div class="mt-6 flex justify-end space-x-3">
                                <a href="{{ route('members.show', $attendanceRecord->member) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-white bg-blue-500 hover:bg-blue-60 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="-ml-1 mr-2 h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    View Member Profile
                                </a>
                                <a href="{{ route('events.show', $attendanceRecord->event) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-white bg-blue-500 hover:bg-blue focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    View Event Details
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>