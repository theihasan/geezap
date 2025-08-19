<?php

namespace App\Jobs;

use App\Services\MetricsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class CollectMetricsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $type = 'all'
    ) {
        $this->onQueue('default');
    }

    public function handle(MetricsService $metricsService): void
    {
        match ($this->type) {
            'business' => $this->collectBusinessMetrics($metricsService),
            'system' => $this->collectSystemMetrics($metricsService),
            'all' => $this->collectAllMetrics($metricsService),
            default => $this->collectAllMetrics($metricsService)
        };
    }

    private function collectAllMetrics(MetricsService $metricsService): void
    {
        $this->collectBusinessMetrics($metricsService);
        $this->collectSystemMetrics($metricsService);
    }

    private function collectBusinessMetrics(MetricsService $metricsService): void
    {
        $metricsService->collectBusinessMetrics();
        $this->collectJobApplicationMetrics($metricsService);
        $this->collectUserEngagementMetrics($metricsService);
        $this->collectContentMetrics($metricsService);
    }

    private function collectSystemMetrics(MetricsService $metricsService): void
    {
        $this->collectDatabaseMetrics();
        $this->collectCacheMetrics();
        $this->collectStorageMetrics();
    }

    private function collectJobApplicationMetrics(MetricsService $metricsService): void
    {
        $recentApplications = DB::table('job_user')
            ->where('created_at', '>=', now()->subDay())
            ->count();
    }

    private function collectUserEngagementMetrics(MetricsService $metricsService): void
    {
        $dailyLogins = DB::table('users')
            ->where('last_login_at', '>=', now()->subDay())
            ->count();

        $weeklyLogins = DB::table('users')
            ->where('last_login_at', '>=', now()->subWeek())
            ->count();

        $monthlyLogins = DB::table('users')
            ->where('last_login_at', '>=', now()->subMonth())
            ->count();

        $metricsService->updateActiveUsers($dailyLogins, '24h');
        $metricsService->updateActiveUsers($weeklyLogins, '7d');
        $metricsService->updateActiveUsers($monthlyLogins, '30d');
    }

    private function collectContentMetrics(MetricsService $metricsService): void
    {
        // Content metrics collection will be implemented when needed
    }

    private function collectDatabaseMetrics(): void
    {
        collect(['users', 'job_listings', 'job_applications', 'airesponses'])
            ->each(function ($table) {
                try {
                    DB::table($table)->count();
                } catch (\Exception $e) {
                    // Table might not exist
                }
            });
    }

    private function collectCacheMetrics(): void
    {
        $testKey = 'metrics_test_' . time();

        Cache::put($testKey, 'test_value', 60);
        Cache::get($testKey);
        Cache::forget($testKey);
    }

    private function collectStorageMetrics(): void
    {
        $storagePath = storage_path();

        if (function_exists('disk_free_space') && function_exists('disk_total_space')) {
            $freeBytes = disk_free_space($storagePath);
            $totalBytes = disk_total_space($storagePath);
        }

        $logPath = storage_path('logs');
        if (is_dir($logPath)) {
            collect(glob($logPath . '/*.log'))
                ->sum(fn($file) => filesize($file));
        }
    }
}
