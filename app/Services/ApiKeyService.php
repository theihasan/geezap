<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\ApiName;
use App\Models\ApiKey;
use Carbon\Carbon;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApiKeyService
{
    public function getAvailableApiKey(): ?ApiKey
    {
        $apiKey = ApiKey::query()
            ->where('api_name', ApiName::JOB)
            ->where('request_remaining', '>', 0)
            ->selectRaw('*, (sent_request / NULLIF(request_remaining, 0)) as usage_ratio')
            ->orderBy('usage_ratio', 'asc')
            ->first();

        if (! $apiKey) {
            Log::warning('No available API key with remaining requests');
        }

        return $apiKey;
    }

    public function updateUsage(ApiKey $apiKey, Response $response): void
    {
        $resetTimestamp = $response->header('X-RateLimit-Reset');
        $resetTime = $resetTimestamp ? Carbon::createFromTimestamp($resetTimestamp) : null;
        $remainingRequests = $response->header('X-RateLimit-Requests-Remaining');

        Log::debug('Updating API key usage', [
            'api_key_id' => $apiKey->id,
            'before_remaining' => $apiKey->request_remaining,
            'before_sent' => $apiKey->sent_request,
            'new_remaining' => $remainingRequests,
            'response_status' => $response->status(),
        ]);

        DB::table('api_keys')
            ->where('id', $apiKey->id)
            ->update([
                'request_remaining' => $remainingRequests,
                'rate_limit_reset' => $resetTime,
                'sent_request' => DB::raw('sent_request + 1'),
                'updated_at' => now(),
            ]);

        Log::debug('API key usage updated successfully', [
            'api_key_id' => $apiKey->id,
        ]);
    }
}
