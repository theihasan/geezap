<?php

declare(strict_types=1);

namespace App\Services\Cache;

use App\Models\ApiKey;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ApiKeyHealthCache
{
    private array $config;

    public function __construct()
    {
        $this->config = config('cache.api_key_health');
    }

    /**
     * Check if circuit breaker is open for an API key
     */
    public function isCircuitBreakerOpen(ApiKey $apiKey): bool
    {
        $circuitBreakerKey = $this->getCircuitBreakerKey($apiKey->id);

        if (Cache::has($circuitBreakerKey)) {
            Log::debug('Circuit breaker is open for API key', [
                'api_key_id' => $apiKey->id,
                'expires_at' => Cache::get($circuitBreakerKey),
            ]);

            return true;
        }

        // Check if we should open the circuit breaker
        $recentFailures = $this->getFailureCount($apiKey->id);
        $recentRequests = $this->getRequestCount($apiKey->id);

        if ($recentRequests >= $this->config['min_requests_threshold']) {
            $failureRate = $recentFailures / $recentRequests;

            if ($failureRate >= $this->config['circuit_breaker_failure_threshold']) {
                $this->openCircuitBreaker($apiKey->id, $failureRate, $recentFailures, $recentRequests);

                return true;
            }
        }

        return false;
    }

    /**
     * Open circuit breaker for an API key
     */
    private function openCircuitBreaker(int $apiKeyId, float $failureRate, int $recentFailures, int $recentRequests): void
    {
        $circuitBreakerKey = $this->getCircuitBreakerKey($apiKeyId);
        $cooldownSeconds = $this->config['circuit_breaker_cooldown'];

        Cache::put($circuitBreakerKey, now()->addSeconds($cooldownSeconds), $cooldownSeconds);

        Log::warning('Circuit breaker opened for API key', [
            'api_key_id' => $apiKeyId,
            'failure_rate' => round($failureRate, 4),
            'recent_failures' => $recentFailures,
            'recent_requests' => $recentRequests,
            'cooldown_until' => now()->addSeconds($cooldownSeconds),
        ]);
    }

    /**
     * Get health factor for an API key (cached)
     */
    public function getHealthFactor(ApiKey $apiKey): float
    {
        $cacheKey = $this->getHealthKey($apiKey->id);

        return (float) Cache::remember($cacheKey, $this->config['health_cache_ttl'], function () use ($apiKey) {
            $recentFailures = $this->getFailureCount($apiKey->id);
            $recentRequests = $this->getRequestCount($apiKey->id);

            if ($recentRequests < $this->config['min_requests_threshold']) {
                return 1.0; // No data, assume healthy
            }

            $failureRate = $recentFailures / $recentRequests;

            // Apply penalty factor to failure rate
            return max(1.0 - ($failureRate * $this->config['failure_penalty_factor']), 0.1);
        });
    }

    /**
     * Update health metrics after an API request
     */
    public function updateHealthMetrics(ApiKey $apiKey, bool $isSuccessful): void
    {
        $requestsKey = $this->getRequestsKey($apiKey->id);
        $failuresKey = $this->getFailuresKey($apiKey->id);
        $ttl = $this->config['metrics_ttl'];

        // Increment request count
        $currentRequests = (int) Cache::get($requestsKey, 0) + 1;
        Cache::put($requestsKey, $currentRequests, $ttl);

        // Increment failure count if request failed
        if (! $isSuccessful) {
            $currentFailures = (int) Cache::get($failuresKey, 0) + 1;
            Cache::put($failuresKey, $currentFailures, $ttl);
        }

        // Clear health factor cache to force recalculation
        $this->clearHealthCache($apiKey->id);
    }

    /**
     * Get current request count for an API key
     */
    public function getRequestCount(int $apiKeyId): int
    {
        return (int) Cache::get($this->getRequestsKey($apiKeyId), 0);
    }

    /**
     * Get current failure count for an API key
     */
    public function getFailureCount(int $apiKeyId): int
    {
        return (int) Cache::get($this->getFailuresKey($apiKeyId), 0);
    }

    /**
     * Clear all health-related cache for an API key
     */
    public function clearHealthCache(int $apiKeyId): void
    {
        Cache::forget($this->getHealthKey($apiKeyId));
    }

    /**
     * Clear all cache data for an API key
     */
    public function clearAllCache(int $apiKeyId): void
    {
        Cache::forget($this->getCircuitBreakerKey($apiKeyId));
        Cache::forget($this->getHealthKey($apiKeyId));
        Cache::forget($this->getRequestsKey($apiKeyId));
        Cache::forget($this->getFailuresKey($apiKeyId));
    }

    /**
     * Get cache statistics for debugging
     */
    public function getCacheStats(int $apiKeyId): array
    {
        return [
            'requests' => $this->getRequestCount($apiKeyId),
            'failures' => $this->getFailureCount($apiKeyId),
            'failure_rate' => $this->calculateFailureRate($apiKeyId),
            'circuit_breaker_open' => Cache::has($this->getCircuitBreakerKey($apiKeyId)),
            'circuit_breaker_expires' => Cache::get($this->getCircuitBreakerKey($apiKeyId)),
        ];
    }

    /**
     * Calculate current failure rate for an API key
     */
    private function calculateFailureRate(int $apiKeyId): ?float
    {
        $requests = $this->getRequestCount($apiKeyId);
        $failures = $this->getFailureCount($apiKeyId);

        if ($requests === 0) {
            return null;
        }

        return round($failures / $requests, 4);
    }

    /**
     * Generate cache key for circuit breaker
     */
    private function getCircuitBreakerKey(int $apiKeyId): string
    {
        return "circuit_breaker_{$apiKeyId}";
    }

    /**
     * Generate cache key for health factor
     */
    private function getHealthKey(int $apiKeyId): string
    {
        return "api_key_health_{$apiKeyId}";
    }

    /**
     * Generate cache key for request count
     */
    private function getRequestsKey(int $apiKeyId): string
    {
        return "api_key_requests_{$apiKeyId}";
    }

    /**
     * Generate cache key for failure count
     */
    private function getFailuresKey(int $apiKeyId): string
    {
        return "api_key_failures_{$apiKeyId}";
    }
}
