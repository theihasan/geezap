<?php

namespace App\Console\Commands;

use App\Services\ApiKeyService;
use Illuminate\Console\Command;

class ShowApiKeyStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:stats {--refresh : Refresh cache before showing stats}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show API key performance statistics and health metrics';

    /**
     * Execute the console command.
     */
    public function handle(ApiKeyService $apiKeyService): int
    {
        if ($this->option('refresh')) {
            $this->info('Refreshing API key health metrics...');
            cache()->flush();
        }

        $stats = $apiKeyService->getApiKeyStats();

        if (empty($stats)) {
            $this->warn('No API keys found.');

            return Command::SUCCESS;
        }

        $this->info('API Key Performance Statistics');
        $this->info('================================');

        $headers = [
            'ID',
            'Remaining',
            'Sent',
            'Health',
            'Weight',
            'Last Used',
            'Recent Requests',
            'Recent Failures',
            'Success Rate',
        ];

        $rows = [];
        foreach ($stats as $stat) {
            $successRate = $stat['recent_requests'] > 0
                ? round((($stat['recent_requests'] - $stat['recent_failures']) / $stat['recent_requests']) * 100, 1).'%'
                : 'N/A';

            $rows[] = [
                $stat['id'],
                number_format($stat['request_remaining']),
                number_format($stat['sent_request']),
                $stat['health_factor'],
                $stat['weight'],
                $stat['last_used'] ?? 'Never',
                $stat['recent_requests'],
                $stat['recent_failures'],
                $successRate,
            ];
        }

        $this->table($headers, $rows);

        // Summary
        $totalRemaining = array_sum(array_column($stats, 'request_remaining'));
        $totalSent = array_sum(array_column($stats, 'sent_request'));
        $totalRecentRequests = array_sum(array_column($stats, 'recent_requests'));
        $totalRecentFailures = array_sum(array_column($stats, 'recent_failures'));

        $this->info("\nSummary:");
        $this->info('- Total API Keys: '.count($stats));
        $this->info('- Total Remaining Requests: '.number_format($totalRemaining));
        $this->info('- Total Sent Requests: '.number_format($totalSent));
        $this->info('- Recent Requests (24h): '.number_format($totalRecentRequests));
        $this->info('- Recent Failures (24h): '.number_format($totalRecentFailures));

        if ($totalRecentRequests > 0) {
            $overallSuccessRate = round((($totalRecentRequests - $totalRecentFailures) / $totalRecentRequests) * 100, 1);
            $this->info("- Overall Success Rate (24h): {$overallSuccessRate}%");
        }

        return Command::SUCCESS;
    }
}
