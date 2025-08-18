<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class RedisCache
{
    /**
     * Delete cache keys matching a pattern using Redis SCAN
     */
    public static function forgetPattern(string $pattern): int
    {
        $redis = Redis::connection('default');
        $deleted = 0;
        $cursor = 0;

        $prefix = config('cache.prefix', '');
        if ($prefix && !str_starts_with($pattern, $prefix)) {
            $pattern = $prefix . $pattern;
        }

        do {
            $result = $redis->scan($cursor, ['MATCH' => $pattern, 'COUNT' => 1000]);
            $cursor = $result[0];
            $keys = $result[1];

            if (!empty($keys)) {
                $deleted += $redis->del($keys);
            }
        } while ($cursor !== 0);

        return $deleted;
    }

    /**
     * Enhanced forget method that handles both exact keys and patterns
     */
    public static function forget($key): bool
    {
        if (str_contains($key, '*')) {
            return self::forgetPattern($key) > 0;
        }

        return Cache::forget($key);
    }

    /**
     * Get all keys matching a pattern
     */
    public static function getKeysMatching(string $pattern): array
    {
        $redis = Redis::connection('default');
        $keys = [];
        $cursor = 0;

        $prefix = config('cache.prefix', '');
        if ($prefix && !str_starts_with($pattern, $prefix)) {
            $pattern = $prefix . $pattern;
        }

        do {
            $result = $redis->scan($cursor, ['MATCH' => $pattern, 'COUNT' => 1000]);
            $cursor = $result[0];
            $keys = array_merge($keys, $result[1]);
        } while ($cursor !== 0);

        return $keys;
    }
}
