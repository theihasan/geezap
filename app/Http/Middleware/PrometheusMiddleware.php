<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Prometheus\CollectorRegistry;
use Symfony\Component\HttpFoundation\Response;

class PrometheusMiddleware
{
    private $counter;
    private $durationGauge;
    private $activeRequestsGauge;
    private $memoryUsageBytesGauge;
    private $histogram;
    private $nodeCpuSecondsTotal;
    private $nodeMemoryMemTotalBytes;
    private $nodeMemoryMemFreeBytes;
    private $nodeDiskReadBytesTotal;
    private $nodeDiskWrittenBytesTotal;
    private $nodeDiskIoTimeSecondsTotal;
    private $apiRequestsCounter;
    private $userSessionsGauge;
    private $responseTimeHistogram;
    private $requestSizeHistogram;
    private $responseSizeHistogram;
    private $errorRateCounter;
    private $throughputGauge;
    private $concurrentUsersGauge;
    private $phpMemoryUsageGauge;
    private $phpProcessesGauge;

    public function __construct(private CollectorRegistry $registry)
    {
        $this->initializeMetrics();
    }

    private function initializeMetrics(): void
    {
        $this->initializeCounterMetrics();
        $this->initializeDurationGaugeMetrics();
        $this->initializeActiveRequestsGaugeMetrics();
        $this->initializeMemoryUsageBytesGaugeMetrics();
        $this->initializeHistogramMetrics();
        $this->initializeNodeCpuSecondsTotal();
        $this->initializeNodeMemoryMetrics();
        $this->initializeNodeDiskMetrics();
        $this->initializeApplicationMetrics();
        $this->initializePerformanceMetrics();
        $this->initializeUserMetrics();
    }

    private function initializeCounterMetrics(): void
    {
        $this->counter = $this->registry->getOrRegisterCounter(
            'geezap',
            'http_requests_total',
            'Total number of HTTP requests',
            ['method', 'path', 'status']
        );
    }

    private function initializeNodeCpuSecondsTotal(): void
    {
        $this->nodeCpuSecondsTotal = $this->registry->getOrRegisterCounter(
            'geezap',
            'node_cpu_seconds_total',
            'Total CPU seconds',
            ['cpu', 'mode']
        );
    }

    private function initializeNodeMemoryMetrics(): void
    {
        $this->nodeMemoryMemTotalBytes = $this->registry->getOrRegisterGauge(
            'geezap',
            'node_memory_MemTotal_bytes',
            'Total memory in bytes',
            []
        );

        $this->nodeMemoryMemFreeBytes = $this->registry->getOrRegisterGauge(
            'geezap',
            'node_memory_MemFree_bytes',
            'Free memory in bytes',
            []
        );
    }

    private function initializeNodeDiskMetrics(): void
    {
        $this->nodeDiskReadBytesTotal = $this->registry->getOrRegisterCounter(
            'geezap',
            'node_disk_read_bytes_total',
            'Total disk read bytes',
            ['device']
        );

        $this->nodeDiskWrittenBytesTotal = $this->registry->getOrRegisterCounter(
            'geezap',
            'node_disk_written_bytes_total',
            'Total disk written bytes',
            ['device']
        );

        $this->nodeDiskIoTimeSecondsTotal = $this->registry->getOrRegisterCounter(
            'geezap',
            'node_disk_io_time_seconds_total',
            'Total disk I/O time in seconds',
            ['device']
        );
    }

    private function initializeDurationGaugeMetrics(): void
    {
        $this->durationGauge = $this->registry->getOrRegisterGauge(
            'geezap',
            'http_requests_duration_seconds',
            'Duration of HTTP requests',
            ['method', 'path', 'status']
        );
    }

    private function initializeActiveRequestsGaugeMetrics(): void
    {
        $this->activeRequestsGauge = $this->registry->getOrRegisterGauge(
            'geezap',
            'http_active_requests',
            'Number of active HTTP requests',
            ['method', 'path']
        );
    }

    private function initializeMemoryUsageBytesGaugeMetrics(): void
    {
        $this->memoryUsageBytesGauge = $this->registry->getOrRegisterGauge(
            'geezap',
            'memory_usage_bytes',
            'Memory usage in bytes',
            ['type']
        );
    }

    private function initializeHistogramMetrics(): void
    {
        $this->histogram = $this->registry->getOrRegisterHistogram(
            'geezap',
            'http_requests_duration_seconds',
            'Histogram of HTTP request durations',
            ['method', 'path', 'status'],
            [0.005, 0.01, 0.025, 0.05, 0.1, 0.25, 0.5, 1, 2.5, 5, 10]
        );
    }

    private function initializeApplicationMetrics(): void
    {
        $this->apiRequestsCounter = $this->registry->getOrRegisterCounter(
            'geezap',
            'api_requests_total',
            'Total API requests by endpoint',
            ['endpoint', 'method', 'status', 'user_type']
        );

        $this->errorRateCounter = $this->registry->getOrRegisterCounter(
            'geezap',
            'http_errors_total',
            'Total HTTP errors',
            ['method', 'path', 'status', 'error_type']
        );

        $this->throughputGauge = $this->registry->getOrRegisterGauge(
            'geezap',
            'requests_per_second',
            'Current requests per second',
            ['method']
        );
    }

    private function initializePerformanceMetrics(): void
    {
        $this->responseTimeHistogram = $this->registry->getOrRegisterHistogram(
            'geezap',
            'response_time_seconds',
            'Response time distribution',
            ['endpoint', 'method'],
            [0.001, 0.005, 0.01, 0.025, 0.05, 0.1, 0.25, 0.5, 1, 2.5, 5, 10]
        );

        $this->requestSizeHistogram = $this->registry->getOrRegisterHistogram(
            'geezap',
            'request_size_bytes',
            'Request size distribution',
            ['method', 'endpoint'],
            [100, 1000, 10000, 100000, 1000000, 10000000]
        );

        $this->responseSizeHistogram = $this->registry->getOrRegisterHistogram(
            'geezap',
            'response_size_bytes',
            'Response size distribution',
            ['method', 'endpoint', 'status'],
            [100, 1000, 10000, 100000, 1000000, 10000000]
        );

        $this->phpMemoryUsageGauge = $this->registry->getOrRegisterGauge(
            'geezap',
            'php_memory_usage_bytes',
            'PHP memory usage',
            ['type']
        );

        $this->phpProcessesGauge = $this->registry->getOrRegisterGauge(
            'geezap',
            'php_processes_active',
            'Active PHP processes',
            []
        );
    }

    private function initializeUserMetrics(): void
    {
        $this->userSessionsGauge = $this->registry->getOrRegisterGauge(
            'geezap',
            'user_sessions_active',
            'Active user sessions',
            ['user_type']
        );

        $this->concurrentUsersGauge = $this->registry->getOrRegisterGauge(
            'geezap',
            'concurrent_users',
            'Concurrent users',
            ['authenticated']
        );
    }

    private function collectNodeMetrics(): void
    {
        $this->collectCpuMetrics();
        $this->collectMemoryMetrics();
        $this->collectDiskMetrics();
    }

    private function collectCpuMetrics(): void
    {
        if (!file_exists('/proc/stat')) {
            return;
        }

        $contents = file_get_contents('/proc/stat');
        $modes = ['user', 'nice', 'system', 'idle', 'iowait', 'irq', 'softirq'];
        
        collect(explode("\n", $contents))
            ->filter(fn($line) => str_starts_with($line, 'cpu'))
            ->map(fn($line) => preg_split('/\s+/', trim($line)))
            ->filter(fn($parts) => count($parts) >= 8)
            ->each(function ($parts) use ($modes) {
                $cpu = $parts[0];
                collect($modes)
                    ->take(7)
                    ->each(function ($mode, $index) use ($parts, $cpu) {
                        $partIndex = $index + 1;
                        if (isset($parts[$partIndex])) {
                            $this->nodeCpuSecondsTotal->incBy(
                                (float)$parts[$partIndex] / 100,
                                [$cpu, $mode]
                            );
                        }
                    });
            });
    }

    private function collectMemoryMetrics(): void
    {
        if (!file_exists('/proc/meminfo')) {
            return;
        }

        $contents = file_get_contents('/proc/meminfo');
        
        collect(explode("\n", $contents))
            ->map(fn($line) => preg_split('/\s+/', trim($line)))
            ->filter(fn($parts) => isset($parts[1]))
            ->each(function ($parts) {
                match ($parts[0]) {
                    'MemTotal:' => $this->nodeMemoryMemTotalBytes->set((float)$parts[1] * 1024),
                    'MemFree:' => $this->nodeMemoryMemFreeBytes->set((float)$parts[1] * 1024),
                    default => null
                };
            });
    }

    private function collectDiskMetrics(): void
    {
        if (!file_exists('/proc/diskstats')) {
            return;
        }

        $contents = file_get_contents('/proc/diskstats');
        
        collect(explode("\n", $contents))
            ->map(fn($line) => preg_split('/\s+/', trim($line)))
            ->filter(fn($parts) => count($parts) >= 14)
            ->reject(fn($parts) => str_starts_with($parts[2], 'loop') || str_starts_with($parts[2], 'ram'))
            ->each(function ($parts) {
                $device = $parts[2];
                $readBytes = (float)$parts[5] * 512;
                $writeBytes = (float)$parts[9] * 512;
                $ioTimeMs = (float)$parts[12];
                
                $this->nodeDiskReadBytesTotal->incBy($readBytes, [$device]);
                $this->nodeDiskWrittenBytesTotal->incBy($writeBytes, [$device]);
                $this->nodeDiskIoTimeSecondsTotal->incBy($ioTimeMs / 1000, [$device]);
            });
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);
        $startMemory = memory_get_usage(true);
        
        $labels = [
            'method' => $request->method(),
            'path' => $this->normalizePath($request->path()),
        ];

        $this->activeRequestsGauge->inc($labels);
        $this->collectUserMetrics($request);
        
        $response = $next($request);
        
        $endTime = microtime(true);
        $duration = $endTime - $startTime;
        $endMemory = memory_get_usage(true);
        
        $labelsWithStatus = array_merge($labels, [
            'status' => $response->getStatusCode()
        ]);

        $this->recordRequestMetrics($request, $response, $duration, $labelsWithStatus);
        $this->recordPerformanceMetrics($request, $response, $duration, $startMemory, $endMemory);
        $this->recordErrorMetrics($request, $response);
        
        $this->activeRequestsGauge->dec($labels);
        $this->collectSystemMetrics();
        $this->collectNodeMetrics();

        return $response;
    }

    private function normalizePath(string $path): string
    {
        // Replace dynamic segments with placeholders
        $path = preg_replace('/\/\d+/', '/{id}', $path);
        $path = preg_replace('/\/[a-f0-9-]{36}/', '/{uuid}', $path);
        return $path ?: '/';
    }

    private function recordRequestMetrics(Request $request, Response $response, float $duration, array $labels): void
    {
        $this->counter->inc($labels);
        $this->durationGauge->set($duration, $labels);
        $this->histogram->observe($duration, $labels);

        // API-specific metrics
        if ($request->is('api/*')) {
            $userType = Auth::check() ? 'authenticated' : 'anonymous';
            $apiLabels = [
                'endpoint' => $this->normalizePath($request->path()),
                'method' => $request->method(),
                'status' => $response->getStatusCode(),
                'user_type' => $userType
            ];
            $this->apiRequestsCounter->inc($apiLabels);
        }
    }

    private function recordPerformanceMetrics(Request $request, Response $response, float $duration, int $startMemory, int $endMemory): void
    {
        $endpoint = $this->normalizePath($request->path());
        $method = $request->method();

        // Response time
        $this->responseTimeHistogram->observe($duration, ['endpoint' => $endpoint, 'method' => $method]);

        // Request size
        $requestSize = strlen($request->getContent());
        if ($requestSize > 0) {
            $this->requestSizeHistogram->observe($requestSize, ['method' => $method, 'endpoint' => $endpoint]);
        }

        // Response size
        $responseSize = strlen($response->getContent());
        if ($responseSize > 0) {
            $this->responseSizeHistogram->observe($responseSize, [
                'method' => $method,
                'endpoint' => $endpoint,
                'status' => $response->getStatusCode()
            ]);
        }

        // Memory usage for this request
        $memoryUsed = $endMemory - $startMemory;
        if ($memoryUsed > 0) {
            $this->phpMemoryUsageGauge->set($memoryUsed, ['type' => 'request']);
        }
    }

    private function recordErrorMetrics(Request $request, Response $response): void
    {
        $statusCode = $response->getStatusCode();
        
        if ($statusCode >= 400) {
            $errorType = match (true) {
                $statusCode >= 500 => 'server_error',
                $statusCode >= 400 => 'client_error',
                default => 'unknown'
            };

            $this->errorRateCounter->inc([
                'method' => $request->method(),
                'path' => $this->normalizePath($request->path()),
                'status' => $statusCode,
                'error_type' => $errorType
            ]);
        }
    }

    private function collectUserMetrics(Request $request): void
    {
        if (Auth::check()) {
            $this->userSessionsGauge->inc(['user_type' => 'authenticated']);
            $this->concurrentUsersGauge->inc(['authenticated' => 'true']);
        } else {
            $this->userSessionsGauge->inc(['user_type' => 'anonymous']);
            $this->concurrentUsersGauge->inc(['authenticated' => 'false']);
        }
    }

    private function collectSystemMetrics(): void
    {
        // PHP memory usage
        $this->phpMemoryUsageGauge->set(memory_get_usage(true), ['type' => 'real']);
        $this->phpMemoryUsageGauge->set(memory_get_peak_usage(true), ['type' => 'peak']);
        $this->phpMemoryUsageGauge->set(memory_get_usage(false), ['type' => 'allocated']);

        // Database connections
        try {
            $connections = DB::getConnections();
            foreach ($connections as $name => $connection) {
                $pdo = $connection->getPdo();
                if ($pdo) {
                    // This is a simple way to check if connection is active
                    $this->registry->getOrRegisterGauge(
                        'geezap',
                        'database_connections_active',
                        'Active database connections',
                        ['connection']
                    )->set(1, ['connection' => $name]);
                }
            }
        } catch (\Exception $e) {
            // Log error but don't break the request
            Log::warning('Failed to collect database connection metrics: ' . $e->getMessage());
        }

        // PHP process count (approximate)
        if (function_exists('sys_getloadavg')) {
            $load = sys_getloadavg();
            if ($load !== false) {
                $this->registry->getOrRegisterGauge(
                    'geezap',
                    'system_load_average',
                    'System load average',
                    ['period']
                )->set($load[0], ['period' => '1m']);
                
                $this->registry->getOrRegisterGauge(
                    'geezap',
                    'system_load_average',
                    'System load average',
                    ['period']
                )->set($load[1], ['period' => '5m']);
                
                $this->registry->getOrRegisterGauge(
                    'geezap',
                    'system_load_average',
                    'System load average',
                    ['period']
                )->set($load[2], ['period' => '15m']);
            }
        }
    }
}
