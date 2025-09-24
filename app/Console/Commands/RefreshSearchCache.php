<?php

namespace App\Console\Commands;

use App\Jobs\RefreshSearchSuggestionsCache;
use App\Services\SearchSuggestionService;
use Illuminate\Console\Command;

class RefreshSearchCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:refresh-cache {--sync : Run synchronously instead of queuing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh the search suggestions cache';

    /**
     * Execute the console command.
     */
    public function handle(SearchSuggestionService $searchService)
    {
        $this->info('Refreshing search suggestions cache...');

        if ($this->option('sync')) {
            // Run synchronously
            $job = new RefreshSearchSuggestionsCache;
            $job->handle($searchService);
            $this->info('Search cache refreshed synchronously.');
        } else {
            // Queue the job
            RefreshSearchSuggestionsCache::dispatch();
            $this->info('Search cache refresh job queued.');
        }

        $this->info('Done!');
    }
}
