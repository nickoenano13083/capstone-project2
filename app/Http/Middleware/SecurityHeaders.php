<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
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
        $response = $next($request);

        if (!method_exists($response, 'header')) {
            return $response;
        }

        // Security Headers
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        
        // Content Security Policy (CSP) - Basic configuration
        // Note: You may need to adjust this based on your application's needs
        $csp = [
            "default-src 'self';",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https:;",
            "style-src 'self' 'unsafe-inline' https:;",
            "img-src 'self' data: https:;",
            "font-src 'self' data: https:;",
            "connect-src 'self' https:;",
            "frame-ancestors 'self';",
            "form-action 'self';",
            "object-src 'none';",
            "base-uri 'self';",
            "frame-src 'self' https:;",
            "media-src 'self' https:;",
        ];
        
        $response->headers->set('Content-Security-Policy', implode(' ', $csp));
        
        // Feature Policy (now Permissions Policy in newer browsers)
        $permissionsPolicy = [
            'geolocation=()',
            'midi=()',
            'sync-xhr=()',
            'microphone=()',
            'camera=()',
            'magnetometer=()',
            'gyroscope=()',
            'speaker=()',
            'vibrate=()',
            'fullscreen=()',
            'payment=()',
        ];
        
        $response->headers->set('Permissions-Policy', implode(', ', $permissionsPolicy));
        
        // HSTS - Only enable in production with HTTPS
        if (app()->environment('production')) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        return $response;
    }
}
