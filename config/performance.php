<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Performance Optimization Settings
    |--------------------------------------------------------------------------
    */

    // Query optimization
    'queries' => [
        'max_results' => env('QUERY_MAX_RESULTS', 10000),
        'cache_duration' => env('QUERY_CACHE_DURATION', 3600),
        'log_slow_queries' => env('LOG_SLOW_QUERIES', true),
        'slow_query_threshold' => env('SLOW_QUERY_THRESHOLD', 1000), // milliseconds
    ],

    // Cache optimization
    'cache' => [
        'user_list_ttl' => env('CACHE_USER_LIST_TTL', 300),
        'dashboard_ttl' => env('CACHE_DASHBOARD_TTL', 300),
        'reports_ttl' => env('CACHE_REPORTS_TTL', 3600),
    ],

    // Rate limiting
    'rate_limiting' => [
        'enabled' => env('RATE_LIMITING_ENABLED', true),
        'public_per_minute' => env('RATE_LIMIT_PUBLIC', 60),
        'authenticated_per_minute' => env('RATE_LIMIT_AUTH', 120),
    ],

    // Response compression
    'compression' => [
        'enabled' => env('RESPONSE_COMPRESSION', true),
        'min_size' => env('COMPRESSION_MIN_SIZE', 1000), // bytes
    ],

    // Database
    'database' => [
        'connection_pool_size' => env('DB_POOL_SIZE', 50),
        'lazy_loading_strict' => env('LAZY_LOADING_STRICT', !env('APP_DEBUG')),
    ],

    // Static assets
    'assets' => [
        'minify_css' => env('MINIFY_CSS', true),
        'minify_js' => env('MINIFY_JS', true),
        'versioning' => env('ASSET_VERSIONING', true),
    ],
];
