<?php

namespace App\Jobs;

use App\Services\Cache\ApiKeyHealthCache;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ResetAPIKeyLimit implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(ApiKeyHealthCache $cache): void
    {
        try {
            // Get all API key IDs before updating them
            $apiKeyIds = DB::table('api_keys')->pluck('id')->toArray();

            // Perform bulk update of API key limits
            $randomExpression = $this->getRandomExpression();
            $updateCount = DB::table('api_keys')
                ->update([
                    'request_remaining' => DB::raw($randomExpression),
                    'sent_request' => 0,
                    'rate_limit_reset' => Carbon::now()->addMonth(),
                    'request_sent_at' => null,
                    'updated_at' => Carbon::now(),
                ]);

            // Clear all cached health metrics for the reset API keys
            $clearedCacheCount = 0;
            foreach ($apiKeyIds as $apiKeyId) {
                $cache->clearAllCache($apiKeyId);
                $clearedCacheCount++;
            }

            Log::info('API Key limits reset via bulk update', [
                'updated_count' => $updateCount,
                'cleared_cache_count' => $clearedCacheCount,
                'timestamp' => Carbon::now(),
            ]);

        } catch (Exception $e) {
            Log::error('Failed to bulk reset API key limits', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            throw $e;
        }
    }

    /**
     * Get database-specific random expression for request_remaining
     */
    private function getRandomExpression(): string
    {
        $driver = DB::connection()->getConfig('driver');

        return match ($driver) {
            'sqlite' => 'CAST((50 + (ABS(RANDOM()) % 31)) AS INTEGER)',
            'mysql', 'mariadb' => 'FLOOR(50 + (RAND() * 30))',
            'pgsql' => 'FLOOR(50 + (RANDOM() * 30))',
            default => 'FLOOR(50 + (RANDOM() * 30))',
        };
    }
}
