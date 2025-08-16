<?php

namespace App\Providers;

use Prometheus\Storage\Redis;
use Prometheus\CollectorRegistry;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Cache\Events\CacheHit;
use Illuminate\Cache\Events\CacheMissed;
use Illuminate\Cache\Events\KeyWritten;

class PrometheusServiceProvider extends ServiceProvider
{
    private CollectorRegistry $registry;

    /**
     * Register services.
     */
    public function register()
    {
        $this->app->singleton(CollectorRegistry::class, function ($app) {
            try {
                $redis = new Redis([
                    'host' => config('database.redis.default.host'),
                    'port' => config('database.redis.default.port'),
                    'password' => config('database.redis.default.password'),
                    'timeout' => 0.1,
                    'read_timeout' => '10',
                    'persistent_connections' => false
                ]);

                return new CollectorRegistry($redis);
            } catch (\Exception $e) {
                // If Redis is not available, return a null registry or use in-memory storage
                return new CollectorRegistry(new \Prometheus\Storage\InMemory());
            }
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        try {
            $this->registry = app(CollectorRegistry::class);

            $this->registerDatabaseMetrics();
            $this->registerQueueMetrics();
            $this->registerCacheMetrics();
            $this->registerApplicationMetrics();
        } catch (\Exception $e) {
            // Log the error but don't break the application
            \Illuminate\Support\Facades\Log::warning('Prometheus metrics registration failed: ' . $e->getMessage());
        }
    }

    /**
     * @throws \Prometheus\Exception\MetricsRegistrationException
     */
    private function registerDatabaseMetrics(): void
    {
        $queryCounter = $this->registry->getOrRegisterCounter(
            'geezap',
            'database_queries_total',
            'Total number of database queries',
            ['connection', 'type']
        );

        $queryDurationHistogram = $this->registry->getOrRegisterHistogram(
            'geezap',
            'database_query_duration_seconds',
            'Database query execution time',
            ['connection', 'type'],
            [0.001, 0.005, 0.01, 0.025, 0.05, 0.1, 0.25, 0.5, 1, 2.5, 5]
        );

        $activeConnectionsGauge = $this->registry->getOrRegisterGauge(
            'geezap',
            'database_connections_active',
            'Number of active database connections',
            ['connection']
        );

        DB::listen(function (QueryExecuted $query) use ($queryCounter, $queryDurationHistogram) {
            $queryType = strtolower(explode(' ', trim($query->sql))[0]);
            $labels = ['connection' => $query->connectionName, 'type' => $queryType];

            $queryCounter->inc($labels);
            $queryDurationHistogram->observe($query->time / 1000, $labels);
        });
    }

    private function registerQueueMetrics(): void
    {
        $jobProcessedCounter = $this->registry->getOrRegisterCounter(
            'geezap',
            'queue_jobs_processed_total',
            'Total number of processed queue jobs',
            ['queue', 'job', 'status']
        );

        $jobDurationHistogram = $this->registry->getOrRegisterHistogram(
            'geezap',
            'queue_job_duration_seconds',
            'Queue job processing time',
            ['queue', 'job'],
            [0.1, 0.5, 1, 2.5, 5, 10, 30, 60, 300]
        );

        $activeJobsGauge = $this->registry->getOrRegisterGauge(
            'geezap',
            'queue_jobs_active',
            'Number of currently processing jobs',
            ['queue']
        );

        $queueSizeGauge = $this->registry->getOrRegisterGauge(
            'geezap',
            'queue_size',
            'Number of jobs waiting in queue',
            ['queue']
        );

        Queue::before(function (JobProcessing $event) use ($activeJobsGauge) {
            $activeJobsGauge->inc(['queue' => $event->job->getQueue()]);
        });

        Queue::after(function (JobProcessed $event) use ($jobProcessedCounter, $activeJobsGauge) {
            $labels = [
                'queue' => $event->job->getQueue(),
                'job' => $event->job->resolveName(),
                'status' => 'success'
            ];
            $jobProcessedCounter->inc($labels);
            $activeJobsGauge->dec(['queue' => $event->job->getQueue()]);
        });

        Queue::failing(function (JobFailed $event) use ($jobProcessedCounter, $activeJobsGauge) {
            $labels = [
                'queue' => $event->job->getQueue(),
                'job' => $event->job->resolveName(),
                'status' => 'failed'
            ];
            $jobProcessedCounter->inc($labels);
            $activeJobsGauge->dec(['queue' => $event->job->getQueue()]);
        });
    }

    private function registerCacheMetrics(): void
    {
        $cacheHitsCounter = $this->registry->getOrRegisterCounter(
            'geezap',
            'cache_hits_total',
            'Total number of cache hits',
            ['store']
        );

        $cacheMissesCounter = $this->registry->getOrRegisterCounter(
            'geezap',
            'cache_misses_total',
            'Total number of cache misses',
            ['store']
        );

        $cacheWritesCounter = $this->registry->getOrRegisterCounter(
            'geezap',
            'cache_writes_total',
            'Total number of cache writes',
            ['store']
        );

        \Illuminate\Support\Facades\Event::listen(CacheHit::class, function ($event) use ($cacheHitsCounter) {
            $cacheHitsCounter->inc(['store' => $event->storeName ?? 'default']);
        });

        \Illuminate\Support\Facades\Event::listen(CacheMissed::class, function ($event) use ($cacheMissesCounter) {
            $cacheMissesCounter->inc(['store' => $event->storeName ?? 'default']);
        });

        \Illuminate\Support\Facades\Event::listen(KeyWritten::class, function ($event) use ($cacheWritesCounter) {
            $cacheWritesCounter->inc(['store' => $event->storeName ?? 'default']);
        });
    }

    private function registerApplicationMetrics(): void
    {
        $this->registry->getOrRegisterCounter(
            'geezap',
            'user_registrations_total',
            'Total number of user registrations',
            []
        );

        $this->registry->getOrRegisterCounter(
            'geezap',
            'job_applications_total',
            'Total number of job applications',
            ['status']
        );

        $this->registry->getOrRegisterCounter(
            'geezap',
            'cover_letters_generated_total',
            'Total number of cover letters generated',
            ['status']
        );

        $this->registry->getOrRegisterGauge(
            'geezap',
            'active_users',
            'Number of active users',
            ['period']
        );

        $this->registry->getOrRegisterGauge(
            'geezap',
            'job_listings_active',
            'Number of active job listings',
            []
        );

        $this->registry->getOrRegisterCounter(
            'geezap',
            'api_requests_total',
            'Total number of API requests',
            ['endpoint', 'method', 'status']
        );

        $this->registry->getOrRegisterCounter(
            'geezap',
            'exceptions_total',
            'Total number of exceptions',
            ['type', 'class', 'file']
        );

        $this->registry->getOrRegisterHistogram(
            'geezap',
            'ai_service_response_time_seconds',
            'AI service response time',
            ['service', 'operation'],
            [0.1, 0.5, 1, 2, 5, 10, 30]
        );
    }
}
