<?php

namespace App\Caches;

use App\Helpers\RedisCache;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class JobRecommendationCache
{
    public static function getUserRecommendations(User $user, int $limit, callable $callback): Collection
    {
        $key = self::userKey($user->id, $limit);
        
        return Cache::remember($key, 60 * 30, $callback);
    }

    public static function invalidateUserRecommendations(?int $userId): bool
    {
        if ($userId) {
            return RedisCache::forgetPattern("user_recommendations_{$userId}_*");
        }
        
        return RedisCache::forgetPattern('user_recommendations_*');
    }

    public static function invalidateAll(): bool
    {
        return RedisCache::forgetPattern('user_recommendations_*');
    }

    public static function userKey(int $userId, int $limit): string
    {
        return "user_recommendations_{$userId}_{$limit}";
    }
}