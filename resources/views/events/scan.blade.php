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
                        <p class="text-gray-600">Scan your QR code to check in for this event</p>
                        <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                            <p class="text-blue-800">
                                <i class="fas fa-info-circle mr-2"></i>
                                Please allow camera access and position your QR code within the scanner
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-col md:flex-row gap-8">
                        <!-- QR Scanner -->
                        <div class="w-full md:w-1/2">
                            <div class="bg-gray-100 rounded-lg overflow-hidden mb-4" style="min-height: 300px;">
                                <div id="qr-reader" style="width: 100%;"></div>
                            </div>
                            
                            <!-- Manual QR Code Input -->
                            <div class="mt-6">
                                <p class="text-center text-gray-600 mb-2">- OR -</p>
                                <div class="flex">
                                    <input 
                                        type="text" 
                                        id="manual-qr-input" 
                                        placeholder="Enter your QR code value"
                                        class="flex-1 rounded-l-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    >
                                    <button 
                                        id="manual-submit"
                                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-r-lg transition-colors"
                                    >
                                        Submit
                                    </button>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Enter your personal QR code value if the scanner isn't working</p>
                            </div>
                            
                            <div id="qr-reader-results" class="mt-4 text-center"></div>
                        </div>

                        <!-- Event Details -->
                        <div class="w-full md:w-1/2">
                            <div class="bg-gray-50 p-6 rounded-lg h-full">
                                <h4 class="text-lg font-semibold mb-4">Event Details</h4>
                                <div class="space-y-4">
                                    <div>
                                        <p class="text-sm text-gray-500">Date & Time</p>
                                        <p>{{ \Carbon\Carbon::parse($event->date)->format('F j, Y') }} at {{ $event->time }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Location</p>
                                        <p>{{ $event->location }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Description</p>
                                        <p class="whitespace-pre-line">{{ $event->description }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        function showError(message) {
            const resultDiv = document.getElementById('qr-reader-results');
            resultDiv.innerHTML = `
                <div class="p-4 bg-red-50 text-red-800 rounded-lg">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    ${message}
                </div>
            `;
            console.error(message);
        }

        function showSuccess(message) {
            const resultDiv = document.getElementById('qr-reader-results');
            resultDiv.innerHTML = `
                <div class="p-4 bg-green-50 text-green-800 rounded-lg">
                    <i class="fas fa-check-circle mr-2"></i>
                    ${message}
                </div>
            `;
        }

        function showLoading(message = 'Processing your check-in...') {
            const resultDiv = document.getElementById('qr-reader-results');
            resultDiv.innerHTML = `
                <div class="p-4 bg-yellow-50 text-yellow-800 rounded-lg">
                    <i class="fas fa-spinner fa-spin mr-2"></i>
                    ${message}
                </div>
            `;
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
                    showSuccess(data.message || 'Check-in successful!');
                    // Disable the scanner after successful check-in
                    if (window.html5QrCode) {
                        window.html5QrCode.pause();
                    }
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

        // Initialize QR Code Scanner
        function initScanner() {
            try {
                // Check if the browser supports the required APIs
                if (!navigator.mediaDevices || !window.Html5Qrcode) {
                    throw new Error('QR code scanning is not supported in your browser. Please use the manual entry option below.');
                }

                // Create a new scanner instance
                const html5QrCode = new Html5Qrcode(
                    "qr-reader",
                    { 
                        formatsToSupport: [
                            Html5QrcodeSupportedFormats.QR_CODE
                        ],
                        experimentalFeatures: {
                            useBarCodeDetectorIfSupported: true
                        },
                        aspectRatio: 1.0
                    }
                );

                // Store the scanner instance for later use
                window.html5QrCode = html5QrCode;

                // Start the scanner
                html5QrCode.start(
                    { facingMode: "environment" },
                    {
                        fps: 10,
                        qrbox: { width: 250, height: 250 },
                        disableFlip: false
                    },
                    (decodedText, decodedResult) => {
                        // Success callback
                        console.log('QR Code scanned:', decodedText);
                        handleQrCode(decodedText)
                            .then(() => {
                                // Success handling is done in handleQrCode
                            })
                            .catch(error => {
                                console.error('Error handling QR code:', error);
                            });
                    },
                    (errorMessage) => {
                        // Error callback
                        console.warn('QR Code error:', errorMessage);
                        // Don't show error message to user for every scan failure
                    }
                ).catch(error => {
                    console.error('Failed to start QR scanner:', error);
                    showError('Failed to start camera. Please check your camera permissions and try again.');
                });

            } catch (error) {
                console.error('Scanner initialization error:', error);
                showError(error.message || 'Failed to initialize QR scanner. Please use the manual entry option below.');
            }
        }

        // Handle manual form submission
        document.addEventListener('DOMContentLoaded', function() {
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

            // Initialize the scanner when the page loads
            initScanner();
        });

        // Cleanup scanner when the page is unloaded
        window.addEventListener('beforeunload', function() {
            if (window.html5QrCode) {
                window.html5QrCode.stop().catch(error => {
                    console.error('Error stopping scanner:', error);
                });
            }
        });
    </script>
    @endpush
</x-app-layout>
