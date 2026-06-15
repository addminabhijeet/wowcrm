<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\AllowedIp;
use Symfony\Component\HttpFoundation\Response;

class RestrictIpAddress
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!AllowedIp::where('ip_address', $request->ip())->exists()) {
            abort(403, 'Access denied.');
        }

        return $next($request);
    }
}