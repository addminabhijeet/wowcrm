<?php

namespace App\Http\Middleware;

use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

class EncryptCookies extends Middleware
{
    /**
     * The names of the cookies that should not be encrypted.
     *
     * @var array<int, string>
     */
    protected $except = [
        // ✅ Exclude session & CSRF cookies from encryption to prevent 419 errors
        // Session data is already encrypted by the session handler
        'norloxcrm_session',
        'XSRF-TOKEN',  // CSRF token cookie
    ];
}
