<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\RestrictIpAddress;

// ✅ Override PHP session settings to support 8+ hour continuous usage
// Set session.gc_maxlifetime to match Laravel SESSION_LIFETIME
$sessionLifetime = (int)(getenv('SESSION_LIFETIME') ?: 540) * 60; // Convert minutes to seconds
$gcMaxLifetime = max($sessionLifetime + 3600, 32400); // At least 9 hours (32400 seconds)

if (function_exists('ini_set')) {
    ini_set('session.gc_maxlifetime', $gcMaxLifetime);
}

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'allowedip' => RestrictIpAddress::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
