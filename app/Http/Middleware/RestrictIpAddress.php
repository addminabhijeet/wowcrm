<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\AllowedIp;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cache;

class RestrictIpAddress
{
    public function handle(Request $request, Closure $next): Response
    {
        // IP check switch: true = skip IP check for all routes, false = original IP check runs
        $ipCheckDisabled = true;
        if ($ipCheckDisabled) {
            return $next($request);
        }

        // Cache allowed IPs for 1 hour to avoid DB query on every request
        $allowedIps = Cache::remember('allowed_ips_cache', 3600, function () {
            return AllowedIp::pluck('ip_address')->toArray();
        });

        if (!in_array($request->ip(), $allowedIps)) {
            abort(403, 'Access denied.');
        }

        return $next($request);
    }
}