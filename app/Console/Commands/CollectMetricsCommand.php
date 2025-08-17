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

}
