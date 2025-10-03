<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Event Check-in') }}
            </h2>
            <a href="{{ route('events.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back to Events
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="text-center mb-8">
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">{{ $event->title }}</h3>
                        <p class="text-gray-600">Show your QR code to the admin/leader to check in</p>
                        @if($hasCheckedIn)
                            <div class="mt-4 p-4 bg-green-50 rounded-lg">
                                <p class="text-green-800">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    You are already checked in for this event
                                    @if($checkInTime)
                                        ({{ \Carbon\Carbon::parse($checkInTime)->format('M j, Y g:i A') }})
                                    @endif
                                </p>
                            </div>
                        @else
                            <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                                <p class="text-blue-800">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Present this QR code to the event organizer to check in
                                </p>
                            </div>
                        @endif
                    </div>

                    <div class="flex flex-col md:flex-row gap-8">
                        <!-- Member QR Code -->
                        <div class="w-full md:w-1/2">
                            <div class="bg-gray-50 p-6 rounded-lg text-center">
                                <h4 class="text-lg font-semibold mb-4">Your Check-in QR Code</h4>
                                
                                @if($member && $member->qr_code)
                                    <!-- QR Code Display -->
                                    <div class="bg-white p-4 rounded-lg shadow-sm mb-4">
                                        <div id="qr-code" class="flex justify-center"></div>
                                    </div>
                                    
                                    <!-- Member Information -->
                                    <div class="text-left bg-white p-4 rounded-lg shadow-sm">
                                        <h5 class="font-semibold text-gray-800 mb-2">Member Information</h5>
                                        <div class="space-y-2 text-sm">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Name:</span>
                                                <span class="font-medium">{{ $member->name }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">QR Code:</span>
                                                <span class="font-mono text-xs bg-gray-100 px-2 py-1 rounded">{{ $member->qr_code }}</span>
                                            </div>
                                            @if($member->chapter)
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Chapter:</span>
                                                <span class="font-medium">{{ $member->chapter->name }}</span>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- Instructions -->
                                    <div class="mt-4 text-sm text-gray-600">
                                        <p class="mb-2">
                                            <i class="fas fa-mobile-alt mr-2"></i>
                                            Hold your phone steady for scanning
                                        </p>
                                        <p>
                                            <i class="fas fa-user-check mr-2"></i>
                                            The admin will scan this code to check you in
                                        </p>
                                    </div>
                                @else
                                    <div class="text-center py-8">
                                        <i class="fas fa-exclamation-triangle text-yellow-500 text-4xl mb-4"></i>
                                        <p class="text-gray-600">No QR code found for your account.</p>
                                        <p class="text-sm text-gray-500 mt-2">Please contact an administrator to set up your QR code.</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Event Details -->
                        <div class="w-full md:w-1/2">
                            <div class="bg-gray-50 p-6 rounded-lg h-full">
                                <h4 class="text-lg font-semibold mb-4">Event Details</h4>
                                <div class="space-y-4">
                                    <div>
                                        <p class="text-sm text-gray-500">Date & Time</p>
                                        <p class="font-medium">{{ \Carbon\Carbon::parse($event->date)->format('F j, Y') }} at {{ $event->time }}</p>
                                        @if($event->end_time)
                                            <p class="text-sm text-gray-600">Ends at {{ $event->end_time }}</p>
                                        @endif
                                    </div>
                                    @if($event->location)
                                    <div>
                                        <p class="text-sm text-gray-500">Location</p>
                                        <p class="font-medium">{{ $event->location }}</p>
                                    </div>
                                    @endif
                                    @if($event->description)
                                    <div>
                                        <p class="text-sm text-gray-500">Description</p>
                                        <p class="whitespace-pre-line text-sm">{{ $event->description }}</p>
                                    </div>
                                    @endif
                                    @if($event->chapter)
                                    <div>
                                        <p class="text-sm text-gray-500">Chapter</p>
                                        <p class="font-medium">{{ $event->chapter->name }}</p>
                                    </div>
                                    @endif
                                </div>
                                
                                <!-- Check-in Status -->
                                <div class="mt-6 p-4 rounded-lg {{ $hasCheckedIn ? 'bg-green-100' : 'bg-yellow-100' }}">
                                    <div class="flex items-center">
                                        <i class="fas {{ $hasCheckedIn ? 'fa-check-circle text-green-600' : 'fa-clock text-yellow-600' }} mr-2"></i>
                                        <span class="font-medium {{ $hasCheckedIn ? 'text-green-800' : 'text-yellow-800' }}">
                                            {{ $hasCheckedIn ? 'Checked In' : 'Not Checked In' }}
                                        </span>
                                    </div>
                                    @if($hasCheckedIn && $checkInTime)
                                        <p class="text-sm {{ $hasCheckedIn ? 'text-green-700' : 'text-yellow-700' }} mt-1">
                                            Checked in at {{ \Carbon\Carbon::parse($checkInTime)->format('g:i A') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if($member && $member->qr_code)
            // Generate QR Code
            const qrCodeElement = document.getElementById('qr-code');
            if (qrCodeElement) {
                QRCode.toCanvas(qrCodeElement, '{{ $member->qr_code }}', {
                    width: 200,
                    height: 200,
                    margin: 2,
                    color: {
                        dark: '#000000',
                        light: '#FFFFFF'
                    }
                }, function (error) {
                    if (error) {
                        console.error('Error generating QR code:', error);
                        qrCodeElement.innerHTML = '<p class="text-red-600">Error generating QR code</p>';
                    }
                });
            }
            @endif
        });
    </script>
    @endpush
</x-app-layout>
