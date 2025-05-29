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

    public static function getGuestRecommendations(string $sessionId, int $limit, callable $callback): Collection
    {
        $key = self::guestKey($sessionId, $limit);
        
        return Cache::remember($key, 60 * 15, $callback);
    }

    public static function invalidateUserRecommendations(int $userId): bool
    {
        $pattern = "user_recommendations_{$userId}_*";
        return self::forgetPattern($pattern);
    }

    public static function invalidateGuestRecommendations(string $sessionId): bool
    {
        $pattern = "guest_recommendations_{$sessionId}_*";
        return self::forgetPattern($pattern);
    }

    public static function invalidateAll(): bool
    {
        $userPattern = 'user_recommendations_*';
        $guestPattern = 'guest_recommendations_*';
        
        return self::forgetPattern($userPattern) && self::forgetPattern($guestPattern);
    }

    public static function userKey(int $userId, int $limit): string
    {
        return "user_recommendations_{$userId}_{$limit}";
    }

    public static function guestKey(string $sessionId, int $limit): string
    {
        return "guest_recommendations_{$sessionId}_{$limit}";
    }

    private static function forgetPattern(string $pattern): bool
    {
        return Cache::forget($pattern);
    }
}