<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CompressResponse
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Add compression headers
        if ($request->header('Accept-Encoding')) {
            if (str_contains($request->header('Accept-Encoding'), 'gzip')) {
                $response->headers->set('Content-Encoding', 'gzip');
            } elseif (str_contains($request->header('Accept-Encoding'), 'deflate')) {
                $response->headers->set('Content-Encoding', 'deflate');
            }
        }

        // Remove unnecessary headers to reduce payload
        $response->headers->remove('X-Powered-By');
        $response->headers->remove('Server');

        // Add optimization headers
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        return $response;
    }
}
