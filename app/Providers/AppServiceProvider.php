<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Configure rate limiting for API endpoints
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('global', function (Request $request) {
            return Limit::perMinute(100)->by($request->ip());
        });

        // Email the admin-chosen addresses when a non-admin logs in via the login form
        \Illuminate\Support\Facades\Event::listen(\Illuminate\Auth\Events\Login::class, function ($event) {
            if (!request()->routeIs('login.submit') || ($event->user->role ?? '') === 'admin') {
                return;
            }
            $user = $event->user;
            $ip = request()->ip();
            $ua = request()->userAgent();
            app()->terminating(fn () => \App\Services\LoginAlertService::notify($user, $ip, $ua));
        });

        // Email the same recipients when a non-admin logs out via the logout button
        \Illuminate\Support\Facades\Event::listen(\Illuminate\Auth\Events\Logout::class, function ($event) {
            if (!request()->routeIs('logout') || !$event->user || ($event->user->role ?? '') === 'admin') {
                return;
            }
            $user = $event->user;
            $ip = request()->ip();
            $ua = request()->userAgent();
            app()->terminating(fn () => \App\Services\LoginAlertService::notify($user, $ip, $ua, 'logout'));
        });

        // Call report mails: show each recruiter's call duration (same numbers as Call Duration > Group Report)
        \Illuminate\Support\Facades\View::composer('emails.call-report', function ($view) {
            $data = $view->getData();
            if (isset($data['report']) && is_array($data['report']) && !isset($data['report']['duration_note'])) {
                $view->with('report', \App\Services\GroupCallReportMail::withDurations($data['report']));
            }
        });

        // Enable query optimization in production
        if (!$this->app->isLocal()) {
            Model::preventLazyLoading();
        }
    }
}
