<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Church Attendance QR Code</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .qr-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        .church-logo {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            border-radius: 50%;
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
        }
    </style>
</head>
<body class="flex items-center justify-center p-4">
    <div class="qr-container p-8 max-w-md w-full text-center">
        <!-- Church Logo -->
        <div class="church-logo mb-6">
            <i class="fas fa-church text-white text-3xl"></i>
        </div>

        <!-- Event Information -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">{{ $qrCode->event->title }}</h1>
            <div class="text-gray-600 space-y-1">
                <p><i class="fas fa-calendar-alt mr-2"></i>{{ $qrCode->event->date->format('l, F j, Y') }}</p>
                @if($qrCode->event->time)
                    <p><i class="fas fa-clock mr-2"></i>{{ $qrCode->event->time }}</p>
                @endif
                @if($qrCode->event->location)
                    <p><i class="fas fa-map-marker-alt mr-2"></i>{{ $qrCode->event->location }}</p>
                @endif
            </div>
        </div>

        <!-- QR Code -->
        <div class="mb-6">
            <div class="bg-white p-4 rounded-lg shadow-lg inline-block">
                <img src="{{ route('qr.image', $qrCode) }}" 
                     alt="Attendance QR Code" 
                     class="w-64 h-64 mx-auto">
            </div>
        </div>

        <!-- Instructions -->
        <div class="text-gray-700">
            <h3 class="font-semibold text-lg mb-2">How to Mark Attendance:</h3>
            <div class="text-left space-y-2 text-sm">
                <div class="flex items-start">
                    <span class="bg-blue-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs mr-3 mt-0.5">1</span>
                    <span>Open your phone's camera app</span>
                </div>
                <div class="flex items-start">
                    <span class="bg-blue-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs mr-3 mt-0.5">2</span>
                    <span>Point camera at this QR code</span>
                </div>
                <div class="flex items-start">
                    <span class="bg-blue-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs mr-3 mt-0.5">3</span>
                    <span>Tap the notification to mark attendance</span>
                </div>
            </div>
        </div>

        <!-- Status -->
        <div class="mt-6 p-3 bg-green-50 border border-green-200 rounded-lg">
            <div class="flex items-center justify-center text-green-700">
                <i class="fas fa-check-circle mr-2"></i>
                <span class="font-medium">QR Code Active</span>
            </div>
            <p class="text-xs text-green-600 mt-1">
                Expires: {{ $qrCode->expires_at->format('g:i A') }}
            </p>
        </div>

        <!-- Footer -->
        <div class="mt-6 text-xs text-gray-500">
            <p>Church Management System</p>
            <p>Generated on {{ $qrCode->created_at->format('M j, Y g:i A') }}</p>
        </div>
    </div>

    <script>
        // Auto-refresh QR code every 30 seconds to prevent caching issues
        setInterval(function() {
            const img = document.querySelector('img');
            img.src = img.src + '?t=' + new Date().getTime();
        }, 30000);
    </script>
</body>
</html>