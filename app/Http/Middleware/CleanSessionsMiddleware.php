<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CleanSessionsMiddleware
{
    /**
     * Handle an incoming request.
     * Periodically clean old session files to prevent 419 errors caused by accumulation
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Run garbage collection on 2% of requests (similar to Laravel's default)
        if (random_int(1, 100) <= 2) {
            $this->cleanOldSessions();
        }

        return $next($request);
    }

    /**
     * Clean old session files (older than SESSION_LIFETIME minutes)
     * This prevents 419 errors caused by excessive session file accumulation
     */
    private function cleanOldSessions()
    {
        $sessionPath = storage_path('framework/sessions');
        $lifetime = config('session.lifetime', 120) * 60; // Convert minutes to seconds

        if (!is_dir($sessionPath)) {
            return;
        }

        try {
            $now = time();
            $files = scandir($sessionPath);

            if ($files === false) {
                return;
            }

            foreach ($files as $file) {
                // Skip . and .. entries
                if ($file === '.' || $file === '..') {
                    continue;
                }

                $filePath = $sessionPath . DIRECTORY_SEPARATOR . $file;

                // Skip if not a file
                if (!is_file($filePath)) {
                    continue;
                }

                // Delete if file is older than session lifetime
                if ($now - filemtime($filePath) >= $lifetime) {
                    @unlink($filePath);
                }
            }
        } catch (\Exception $e) {
            // Silently fail - don't disrupt request if cleanup fails
            return;
        }
    }
}
