<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\JobCategory;
use App\Models\JobListing;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class JobListingFactory extends Factory
{
    protected $model = JobListing::class;

    public function definition(): array
    {
        $jobTitle = $this->faker->jobTitle();
        
        return [
            'uuid' => Str::uuid(),
            'slug' => Str::slug($jobTitle . '-' . time()),
            'employer_name' => $this->faker->company(),
            'employer_logo' => $this->faker->imageUrl(200, 200),
            'employer_website' => $this->faker->url(),
            'employer_company_type' => $this->faker->randomElement(['Startup', 'Enterprise', 'Agency', 'Non-profit']),
            'publisher' => $this->faker->randomElement(['LinkedIn', 'Indeed', 'Glassdoor', 'Direct']),
            'employment_type' => $this->faker->randomElement(['Full-time', 'Part-time', 'Contract', 'Temporary']),
            'job_title' => $jobTitle,
            'job_category' => JobCategory::factory(),
            'apply_link' => $this->faker->url(),
            'description' => $this->faker->paragraphs(3, true),
            'is_remote' => $this->faker->boolean(),
            'city' => $this->faker->city(),
            'state' => $this->faker->state(),
            'country' => $this->faker->country(),
            'google_link' => $this->faker->url(),
            'posted_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'expired_at' => $this->faker->dateTimeBetween('+1 day', '+30 days'),
            'min_salary' => $this->faker->numberBetween(30000, 70000),
            'max_salary' => $this->faker->numberBetween(80000, 150000),
            'salary_currency' => $this->faker->currencyCode(),
            'salary_period' => $this->faker->randomElement(['yearly', 'monthly', 'weekly', 'hourly']),
            'benefits' => $this->faker->randomElements(['Health Insurance', 'Dental Insurance', 'Vision Insurance', 'Retirement Plan', 'Paid Time Off', 'Remote Work'], $this->faker->numberBetween(2, 5)),
            'qualifications' => $this->faker->randomElements(['Bachelor\'s Degree', 'Master\'s Degree', '2+ years experience', '5+ years experience', 'Certification'], $this->faker->numberBetween(2, 4)),
            'responsibilities' => $this->faker->randomElements(['Team management', 'Project planning', 'Client communication', 'Code review', 'Documentation', 'Testing'], $this->faker->numberBetween(3, 6)),
            'required_experience' => $this->faker->numberBetween(1, 10),
            'skills' => $this->faker->randomElements(['PHP', 'Laravel', 'JavaScript', 'Vue.js', 'React', 'MySQL', 'PostgreSQL', 'AWS', 'Docker'], $this->faker->numberBetween(3, 7)),
        ];
    }
    
    public function remote(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'is_remote' => true,
                'city' => null,
                'state' => null,
            ];
        });
    }
    
    public function expired(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'expired_at' => $this->faker->dateTimeBetween('-30 days', '-1 day'),
            ];
        });
    }
}