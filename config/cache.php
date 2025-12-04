<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Cache Store
    |--------------------------------------------------------------------------
    |
    | This option controls the default cache store that will be used by the
    | framework. This connection is utilized if another isn't explicitly
    | specified when running a cache operation inside the application.
    |
    */

    'default' => env('CACHE_STORE', 'redis'),

    /*
    |--------------------------------------------------------------------------
    | Cache Stores
    |--------------------------------------------------------------------------
    |
    | Here you may define all of the cache "stores" for your application as
    | well as their drivers. You may even define multiple stores for the
    | same cache driver to group types of items stored in your caches.
    |
    | Supported drivers: "array", "database", "file", "memcached",
    |                    "redis", "dynamodb", "octane", "null"
    |
    */

    'stores' => [

        'array' => [
            'driver' => 'array',
            'serialize' => false,
        ],

        'database' => [
            'driver' => 'database',
            'table' => env('DB_CACHE_TABLE', 'cache'),
            'connection' => env('DB_CACHE_CONNECTION'),
            'lock_connection' => env('DB_CACHE_LOCK_CONNECTION'),
        ],

        'file' => [
            'driver' => 'file',
            'path' => storage_path('framework/cache/data'),
            'lock_path' => storage_path('framework/cache/data'),
        ],

        'memcached' => [
            'driver' => 'memcached',
            'persistent_id' => env('MEMCACHED_PERSISTENT_ID'),
            'sasl' => [
                env('MEMCACHED_USERNAME'),
                env('MEMCACHED_PASSWORD'),
            ],
            'options' => [
                // Memcached::OPT_CONNECT_TIMEOUT => 2000,
            ],
            'servers' => [
                [
                    'host' => env('MEMCACHED_HOST', '127.0.0.1'),
                    'port' => env('MEMCACHED_PORT', 11211),
                    'weight' => 100,
                ],
            ],
        ],

        'redis' => [
            'driver' => 'redis',
            'connection' => 'default',
            'lock_connection' => 'default',
        ],

        'dynamodb' => [
            'driver' => 'dynamodb',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
            'table' => env('DYNAMODB_CACHE_TABLE', 'cache'),
            'endpoint' => env('DYNAMODB_ENDPOINT'),
        ],

        'octane' => [
            'driver' => 'octane',
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Key Prefix
    |--------------------------------------------------------------------------
    |
    | When utilizing the APC, database, memcached, Redis, and DynamoDB cache
    | stores, there might be other applications using the same cache. For
    | that reason, you may prefix every cache key to avoid collisions.
    |
    */

    'prefix' => env('CACHE_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_').'_cache_'),

    /*
    |--------------------------------------------------------------------------
    | API Key Health Cache Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for API key health tracking and circuit breaker pattern.
    | Carefully tuned to balance quick failure detection with avoiding false positives.
    |
    | - health_cache_ttl: How long to cache health factor before recalculation (reduces CPU)
    | - metrics_ttl: How long to keep request/failure metrics (shorter = forgiving)
    | - circuit_breaker_cooldown: How long to isolate a failing key
    | - min_requests_threshold: Minimum requests before circuit breaker can activate
    | - failure_penalty_factor: How much each failure impacts health (0.5 = half weight per failure)
    | - circuit_breaker_failure_threshold: Failure rate to trigger circuit breaker (0.5 = 50%)
    |
    */

    'api_key_health' => [
        'health_cache_ttl' => env('API_HEALTH_CACHE_TTL', 300), 
        'metrics_ttl' => env('API_METRICS_TTL', 3600), 
        'circuit_breaker_cooldown' => env('API_CIRCUIT_BREAKER_COOLDOWN', 300), 
        'min_requests_threshold' => env('API_MIN_REQUESTS_THRESHOLD', 5), 
        'failure_penalty_factor' => env('API_FAILURE_PENALTY_FACTOR', 0.8),
        'circuit_breaker_failure_threshold' => env('API_CIRCUIT_BREAKER_FAILURE_THRESHOLD', 0.5), 
    ],

];
