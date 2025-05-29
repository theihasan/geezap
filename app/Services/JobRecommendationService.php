<?php

namespace App\Services;

use App\Models\JobListing;
use App\Models\User;
use App\Models\GuestPreference;
use App\Caches\JobRecommendationCache;
use Illuminate\Support\Collection;

class JobRecommendationService
{
    public function getRecommendedJobsForUser(User $user, int $limit = 6): Collection
    {
        $preferences = $user->preferences;
        
        if (!$preferences || !$preferences->show_recommendations) {
            return collect();
        }

        return JobRecommendationCache::getUserRecommendations($user, $limit, function () use ($preferences, $limit) {
            return $this->buildRecommendationQuery($preferences, $limit);
        });
    }

    public function getRecommendedJobsForGuest(string $sessionId, int $limit = 3): Collection
    {
        $preferences = GuestPreference::where('session_id', $sessionId)->first();
        
        if (!$preferences) {
            return JobListing::with(['category'])
                ->orderBy('views', 'desc')
                ->limit($limit)
                ->get();
        }

        if ($preferences->hasReachedDailyLimit()) {
            return collect();
        }

        $preferences->incrementViews();

        return JobRecommendationCache::getGuestRecommendations($sessionId, $limit, function () use ($preferences, $limit) {
            return $this->buildRecommendationQuery($preferences, $limit);
        });
    }

    private function buildRecommendationQuery($preferences, int $limit): Collection
    {
        $query = JobListing::with(['category']);

        if (!empty($preferences->preferred_job_topics)) {
            $query->whereIn('job_category', $preferences->preferred_job_topics);
        }

        if (!empty($preferences->preferred_job_types)) {
            $query->whereIn('job_type', $preferences->preferred_job_types);
        }

        if ($preferences->remote_only) {
            $query->where('is_remote', true);
        }

        if (isset($preferences->min_salary) && $preferences->min_salary > 0) {
            $query->where('salary_min', '>=', $preferences->min_salary);
        }

        if (isset($preferences->max_salary) && $preferences->max_salary > 0) {
            $query->where('salary_max', '<=', $preferences->max_salary);
        }

        return $query->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Clear cache for a specific user's recommendations
     */
    public function clearUserCache(int $userId): bool
    {
        return JobRecommendationCache::invalidateUserRecommendations($userId);
    }

    /**
     * Clear cache for a specific guest's recommendations
     */
    public function clearGuestCache(string $sessionId): bool
    {
        return JobRecommendationCache::invalidateGuestRecommendations($sessionId);
    }

    /**
     * Clear all recommendation caches
     */
    public function clearAllCache(): bool
    {
        return JobRecommendationCache::invalidateAll();
    }
}