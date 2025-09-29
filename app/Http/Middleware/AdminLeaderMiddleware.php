<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminLeaderMiddleware
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
        $userRole = strtolower($user->role ?? '');
        
        // Debug logging
        \Log::info('AdminLeaderMiddleware', [
            'user_id' => $user->id,
            'user_role' => $user->role,
            'normalized_role' => $userRole,
            'request_path' => $request->path(),
        ]);
        
        if (!in_array($userRole, ['admin', 'leader'])) {
            \Log::warning('Access denied', [
                'user_id' => $user->id,
                'role' => $user->role,
                'path' => $request->path(),
                'ip' => $request->ip()
            ]);
            abort(403, 'Access denied. This area is restricted to administrators and leaders only.');
        }

        return $next($request);
    }
}
