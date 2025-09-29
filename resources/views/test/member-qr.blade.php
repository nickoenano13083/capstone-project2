@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Test Member QR Code</h1>
    
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold mb-4">Member: {{ $member->name }}</h2>
        <p class="mb-4">QR Code: <code>{{ $member->qr_code }}</code></p>
        
        <div class="mb-6 p-4 bg-gray-100 rounded-lg">
            <h3 class="font-medium mb-2">QR Code Image:</h3>
            <div class="p-4 bg-white border rounded inline-block">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode(url('qr/scan/' . $member->qr_code)) }}" 
                     alt="QR Code" 
                     class="mx-auto">
            </div>
        </div>
        
        <div class="mb-6 p-4 bg-gray-100 rounded-lg">
            <h3 class="font-medium mb-2">Test Links:</h3>
            <div class="space-y-2">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Direct Test Link:</p>
                    <a href="{{ url('test-scan/' . $member->qr_code) }}" 
                       class="text-blue-600 hover:underline break-all"
                       target="_blank">
                        {{ url('test-scan/' . $member->qr_code) }}
                    </a>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">QR Code Link:</p>
                    <span class="break-all">{{ url('qr/scan/' . $member->qr_code) }}</span>
                </div>
            </div>
        </div>
        
        <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
            <h3 class="font-medium text-yellow-800 mb-2">Testing Instructions:</h3>
            <ol class="list-decimal list-inside space-y-2 text-sm text-yellow-700">
                <li>Click the "Direct Test Link" above to test the QR code scanning</li>
                <li>Scan the QR code with a mobile device to test the actual scanning</li>
                <li>Check your browser's developer console (F12) for any errors if it's not working</li>
            </ol>
        </div>
    </div>
</div>
@endsection
