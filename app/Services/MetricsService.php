<?php

namespace App\Services;

use Prometheus\CollectorRegistry;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Queue;

class MetricsService
{
    public function __construct(private CollectorRegistry $registry)
    {
    }

    /**
     * @throws \Prometheus\Exception\MetricsRegistrationException
     */
    public function recordUserRegistration(): void
    {
        $this->registry->getOrRegisterCounter(
            'geezap',
            'user_registrations_total',
            'Total number of user registrations',
            []
        )->inc();
    }

    /**
     * @throws \Prometheus\Exception\MetricsRegistrationException
     */
    public function recordCoverLetterGeneration(string $status = 'success'): void
    {
        $this->registry->getOrRegisterCounter(
            'geezap',
            'cover_letters_generated_total',
            'Total number of cover letters generated',
            ['status']
        )->inc(['status' => $status]);
    }

    /**
     * @throws \Prometheus\Exception\MetricsRegistrationException
     */
    public function recordAIServiceCall(string $service, string $operation, float $duration): void
    {
        $this->registry->getOrRegisterHistogram(
            'geezap',
            'ai_service_response_time_seconds',
            'AI service response time',
            ['service', 'operation'],
            [0.1, 0.5, 1, 2, 5, 10, 30]
        )->observe($duration, ['service' => $service, 'operation' => $operation]);
    }

    /**
     * @throws \Prometheus\Exception\MetricsRegistrationException
     */
    public function recordException(string $type, string $class, string $file): void
    {
        $this->registry->getOrRegisterCounter(
            'geezap',
            'exceptions_total',
            'Total number of exceptions',
            ['type', 'class', 'file']
        )->inc(['type' => $type, 'class' => $class, 'file' => basename($file)]);
    }

    /**
     * @throws \Prometheus\Exception\MetricsRegistrationException
     */
    public function updateActiveUsers(int $count, string $period = 'current'): void
    {
        $this->registry->getOrRegisterGauge(
            'geezap',
            'active_users',
            'Number of active users',
            ['period']
        )->set($count, ['period' => $period]);
    }

    /**
     * @throws \Prometheus\Exception\MetricsRegistrationException
     */
    public function updateActiveJobListings(int $count): void
    {
        $this->registry->getOrRegisterGauge(
            'geezap',
            'job_listings_active',
            'Number of active job listings',
            []
        )->set($count);
    }

    public function collectBusinessMetrics(): void
    {
        try {
            // Active job listings
            $activeJobs = DB::table('job_listings')
                ->where('status', 'active')
                ->count();
            $this->updateActiveJobListings($activeJobs);

            // Active users (last 24 hours)
            $activeUsers24h = DB::table('users')
                ->where('last_login_at', '>=', now()->subDay())
                ->count();
            $this->updateActiveUsers($activeUsers24h, '24h');

            // Active users (last 7 days)
            $activeUsers7d = DB::table('users')
                ->where('last_login_at', '>=', now()->subWeek())
                ->count();
            $this->updateActiveUsers($activeUsers7d, '7d');

            // Queue sizes
            $this->collectQueueSizes();

        } catch (\Exception $e) {
            $this->recordException('business_metrics', get_class($e), __FILE__);
        }
    }

    /**
     * @throws \Prometheus\Exception\MetricsRegistrationException
     */
    private function collectQueueSizes(): void
    {
        $queueSizeGauge = $this->registry->getOrRegisterGauge(
            'geezap',
            'queue_size',
            'Number of jobs waiting in queue',
            ['queue']
        );

        collect(['default', 'high', 'low', 'emails', 'notifications'])
            ->each(function ($queue) use ($queueSizeGauge) {
                try {
                    $size = Queue::size($queue);
                    $queueSizeGauge->set($size, ['queue' => $queue]);
                } catch (\Exception $e) {
                    // Queue might not exist, that's ok
                }
            });
    }

    public function recordEmailSent(string $type, string $status = 'sent'): void
    {
        $this->registry->getOrRegisterCounter(
            'geezap',
            'emails_sent_total',
            'Total emails sent',
            ['type', 'status']
        )->inc(['type' => $type, 'status' => $status]);
    }

    public function recordNotificationSent(string $channel, string $status = 'sent'): void
    {
        $this->registry->getOrRegisterCounter(
            'geezap',
            'notifications_sent_total',
            'Total notifications sent',
            ['channel', 'status']
        )->inc(['channel' => $channel, 'status' => $status]);
    }

    /**
     * @throws \Prometheus\Exception\MetricsRegistrationException
     */
    public function recordSearchQuery(string $type, int $results): void
    {
        $this->registry->getOrRegisterCounter(
            'geezap',
            'search_queries_total',
            'Total search queries',
            ['type']
        )->inc(['type' => $type]);

        $this->registry->getOrRegisterHistogram(
            'geezap',
            'search_results_count',
            'Number of search results',
            ['type'],
            [0, 1, 5, 10, 25, 50, 100, 250, 500, 1000]
        )->observe($results, ['type' => $type]);
    }

    /**
     * @throws \Prometheus\Exception\MetricsRegistrationException
     */
    public function recordFileUpload(string $type, int $size): void
    {
        $this->registry->getOrRegisterCounter(
            'geezap',
            'file_uploads_total',
            'Total file uploads',
            ['type']
        )->inc(['type' => $type]);

        $this->registry->getOrRegisterHistogram(
            'geezap',
            'file_upload_size_bytes',
            'File upload sizes',
            ['type'],
            [1024, 10240, 102400, 1048576, 10485760, 104857600]
        )->observe($size, ['type' => $type]);
    }
}
