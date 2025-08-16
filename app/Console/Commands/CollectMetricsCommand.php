<?php

namespace App\Console\Commands;

use App\Services\MetricsService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class CollectMetricsCommand extends Command
{
    protected $signature = 'metrics:collect {--type=all : Type of metrics to collect (all, business, system)}';
    protected $description = 'Collect various application metrics for Prometheus';

    public function __construct(private MetricsService $metricsService)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $type = $this->option('type');

        $this->info("Dispatching {$type} metrics collection job...");

        try {
            \App\Jobs\CollectMetricsJob::dispatch($type);
            
            $this->info('Metrics collection job dispatched successfully.');
            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('Failed to dispatch metrics job: ' . $e->getMessage());
            $this->metricsService->recordException('metrics_dispatch', get_class($e), __FILE__);
            return Command::FAILURE;
        }
    }

    private function collectAllMetrics(): void
    {
        $this->collectBusinessMetrics();
        $this->collectSystemMetrics();
    }

    private function collectBusinessMetrics(): void
    {
        $this->info('Collecting business metrics...');
        $this->metricsService->collectBusinessMetrics();

        // Additional business metrics
        $this->collectJobApplicationMetrics();
        $this->collectUserEngagementMetrics();
        $this->collectContentMetrics();
    }

    private function collectSystemMetrics(): void
    {
        $this->info('Collecting system metrics...');

        // Database metrics
        $this->collectDatabaseMetrics();

        // Cache metrics
        $this->collectCacheMetrics();

        // Storage metrics
        $this->collectStorageMetrics();
    }

    private function collectJobApplicationMetrics(): void
    {
        // Applications in last 24 hours
        $recentApplications = DB::table('job_applications')
            ->where('created_at', '>=', now()->subDay())
            ->count();

        $this->info("Recent job applications (24h): {$recentApplications}");
    }

    /**
     * @throws \Prometheus\Exception\MetricsRegistrationException
     */
    private function collectUserEngagementMetrics(): void
    {
        // User login frequency
        $dailyLogins = DB::table('users')
            ->where('last_login_at', '>=', now()->subDay())
            ->count();

        $weeklyLogins = DB::table('users')
            ->where('last_login_at', '>=', now()->subWeek())
            ->count();

        $monthlyLogins = DB::table('users')
            ->where('last_login_at', '>=', now()->subMonth())
            ->count();

        $this->metricsService->updateActiveUsers($dailyLogins, '24h');
        $this->metricsService->updateActiveUsers($weeklyLogins, '7d');
        $this->metricsService->updateActiveUsers($monthlyLogins, '30d');

        $this->info("Active users - 24h: {$dailyLogins}, 7d: {$weeklyLogins}, 30d: {$monthlyLogins}");
    }

    private function collectContentMetrics(): void
    {
        // Job listings by category
        DB::table('job_listings')
            ->select('category', DB::raw('count(*) as count'))
            ->where('status', 'active')
            ->groupBy('category')
            ->get()
            ->each(fn($category) => 
                $this->info("Active jobs in {$category->category}: {$category->count}")
            );

        // Cover letters generated today
        $coverLettersToday = DB::table('airesponses')
            ->where('created_at', '>=', now()->startOfDay())
            ->where('type', 'cover_letter')
            ->count();

        collect(range(1, $coverLettersToday))
            ->each(fn() => $this->metricsService->recordCoverLetterGeneration('success'));

        $this->info("Cover letters generated today: {$coverLettersToday}");
    }

    private function collectDatabaseMetrics(): void
    {
        // Table sizes
        collect(['users', 'job_listings', 'job_applications', 'airesponses'])
            ->each(function ($table) {
                try {
                    $count = DB::table($table)->count();
                    $this->info("Table {$table}: {$count} records");
                } catch (\Exception $e) {
                    $this->warn("Could not get count for table {$table}: " . $e->getMessage());
                }
            });

        // Database connection pool status
        $connections = DB::getConnections();
        $this->info("Active database connections: " . count($connections));
    }

    private function collectCacheMetrics(): void
    {
        // Test cache performance
        $start = microtime(true);
        $testKey = 'metrics_test_' . time();

        Cache::put($testKey, 'test_value', 60);
        $retrieved = Cache::get($testKey);
        Cache::forget($testKey);

        $duration = microtime(true) - $start;
        $this->info("Cache operation took: " . round($duration * 1000, 2) . "ms");
    }

    private function collectStorageMetrics(): void
    {
        // Storage usage
        $storagePath = storage_path();

        if (function_exists('disk_free_space') && function_exists('disk_total_space')) {
            $freeBytes = disk_free_space($storagePath);
            $totalBytes = disk_total_space($storagePath);

            if ($freeBytes !== false && $totalBytes !== false) {
                $usedBytes = $totalBytes - $freeBytes;
                $usagePercent = round(($usedBytes / $totalBytes) * 100, 2);

                $this->info("Storage usage: {$usagePercent}% ({$this->formatBytes($usedBytes)} / {$this->formatBytes($totalBytes)})");
            }
        }

        // Log file sizes
        $logPath = storage_path('logs');
        if (is_dir($logPath)) {
            $totalLogSize = collect(glob($logPath . '/*.log'))
                ->sum(fn($file) => filesize($file));

            $this->info("Total log files size: " . $this->formatBytes($totalLogSize));
        }
    }

    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, 2) . ' ' . $units[$pow];
    }
}
