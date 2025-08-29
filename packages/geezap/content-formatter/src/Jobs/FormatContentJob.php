<?php

namespace Geezap\ContentFormatter\Jobs;

use App\Models\JobListing;
use Geezap\ContentFormatter\Models\Package;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\ThrottlesExceptions;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Prism\Prism;

class FormatContentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public $tries = 3;
    public $timeout = 120;
    public $maxExceptions = 3;
    public $backoff = [10, 30, 60];
    
    public function __construct(
        public int $packageId
    ) {}

    public function handle(): void
    {
        try {
            $package = Package::findOrFail($this->packageId);
            $package->update(['status' => 'processing']);

            $prompt = $this->buildPrompt($package->content);
            
            $response = Prism::text()
                ->using('deepseek', 'deepseek-chat')
                ->withPrompt($prompt)
                ->generate();

            $formattedContent = $response->json();
            $jobData = $this->parseFormattedContent($formattedContent);

            if ($jobData) {
                $jobListing = JobListing::query()->create($jobData);
                
                $this->package->update([
                    'status' => 'completed',
                    'formatted_content' => $formattedContent,
                    'metadata' => [
                        'job_listing_id' => $jobListing->id,
                        'processed_at' => now(),
                    ],
                ]);

                Log::info('Content formatted and job listing created', [
                    'package_id' => $this->package->id,
                    'job_listing_id' => $jobListing->id,
                ]);
            } else {
                throw new \Exception('Failed to parse formatted content');
            }

        } catch (\Exception $e) {
            $this->package->update([
                'status' => 'failed',
                'metadata' => [
                    'error' => $e->getMessage(),
                    'failed_at' => now(),
                ],
            ]);

            Log::error('Content formatting failed', [
                'package_id' => $this->package->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    private function buildPrompt(string $content): string
    {
        return "Please format the following job posting content into a structured JSON format with these fields:
        
        - job_title: string
        - employer_name: string  
        - description: string
        - employment_type: string (full-time, part-time, contract, etc.)
        - city: string
        - state: string
        - country: string
        - is_remote: boolean
        - benefits: array of strings
        - qualifications: array of strings
        - responsibilities: array of strings
        - min_salary: integer (optional)
        - max_salary: integer (optional)
        - salary_currency: string (optional)
        - salary_period: string (optional, yearly, monthly, hourly)
        - job_category: integer (1-15, use your best judgment for category)
        - posted_at: datetime (use current date if not specified)
        - expired_at: datetime (30 days from posted_at if not specified)

        Content to format:
        {$content}

        Return only valid JSON without any additional text or formatting.";
    }

    private function parseFormattedContent(string $formattedContent): ?array
    {
        try {
            $data = json_decode($formattedContent, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Invalid JSON response');
            }

            $defaults = [
                'publisher' => 'Facebook Group',
                'job_id' => uniqid('cf_'),
                'posted_at' => now(),
                'expired_at' => now()->addDays(30),
                'employer_company_type' => 'Unknown',
                'apply_link' => '#',
                'required_experience' => 0,
            ];

            return array_merge($defaults, $data);
            
        } catch (\Exception $e) {
            Log::error('Failed to parse formatted content', [
                'content' => $formattedContent,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }
    
    public function middleware(): array
    {
        return [
            new ThrottlesExceptions(
                maxAttempts: 2,
                decayMinutes: 5
            )
        ];
    }
}
