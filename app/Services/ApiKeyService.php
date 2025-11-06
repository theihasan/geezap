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
            ->orderBy('sent_request')
            ->first();

        if (! $apiKey) {
            Log::warning('No available API key with remaining requests');
        }

        return $apiKey;
    }

    public function updateUsage(ApiKey $apiKey, Response $response): void
    {
        DB::table('api_keys')
            ->where('id', $apiKey->id)
            ->update([
                'request_remaining' => $response->header('X-RateLimit-Requests-Remaining'),
                'rate_limit_reset' => Carbon::createFromTimestamp($response->header('X-RateLimit-Reset')),
                'sent_request' => DB::raw('sent_request + 1'),
                'updated_at' => now(),
            ]);
    }
}
