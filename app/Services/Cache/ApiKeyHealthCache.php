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
     * Check if circuit breaker is open for an API key.
     * Uses a strict check to isolate problematic keys quickly.
     */
    public function isCircuitBreakerOpen(ApiKey $apiKey): bool
    {
        $circuitBreakerKey = $this->getCircuitBreakerKey($apiKey->id);

        // Check if breaker is already open
        if (Cache::has($circuitBreakerKey)) {
            Log::debug('Circuit breaker is open for API key', [
                'api_key_id' => $apiKey->id,
                'expires_at' => Cache::get($circuitBreakerKey),
            ]);

            return true;
        }

        // Check if we should open the circuit breaker based on failure rate
        return $this->shouldOpenCircuitBreaker($apiKey);
    }

    /**
     * Determine if circuit breaker should be opened based on recent metrics.
     * Uses sliding window of recent requests to avoid stale data.
     */
    private function shouldOpenCircuitBreaker(ApiKey $apiKey): bool
    {
        $recentFailures = $this->getFailureCount($apiKey->id);
        $recentRequests = $this->getRequestCount($apiKey->id);

        // Not enough data to make a decision
        if ($recentRequests < $this->config['min_requests_threshold']) {
            return false;
        }

        $failureRate = $recentFailures / $recentRequests;
        $threshold = $this->config['circuit_breaker_failure_threshold'];

        // Open circuit breaker if failure rate exceeds threshold
        if ($failureRate >= $threshold) {
            $this->openCircuitBreaker($apiKey->id, $failureRate, $recentFailures, $recentRequests);

            return true;
        }

        return false;
    }

    /**
     * Open circuit breaker for an API key with configurable cooldown.
     * Logs detailed information for monitoring and debugging.
     */
    private function openCircuitBreaker(int $apiKeyId, float $failureRate, int $recentFailures, int $recentRequests): void
    {
        $circuitBreakerKey = $this->getCircuitBreakerKey($apiKeyId);
        $cooldownSeconds = $this->config['circuit_breaker_cooldown'];
        $expiresAt = now()->addSeconds($cooldownSeconds);

        Cache::put($circuitBreakerKey, $expiresAt->timestamp, $cooldownSeconds);

        Log::warning('Circuit breaker opened for API key', [
            'api_key_id' => $apiKeyId,
            'failure_rate' => round($failureRate, 4),
            'recent_failures' => $recentFailures,
            'recent_requests' => $recentRequests,
            'cooldown_seconds' => $cooldownSeconds,
            'cooldown_until' => $expiresAt,
        ]);
    }

    /**
     * Get health factor for an API key.
     * Health factor ranges from 0.1 (unhealthy) to 1.0 (healthy).
     * Cached to reduce computation overhead.
     */
    public function getHealthFactor(ApiKey $apiKey): float
    {
        $cacheKey = $this->getHealthKey($apiKey->id);

        return (float) Cache::remember($cacheKey, $this->config['health_cache_ttl'], function () use ($apiKey) {
            return $this->calculateHealthFactor($apiKey);
        });
    }

    /**
     * Calculate health factor based on recent failure rate.
     * Returns 1.0 if no sufficient data, otherwise applies penalty.
     */
    private function calculateHealthFactor(ApiKey $apiKey): float
    {
        $recentFailures = $this->getFailureCount($apiKey->id);
        $recentRequests = $this->getRequestCount($apiKey->id);

        // No data yet, assume healthy
        if ($recentRequests < $this->config['min_requests_threshold']) {
            return 1.0;
        }

        $failureRate = $recentFailures / $recentRequests;

        // Apply penalty factor to reduce selection weight
        // failureRate 0.0 -> health 1.0
        // failureRate 0.5 -> health 0.75
        // failureRate 1.0 -> health 0.5
        $penaltyFactor = $this->config['failure_penalty_factor'];
        $health = 1.0 - ($failureRate * $penaltyFactor);

        return max($health, 0.1); // Minimum health of 0.1 to allow recovery
    }

    /**
     * Update health metrics after an API request.
     * Tracks both successful and failed requests for health calculation.
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

            Log::debug('API key request failed', [
                'api_key_id' => $apiKey->id,
                'failure_count' => $currentFailures,
                'request_count' => $currentRequests,
            ]);
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
     * Clear health factor cache to force recalculation
     */
    public function clearHealthCache(int $apiKeyId): void
    {
        Cache::forget($this->getHealthKey($apiKeyId));
    }

    /**
     * Clear all cache data for an API key.
     * Useful when resetting or recovering a key.
     */
    public function clearAllCache(int $apiKeyId): void
    {
        Cache::forget($this->getCircuitBreakerKey($apiKeyId));
        Cache::forget($this->getHealthKey($apiKeyId));
        Cache::forget($this->getRequestsKey($apiKeyId));
        Cache::forget($this->getFailuresKey($apiKeyId));

        Log::info('All cache cleared for API key', ['api_key_id' => $apiKeyId]);
    }

    /**
     * Get cache statistics for debugging and monitoring
     */
    public function getCacheStats(int $apiKeyId): array
    {
        $requests = $this->getRequestCount($apiKeyId);
        $failures = $this->getFailureCount($apiKeyId);

        return [
            'requests' => $requests,
            'failures' => $failures,
            'failure_rate' => $this->calculateFailureRate($requests, $failures),
            'circuit_breaker_open' => Cache::has($this->getCircuitBreakerKey($apiKeyId)),
            'circuit_breaker_expires' => Cache::get($this->getCircuitBreakerKey($apiKeyId)),
        ];
    }

    /**
     * Calculate failure rate safely
     */
    private function calculateFailureRate(int $requests, int $failures): ?float
    {
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
