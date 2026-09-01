<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class CacheHelper
{
    /**
     * Cache query results for expensive database operations
     * Solves: CallReportController::senior() 6 COUNT queries (2-3s)
     */
    public static function cacheCallMetrics($senior_id, $selectedDate, $callback, $minutes = 60)
    {
        $cacheKey = "call_metrics_{$senior_id}_{$selectedDate}";

        return Cache::remember($cacheKey, $minutes * 60, $callback);
    }

    /**
     * Cache hourly aggregations
     * Solves: Hourly Aggregations - 5 CONCAT queries (2-4s)
     */
    public static function cacheHourlyAggregations($senior_id, $selectedDate, $callback, $minutes = 60)
    {
        $cacheKey = "hourly_agg_{$senior_id}_{$selectedDate}";

        return Cache::remember($cacheKey, $minutes * 60, $callback);
    }

    /**
     * Cache target analytics
     * Solves: TargetAnalyticsController - 600+ queries (5-10s)
     */
    public static function cacheTargetAnalytics($user_id, $year, $callback, $minutes = 120)
    {
        $cacheKey = "target_analytics_{$user_id}_{$year}";

        return Cache::remember($cacheKey, $minutes * 60, $callback);
    }

    /**
     * Cache user list for GoogleSheetController
     */
    public static function cacheUsersList($callback, $minutes = 300)
    {
        $cacheKey = "users_active_list";

        return Cache::remember($cacheKey, $minutes * 60, $callback);
    }

    /**
     * Cache search results
     * Solves: GoogleSheetController::admin() string parsing (200-500ms)
     */
    public static function cacheSearchResults($searchKey, $callback, $minutes = 30)
    {
        $cacheKey = "search_{$searchKey}";

        return Cache::remember($cacheKey, $minutes * 60, $callback);
    }

    /**
     * Invalidate related caches when data changes
     */
    public static function invalidateCallMetrics($senior_id, $date = null)
    {
        if ($date) {
            Cache::forget("call_metrics_{$senior_id}_{$date}");
            Cache::forget("hourly_agg_{$senior_id}_{$date}");
        } else {
            // Invalidate all call metrics for this senior
            Cache::tags(["call_metrics_{$senior_id}"])->flush();
        }
    }

    public static function invalidateTargetAnalytics($user_id = null)
    {
        if ($user_id) {
            Cache::tags(["target_analytics_{$user_id}"])->flush();
        } else {
            Cache::tags(["target_analytics"])->flush();
        }
    }

    public static function invalidateUsersList()
    {
        Cache::forget("users_active_list");
    }
}
