<?php

namespace App\Caches;

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
            $pattern = "user_recommendations_{$userId}_*";
        } else {
            $pattern = 'user_recommendations_*';
        }
        return self::forgetPattern($pattern);
    }

    public static function invalidateAll(): bool
    {
        $userPattern = 'user_recommendations_*';
        
        return self::forgetPattern($userPattern);
    }

    public static function userKey(int $userId, int $limit): string
    {
        return "user_recommendations_{$userId}_{$limit}";
    }

    private static function forgetPattern(string $pattern): bool
    {
        return Cache::forget($pattern);
    }
}