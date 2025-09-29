<?php

namespace App\Http\Controllers;

use App\Models\QrCode;
use App\Models\Event;
use App\Models\Attendance;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Endroid\QrCode\QrCode as EndroidQrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Response\QrCodeResponse;
use Endroid\QrCode\Label\Label;

class QrCodeController extends Controller
{
    public function generate(Request $request, Event $event)
    {
        // Check if user has permission
        if (!in_array(auth()->user()->role, ['Admin', 'Leader'])) {
            abort(403, 'Unauthorized');
        }

        // Deactivate any existing active QR codes for this event
        $event->qrCodes()->where('is_active', true)->update(['is_active' => false]);

        // Generate unique code in format: EVENT_[event_id]_[random]
        $code = 'EVENT_' . $event->id . '_' . strtoupper(Str::random(6));

        // Create new QR code
        $qrCode = QrCode::create([
            'event_id' => $event->id,
            'code' => $code,
            'is_active' => true,
            'expires_at' => now()->addHours(4), // Expires in 4 hours
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('qr.show', $qrCode)->with('success', 'QR Code generated successfully!');
    }

    public function show(QrCode $qrCode)
    {
        if (!in_array(auth()->user()->role, ['Admin', 'Leader'])) {
            abort(403, 'Unauthorized');
        }

        return view('qr.show', compact('qrCode'));
    }

    /**
     * Handle public QR code scanning
     */
    public function scanPublic($code)
    {
        // Check if it's a member QR code (UUID format)
        if (preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $code)) {
            $member = \App\Models\Member::with('chapter')->where('qr_code', $code)->first();
            
            if (!$member) {
                return response()->json([
                    'success' => false,
                    'message' => 'Member not found.'
                ], 404);
            }

            // Get the event ID from the request if available (for event check-ins)
            $eventId = request('event_id');
            
            if ($eventId) {
                $event = \App\Models\Event::find($eventId);
                if (!$event) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Event not found.'
                    ], 404);
                }

                // Record attendance for the member
                $attendance = \App\Models\Attendance::firstOrCreate(
                    [
                        'member_id' => $member->id,
                        'event_id' => $eventId,
                    ],
                    ['check_in' => now()]
                );

                return response()->json([
                    'success' => true,
                    'type' => 'event_checkin',
                    'message' => 'Check-in recorded for ' . $member->name,
                    'member' => [
                        'id' => $member->id,
                        'name' => $member->name,
                        'email' => $member->email,
                        'chapter' => $member->chapter ? $member->chapter->name : 'No Chapter'
                    ],
                    'event' => [
                        'id' => $event->id,
                        'title' => $event->title,
                        'date' => $event->date,
                        'time' => $event->time
                    ],
                    'attendance' => [
                        'status' => $attendance->wasRecentlyCreated ? 'checked_in' : 'already_checked_in',
                        'check_in_time' => $attendance->check_in->toDateTimeString()
                    ]
                ]);
            }

            // If no event ID, just return member info
            return response()->json([
                'success' => true,
                'type' => 'member',
                'message' => 'Member verified: ' . $member->name,
                'member' => [
                    'id' => $member->id,
                    'name' => $member->name,
                    'email' => $member->email,
                    'chapter' => $member->chapter ? $member->chapter->name : 'No Chapter',
                    'qr_code' => $member->qr_code
                ]
            ]);
        }

        // Handle event QR codes (format: EVENT_123_ABC123)
        if (preg_match('/^EVENT_\d+_\w+$/', $code)) {
            $qrCode = QrCode::with('event')
                ->where('code', $code)
                ->where('is_active', true)
                ->where('expires_at', '>', now())
                ->first();

            if (!$qrCode || !$qrCode->event) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid or expired event QR code.'
                ], 404);
            }

            $response = [
                'success' => true,
                'type' => 'event',
                'message' => 'Event QR code scanned',
                'event' => [
                    'id' => $qrCode->event->id,
                    'title' => $qrCode->event->title,
                    'date' => $qrCode->event->date,
                    'time' => $qrCode->event->time,
                    'location' => $qrCode->event->location
                ]
            ];

            // If user is logged in, record attendance
            if (auth()->check()) {
                $attendance = Attendance::firstOrCreate(
                    [
                        'user_id' => auth()->id(),
                        'event_id' => $qrCode->event_id,
                    ],
                    ['check_in' => now()]
                );

                $response['attendance'] = [
                    'status' => $attendance->wasRecentlyCreated ? 'checked_in' : 'already_checked_in',
                    'check_in_time' => $attendance->check_in->toDateTimeString()
                ];
            } else {
                $response['requires_login'] = true;
            }

            return response()->json($response);
        }

        // If we get here, the QR code format is not recognized
        return response()->json([
            'success' => false,
            'message' => 'Invalid QR code format.'
        ], 400);
    }

    public function display(QrCode $qrCode)
    {
        // Public route for displaying QR code (no auth required)
        if (!$qrCode->isValid()) {
            abort(404, 'QR Code is not valid or has expired.');
        }

        return view('qr.display', compact('qrCode'));
    }

    public function scan(Request $request)
    {
        $code = $request->input('code');
        $eventId = $request->input('event_id');
        
        if (!$code) {
            return response()->json(['error' => 'No QR code provided'], 400);
        }

        // Get the QR code first
        $qrCode = QrCode::where('code', $code)->first();

        if (!$qrCode) {
            return response()->json(['error' => 'Invalid QR code'], 404);
        }

        if (!$qrCode->isValid()) {
            return response()->json(['error' => 'QR code is not valid or has expired'], 400);
        }

        // If event_id was provided, use it instead of the one from the QR code
        $effectiveEventId = $eventId ?? $qrCode->event_id;
        
        // Get the event and check its status
        $event = Event::find($effectiveEventId);
        
        if (!$event) {
            return response()->json(['error' => 'Event not found'], 404);
        }
        
        if ($event->status === 'completed') {
            return response()->json(['error' => 'Cannot check in to a completed event'], 400);
        }

        // Check if user is authenticated and linked to a member
        if (!auth()->check()) {
            return response()->json(['error' => 'Please log in to mark attendance'], 401);
        }

        $user = auth()->user();
        $member = Member::where('user_id', $user->id)->first();

        if (!$member) {
            return response()->json(['error' => 'No member profile found. Please contact administrator.'], 400);
        }

        // Check if already marked attendance for this event today
        $existingAttendance = Attendance::where('member_id', $member->id)
            ->where('event_id', $effectiveEventId)
            ->whereDate('attendance_date', today())
            ->first();

        if ($existingAttendance) {
            return response()->json([
                'error' => 'Attendance already marked for today',
                'member_name' => $member->name,
                'event_title' => $event->title,
                'scanned_at' => $existingAttendance->scanned_at
            ], 400);
        }

        // Mark attendance
        $attendance = Attendance::create([
            'member_id' => $member->id,
            'event_id' => $effectiveEventId,
            'attendance_date' => today(),
            'status' => 'present',
            'qr_code_id' => $qrCode->id,
            'scanned_at' => now(),
            'scanned_by' => $user->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Attendance marked successfully!',
            'member_name' => $member->name,
            'event_title' => $event->title,
            'scanned_at' => $attendance->scanned_at->toDateTimeString()
        ]);
    }

    public function generateImage(QrCode $qrCode)
    {
        $qrCodeObject = new EndroidQrCode(
            data: $qrCode->code,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 300,
            margin: 10,
            foregroundColor: new Color(0, 0, 0),
            backgroundColor: new Color(255, 255, 255)
        );

        $label = Label::create('');

        return new QrCodeResponse($qrCodeObject, $label);
    }

    public function deactivate(QrCode $qrCode)
    {
        if (!in_array(auth()->user()->role, ['Admin', 'Leader'])) {
            abort(403, 'Unauthorized');
        }

        $qrCode->update(['is_active' => false]);

        return redirect()->back()->with('success', 'QR Code deactivated successfully!');
    }

    public function scanner(Event $event = null)
    {
        // Log the scanner access
        \Illuminate\Support\Facades\Log::info('QR Scanner accessed', [
            'event_id' => $event ? $event->id : null,
            'event_status' => $event ? $event->status : null,
            'user_id' => auth()->id()
        ]);

        return view('qr.scanner', ['event' => $event]);
    }

    public function showLatest()
    {
        $latestQrCode = \App\Models\QrCode::orderBy('created_at', 'desc')->first();
        if (!$latestQrCode) {
            abort(404, 'No QR code found.');
        }
        return redirect()->route('qr.show', $latestQrCode->id);
    }
}
