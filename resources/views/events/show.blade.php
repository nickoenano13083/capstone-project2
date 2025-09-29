<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Event Details') }}
            </h2>
            <div class="flex gap-4">
                <a href="{{ route('events.index') }}" class="inline-flex items-left px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back
                </a>
                @php $role = auth()->user()->role ?? 'Guest'; @endphp
                @if(in_array($role, ['Admin', 'Leader']))
                    <a href="{{ route('events.edit', $event) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-yellow-500 hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                        <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit Event
                    </a>
                    <form action="{{ route('events.destroy', $event) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" onclick="return confirm('Are you sure you want to delete this event?')">
                            <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Delete
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                @if(in_array(auth()->user()->role, ['Admin', 'Leader']))
                <div class="p-6 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                    <!-- Event Information Card -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200 mb-6">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 text-gray-900">
                            <h3 class="text-2xl font-bold">{{ $event->title }}</h3>
                            <p class="text-blue-100 mt-1">{{ $event->date->format('l, F j, Y') }}</p>
                        </div>
                        <div class="p-6">
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
                                                <dd class="text-sm text-gray-900">{{ $event->date->format('l, F j, Y') }}</dd>
                                            </div>
                                        </div>
                                        <div class="flex items-start">
                                            <svg class="h-5 w-5 text-gray-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <div>
                                                <dt class="text-sm font-medium text-gray-500">Time</dt>
                                                <dd class="text-sm text-gray-900">
                                                    {{ \Carbon\Carbon::parse($event->time)->format('g:i A') }}
                                                    @if($event->end_time)
                                                        - {{ \Carbon\Carbon::parse($event->end_time)->format('g:i A') }}
                                                    @endif
                                                </dd>
                                            </div>
                                        </div>
                                        @if($event->location)
                                        <div class="flex items-start">
                                            <svg class="h-5 w-5 text-gray-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            <div>
                                                <dt class="text-sm font-medium text-gray-500">Location</dt>
                                                <dd class="text-sm text-gray-900">{{ $event->location }}</dd>
                                            </div>
                                        </div>
                                        @endif
                                    </dl>
                                </div>
                                <div>
                                    <h5 class="text-sm font-medium text-gray-500 mb-3">ADDITIONAL INFORMATION</h5>
                                    <dl class="space-y-3">
                                        <div class="flex items-start">
                                            <svg class="h-5 w-5 text-gray-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <div>
                                                <dt class="text-sm font-medium text-gray-500">Status</dt>
                                                <dd class="text-sm">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $event->status === 'upcoming' ? 'bg-green-100 text-green-800' : ($event->status === 'ongoing' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                                        {{ ucfirst($event->status) }}
                                                    </span>
                                                </dd>
                                            </div>
                                        </div>
                                        @if($event->description)
                                        <div class="flex items-start">
                                            <svg class="h-5 w-5 text-gray-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <div>
                                                <dt class="text-sm font-medium text-gray-500">Description</dt>
                                                <dd class="text-sm text-gray-900">{{ $event->description }}</dd>
                                            </div>
                                        </div>
                                        @endif
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Attendance List -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Attendance List</h3>
                    </div>
                    <div class="p-6">
                        @if($event->attendance->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check-in Time</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($event->attendance as $attendance)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-medium">
                                                            {{ substr($attendance->member->name, 0, 1) }}
                                                        </div>
                                                        <div class="ml-4">
                                                            <div class="text-sm font-medium text-gray-900">{{ $attendance->member->name }}</div>
                                                            @if($attendance->member->email)
                                                                <div class="text-sm text-gray-500">{{ $attendance->member->email }}</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $attendance->status === 'present' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                        {{ ucfirst($attendance->status) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    @if($attendance->check_in_time)
                                                        {{ \Carbon\Carbon::parse($attendance->check_in_time)->format('M j, Y g:i A') }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <a href="{{ route('attendance.edit', $attendance) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">
                                                        <svg class="h-5 w-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </a>
                                                    <a href="{{ route('members.show', $attendance->member) }}" class="text-blue-600 hover:text-blue-900">
                                                        <svg class="h-5 w-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                        </svg>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No attendance records</h3>
                                <p class="mt-1 text-sm text-gray-500">Get started by adding members to this event.</p>
                                @if(in_array(auth()->user()->role, ['Admin', 'Leader']))
                                    <div class="mt-6">
                                        <a href="{{ route('attendance.create', ['event_id' => $event->id]) }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                            </svg>
                                            Add Attendance
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@push('scripts')
@endpush