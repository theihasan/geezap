<?php

namespace Geezap\ContentFormatter\Console\Commands;

use Geezap\ContentFormatter\Jobs\FormatContentJob;
use Geezap\ContentFormatter\Models\Package;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Traits\Batchable;

class ProcessPackagesCommand extends Command
{
    use Batchable;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'content-formatter:process-packages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process pending and failed packages by dispatching FormatContentJob';

    /**
     * Execute the console command.
     *
     * @return int
     */
     public function handle()
     {
         $pendingCount = Package::pending()->count();
         $failedCount = Package::failed()->count();

         $this->info("Found {$pendingCount} pending and {$failedCount} failed packages to process.");

         if ($pendingCount + $failedCount === 0) {
             $this->info('No packages to process.');

             return 0;
         }

         $packages = Package::query()
             ->where('status', 'pending')
             ->orWhere('status', 'failed')
             ->get();

         $bar = $this->output->createProgressBar($packages->count());
         $bar->start();

         $jobs = $packages->map(function ($package) use ($bar) {
             $this->line(" Processing package ID: {$package->id}");
             $bar->advance();
             return new FormatContentJob($package->id);
         })->toArray();

         Bus::batch($jobs)
             ->name('Format Content Batch ' . now()->format('Y-m-d H:i:s'))
             ->dispatch();

         $bar->finish();
         $this->newLine();
         $this->info('All packages have been queued for processing in batch.');

         return 0;
     }
}
