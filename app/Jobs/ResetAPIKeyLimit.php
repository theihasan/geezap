<?php

namespace App\Jobs;

use Exception;
use Carbon\Carbon;
use App\Models\ApiKey;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

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
    public function handle(): void
    {
        try {
            $updateCount = DB::table('api_keys')
                ->update([
                    'request_remaining' => DB::raw('FLOOR(50 + (RAND() * 30))'),
                    'sent_request' => 0,
                    'rate_limit_reset' => Carbon::now()->addMonth(),
                    'request_sent_at' => null,
                    'updated_at' => Carbon::now(),
                ]);
            
            Log::info('API Key limits reset via bulk update', [
                'updated_count' => $updateCount,
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
}
