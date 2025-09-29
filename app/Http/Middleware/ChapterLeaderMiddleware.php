<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ChapterLeaderMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        if ($user->role === 'Leader') {
            // Check if the user is a chapter leader
            $ledChaptersCount = $user->ledChapters()->count();
            if ($ledChaptersCount === 0) {
                abort(403, 'Access denied. You are not assigned as a leader for any chapter.');
            }
        }

        return $next($request);
    }
}
