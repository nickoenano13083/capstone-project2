@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    @if(isset($event))
                        {{ __('Event Check-In') }}
                    @else
                        {{ __('QR Code Scanner') }}
                    @endif
                </div>

                <div class="card-body">
                    @if(isset($event) && $event->status === 'completed')
                        <!-- Show event completed message -->
                        <div class="text-center py-8">
                            <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                <i class="fas fa-calendar-times text-red-500 text-3xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Event Completed</h3>
                            <p class="text-gray-600 mb-6">
                                The event "{{ $event->title }}" has already ended. 
                                Check-in is no longer available for this event.
                            </p>
                            <a href="{{ route('events.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Back to Events
                            </a>
                        </div>
                    @else
                        <!-- Scanner Instructions -->
                        <div class="mb-6 text-center">
                            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-qrcode text-blue-600 text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                @if(isset($event))
                                    Scan Member QR Code
                                @else
                                    Scan QR Code for Attendance
                                @endif
                            </h3>
                            <p class="text-gray-600">
                                @if(isset($event))
                                    Point your camera at the member's QR code to check them in for <strong>{{ $event->title }}</strong>
                                @else
                                    Point your camera at the QR code displayed on the screen
                                @endif
                            </p>
                        </div>

                        <!-- Live Camera QR Scanner -->
                        <div class="mb-6">
                            <div id="reader" style="width: 100%"></div>
                            <div id="result" class="mt-2 text-green-700 font-semibold"></div>
                        </div>

                        <!-- Manual Entry Option -->
                        <div class="mb-6">
                            <div class="border-t border-gray-200 my-4">
                                <div class="text-center">
                                    <span class="bg-white px-4 text-gray-500 text-sm">OR</span>
                                </div>
                            </div>
                            
                            <form id="manualEntryForm" class="space-y-4">
                                @csrf
                                @if(isset($event))
                                    <input type="hidden" name="event_id" value="{{ $event->id }}">
                                @endif
                                <div>
                                    <label for="manualCode" class="block text-sm font-medium text-gray-700 mb-1">
                                        Enter QR Code Manually
                                    </label>
                                    <div class="flex space-x-2">
                                        <input type="text" id="manualCode" name="code" required
                                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                                               placeholder="Enter QR code">
                                        <button type="submit" 
                                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            Submit
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Status Message -->
<div id="statusMessage" class="fixed top-4 right-4 p-4 rounded-lg shadow-lg hidden z-50" style="width: 300px;">
    <div class="flex items-start">
        <div id="statusIcon" class="flex-shrink-0"></div>
        <div class="ml-3 w-0 flex-1 pt-0.5">
            <p id="statusText" class="text-sm font-medium text-gray-900"></p>
        </div>
        <div class="ml-4 flex-shrink-0 flex">
            <button onclick="this.parentElement.parentElement.parentElement.classList.add('hidden')" 
                    class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none">
                <span class="sr-only">Close</span>
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </div>
</div>

<!-- Recent Scans -->
<div id="recentScans" class="fixed bottom-4 right-4 bg-white p-4 rounded-lg shadow-lg hidden z-50" style="width: 300px; max-height: 400px; overflow-y: auto;">
    <div class="flex justify-between items-center mb-3">
        <h3 class="text-lg font-medium text-gray-900">Recent Scans</h3>
        <button onclick="document.getElementById('recentScans').classList.add('hidden')" class="text-gray-400 hover:text-gray-500">
            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </button>
    </div>
    <div id="scanList" class="space-y-2">
        <!-- Recent scans will be added here -->
    </div>
</div>

<!-- Scan Feedback Modal -->
<div id="scanFeedback" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
    <div class="bg-white p-6 rounded-lg shadow-xl transform transition-all duration-300 scale-0" id="feedbackContainer">
        <div class="text-center">
            <i id="feedbackIcon" class="text-5xl mb-4"></i>
            <h3 id="feedbackText" class="text-xl font-semibold mb-2"></h3>
            <p id="feedbackDescription" class="text-gray-600 mb-4"></p>
            <button onclick="closeFeedback()" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                Close
            </button>
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Only initialize scanner if we're not showing the event completed message
            @if(!isset($event) || $event->status !== 'completed')
                const manualForm = document.getElementById('manualEntryForm');
                const statusMessage = document.getElementById('statusMessage');
                const statusIcon = document.getElementById('statusIcon');
                const statusText = document.getElementById('statusText');
                const recentScans = [];

                // Initialize QR code scanner
                let html5QrCode;

                // Handle manual form submission
                if (manualForm) {
                    manualForm.addEventListener('submit', function(e) {
                        e.preventDefault();
                        const code = document.getElementById('manualCode').value.trim();
                        if (code) {
                            processCode(code);
                        }
                    });
                }

                // Process scanned or manually entered code
                function processCode(code) {
                    const eventId = document.querySelector('input[name="event_id"]')?.value || null;
                    
                    fetch('{{ route("qr.scan") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            code: code,
                            event_id: eventId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showFeedback('success', 'Check-in Successful', `Member: ${data.member_name}`);
                            addRecentScan(data.member_name, data.event_title, data.scanned_at);
                        } else {
                            showFeedback('error', 'Check-in Failed', data.error || 'An error occurred');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showFeedback('error', 'Error', 'Failed to process QR code');
                    });
                }

                // Show feedback message
                function showFeedback(type, title, message) {
                    const feedbackContainer = document.getElementById('feedbackContainer');
                    const feedbackIcon = document.getElementById('feedbackIcon');
                    const feedbackText = document.getElementById('feedbackText');
                    const feedbackDescription = document.getElementById('feedbackDescription');
                    const feedbackModal = document.getElementById('scanFeedback');
                    
                    feedbackContainer.className = `bg-white p-6 rounded-lg shadow-xl transform transition-all duration-300 ${type === 'success' ? 'scale-100' : 'scale-0'}`;
                    
                    if (type === 'success') {
                        feedbackIcon.className = 'fas fa-check-circle text-green-500 text-5xl mb-4';
                    } else {
                        feedbackIcon.className = 'fas fa-times-circle text-red-500 text-5xl mb-4';
                    }
                    
                    feedbackText.textContent = title;
                    feedbackDescription.textContent = message;
                    feedbackModal.classList.remove('hidden');
                    
                    setTimeout(() => {
                        feedbackContainer.classList.add('scale-100');
                    }, 10);
                }

                // Close feedback modal
                window.closeFeedback = function() {
                    const feedbackModal = document.getElementById('scanFeedback');
                    const feedbackContainer = document.getElementById('feedbackContainer');
                    
                    feedbackContainer.classList.remove('scale-100');
                    
                    setTimeout(() => {
                        feedbackModal.classList.add('hidden');
                    }, 300);
                };

                // Add scan to recent scans
                function addRecentScan(memberName, eventTitle, scannedAt) {
                    const scanList = document.getElementById('scanList');
                    const scanItem = document.createElement('div');
                    scanItem.className = 'flex justify-between items-center p-2 bg-gray-50 rounded-md';
                    scanItem.innerHTML = `
                        <div>
                            <div class="font-medium">${memberName}</div>
                            <div class="text-xs text-gray-500">${eventTitle}</div>
                        </div>
                        <div class="text-xs text-gray-400">${new Date(scannedAt).toLocaleTimeString()}</div>
                    `;
                    
                    // Add to beginning of list
                    if (scanList.firstChild) {
                        scanList.insertBefore(scanItem, scanList.firstChild);
                    } else {
                        scanList.appendChild(scanItem);
                    }
                    
                    // Show recent scans container
                    document.getElementById('recentScans').classList.remove('hidden');
                }

                // Initialize QR code scanner
                if (document.getElementById('reader')) {
                    function onScanSuccess(decodedText, decodedResult) {
                        // Stop the scanner
                        html5QrCode.pause();
                        
                        // Process the scanned code
                        processCode(decodedText);
                        
                        // Resume scanning after a delay
                        setTimeout(() => {
                            html5QrCode.resume();
                        }, 2000);
                    }

                    function onScanFailure(error) {
                        // Handle scan failure
                        console.error('QR scan failed:', error);
                    }

                    // Start the scanner
                    if (navigator.mediaDevices) {
                        html5QrCode = new Html5Qrcode("reader");
                        html5QrCode.start(
                            { facingMode: "environment" },
                            {
                                fps: 10,
                                qrbox: { width: 250, height: 250 }
                            },
                            onScanSuccess,
                            onScanFailure
                        ).catch(err => {
                            console.error('Error starting scanner:', err);
                            document.getElementById('result').innerText = 'Could not access camera. Please check permissions.';
                        });
                    }
                }
            @endif
        });
    </script>
@endpush
@endsection