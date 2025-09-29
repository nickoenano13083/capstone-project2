@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Test Event QR Code</h1>
    
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold mb-4">Event: {{ $event->title }}</h2>
        <p class="mb-2"><strong>Date:</strong> {{ $event->date }} at {{ $event->time }}</p>
        <p class="mb-4"><strong>Location:</strong> {{ $event->location }}</p>
        
        <div class="mb-6 p-4 bg-gray-100 rounded-lg">
            <h3 class="font-medium mb-2">QR Code:</h3>
            <div class="flex flex-col items-center space-y-4">
                <div class="p-4 bg-white border rounded">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode(url('qr/scan/' . $qrCode->code)) }}" 
                         alt="Event QR Code" 
                         class="mx-auto">
                </div>
                <div class="text-sm text-gray-600 break-all text-center">
                    {{ $qrCode->code }}
                </div>
            </div>
        </div>
        
        <div class="mb-6 p-4 bg-gray-100 rounded-lg">
            <h3 class="font-medium mb-2">Test Links:</h3>
            <div class="space-y-2">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Direct Test Link:</p>
                    <a href="{{ url('qr/scan/' . $qrCode->code) }}" 
                       class="text-blue-600 hover:underline break-all"
                       target="_blank">
                        {{ url('qr/scan/' . $qrCode->code) }}
                    </a>
                </div>
            </div>
        </div>
        
        <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <h3 class="font-medium text-blue-800 mb-2">Testing Instructions:</h3>
            <ol class="list-decimal list-inside space-y-2 text-sm text-blue-700">
                <li>Click the "Direct Test Link" above to test the event QR code scanning</li>
                <li>If you're not logged in, you should see the event details</li>
                <li>If you're logged in, it should record your attendance</li>
                <li>Scan the QR code with a mobile device to test the actual scanning</li>
            </ol>
        </div>
    </div>
</div>
@endsection
