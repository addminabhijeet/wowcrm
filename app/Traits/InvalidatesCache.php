<?php

namespace App\Traits;

trait InvalidatesCache
{
    public static function bootInvalidatesCache()
    {
        static::created(function ($model) {
            self::clearUserCache();
        });

        static::updated(function ($model) {
            self::clearUserCache();
        });

        static::deleted(function ($model) {
            self::clearUserCache();
        });
    }

    private static function clearUserCache()
    {
        try {
            \Illuminate\Support\Facades\Cache::forget('all_active_users');
            \Illuminate\Support\Facades\Cache::forget('new_users_30days');
            \Illuminate\Support\Facades\Cache::forget('active_users_list');
            \Illuminate\Support\Facades\Cache::forget('all_users_count');
            \Illuminate\Support\Facades\Cache::forget('active_users_count');
        } catch (\Exception $e) {
            // Silently fail cache invalidation
        }
    }
}
