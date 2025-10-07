<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PrayerRequestController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AuditLogController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AnnouncementController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// Test route to create and test an event QR code
Route::get('/test-event-qr', function() {
    // Create a test event if none exists
    $event = \App\Models\Event::first();
    
    if (!$event) {
        $event = \App\Models\Event::create([
            'title' => 'Test Event',
            'description' => 'This is a test event',
            'date' => now()->addDays(7)->format('Y-m-d'),
            'time' => '19:00:00',
            'location' => 'Test Location',
            'status' => 'upcoming'
        ]);
    }
    
    // Generate a QR code for the event
    $qrCode = \App\Models\QrCode::firstOrCreate(
        ['event_id' => $event->id, 'is_active' => true],
        [
            'code' => 'EVENT_' . $event->id . '_' . strtoupper(\Illuminate\Support\Str::random(6)),
            'is_active' => true,
            'expires_at' => now()->addDays(1),
            'created_by' => 1
        ]
    );
    
    return view('test.event-qr', [
        'event' => $event,
        'qrCode' => $qrCode
    ]);
});

// Test route to create a test member and show their QR code
Route::get('/test-member', function() {
    // Create a test member if none exists
    $member = \App\Models\Member::first();
    
    if (!$member) {
        $member = \App\Models\Member::create([
            'name' => 'Test Member',
            'email' => 'test@example.com',
            'phone' => '1234567890',
            'address' => '123 Test St',
            'birthdate' => '1990-01-01',
            'gender' => 'Other',
            'marital_status' => 'Single',
            'membership_date' => now(),
            'status' => 'active',
            'qr_code' => \Illuminate\Support\Str::uuid()->toString()
        ]);
    }
    
    return view('test.member-qr', ['member' => $member]);
});

// Test route for QR code scanning
Route::get('/test-scan/{code}', function($code) {
    $member = \App\Models\Member::where('qr_code', $code)->first();
    
    if (!$member) {
        return response()->json([
            'success' => false,
            'message' => 'Member not found.'
        ], 404);
    }
    
    return response()->json([
        'success' => true,
        'message' => 'Member verified: ' . $member->name,
        'member' => [
            'id' => $member->id,
            'name' => $member->name,
            'email' => $member->email,
            'qr_code' => $member->qr_code
        ]
    ]);
})->name('test.scan');

// Test route - remove in production
Route::get('/test/qr-code', function() {
    $member = \App\Models\Member::first();
    if (!$member) {
        return 'No members found';
    }
    return view('test.qr-code', ['member' => $member]);
})->name('test.qr');

// Public QR code scanning route (must be outside auth middleware)
Route::get('/qr/scan/{code}', [QrCodeController::class, 'scanPublic'])->name('public.qr.scan');
Route::get('/qr/scan/{code}/new', [QrCodeController::class, 'scanPublic'])->name('public.qr.scan.new');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/personal-info', [ProfileController::class, 'updatePersonalInfo'])->name('profile.personal-info.update');
    Route::put('/profile/picture', [ProfileController::class, 'updateProfilePicture'])->name('profile.picture.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Member routes with archive functionality
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('members/download', [MemberController::class, 'download'])->name('members.download');
        Route::resource('members', MemberController::class);
        Route::patch('members/{member}/archive', [MemberController::class, 'archive'])->name('members.archive');
        Route::patch('members/{member}/unarchive', [MemberController::class, 'unarchive'])->name('members.unarchive');
    });
    
    // Attendance routes - allow viewing for all authenticated users, but protect other operations
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/{attendance}', [AttendanceController::class, 'show'])->name('attendance.show');
    // Event-specific attendance listing
    Route::get('/events/{event}/attendance', [AttendanceController::class, 'eventAttendance'])
        ->name('attendance.event');
    
    // Protected attendance routes for creating/editing/deleting
    Route::middleware(['auth', 'admin.leader'])->group(function() {
        Route::get('/attendance/create', [AttendanceController::class, 'create'])->name('attendance.create');
        Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store');
        Route::get('/attendance/{attendance}/edit', [AttendanceController::class, 'edit'])->name('attendance.edit');
        Route::put('/attendance/{attendance}', [AttendanceController::class, 'update'])->name('attendance.update');
        Route::delete('/attendance/{attendance}', [AttendanceController::class, 'destroy'])->name('attendance.destroy');
    });
    
    Route::middleware(['auth'])->group(function () {
        Route::resource('chapters', ChapterController::class);

           // Admin/Leader only routes for event management
           Route::middleware([ 'auth', 'admin.leader'])->group(function () {
            Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
            Route::post('/events', [EventController::class, 'store'])->name('events.store');
            Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
            Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
            Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');
            Route::patch('/events/{event}/restore', [EventController::class, 'restore'])->name('events.restore');
        });

        
        // Public event routes (viewing only)
        Route::get('/events', [EventController::class, 'index'])->name('events.index');
        Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
        
        // Member QR code route
        Route::get('/events/{event}/member-qr', [EventController::class, 'memberQrCode'])->name('events.member-qr');
        
        // Check-in routes - accessible by all authenticated users with member profiles
        Route::middleware('auth')->group(function() {
            // Show check-in page
            Route::get('/events/{event}/check-in', [EventController::class, 'scan'])
                ->name('events.check-in');
            
            // Handle check-in submission
            Route::post('/events/{event}/check-in', [EventController::class, 'checkIn'])
                ->name('events.check-in.submit');
        });
        
     
        Route::middleware(['auth'])->group(function () {
            Route::resource('prayer-requests', PrayerRequestController::class);
            Route::patch('/prayer-requests/{prayerRequest}/approve', [PrayerRequestController::class, 'approve'])->name('prayer-requests.approve');
            Route::patch('/prayer-requests/{prayerRequest}/decline', [PrayerRequestController::class, 'decline'])->name('prayer-requests.decline');
        });
    });

    Route::resource('resources', ResourceController::class);
    Route::get('/resources/{resource}/download', [ResourceController::class, 'download'])->name('resources.download');
    Route::post('/resources/test-upload', [ResourceController::class, 'testUpload'])->name('resources.test-upload');

    Route::get('/admin/users', [AdminUserController::class, 'index'])->name('admin.users.index')->middleware('admin.leader');
    Route::get('/admin/users/{id}', [AdminUserController::class, 'show'])->name('admin.users.show')->middleware('admin.leader');
    Route::post('/admin/users/{id}/role', [AdminUserController::class, 'updateRole'])->name('admin.users.updateRole')->middleware('admin.leader');
    Route::post('/admin/users/{id}/chapter', [AdminUserController::class, 'updateChapter'])->name('admin.users.updateChapter')->middleware('admin.leader');
    Route::post('/admin/users/{id}/assign-leader', [AdminUserController::class, 'assignLeader'])->name('admin.users.assignLeader')->middleware('admin.leader');
    Route::post('/admin/users/{id}/assign-leader-role', [AdminUserController::class, 'assignLeaderAndRole'])->name('admin.users.assignLeaderAndRole')->middleware('admin.leader');
    Route::post('/admin/users/{id}/remove-leader', [AdminUserController::class, 'removeLeader'])->name('admin.users.removeLeader')->middleware('admin.leader');
    Route::post('/admin/users/{id}/impersonate', [AdminUserController::class, 'impersonate'])->name('admin.users.impersonate')->middleware('admin');
    Route::post('/admin/users/{id}/activate', [AdminUserController::class, 'activate'])->name('admin.users.activate')->middleware('admin');
    Route::post('/admin/users/{id}/deactivate', [AdminUserController::class, 'deactivate'])->name('admin.users.deactivate')->middleware('admin');
    Route::delete('/admin/users/{id}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy')->middleware('admin');
    Route::get('/admin/stop-impersonate', [AdminUserController::class, 'stopImpersonate'])->name('admin.users.stopImpersonate');

    // Admin/Leader: Create a new Member user
    Route::post('/admin/users', [AdminUserController::class, 'store'])
        ->name('admin.users.store')
        ->middleware('admin.leader');

    // Bible Verse Routes
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::post('/dashboard/update-bible-verse', [DashboardController::class, 'updateBibleVerse'])->name('dashboard.updateBibleVerse');
        Route::delete('/dashboard/delete-bible-verse', [DashboardController::class, 'deleteBibleVerse'])->name('dashboard.bibleVerse.delete');
    });

    // Dashboard Image Routes
    Route::post('/dashboard/upload-image', [DashboardController::class, 'uploadDashboardImage'])->name('dashboard.uploadImage');
    Route::patch('/dashboard/image/{dashboardImage}', [DashboardController::class, 'updateDashboardImage'])->name('dashboard.image.update');
    Route::delete('/dashboard/image/{dashboardImage}', [DashboardController::class, 'deleteDashboardImage'])->name('dashboard.image.delete');
    Route::post('/notifications/mark-read', [\App\Http\Controllers\DashboardController::class, 'markNotificationsRead'])->name('notifications.markRead');

    // QR Code Routes
    Route::prefix('qr')->name('qr.')->group(function () {
        Route::post('/generate/{event}', [QrCodeController::class, 'generate'])->name('generate');
        
        // Scanner route with optional event parameter
        Route::middleware(['auth'])->group(function () {
            Route::get('/scanner/{event?}', [QrCodeController::class, 'scanner'])
                ->name('scanner')
                ->middleware(\App\Http\Middleware\CheckEventStatus::class)
                ->where('event', '[0-9]+');
        });
        
        Route::get('/show/{qrCode}', [QrCodeController::class, 'show'])->name('show');
        Route::get('/display/{qrCode}', [QrCodeController::class, 'display'])->name('display');
        Route::post('/scan', [QrCodeController::class, 'scan'])->name('scan');
        Route::get('/generate-image/{qrCode}', [QrCodeController::class, 'generateImage'])->name('generate-image');
        Route::put('/deactivate/{qrCode}', [QrCodeController::class, 'deactivate'])->name('deactivate');
        Route::get('/latest', [QrCodeController::class, 'showLatest'])->name('latest');
    });

    // Attendance QR Management
    Route::get('/attendance/qr-management/{event}', [AttendanceController::class, 'qrManagement'])->name('attendance.qr-management')->middleware('admin.leader');
    Route::get('/attendance/real-time', [AttendanceController::class, 'getRealTimeAttendance'])->name('attendance.real-time')->middleware('admin.leader');

    // Invitation Codes - only for admins and leaders
    Route::middleware(['auth', 'admin.leader'])->group(function() {
        // Define routes explicitly instead of using resource()
        Route::get('/invitations', [\App\Http\Controllers\InvitationCodeController::class, 'index'])
            ->name('invitations.index');
            
        Route::get('/invitations/create', [\App\Http\Controllers\InvitationCodeController::class, 'create'])
            ->name('invitations.create');
            
        Route::post('/invitations', [\App\Http\Controllers\InvitationCodeController::class, 'store'])
            ->name('invitations.store');
            
        Route::get('/invitations/{invitation}', [\App\Http\Controllers\InvitationCodeController::class, 'show'])
            ->name('invitations.show');
            
        Route::delete('/invitations/{invitation}', [\App\Http\Controllers\InvitationCodeController::class, 'destroy'])
            ->name('invitations.destroy');
        
        // Add revoke route
        Route::patch('/invitations/{invitation}/revoke', [\App\Http\Controllers\InvitationCodeController::class, 'revoke'])
            ->name('invitations.revoke');
    });

    // Message Routes
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{userId}', [MessageController::class, 'getMessages']);
    Route::get('/messages/search-users', [MessageController::class, 'searchUsers']);
    Route::post('/messages/send', [MessageController::class, 'sendMessage'])->name('messages.send');
    Route::post('/messages/mark-read/{userId}', [MessageController::class, 'markAsRead']);
    Route::post('/messages/update-activity', [MessageController::class, 'updateActivity']);

    Route::get('/avatar-demo', function() { return view('components.avatar-demo'); })->name('avatar.demo');

    Route::get('/my-qr-code', [ProfileController::class, 'myQrCode'])->name('profile.my-qr-code');
    Route::post('/announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
    Route::put('/announcements/{announcement}', [AnnouncementController::class, 'update'])->name('announcements.update');
    Route::delete('/announcements/{announcement}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');

    // Activity Log (Admin/Leader)
    Route::get('/admin/activity-log', [AuditLogController::class, 'index'])->name('admin.activity-log')->middleware('admin.leader');
});

// Public routes
Route::get('/map', function () {
    return view('map');
})->name('map');

// Analytics page for admin only
Route::middleware(['auth'])->group(function () {
    Route::get('/analytics', [\App\Http\Controllers\AnalyticsController::class, 'index'])->name('analytics.index');
});

Route::get('/create-test-event', function() {
    if (app()->isLocal()) {
        try {
            $event = \App\Models\Event::create([
                'title' => 'Simple Test Event',
                'description' => 'Test event.',
                'date' => '2025-01-01',
                'time' => '12:00',
                'location' => 'Test Location',
                'status' => 'upcoming'
            ]);
            return "SUCCESS: Event '{$event->title}' created with ID: {$event->id}";
        } catch (Exception $e) {
            return "ERROR: " . $e->getMessage();
        }
    }
    abort(404);
});

Route::get('/react-dashboard', function () {
    return view('react-dashboard');
});

// Test route for debugging file uploads
Route::post('/test-upload', function (Request $request) {
    if ($request->hasFile('file')) {
        $file = $request->file('file');
        return response()->json([
            'success' => true,
            'file_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'file_type' => $file->getMimeType(),
            'is_valid' => $file->isValid(),
        ]);
    }
    return response()->json(['success' => false, 'message' => 'No file uploaded']);
})->name('test.upload');

// Simple upload test route
Route::get('/upload-test', function () {
    return view('upload-test');
})->name('upload.test');

// Notification routes
Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
    Route::get('/notifications/recent', [NotificationController::class, 'getRecent'])->name('notifications.recent');
});

require __DIR__.'/auth.php';
