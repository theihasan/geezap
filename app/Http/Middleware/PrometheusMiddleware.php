<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
        $lines = explode("\n", $contents);
        
        foreach ($lines as $line) {
            if (strpos($line, 'cpu') === 0) {
                $parts = preg_split('/\s+/', trim($line));
                if (count($parts) >= 8) {
                    $cpu = $parts[0];
                    $modes = ['user', 'nice', 'system', 'idle', 'iowait', 'irq', 'softirq'];
                    
                    for ($i = 1; $i <= 7; $i++) {
                        if (isset($parts[$i]) && isset($modes[$i-1])) {
                            $this->nodeCpuSecondsTotal->incBy(
                                (float)$parts[$i] / 100, // Convert from jiffies to seconds
                                [$cpu, $modes[$i-1]]
                            );
                        }
                    }
                }
            }
        }
    }

    private function collectMemoryMetrics(): void
    {
        if (!file_exists('/proc/meminfo')) {
            return;
        }

        $contents = file_get_contents('/proc/meminfo');
        $lines = explode("\n", $contents);
        
        foreach ($lines as $line) {
            if (strpos($line, 'MemTotal:') === 0) {
                $parts = preg_split('/\s+/', trim($line));
                if (isset($parts[1])) {
                    $this->nodeMemoryMemTotalBytes->set((float)$parts[1] * 1024); // Convert kB to bytes
                }
            } elseif (strpos($line, 'MemFree:') === 0) {
                $parts = preg_split('/\s+/', trim($line));
                if (isset($parts[1])) {
                    $this->nodeMemoryMemFreeBytes->set((float)$parts[1] * 1024); // Convert kB to bytes
                }
            }
        }
    }

    private function collectDiskMetrics(): void
    {
        if (!file_exists('/proc/diskstats')) {
            return;
        }

        $contents = file_get_contents('/proc/diskstats');
        $lines = explode("\n", $contents);
        
        foreach ($lines as $line) {
            $parts = preg_split('/\s+/', trim($line));
            if (count($parts) >= 14) {
                $device = $parts[2];
                
                // Skip loop devices and other virtual devices
                if (strpos($device, 'loop') === 0 || strpos($device, 'ram') === 0) {
                    continue;
                }
                
                $readBytes = (float)$parts[5] * 512; // sectors to bytes
                $writeBytes = (float)$parts[9] * 512; // sectors to bytes
                $ioTimeMs = (float)$parts[12];
                
                $this->nodeDiskReadBytesTotal->incBy($readBytes, [$device]);
                $this->nodeDiskWrittenBytesTotal->incBy($writeBytes, [$device]);
                $this->nodeDiskIoTimeSecondsTotal->incBy($ioTimeMs / 1000, [$device]); // Convert ms to seconds
            }
        }
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);
        
        $labels = [
            'method' => $request->method(),
            'path' => $request->path(),
        ];

        $this->activeRequestsGauge->inc($labels);
        
        $response = $next($request);
        
        $endTime = microtime(true);
        $duration = $endTime - $startTime;
        
        $labelsWithStatus = array_merge($labels, [
            'status' => $response->getStatusCode()
        ]);

        $this->counter->inc($labelsWithStatus);
        $this->durationGauge->set($duration, $labelsWithStatus);
        $this->histogram->observe($duration, $labelsWithStatus);

        $this->activeRequestsGauge->dec($labels);
        $this->memoryUsageBytesGauge->set(memory_get_usage(true), ['real']);
        $this->memoryUsageBytesGauge->set(memory_get_peak_usage(true), ['peak']);
        $this->memoryUsageBytesGauge->set(memory_get_usage(false), ['allocated']);

        $this->collectNodeMetrics();

        return $response;
    }
}
