<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col lg:flex-row lg:items-center space-y-4 lg:space-y-0">
            <div class="flex items-center space-x-4 flex-1">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $event->title }}</h2>
                    <p class="text-sm text-gray-600">{{ $event->date->format('l, F j, Y') }} • {{ \Carbon\Carbon::parse($event->time)->format('g:i A') }}</p>
                </div>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('events.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Events
                </a>
                @php $role = auth()->user()->role ?? 'Guest'; @endphp
                @if(in_array($role, ['Admin', 'Leader']))
                    <a href="{{ route('events.edit', $event) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium text-white bg-yellow-500 hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit Event
                    </a>
                    <form action="{{ route('events.destroy', $event) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium text-white bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200" onclick="return confirm('Are you sure you want to delete this event?')">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                    <!-- Event Header with Enhanced Info -->
                    <div class="bg-gradient-to-r from-blue-500 via-purple-600 to-indigo-600 p-6 text-white rounded-t-lg relative overflow-hidden">
                        <div class="absolute inset-0 bg-black opacity-10"></div>
                        <div class="relative z-10">
                            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                                <div class="flex-1">
                                    <h3 class="text-3xl font-bold mb-2">{{ $event->title }}</h3>
                                    <div class="flex flex-wrap items-center gap-4 text-blue-100">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            {{ $event->date->format('l, F j, Y') }}
                                        </div>
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ \Carbon\Carbon::parse($event->time)->format('g:i A') }}
                                            @if($event->end_time)
                                                - {{ \Carbon\Carbon::parse($event->end_time)->format('g:i A') }}
                                            @endif
                                        </div>
                                        @if($event->location)
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            {{ $event->location }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="mt-4 lg:mt-0 lg:ml-6">
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                        <div class="text-center">
                                            <div class="text-2xl font-bold">{{ $event->attendance->count() }}</div>
                                            <div class="text-sm text-blue-200">Total Attendees</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="text-2xl font-bold">{{ $event->attendance->where('status', 'present')->count() }}</div>
                                            <div class="text-sm text-blue-200">Present</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="text-2xl font-bold">{{ $event->attendance->where('status', 'absent')->count() }}</div>
                                            <div class="text-sm text-blue-200">Absent</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="text-2xl font-bold">
                                                @php
                                                    $daysUntil = now()->diffInDays($event->date, false);
                                                    echo $daysUntil >= 0 ? $daysUntil : 0;
                                                @endphp
                                            </div>
                                            <div class="text-sm text-blue-200">Days Until</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Event Content with Image and Information Side by Side -->
                    <div class="bg-white rounded-b-lg shadow-md border border-gray-200 mb-6 overflow-hidden">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
                            <!-- Event Image Container -->
                            <div class="lg:order-1">
                                @if($event->image && file_exists(storage_path('app/public/' . $event->image)))
                                    <div class="h-80 lg:h-full min-h-80 overflow-hidden">
                                        <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                    </div>
                                @else
                                    <div class="h-80 lg:h-full min-h-80 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                        <div class="text-center">
                                            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <p class="text-gray-500 text-sm">No image available</p>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Event Information Container -->
                            <div class="lg:order-2 p-6">
                                <div class="space-y-6">
                                    <!-- Event Details Section -->
                                            <div>
                                        <h5 class="text-sm font-medium text-gray-500 mb-4 uppercase tracking-wide">Event Details</h5>
                                        <dl class="space-y-4">
                                            <div class="flex items-start">
                                                <div class="flex-shrink-0">
                                                    <svg class="h-5 w-5 text-blue-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                                <div class="flex-1">
                                                    <dt class="text-sm font-medium text-gray-500">Date</dt>
                                                    <dd class="text-sm text-gray-900 font-medium">{{ $event->date->format('l, F j, Y') }}</dd>
                                                </div>
                                            </div>
                                            <div class="flex items-start">
                                                <div class="flex-shrink-0">
                                                    <svg class="h-5 w-5 text-green-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </div>
                                                <div class="flex-1">
                                                <dt class="text-sm font-medium text-gray-500">Time</dt>
                                                    <dd class="text-sm text-gray-900 font-medium">
                                                    {{ \Carbon\Carbon::parse($event->time)->format('g:i A') }}
                                                    @if($event->end_time)
                                                        - {{ \Carbon\Carbon::parse($event->end_time)->format('g:i A') }}
                                                    @endif
                                                </dd>
                                            </div>
                                        </div>
                                        @if($event->location)
                                        <div class="flex items-start">
                                                <div class="flex-shrink-0">
                                                    <svg class="h-5 w-5 text-red-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                                </div>
                                                <div class="flex-1">
                                                <dt class="text-sm font-medium text-gray-500">Location</dt>
                                                    <dd class="text-sm text-gray-900 font-medium">{{ $event->location }}</dd>
                                                </div>
                                            </div>
                                            @endif
                                        </dl>
                                    </div>

                                    <!-- Status and Additional Info Section -->
                                    <div class="border-t border-gray-200 pt-6">
                                        <h5 class="text-sm font-medium text-gray-500 mb-4 uppercase tracking-wide">Additional Information</h5>
                                        <dl class="space-y-4">
                                            <div class="flex items-start">
                                                <div class="flex-shrink-0">
                                                    <svg class="h-5 w-5 text-purple-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                </div>
                                                <div class="flex-1">
                                                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                                                    <dd class="text-sm">
                                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $event->status === 'upcoming' ? 'bg-green-100 text-green-800' : ($event->status === 'ongoing' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                                            {{ ucfirst($event->status) }}
                                                        </span>
                                                    </dd>
                                                </div>
                                            </div>
                                            @if($event->description)
                                            <div class="flex items-start">
                                                <div class="flex-shrink-0">
                                                    <svg class="h-5 w-5 text-indigo-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </div>
                                                <div class="flex-1">
                                                    <dt class="text-sm font-medium text-gray-500">Description</dt>
                                                    <dd class="text-sm text-gray-900 leading-relaxed">{{ $event->description }}</dd>
                                            </div>
                                        </div>
                                        @endif
                                    </dl>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- QR Code Scanner for Check-in -->
                    <div class="bg-white border border-gray-200 mb-6 p-6">
                        <div class="text-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Member Check-in Scanner</h3>
                            <p class="text-gray-600">Scan member QR codes to check them in for this event</p>
                        </div>
                        
                        <div class="flex flex-col lg:flex-row gap-6">
                            <!-- QR Scanner -->
                            <div class="flex-1">
                                <div class="bg-gray-100 rounded-lg overflow-hidden mb-4" style="min-height: 300px;">
                                <div id="qr-reader" style="width: 100%;">
                                    <div class="flex items-center justify-center h-64 bg-gray-100 rounded-lg">
                                        <div class="text-center">
                                            <i class="fas fa-camera text-gray-400 text-4xl mb-2"></i>
                                            <p class="text-gray-600 font-medium">QR Code Scanner</p>
                                            <p class="text-gray-500 text-sm mt-1">Click "Start Scanner" to begin</p>
                                            <button onclick="window.initScanner()" class="mt-3 px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                                                <i class="fas fa-play mr-2"></i>
                                                Start Scanner
                                            </button>
                                            <div class="mt-2">
                                                <button onclick="window.initSimpleScanner()" class="px-3 py-1 bg-gray-600 text-white text-sm rounded hover:bg-gray-700">
                                                    Try Alternative Scanner
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </div>
                                
                                <!-- Manual QR Code Input -->
                                <div class="mt-4">
                                    <p class="text-center text-gray-600 mb-2">- OR -</p>
                                    <div class="flex">
                                        <input 
                                            type="text" 
                                            id="manual-qr-input" 
                                            placeholder="Enter member QR code value"
                                            class="flex-1 rounded-l-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        >
                                        <button 
                                            id="manual-submit"
                                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-r-lg transition-colors"
                                        >
                                            Check In
                                        </button>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Enter member's QR code value if the scanner isn't working</p>
                                </div>
                                
                                <div id="qr-reader-results" class="mt-4"></div>
                                
                                <!-- Scanner Controls -->
                                <div id="scanner-controls" class="mt-4 text-center" style="display: none;">
                                    <button onclick="stopScanner()" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                                        <i class="fas fa-stop mr-2"></i>
                                        Stop Scanner
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Quick Actions -->
                            <div class="lg:w-80">
                                <div class="space-y-3">
                                    <a href="{{ route('events.check-in', $event) }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-500 text-white text-sm font-medium rounded hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Manual Check-in
                                    </a>
                                    <button onclick="window.print()" class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-500 text-white text-sm font-medium rounded hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                        </svg>
                                        Print Event
                                    </button>
                                    <button onclick="shareEvent()" class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z" />
                                        </svg>
                                        Share Event
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Member View -->
                @if(isset($isMemberView) && $isMemberView)
                <div class="p-6 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                    <!-- Event Header -->
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 text-gray-900 rounded-t-lg">
                        <h3 class="text-2xl font-bold">{{ $event->title }}</h3>
                        <p class="text-blue-100 mt-1">{{ $event->date->format('l, F j, Y') }}</p>
                    </div>

                    <!-- Event Content with Image and Information Side by Side -->
                    <div class="bg-white rounded-b-lg shadow-md border border-gray-200 mb-6 overflow-hidden">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
                            <!-- Event Image Container -->
                            <div class="lg:order-1">
                                @if($event->image && file_exists(storage_path('app/public/' . $event->image)))
                                    <div class="h-80 lg:h-full min-h-80 overflow-hidden">
                                        <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                    </div>
                                @else
                                    <div class="h-80 lg:h-full min-h-80 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                        <div class="text-center">
                                            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <p class="text-gray-500 text-sm">No image available</p>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Event Information Container -->
                            <div class="lg:order-2 p-6">
                                <div class="space-y-6">
                                    <!-- Event Details Section -->
                                            <div>
                                        <h5 class="text-sm font-medium text-gray-500 mb-4 uppercase tracking-wide">Event Details</h5>
                                        <dl class="space-y-4">
                                            <div class="flex items-start">
                                                <div class="flex-shrink-0">
                                                    <svg class="h-5 w-5 text-blue-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                                <div class="flex-1">
                                                    <dt class="text-sm font-medium text-gray-500">Date</dt>
                                                    <dd class="text-sm text-gray-900 font-medium">{{ $event->date->format('l, F j, Y') }}</dd>
                                                </div>
                                            </div>
                                            <div class="flex items-start">
                                                <div class="flex-shrink-0">
                                                    <svg class="h-5 w-5 text-green-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </div>
                                                <div class="flex-1">
                                                    <dt class="text-sm font-medium text-gray-500">Time</dt>
                                                    <dd class="text-sm text-gray-900 font-medium">
                                                        {{ \Carbon\Carbon::parse($event->time)->format('g:i A') }}
                                                        @if($event->end_time)
                                                            - {{ \Carbon\Carbon::parse($event->end_time)->format('g:i A') }}
                                                        @endif
                                                    </dd>
                                                </div>
                                            </div>
                                            @if($event->location)
                                            <div class="flex items-start">
                                                <div class="flex-shrink-0">
                                                    <svg class="h-5 w-5 text-red-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                </div>
                                                <div class="flex-1">
                                                    <dt class="text-sm font-medium text-gray-500">Location</dt>
                                                    <dd class="text-sm text-gray-900 font-medium">{{ $event->location }}</dd>
                                                </div>
                                            </div>
                                            @endif
                                        </dl>
                                    </div>

                                    <!-- Status and Additional Info Section -->
                                    <div class="border-t border-gray-200 pt-6">
                                        <h5 class="text-sm font-medium text-gray-500 mb-4 uppercase tracking-wide">Additional Information</h5>
                                        <dl class="space-y-4">
                                            <div class="flex items-start">
                                                <div class="flex-shrink-0">
                                                    <svg class="h-5 w-5 text-purple-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                </div>
                                                <div class="flex-1">
                                                <dt class="text-sm font-medium text-gray-500">Status</dt>
                                                <dd class="text-sm">
                                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $event->status === 'upcoming' ? 'bg-green-100 text-green-800' : ($event->status === 'ongoing' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                                        {{ ucfirst($event->status) }}
                                                    </span>
                                                </dd>
                                            </div>
                                        </div>
                                        @if($event->description)
                                        <div class="flex items-start">
                                                <div class="flex-shrink-0">
                                                    <svg class="h-5 w-5 text-indigo-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                                </div>
                                                <div class="flex-1">
                                                <dt class="text-sm font-medium text-gray-500">Description</dt>
                                                    <dd class="text-sm text-gray-900 leading-relaxed">{{ $event->description }}</dd>
                                            </div>
                                        </div>
                                        @endif
                                    </dl>
                                    </div>
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
<script src="https://unpkg.com/qr-scanner@1.4.2/qr-scanner.umd.min.js"></script>
<script>
// Global QR Scanner Functions
function initScanner() {
    console.log('initScanner called');
    
    const qrReaderElement = document.getElementById('qr-reader');
    if (!qrReaderElement) {
        console.error('QR reader element not found');
        return;
    }

    // Clear any existing content
    qrReaderElement.innerHTML = '';

    // Show loading message
    qrReaderElement.innerHTML = `
        <div class="flex items-center justify-center h-64 bg-gray-100 rounded-lg">
            <div class="text-center">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600 mx-auto mb-2"></div>
                <p class="text-gray-600">Starting camera...</p>
            </div>
        </div>
    `;

    // Check if browser supports camera
    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
        console.error('Camera not supported');
        qrReaderElement.innerHTML = `
            <div class="flex items-center justify-center h-64 bg-red-50 rounded-lg">
                <div class="text-center">
                    <i class="fas fa-exclamation-triangle text-red-500 text-4xl mb-2"></i>
                    <p class="text-red-600 font-medium">Camera Not Supported</p>
                    <p class="text-red-500 text-sm mt-1">Your browser doesn't support camera access</p>
                    <button onclick="window.initSimpleScanner()" class="mt-3 px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                        Try Alternative
                    </button>
                </div>
            </div>
        `;
        return;
    }

    // Create video element for QR scanner
    const video = document.createElement('video');
    video.id = 'qr-video';
    video.style.width = '100%';
    video.style.height = '100%';
    video.style.objectFit = 'cover';
    video.style.borderRadius = '8px';

    // Create container
    const container = document.createElement('div');
    container.style.position = 'relative';
    container.style.width = '100%';
    container.style.height = '300px';
    container.style.overflow = 'hidden';
    container.style.borderRadius = '8px';
    container.style.backgroundColor = '#000';

    // Add video to container
    container.appendChild(video);

    // Add scanning overlay
    const overlay = document.createElement('div');
    overlay.style.position = 'absolute';
    overlay.style.top = '50%';
    overlay.style.left = '50%';
    overlay.style.transform = 'translate(-50%, -50%)';
    overlay.style.width = '200px';
    overlay.style.height = '200px';
    overlay.style.border = '2px solid #3b82f6';
    overlay.style.borderRadius = '8px';
    overlay.style.backgroundColor = 'transparent';
    overlay.style.pointerEvents = 'none';

    // Add corner indicators
    const corners = ['top-left', 'top-right', 'bottom-left', 'bottom-right'];
    corners.forEach(corner => {
        const cornerEl = document.createElement('div');
        cornerEl.style.position = 'absolute';
        cornerEl.style.width = '20px';
        cornerEl.style.height = '20px';
        cornerEl.style.border = '3px solid #3b82f6';
        cornerEl.style.backgroundColor = 'transparent';
        
        if (corner.includes('top')) cornerEl.style.top = '-3px';
        if (corner.includes('bottom')) cornerEl.style.bottom = '-3px';
        if (corner.includes('left')) cornerEl.style.left = '-3px';
        if (corner.includes('right')) cornerEl.style.right = '-3px';
        
        if (corner === 'top-left') {
            cornerEl.style.borderRight = 'none';
            cornerEl.style.borderBottom = 'none';
        } else if (corner === 'top-right') {
            cornerEl.style.borderLeft = 'none';
            cornerEl.style.borderBottom = 'none';
        } else if (corner === 'bottom-left') {
            cornerEl.style.borderRight = 'none';
            cornerEl.style.borderTop = 'none';
        } else if (corner === 'bottom-right') {
            cornerEl.style.borderLeft = 'none';
            cornerEl.style.borderTop = 'none';
        }
        
        overlay.appendChild(cornerEl);
    });

    container.appendChild(overlay);

    // Add instructions
    const instructions = document.createElement('div');
    instructions.style.position = 'absolute';
    instructions.style.bottom = '10px';
    instructions.style.left = '50%';
    instructions.style.transform = 'translateX(-50%)';
    instructions.style.color = 'white';
    instructions.style.backgroundColor = 'rgba(0,0,0,0.7)';
    instructions.style.padding = '8px 16px';
    instructions.style.borderRadius = '4px';
    instructions.style.fontSize = '14px';
    instructions.textContent = 'Point camera at QR code';
    container.appendChild(instructions);

    // Replace content
    qrReaderElement.innerHTML = '';
    qrReaderElement.appendChild(container);

    // Initialize QR Scanner
    const qrScanner = new QrScanner(video, result => {
        console.log('QR Code detected:', result);
        handleQrCode(result.data);
    }, {
        onDecodeError: error => {
            // Silently handle decode errors - they're very common
            // console.log('QR decode error:', error);
        },
        highlightScanRegion: true,
        highlightCodeOutline: true,
    });

    // Start scanning
    qrScanner.start().then(() => {
        console.log('QR Scanner started successfully');
        
        // Show scanner controls
        const controls = document.getElementById('scanner-controls');
        if (controls) {
            controls.style.display = 'block';
        }

        // Store scanner instance for cleanup
        window.currentQrScanner = qrScanner;
    }).catch(error => {
        console.error('QR Scanner start error:', error);
        qrReaderElement.innerHTML = `
            <div class="flex items-center justify-center h-64 bg-red-50 rounded-lg">
                <div class="text-center">
                    <i class="fas fa-exclamation-triangle text-red-500 text-4xl mb-2"></i>
                    <p class="text-red-600 font-medium">Camera Access Denied</p>
                    <p class="text-red-500 text-sm mt-1">Please allow camera access and try again</p>
                    <button onclick="window.initScanner()" class="mt-3 px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                        Retry
                    </button>
                    <button onclick="window.initSimpleScanner()" class="mt-2 px-3 py-1 bg-gray-600 text-white text-sm rounded hover:bg-gray-700">
                        Try Alternative
                    </button>
                </div>
            </div>
        `;
    });
}

function stopScanner() {
    console.log('stopScanner called');
    
    // Stop QR scanner
    if (window.currentQrScanner) {
        window.currentQrScanner.stop();
        window.currentQrScanner.destroy();
        window.currentQrScanner = null;
        console.log('QR Scanner stopped');
    }
    
    // Hide scanner controls
    const controls = document.getElementById('scanner-controls');
    if (controls) {
        controls.style.display = 'none';
    }
    
    // Reset scanner area
    const qrReaderElement = document.getElementById('qr-reader');
    if (qrReaderElement) {
        qrReaderElement.innerHTML = `
            <div class="flex items-center justify-center h-64 bg-gray-100 rounded-lg">
                <div class="text-center">
                    <i class="fas fa-camera text-gray-400 text-4xl mb-2"></i>
                    <p class="text-gray-600 font-medium">QR Code Scanner</p>
                    <p class="text-gray-500 text-sm mt-1">Click "Start Scanner" to begin</p>
                    <button onclick="window.initScanner()" class="mt-3 px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                        <i class="fas fa-play mr-2"></i>
                        Start Scanner
                    </button>
                    <div class="mt-2">
                        <button onclick="window.initSimpleScanner()" class="px-3 py-1 bg-gray-600 text-white text-sm rounded hover:bg-gray-700">
                            Try Alternative Scanner
                        </button>
                    </div>
                </div>
            </div>
        `;
    }
}

function shareEvent() {
    if (navigator.share) {
        navigator.share({
            title: '{{ $event->title }}',
            text: 'Join us for {{ $event->title }} on {{ $event->date->format("l, F j, Y") }} at {{ $event->time }}',
            url: window.location.href
        }).catch(console.error);
    } else {
        // Fallback for browsers that don't support Web Share API
        const shareText = `Join us for {{ $event->title }} on {{ $event->date->format("l, F j, Y") }} at {{ $event->time }}`;
        const shareUrl = window.location.href;
        
        if (navigator.clipboard) {
            navigator.clipboard.writeText(`${shareText}\n${shareUrl}`).then(() => {
                alert('Event details copied to clipboard!');
            });
        } else {
            // Fallback for older browsers
            const textArea = document.createElement('textarea');
            textArea.value = `${shareText}\n${shareUrl}`;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            alert('Event details copied to clipboard!');
        }
    }
}

// QR Scanner functionality for admin/leader view
function showError(message) {
    const resultDiv = document.getElementById('qr-reader-results');
    if (resultDiv) {
        resultDiv.innerHTML = `
            <div class="p-4 bg-red-50 text-red-800 rounded-lg">
                <i class="fas fa-exclamation-circle mr-2"></i>
                ${message}
            </div>
        `;
    }
    console.error(message);
}

function showSuccess(message) {
    const resultDiv = document.getElementById('qr-reader-results');
    if (resultDiv) {
        resultDiv.innerHTML = `
            <div class="p-4 bg-green-50 text-green-800 rounded-lg">
                <i class="fas fa-check-circle mr-2"></i>
                ${message}
            </div>
        `;
    }
}

function showLoading(message = 'Processing check-in...') {
    const resultDiv = document.getElementById('qr-reader-results');
    if (resultDiv) {
        resultDiv.innerHTML = `
            <div class="p-4 bg-yellow-50 text-yellow-800 rounded-lg">
                <i class="fas fa-spinner fa-spin mr-2"></i>
                ${message}
            </div>
        `;
    }
}

async function handleQrCode(qrData) {
    showLoading();
    console.log('Processing QR code:', qrData);

    try {
        // Extract the QR code value from the URL if it's a full URL
        let qrValue = qrData;
        if (qrData.includes('/qr/scan/')) {
            const urlParts = qrData.split('/qr/scan/');
            if (urlParts.length > 1) {
                qrValue = urlParts[1].split('?')[0]; // Remove any query parameters
            }
        }

        const response = await fetch(`/api/events/{{ $event->id }}/check-in`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                qr_data: qrValue,
                event_id: {{ $event->id }}
            })
        });

        const data = await response.json();
        console.log('API Response:', data);

        if (!response.ok) {
            throw new Error(data.message || 'Failed to process check-in');
        }

        if (data.success) {
            showSuccess(data.message || 'Member checked in successfully!');
            // Reload the page to update attendance stats
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        } else {
            showError(data.message || 'Check-in failed. Please try again.');
        }

        return data;
    } catch (error) {
        console.error('Error during check-in:', error);
        showError(error.message || 'An error occurred. Please try again or contact support.');
        throw error;
    }
}

// Alternative Simple Scanner (using getUserMedia directly)
function initSimpleScanner() {
        const qrReaderElement = document.getElementById('qr-reader');
        if (!qrReaderElement) {
            console.error('QR reader element not found');
            return;
        }

        // Clear any existing content
        qrReaderElement.innerHTML = '';

        // Show loading message
        qrReaderElement.innerHTML = `
            <div class="flex items-center justify-center h-64 bg-gray-100 rounded-lg">
                <div class="text-center">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600 mx-auto mb-2"></div>
                    <p class="text-gray-600">Starting camera...</p>
                </div>
            </div>
        `;

        // Check if getUserMedia is available
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            qrReaderElement.innerHTML = `
                <div class="flex items-center justify-center h-64 bg-red-50 rounded-lg">
                    <div class="text-center">
                        <i class="fas fa-exclamation-triangle text-red-500 text-4xl mb-2"></i>
                        <p class="text-red-600 font-medium">Camera Not Supported</p>
                        <p class="text-red-500 text-sm mt-1">Your browser doesn't support camera access</p>
                    </div>
                </div>
            `;
            return;
        }

        // Request camera access
        navigator.mediaDevices.getUserMedia({ 
            video: { 
                facingMode: 'environment',
                width: { ideal: 640 },
                height: { ideal: 480 }
            } 
        })
        .then(stream => {
            // Create video element
            const video = document.createElement('video');
            video.srcObject = stream;
            video.autoplay = true;
            video.playsInline = true;
            video.style.width = '100%';
            video.style.height = '100%';
            video.style.objectFit = 'cover';

            // Create container
            const container = document.createElement('div');
            container.style.position = 'relative';
            container.style.width = '100%';
            container.style.height = '300px';
            container.style.overflow = 'hidden';
            container.style.borderRadius = '8px';
            container.style.backgroundColor = '#000';

            // Add video to container
            container.appendChild(video);

            // Add overlay with scanning area
            const overlay = document.createElement('div');
            overlay.style.position = 'absolute';
            overlay.style.top = '50%';
            overlay.style.left = '50%';
            overlay.style.transform = 'translate(-50%, -50%)';
            overlay.style.width = '200px';
            overlay.style.height = '200px';
            overlay.style.border = '2px solid #3b82f6';
            overlay.style.borderRadius = '8px';
            overlay.style.backgroundColor = 'transparent';
            overlay.style.pointerEvents = 'none';

            // Add corner indicators
            const corners = ['top-left', 'top-right', 'bottom-left', 'bottom-right'];
            corners.forEach(corner => {
                const cornerEl = document.createElement('div');
                cornerEl.style.position = 'absolute';
                cornerEl.style.width = '20px';
                cornerEl.style.height = '20px';
                cornerEl.style.border = '3px solid #3b82f6';
                cornerEl.style.backgroundColor = 'transparent';
                
                if (corner.includes('top')) cornerEl.style.top = '-3px';
                if (corner.includes('bottom')) cornerEl.style.bottom = '-3px';
                if (corner.includes('left')) cornerEl.style.left = '-3px';
                if (corner.includes('right')) cornerEl.style.right = '-3px';
                
                if (corner === 'top-left') {
                    cornerEl.style.borderRight = 'none';
                    cornerEl.style.borderBottom = 'none';
                } else if (corner === 'top-right') {
                    cornerEl.style.borderLeft = 'none';
                    cornerEl.style.borderBottom = 'none';
                } else if (corner === 'bottom-left') {
                    cornerEl.style.borderRight = 'none';
                    cornerEl.style.borderTop = 'none';
                } else if (corner === 'bottom-right') {
                    cornerEl.style.borderLeft = 'none';
                    cornerEl.style.borderTop = 'none';
                }
                
                overlay.appendChild(cornerEl);
            });

            container.appendChild(overlay);

            // Add instructions
            const instructions = document.createElement('div');
            instructions.style.position = 'absolute';
            instructions.style.bottom = '10px';
            instructions.style.left = '50%';
            instructions.style.transform = 'translateX(-50%)';
            instructions.style.color = 'white';
            instructions.style.backgroundColor = 'rgba(0,0,0,0.7)';
            instructions.style.padding = '8px 16px';
            instructions.style.borderRadius = '4px';
            instructions.style.fontSize = '14px';
            instructions.textContent = 'Point camera at QR code';
            container.appendChild(instructions);

            // Replace content
            qrReaderElement.innerHTML = '';
            qrReaderElement.appendChild(container);

            // Show scanner controls
            const controls = document.getElementById('scanner-controls');
            if (controls) {
                controls.style.display = 'block';
            }

            // Store stream for cleanup
            window.currentStream = stream;

            // Note: This is a basic camera view - for actual QR scanning, 
            // you would need to integrate with a QR code detection library
            console.log('Simple camera started - QR detection not implemented yet');

        })
        .catch(error => {
            console.error('Camera access error:', error);
            qrReaderElement.innerHTML = `
                <div class="flex items-center justify-center h-64 bg-red-50 rounded-lg">
                    <div class="text-center">
                        <i class="fas fa-exclamation-triangle text-red-500 text-4xl mb-2"></i>
                        <p class="text-red-600 font-medium">Camera Access Denied</p>
                        <p class="text-red-500 text-sm mt-1">Please allow camera access and try again</p>
                        <button onclick="window.initSimpleScanner()" class="mt-3 px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                            Retry
                        </button>
                    </div>
                </div>
            `;
        });
    }

// Add smooth scrolling and animations
document.addEventListener('DOMContentLoaded', function() {
    // Add fade-in animation to cards
    const cards = document.querySelectorAll('.bg-white.rounded-lg');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });

    // Add hover effects to stats cards
    const statCards = document.querySelectorAll('.text-center.p-4');
    statCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.transition = 'transform 0.2s ease';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // Handle manual form submission for QR scanner
    const manualSubmit = document.getElementById('manual-submit');
    const manualQrInput = document.getElementById('manual-qr-input');

    if (manualSubmit && manualQrInput) {
        manualSubmit.addEventListener('click', function() {
            const qrValue = manualQrInput.value.trim();
            if (!qrValue) {
                showError('Please enter a QR code value');
                return;
            }
            handleQrCode(qrValue);
        });

        manualQrInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                manualSubmit.click();
            }
        });
    }

    // Don't auto-start scanner - let user click the button
    // initScanner();

    // Cleanup scanner when the page is unloaded
    window.addEventListener('beforeunload', function() {
        if (window.currentQrScanner) {
            window.currentQrScanner.stop();
            window.currentQrScanner.destroy();
        }
    });
});
</script>

<style>
@media print {
    .no-print {
        display: none !important;
    }
    
    .print-header {
        background: #f3f4f6 !important;
        -webkit-print-color-adjust: exact;
        color-adjust: exact;
    }
    
    .print-content {
        color: #000 !important;
    }
}

/* Custom animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.fade-in-up {
    animation: fadeInUp 0.6s ease-out;
}

/* Enhanced hover effects */
.hover-lift {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.hover-lift:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

/* Gradient text effect */
.gradient-text {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
</style>
@endpush