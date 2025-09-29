@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Test QR Code</h1>
    
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold mb-4">Member: {{ $member->name }}</h2>
        
        <div class="mb-6">
            <h3 class="font-medium mb-2">QR Code:</h3>
            <div class="p-4 bg-gray-100 rounded inline-block">
                {!! QrCode::size(200)->generate(route('public.qr.scan', $member->qr_code)) !!}
            </div>
        </div>
        
        <div class="mb-6">
            <h3 class="font-medium mb-2">QR Code URL:</h3>
            <div class="p-3 bg-gray-100 rounded break-all">
                {{ route('public.qr.scan', $member->qr_code) }}
            </div>
        </div>
        
        <div class="mb-6">
            <h3 class="font-medium mb-2">Test the QR Code:</h3>
            <p class="mb-2">1. Scan the QR code above with a QR code scanner</p>
            <p class="mb-2">2. Or click the link below to test the URL directly:</p>
            <a href="{{ route('public.qr.scan', $member->qr_code) }}" 
               class="text-blue-600 hover:underline"
               target="_blank">
                Test QR Code URL
            </a>
        </div>
    </div>
</div>
@endsection
