<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\ApiName;
use App\Models\ApiKey;
use App\Services\Cache\ApiKeyHealthCache;
use Carbon\Carbon;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApiKeyService
{
    private const ROUND_ROBIN_CACHE_KEY = 'api_key_round_robin_index';

    private const ROUND_ROBIN_CACHE_TTL = 3600; // 1 hour

    public function __construct(
        private ApiKeyHealthCache $healthCache
    ) {}

    public function getAvailableApiKey(): ?ApiKey
    {
        $availableKeys = $this->getAvailableKeys();

        if ($availableKeys->isEmpty()) {
            Log::warning('No available API keys', [
                'reason' => 'All keys exhausted, in cooldown, or circuit breaker open',
            ]);

            return null;
        }

        // Use intelligent round-robin selection with health awareness
        return $this->selectKeyUsingRoundRobin($availableKeys);
    }

    private function getAvailableKeys(): Collection
    {
        $apiKeys = ApiKey::query()
            ->where('api_name', ApiName::JOB)
            ->where('request_remaining', '>', 0)
            ->where(function ($query) {
                // Exclude keys that are in cooldown due to rate limiting
                $query->whereNull('rate_limit_reset')
                    ->orWhere('rate_limit_reset', '<', now());
            })
            ->get();

        // Filter out circuit breaker keys
        return $apiKeys->reject(function (ApiKey $apiKey) {
            return $this->healthCache->isCircuitBreakerOpen($apiKey);
        });
    }

    /**
     * Round-robin selection with health-aware sorting.
     * This ensures all keys get used roughly equally while prioritizing healthier keys.
     */
    private function selectKeyUsingRoundRobin(Collection $apiKeys): ApiKey
    {
        // Sort by health factor and remaining requests for better distribution
        $sortedKeys = $apiKeys->sortBy(function (ApiKey $apiKey) {
            $healthFactor = $this->healthCache->getHealthFactor($apiKey);

            // Sort by: health factor (desc) then remaining (desc)
            return [
                -$healthFactor, // Negative for descending
                -$apiKey->request_remaining,
            ];
        })->values();

        Log::debug('Available API keys for selection', [
            'count' => $sortedKeys->count(),
            'keys' => $sortedKeys->map(fn ($key) => [
                'id' => $key->id,
                'remaining' => $key->request_remaining,
                'health_factor' => round($this->healthCache->getHealthFactor($key), 4),
            ])->toArray(),
        ]);

        $selectedKey = $this->getNextKeyInRoundRobin($sortedKeys);

        Log::debug('API key selected via round-robin', [
            'selected_key_id' => $selectedKey->id,
            'remaining' => $selectedKey->request_remaining,
        ]);

        return $selectedKey;
    }

    /**
     * Get the next key in round-robin sequence.
     * This ensures each key is used in turn before cycling back.
     */
    private function getNextKeyInRoundRobin(Collection $sortedKeys): ApiKey
    {
        $currentIndex = (int) Cache::get(self::ROUND_ROBIN_CACHE_KEY, 0);

        // Ensure index is within bounds
        if ($currentIndex >= $sortedKeys->count()) {
            $currentIndex = 0;
        }

        $selectedKey = $sortedKeys->get($currentIndex);

        // Move to next index for next call
        $nextIndex = ($currentIndex + 1) % $sortedKeys->count();
        Cache::put(self::ROUND_ROBIN_CACHE_KEY, $nextIndex, self::ROUND_ROBIN_CACHE_TTL);

        return $selectedKey;
    }

    public function updateUsage(ApiKey $apiKey, Response $response): void
    {
        $resetTimestamp = $response->header('X-RateLimit-Reset');
        $resetTime = $resetTimestamp ? Carbon::createFromTimestamp($resetTimestamp) : null;
        $remainingRequests = $response->header('X-RateLimit-Requests-Remaining');
        $isSuccessful = $response->successful() ?? false;

        Log::debug('Updating API key usage', [
            'api_key_id' => $apiKey->id,
            'before_remaining' => $apiKey->request_remaining,
            'before_sent' => $apiKey->sent_request,
            'new_remaining' => $remainingRequests,
            'response_status' => $response->status(),
            'is_successful' => $isSuccessful,
        ]);

        // Update database
        DB::table('api_keys')
            ->where('id', $apiKey->id)
            ->update([
                'request_remaining' => $remainingRequests,
                'rate_limit_reset' => $resetTime,
                'sent_request' => DB::raw('sent_request + 1'),
                'request_sent_at' => now(),
                'updated_at' => now(),
            ]);

        // Update health tracking in cache
        $this->healthCache->updateHealthMetrics($apiKey, $isSuccessful);

        Log::debug('API key usage updated successfully', [
            'api_key_id' => $apiKey->id,
            'health_metrics_updated' => true,
        ]);
    }

    public function resetRoundRobinIndex(): void
    {
        Cache::forget(self::ROUND_ROBIN_CACHE_KEY);
        Log::info('Round-robin index reset');
    }

    public function getApiKeyStats(): array
    {
        $apiKeys = ApiKey::query()
            ->where('api_name', ApiName::JOB)
            ->select(['id', 'request_remaining', 'sent_request', 'request_sent_at'])
            ->get();

        return $apiKeys->map(function (ApiKey $apiKey) {
            $healthFactor = $this->healthCache->getHealthFactor($apiKey);
            $cacheStats = $this->healthCache->getCacheStats($apiKey->id);

            return [
                'id' => $apiKey->id,
                'request_remaining' => $apiKey->request_remaining,
                'sent_request' => $apiKey->sent_request,
                'health_factor' => round($healthFactor, 4),
                'last_used' => $apiKey->request_sent_at?->diffForHumans(),
                'recent_requests' => $cacheStats['requests'],
                'recent_failures' => $cacheStats['failures'],
                'failure_rate' => $cacheStats['failure_rate'],
                'circuit_breaker_open' => $cacheStats['circuit_breaker_open'],
            ];
        })->toArray();
    }
}
