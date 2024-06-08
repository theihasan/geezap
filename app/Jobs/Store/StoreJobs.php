<?php

namespace App\Jobs\Store;

use App\Models\JobListing;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StoreJobs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 4;

    public $backoff = [45, 90, 180];

    /**
     * Create a new job instance.
     */
    public function __construct(public $response, public $jobCategory)
    {

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach ($this->response['data'] as $job) {
            $existingJob = JobListing::where('job_title', $job['job_title'])->exists();
            if (!$existingJob) {
                $joblisting = JobListing::create([
                    'employer_name' => $job['employer_name'],
                    'employer_logo' => $job['employer_logo'],
                    'employer_website' => $job['employer_website'],
                    'employer_company_type' => $job['employer_company_type'],
                    'publisher' => $job['job_publisher'],
                    'employment_type' => $job['job_employment_type'],
                    'job_title' => $job['job_title'],
                    'job_category' => $this->jobCategory,
                    'apply_link' => $job['job_apply_link'],
                    'latitude' => $job['job_latitude']?? null,
                    'longitude' => $job['job_longitude']?? null,
                    'description' => $job['job_description'],
                    'is_remote' => $job['job_is_remote'],
                    'city' => $job['job_city'],
                    'state' => $job['job_state'],
                    'country' => $job['job_country'],
                    'google_link' => $job['job_google_link'],
                    'posted_at' => isset($job['job_posted_at_datetime_utc'])
                                    ? Carbon::parse($job['job_posted_at_datetime_utc'])->toDateTimeString() : null,
                    'expaire_at' => isset($job['job_offer_expiration_datetime_utc'])
                                    ? Carbon::parse($job['job_offer_expiration_datetime_utc'])->toDateTimeString() : null,
                    'min_salary' => $job['job_min_salary'],
                    'max_salary' => $job['job_max_salary'],
                    'salary_currency' => $job['job_salary_currency'],
                    'salary_period' => $job['job_salary_period'],
                    'benefits' => isset($job['job_highlights']['Benefits'])
                                        ? json_encode($job['job_highlights']['Benefits']) : null,
                    'qualifications' => isset($job['job_highlights']['Qualifications'])
                                        ? json_encode($job['job_highlights']['Qualifications'])
                                        : null,
                    'responsibilities' => isset($job['job_highlights']['Responsibilities'])
                                        ? json_encode($job['job_highlights']['Responsibilities'])
                                        : null,

                    'required_experience' => $job['job_required_experience']['required_experience_in_months'],
                ]);
            }

        }

    }
}
