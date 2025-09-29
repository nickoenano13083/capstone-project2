<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckEventStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Log the incoming request details
            Log::info('CheckEventStatus middleware triggered', [
                'url' => $request->fullUrl(),
                'route_name' => $request->route() ? $request->route()->getName() : null,
                'route_parameters' => $request->route() ? $request->route()->parameters() : null,
            ]);

            // Skip if no event parameter in the route
            if (!$request->route()->hasParameter('event')) {
                Log::info('No event parameter in route, allowing request');
                return $next($request);
            }

            // Get the event from the route parameters
            $event = $request->route('event');
            
            // If no event is provided, continue
            if (!$event) {
                Log::info('No event provided, allowing request');
                return $next($request);
            }

            // Log the raw event value
            Log::info('Raw event parameter', [
                'event' => $event,
                'event_type' => $event ? get_class($event) : 'null',
                'is_numeric' => is_numeric($event),
                'is_object' => is_object($event),
                'is_model' => is_object($event) && method_exists($event, 'getKey')
            ]);

            // If we have a numeric ID but not a model, try to get the event
            if (is_numeric($event)) {
                Log::info('Numeric event ID found, attempting to retrieve event', ['id' => $event]);
                $event = Event::find($event);
                
                if (!$event) {
                    Log::warning('Event not found', ['id' => $event]);
                    return redirect()->route('events.index')
                        ->with('error', 'Event not found.');
                }
                
                // Replace the route parameter with the model instance
                $request->route()->setParameter('event', $event);
                Log::info('Event model loaded and set in route parameters', ['event_id' => $event->id]);
            }

            // Verify we have a valid event model
            if (!($event instanceof Event)) {
                Log::error('Invalid event parameter type', [
                    'expected' => 'App\\Models\\Event',
                    'actual' => is_object($event) ? get_class($event) : gettype($event)
                ]);
                return redirect()->route('events.index')
                    ->with('error', 'Invalid event parameter.');
            }

            // Log the event status check
            Log::info('Checking event status', [
                'event_id' => $event->id,
                'event_title' => $event->title,
                'event_status' => $event->status
            ]);

            // If event is completed, redirect back with error
            if ($event->status === 'completed') {
                Log::info('Event is completed, redirecting to events index');
                return redirect()->route('events.index')
                    ->with('error', 'This event has already been completed and is no longer accepting check-ins.');
            }

            // If event is not active (not upcoming or ongoing)
            if (!in_array($event->status, ['upcoming', 'ongoing'])) {
                Log::info('Event is not active, redirecting to events index', [
                    'current_status' => $event->status
                ]);
                return redirect()->route('events.index')
                    ->with('error', 'This event is not available for check-in.');
            }

            Log::info('Event status check passed, allowing access');
            return $next($request);

        } catch (\Exception $e) {
            Log::error('Error in CheckEventStatus middleware', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Allow the request to continue but log the error
            return $next($request);
        }
    }
}
