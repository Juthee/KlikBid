<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // ISO 27001 Security Headers
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Content Security Policy
        $response->headers->set('Content-Security-Policy',
            "default-src 'self'; " .
            "script-src 'self' 'unsafe-inline' cdn.tailwindcss.com; " .
            "style-src 'self' 'unsafe-inline' cdn.tailwindcss.com; " .
            "img-src 'self' data: blob:; " .
            "font-src 'self';"
        );

        // HSTS for HTTPS (only in production)
        if ($request->secure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        return $response;
    }
}
