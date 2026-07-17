<?php

namespace App\Traits;

use App\Jobs\ProcessCacheFill;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

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
            Cache::forget('all_active_users');
            Cache::forget('new_users_30days');
            Cache::forget('active_users_list');
            Cache::forget('all_users_count');
            Cache::forget('active_users_count');

            // Refill cache in background
            \dispatch(new ProcessCacheFill());
        } catch (\Exception $e) {
            \Log::error('Cache invalidation failed: ' . $e->getMessage());
        }
    }
}
