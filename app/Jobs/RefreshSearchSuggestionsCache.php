<?php

namespace App\Jobs;

use App\Services\SearchSuggestionService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class RefreshSearchSuggestionsCache implements ShouldQueue
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
    public function handle(SearchSuggestionService $searchService): void
    {
        try {
            Log::info('Starting search suggestions cache refresh');

            $searchService->clearCaches();

            // Pre-warm popular suggestions cache
            $searchService->getSuggestions('', 10);

            // Pre-warm some common search caches
            $commonQueries = [
                'developer',
                'engineer',
                'designer',
                'manager',
                'analyst',
                'remote',
                'react',
                'python',
                'javascript',
            ];

            foreach ($commonQueries as $query) {
                $searchService->getSuggestions($query, 8);
            }

            Log::info('Search suggestions cache refresh completed successfully');
        } catch (\Exception $e) {
            Log::error('Failed to refresh search suggestions cache', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }

    /**
     * Handle job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('RefreshSearchSuggestionsCache job failed', [
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ]);
    }
}
