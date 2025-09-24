<?php

namespace App\Console\Commands;

use App\Jobs\SubmitUrlToGoogleIndexing;
use App\Models\JobListing;
use App\Services\GoogleIndexingService;
use Illuminate\Console\Command;

class GoogleIndexingSubmitUrl extends Command
{
    protected $signature = 'google-indexing:submit 
                            {--url= : Specific URL to submit}
                            {--type=URL_UPDATED : Type of submission (URL_UPDATED or URL_DELETED)}
                            {--batch : Submit all job URLs}
                            {--recent= : Submit recent jobs (number of days)}
                            {--sync : Submit synchronously instead of using queue}';

    protected $description = 'Submit URLs to Google Indexing API';

    public function handle(GoogleIndexingService $indexingService): int
    {
        if (!config('services.google_indexing.enabled')) {
            $this->error('Google Indexing API is not enabled. Set GOOGLE_INDEXING_ENABLED=true in your .env file.');
            return 1;
        }

        if (!$indexingService->isConfigured()) {
            $this->error('Google Indexing API is not properly configured. Check your service account key file.');
            return 1;
        }

        $url = $this->option('url');
        $type = $this->option('type');
        $batch = $this->option('batch');
        $recent = $this->option('recent');
        $sync = $this->option('sync');

        if ($url) {
            return $this->submitSingleUrl($url, $type, $sync, $indexingService);
        }

        if ($batch) {
            return $this->submitAllJobs($type, $sync);
        }

        if ($recent) {
            return $this->submitRecentJobs((int) $recent, $type, $sync);
        }

        $this->error('Please specify --url, --batch, or --recent option.');
        return 1;
    }

    private function submitSingleUrl(string $url, string $type, bool $sync, GoogleIndexingService $indexingService): int
    {
        $this->info("Submitting URL: {$url} (Type: {$type})");

        if ($sync) {
            $success = $indexingService->submitUrl($url, $type);
            if ($success) {
                $this->info('URL submitted successfully!');
                return 0;
            } else {
                $this->error('Failed to submit URL.');
                return 1;
            }
        } else {
            SubmitUrlToGoogleIndexing::dispatch($url, $type);
            $this->info('URL submission job dispatched to queue.');
            return 0;
        }
    }

    private function submitAllJobs(string $type, bool $sync): int
    {
        $this->info('Submitting all job URLs...');

        $jobs = JobListing::all();
        $this->output->progressStart($jobs->count());

        $successCount = 0;
        $failureCount = 0;

        foreach ($jobs as $job) {
            try {
                $url = route('job.show', $job->slug);
                
                if ($sync) {
                    $success = app(GoogleIndexingService::class)->submitUrl($url, $type);
                    if ($success) {
                        $successCount++;
                    } else {
                        $failureCount++;
                    }
                } else {
                    SubmitUrlToGoogleIndexing::dispatch($url, $type);
                    $successCount++;
                }
            } catch (\Exception $e) {
                $failureCount++;
                $this->line("\nError with job {$job->id}: " . $e->getMessage());
            }

            $this->output->progressAdvance();
        }

        $this->output->progressFinish();
        $this->info("\nCompleted! Success: {$successCount}, Failures: {$failureCount}");

        return $failureCount > 0 ? 1 : 0;
    }

    private function submitRecentJobs(int $days, string $type, bool $sync): int
    {
        $this->info("Submitting jobs from the last {$days} days...");

        $jobs = JobListing::where('created_at', '>=', now()->subDays($days))->get();
        
        if ($jobs->isEmpty()) {
            $this->info('No recent jobs found.');
            return 0;
        }

        $this->output->progressStart($jobs->count());

        $successCount = 0;
        $failureCount = 0;

        foreach ($jobs as $job) {
            try {
                $url = route('job.show', $job->slug);
                
                if ($sync) {
                    $success = app(GoogleIndexingService::class)->submitUrl($url, $type);
                    if ($success) {
                        $successCount++;
                    } else {
                        $failureCount++;
                    }
                } else {
                    SubmitUrlToGoogleIndexing::dispatch($url, $type);
                    $successCount++;
                }
            } catch (\Exception $e) {
                $failureCount++;
                $this->line("\nError with job {$job->id}: " . $e->getMessage());
            }

            $this->output->progressAdvance();
        }

        $this->output->progressFinish();
        $this->info("\nCompleted! Success: {$successCount}, Failures: {$failureCount}");

        return $failureCount > 0 ? 1 : 0;
    }
}
