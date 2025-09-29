<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\MemberController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Apply rate limiting to all API routes (60 requests per minute per IP)
Route::middleware(['throttle:60,1'])->group(function () {
    // Public routes (if any)

    // Event check-in route - accessible to all (no auth required for check-ins)
    Route::post('/events/{event}/check-in', [EventController::class, 'checkIn'])
        ->name('api.events.check-in');

    // Protected routes
    Route::middleware('auth')->group(function () {
        // Member management
        Route::post('/members', [MemberController::class, 'apiStore']);
        // Add more protected API routes here
    });
});

// Health check endpoint (excluded from rate limiting)
Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
});