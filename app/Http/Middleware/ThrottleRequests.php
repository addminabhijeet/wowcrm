<?php

namespace App\Http\Middleware;

use Illuminate\Routing\Middleware\ThrottleRequests as BaseThrottleRequests;

class ThrottleRequests extends BaseThrottleRequests
{
    /**
     * Resolve the number of requests allowed per minute.
     */
    protected function resolveMaxAttempts($request)
    {
        if ($request->user()) {
            return 120; // Authenticated users: 120 req/min
        }

        return 60; // Public: 60 req/min
    }

    /**
     * Resolve the number of minutes until the rate limit window is reset.
     */
    protected function resolveRequestSignature($request)
    {
        if ($request->user()) {
            return sha1($request->user()->id);
        }

        if ($request->route()) {
            return sha1($request->route()->getDomain() . '|' . $request->ip());
        }

        return sha1($request->ip());
    }
}
