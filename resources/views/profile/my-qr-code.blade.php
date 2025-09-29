@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Section -->
    <div class="relative bg-gradient-to-br from-blue-900 via-blue-800 to-blue-900 overflow-hidden">
        <!-- Decorative cross pattern background -->
        <div class="absolute inset-0 opacity-5">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxMDAgMTAwIiB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCI+PHBhdGggZmlsbD1cIiNmZmZmZmZcIiBkPVwiTTUwIDI1TDM1IDUwaDE1bC0xNSAyNSAyNS0xNXYtMjh6XCIvPjxwYXRoIGZpbGw9XCJ3aGl0ZVwiIGQ9XCJNNTAgNzVsMTUtMjVINTBsMTUtMjUtMTUgMjV2MjV6XCIvPjwvc3ZnPg==')] bg-repeat"></div>
        </div>
        
        <!-- Stained glass effect overlay -->
        <div class="absolute inset-0 bg-gradient-to-br from-blue-900/30 via-transparent to-blue-900/30"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-20 relative z-10">
            <div class="text-center">
                <!-- Church logo/icon -->
                <div class="flex justify-center mb-4">
                    <div class="bg-white/20 backdrop-blur-sm p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                        </svg>
                    </div>
                </div>
                
                <h1 class="text-3xl md:text-4xl font-serif font-bold text-gray-800 tracking-tight mb-3">
                    Welcome, {{ explode(' ', $member->user->name)[0] ?? 'Beloved Member' }}!
                </h1>
                <p class="max-w-2xl mx-auto text-gray-600 text-lg md:text-xl font-light">
                    Your Digital Membership & Worship Companion
                </p>
                
                <!-- Decorative cross separator -->
                <div class="flex justify-center my-6">
                    <div class="w-16 h-1 bg-blue-400/50 relative">
                        <div class="absolute left-1/2 top-1/2 transform -translate-x-1/2 -translate-y-1/2 w-1 h-6 bg-blue-400/50"></div>
                    </div>
                </div>
                
                <div class="mt-6 flex flex-wrap justify-center gap-3">
                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-medium bg-white/10 text-blue-100 border border-blue-300/30 backdrop-blur-sm">
                        <svg class="mr-2 h-3.5 w-3.5 text-blue-300" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2L1 12h3v9h6v-6h4v6h6v-9h3L12 2z"/>
                        </svg>
                        Member Since: {{ $member->created_at->format('M Y') }}
                    </span>
                    @if($member->chapter)
                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-medium bg-amber-600/20 text-amber-100 border border-amber-400/30 backdrop-blur-sm">
                        <svg class="mr-2 h-3.5 w-3.5 text-amber-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        {{ $member->chapter->name }}
                    </span>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Bottom wave divider -->
        <div class="absolute bottom-0 left-0 right-0 h-12 overflow-hidden">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none" class="w-full h-full text-white">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" fill="currentColor"></path>
            </svg>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 -mt-10">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- QR Code Container -->
            <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100">
                <div class="text-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Your Personal QR Code</h2>
                    <p class="text-gray-500">Use this code for event check-ins</p>
                </div>
                
                <div class="flex justify-center mb-6">
                    <div class="p-4 bg-white rounded-xl border-2 border-blue-100 shadow-inner">
                        @if(!empty($member->qr_code))
                            <img src="{{ route('public.qr.scan', $member->qr_code) }}" 
                                 alt="My QR Code" 
                                 class="w-56 h-56 object-contain p-2"
                                 onerror="this.onerror=null; this.src='https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=' + encodeURIComponent('{{ url("qr/scan/" . $member->qr_code) }}');">
                        @else
                            <div class="w-56 h-56 flex flex-col items-center justify-center text-center p-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <span class="text-gray-500">No QR Code Available</span>
                                <p class="text-sm text-gray-400 mt-2">Please contact an administrator to generate your QR code.</p>
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="text-center">
                    @if(!empty($member->qr_code))
                        <button onclick="downloadQR()" 
                                class="flex items-center justify-center w-full px-6 py-3 bg-blue-600 hover:bg-blue-700 text-gray-100 rounded-lg transition-colors duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                            Download QR Code
                        </button>
                    @else
                        <button disabled class="w-full px-6 py-3 bg-gray-300 text-gray-500 rounded-lg cursor-not-allowed opacity-75">
                            Download QR Code
                        </button>
                    @endif
                </div>
            </div>

            <!-- Member Information -->
            <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100">
                <div class="mb-6">
                    <h3 class="font-semibold text-xl text-gray-800 mb-6 pb-3 border-b border-gray-100 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 2a1 1 0 00-1 1v1a1 1 0 002 0V3a1 1 0 00-1-1z" clip-rule="evenodd" />
                            <path d="M4 4h12a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2z" />
                            <path fill-rule="evenodd" d="M10 9a1 1 0 011 1v7a1 1 0 11-2 0v-7a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Member Profile
                    </h3>
                    <div class="space-y-4">
                        <div class="flex items-start p-3 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="bg-blue-100 p-2 rounded-full mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-3 3a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                    <path d="M2 18a8 8 0 1116 0H2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Full Name</p>
                                <p class="text-gray-800">{{ $member->user->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start p-3 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="bg-blue-100 p-2 rounded-full mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Email</p>
                                <p class="text-gray-800">{{ $member->user->email ?? 'N/A' }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start p-3 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="bg-blue-100 p-2 rounded-full mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 2a1 1 0 00-1 1v1a1 1 0 002 0V3a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    <path d="M4 4h12a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2z" />
                                    <path fill-rule="evenodd" d="M10 9a1 1 0 011 1v7a1 1 0 11-2 0v-7a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Member ID</p>
                                <p class="font-mono text-gray-800">{{ $member->id }}</p>
                            </div>
                        </div>
                        
                        @if($member->chapter)
                        <div class="flex items-start p-3 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="bg-blue-100 p-2 rounded-full mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Chapter</p>
                                <p class="text-gray-800">{{ $member->chapter->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                        @endif
                        
                        <div class="mt-6 pt-4 border-t border-gray-100">
                            <a href="{{ route('profile.edit') }}" 
                               class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.793.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                </svg>
                                Edit Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- QR Code URL (Collapsible) -->
        <div class="border rounded-lg overflow-hidden">
            <button onclick="toggleQRUrl()" class="w-full px-4 py-3 text-left bg-gray-50 hover:bg-gray-100 transition-colors flex justify-between items-center">
                <span class="font-medium text-gray-700">QR Code URL</span>
                <svg id="qr-arrow" class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            <div id="qr-url" class="hidden px-4 py-3 bg-white text-sm overflow-auto">
                <div class="p-2 bg-gray-50 rounded font-mono text-sm break-all">
                    {{ ( $member->qr_code) }}
                </div>
                <button onclick="copyToClipboard('{{( $member->qr_code) }}')" 
                        class="mt-2 text-xs text-blue-600 hover:text-blue-800 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Copy URL
                </button>
            </div>
        </div>
    </div>
    
    <!-- Instructions -->
    <div class="bg-blue-50 border-t border-blue-100 p-4">
        <h3 class="font-medium text-blue-800 mb-2">How to use your QR code:</h3>
        <ol class="text-sm text-blue-700 space-y-1">
            <li class="flex items-start">
                <span class="inline-block bg-blue-200 text-blue-800 rounded-full w-5 h-5 text-center text-xs leading-5 mr-2 flex-shrink-0">1</span>
                <span>Save this page to your phone's home screen</span>
            </li>
            <li class="flex items-start">
                <span class="inline-block bg-blue-200 text-blue-800 rounded-full w-5 h-5 text-center text-xs leading-5 mr-2 flex-shrink-0">2</span>
                <span>Present the QR code at event check-ins</span>
            </li>
            <li class="flex items-start">
                <span class="inline-block bg-blue-200 text-blue-800 rounded-full w-5 h-5 text-center text-xs leading-5 mr-2 flex-shrink-0">3</span>
                <span>Keep this code secure and don't share it with others</span>
            </li>
        </ol>
    </div>
</div>

@push('scripts')
<script>
    function toggleQRUrl() {
        const qrUrl = document.getElementById('qr-url');
        const arrow = document.getElementById('qr-arrow');
        qrUrl.classList.toggle('hidden');
        arrow.classList.toggle('rotate-180');
    }
    
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            // Show copied feedback
            const button = event.target.closest('button');
            const originalText = button.innerHTML;
            button.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Copied!
            `;
            button.classList.remove('text-blue-600');
            button.classList.add('text-green-600');
            
            setTimeout(() => {
                button.innerHTML = originalText;
                button.classList.remove('text-green-600');
                button.classList.add('text-blue-600');
            }, 2000);
        });
    }
    
    function downloadQR() {
        const qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=500x500&data={{ urlencode(url('qr/scan/' . $member->qr_code)) }}';
        
        // Create a temporary link element
        const link = document.createElement('a');
        link.href = qrCodeUrl;
        link.download = 'my-qr-code-{{ $member->id }}.png';
        
        // Append to the document, trigger click, and remove
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
</script>
@endpush

<style>
    /* Add smooth transitions */
    .transition-colors {
        transition-property: background-color, border-color, color, fill, stroke;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 150ms;
    }
    
    /* Custom scrollbar for QR URL */
    #qr-url::-webkit-scrollbar {
        height: 4px;
    }
    
    #qr-url::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    
    #qr-url::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 2px;
    }
    
    #qr-url::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
</style>
@endsection