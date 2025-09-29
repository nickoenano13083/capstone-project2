<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('QR Code Management - ') }}{{ $event->title }}
            </h2>
            <a href="{{ route('events.show', $event) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-arrow-left mr-2"></i>Back to Event
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Event Information -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $event->title }}</h3>
                            <div class="text-gray-600 mt-1">
                                <span class="mr-4"><i class="fas fa-calendar-alt mr-1"></i>{{ $event->date->format('l, F j, Y') }}</span>
                                @if($event->time)
                                    <span class="mr-4"><i class="fas fa-clock mr-1"></i>{{ $event->time }}</span>
                                @endif
                                @if($event->location)
                                    <span><i class="fas fa-map-marker-alt mr-1"></i>{{ $event->location }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm text-gray-500">Event Status</div>
                            <div class="font-medium">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $event->status === 'upcoming' ? 'bg-green-100 text-green-800' : 
                                       ($event->status === 'ongoing' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ ucfirst($event->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active QR Code Section -->
            @if($activeQrCode)
                <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-green-900 mb-2">
                                <i class="fas fa-check-circle mr-2"></i>
                                Active QR Code
                            </h3>
                            <p class="text-green-700">QR Code is currently active and can be used for attendance tracking.</p>
                            <p class="text-sm text-green-600 mt-1">
                                Expires: {{ $activeQrCode->expires_at->format('M j, Y g:i A') }}
                            </p>
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('qr.show', $activeQrCode) }}" 
                               class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                                <i class="fas fa-eye mr-2"></i>
                                Manage QR Code
                            </a>
                            <form action="{{ route('qr.deactivate', $activeQrCode) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" 
                                        class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-md transition-colors"
                                        onclick="return confirm('Are you sure you want to deactivate this QR code?')">
                                    <i class="fas fa-stop-circle mr-2"></i>
                                    Deactivate
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-yellow-900 mb-2">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                No Active QR Code
                            </h3>
                            <p class="text-yellow-700">Generate a QR code to enable attendance tracking for this event.</p>
                        </div>
                        <form action="{{ route('qr.generate', $event) }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                                <i class="fas fa-qrcode mr-2"></i>
                                Generate QR Code
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-qrcode text-blue-600 text-xl"></i>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Generate QR Code</h3>
                        <p class="text-gray-600 text-sm mb-4">Create a new QR code for attendance tracking</p>
                        <form action="{{ route('qr.generate', $event) }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                                Generate New QR Code
                            </button>
                        </form>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-users text-green-600 text-xl"></i>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">View Attendance</h3>
                        <p class="text-gray-600 text-sm mb-4">Check attendance records for this event</p>
                        <a href="{{ route('attendance.index', ['event_id' => $event->id]) }}" 
                           class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md transition-colors inline-block">
                            View Attendance
                        </a>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-mobile-alt text-purple-600 text-xl"></i>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Scanner App</h3>
                        <p class="text-gray-600 text-sm mb-4">Access the mobile scanner for manual entry</p>
                        <a href="{{ route('qr.scanner') }}" 
                           class="w-full bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-4 rounded-md transition-colors inline-block">
                            Open Scanner
                        </a>
                    </div>
                </div>
            </div>

            <!-- QR Code History -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">QR Code History</h3>
                    
                    @if($qrCodes->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expires</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created By</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($qrCodes as $qrCode)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900">
                                                {{ Str::limit($qrCode->code, 20) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($qrCode->isValid())
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        Active
                                                    </span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                        Inactive
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $qrCode->created_at->format('M j, Y g:i A') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $qrCode->expires_at->format('M j, Y g:i A') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $qrCode->creator->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('qr.show', $qrCode) }}" class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                                @if($qrCode->isValid())
                                                    <form action="{{ route('qr.deactivate', $qrCode) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="text-red-600 hover:text-red-900" 
                                                                onclick="return confirm('Are you sure you want to deactivate this QR code?')">
                                                            Deactivate
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-qrcode text-gray-300 text-4xl mb-4"></i>
                            <p class="text-gray-500">No QR codes have been generated for this event yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 