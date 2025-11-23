<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\ApiName;
use App\Models\ApiKey;
use App\Services\Cache\ApiKeyHealthCache;
use Carbon\Carbon;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApiKeyService
{
    public function __construct(
        private ApiKeyHealthCache $healthCache
    ) {}

    public function getAvailableApiKey(): ?ApiKey
    {
        $availableKeys = $this->getAvailableKeys();

        if ($availableKeys->isEmpty()) {
            Log::warning('No available API key with remaining requests');

            return null;
        }

        // Use weighted round-robin selection
        return $this->selectKeyUsingWeightedRoundRobin($availableKeys);
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

    private function selectKeyUsingWeightedRoundRobin(Collection $apiKeys): ApiKey
    {
        $weights = $apiKeys->map(function (ApiKey $apiKey) {
            return [
                'key' => $apiKey,
                'weight' => $this->calculateApiKeyWeight($apiKey),
            ];
        })->sortByDesc('weight');

        Log::debug('API Key weights calculated', [
            'weights' => $weights->map(fn ($item) => [
                'id' => $item['key']->id,
                'weight' => round($item['weight'], 4),
                'remaining' => $item['key']->request_remaining,
                'sent' => $item['key']->sent_request,
            ])->toArray(),
        ]);

        // Weighted random selection
        $totalWeight = $weights->sum('weight');
        $randomWeight = mt_rand() / mt_getrandmax() * $totalWeight;

        $currentWeight = 0;
        foreach ($weights as $item) {
            $currentWeight += $item['weight'];
            if ($randomWeight <= $currentWeight) {
                return $item['key'];
            }
        }

        return $weights->first()['key'];
    }

    private function calculateApiKeyWeight(ApiKey $apiKey): float
    {
        $maxRequests = max($apiKey->request_remaining + $apiKey->sent_request, 1);
        $remainingRatio = $apiKey->request_remaining / $maxRequests;

        // Apply logarithmic scaling to prevent extreme values
        $baseWeight = log10($apiKey->request_remaining + 1) / log10($maxRequests + 1);

        $healthFactor = $this->healthCache->getHealthFactor($apiKey);

        // Time-based factor to prevent recently used keys from being selected too frequently
        $timeFactor = $this->getTimeFactor($apiKey);

        $weight = $baseWeight * $healthFactor * $timeFactor;

        Log::debug('Weight calculation', [
            'api_key_id' => $apiKey->id,
            'base_weight' => round($baseWeight, 4),
            'health_factor' => round($healthFactor, 4),
            'time_factor' => round($timeFactor, 4),
            'final_weight' => round($weight, 4),
            'remaining' => $apiKey->request_remaining,
        ]);

        return max($weight, 0.001); // Ensure minimum weight
    }

    private function getTimeFactor(ApiKey $apiKey): float
    {
        if (! $apiKey->request_sent_at) {
            return 1.0; // Never used, full weight
        }

        $secondsSinceLastUse = now()->diffInSeconds($apiKey->request_sent_at);

        return min(1.0, $secondsSinceLastUse / 60.0);
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
        ]);
    }

    public function getApiKeyStats(): array
    {
        $apiKeys = ApiKey::query()
            ->where('api_name', ApiName::JOB)
            ->select(['id', 'request_remaining', 'sent_request', 'request_sent_at'])
            ->get();

        return $apiKeys->map(function (ApiKey $apiKey) {
            $healthFactor = $this->healthCache->getHealthFactor($apiKey);
            $weight = $this->calculateApiKeyWeight($apiKey);
            $cacheStats = $this->healthCache->getCacheStats($apiKey->id);

            return [
                'id' => $apiKey->id,
                'request_remaining' => $apiKey->request_remaining,
                'sent_request' => $apiKey->sent_request,
                'health_factor' => round($healthFactor, 4),
                'weight' => round($weight, 4),
                'last_used' => $apiKey->request_sent_at?->diffForHumans(),
                'recent_requests' => $cacheStats['requests'],
                'recent_failures' => $cacheStats['failures'],
                'failure_rate' => $cacheStats['failure_rate'],
                'circuit_breaker_open' => $cacheStats['circuit_breaker_open'],
            ];
        })->toArray();
    }
}
