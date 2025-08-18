<?php

namespace Database\Factories;

use App\Models\JobListing;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class JobListingFactory extends Factory
{
    protected $model = JobListing::class;

    public function definition(): array
    {
        $jobTitle = fake()->jobTitle();
        
        return [
            'uuid' => Str::uuid(),
            'slug' => Str::slug($jobTitle . '-' . time() . '-' . fake()->randomNumber(4)),
            'employer_name' => fake()->company(),
            'employer_logo' => fake()->imageUrl(200, 200, 'business'),
            'employer_website' => fake()->url(),
            'employer_company_type' => fake()->randomElement(['Startup', 'Corporation', 'Non-profit', 'Government']),
            'publisher' => fake()->randomElement(['Indeed', 'LinkedIn', 'Glassdoor', 'Monster']),
            'employment_type' => fake()->randomElement(['FULLTIME', 'PARTTIME', 'CONTRACTOR', 'INTERN']),
            'job_title' => $jobTitle,
            'job_category' => fake()->randomElement(['Technology', 'Healthcare', 'Finance', 'Marketing', 'Sales']),
            'category_image' => fake()->imageUrl(100, 100, 'business'),
            'apply_link' => fake()->url(),
            'description' => fake()->paragraphs(3, true),
            'is_remote' => fake()->boolean(30), // 30% chance of being remote
            'city' => fake()->city(),
            'state' => fake()->state(),
            'country' => fake()->randomElement(['US', 'CA', 'UK', 'AU', 'DE']),
            'latitude' => fake()->latitude(),
            'longitude' => fake()->longitude(),
            'google_link' => fake()->url(),
            'posted_at' => fake()->dateTimeBetween('-30 days', 'now'),
            'expired_at' => fake()->dateTimeBetween('now', '+60 days'),
            'min_salary' => $minSalary = fake()->numberBetween(30000, 80000),
            'max_salary' => fake()->numberBetween($minSalary, $minSalary + 50000),
            'salary_currency' => fake()->randomElement(['USD', 'CAD', 'GBP', 'EUR']),
            'salary_period' => fake()->randomElement(['YEAR', 'MONTH', 'HOUR']),
            'views' => fake()->numberBetween(0, 1000),
            'job_highlights' => json_encode([
                'Qualifications' => [
                    fake()->sentence(),
                    fake()->sentence(),
                    fake()->sentence(),
                ],
                'Responsibilities' => [
                    fake()->sentence(),
                    fake()->sentence(),
                    fake()->sentence(),
                ],
                'Benefits' => [
                    fake()->sentence(),
                    fake()->sentence(),
                ]
            ]),
            'job_benefits' => json_encode([
                'health_insurance' => fake()->boolean(),
                'retirement_plan' => fake()->boolean(),
                'paid_time_off' => fake()->boolean(),
                'flexible_schedule' => fake()->boolean(),
            ]),
            'required_education' => fake()->randomElement(['High School', 'Bachelor\'s Degree', 'Master\'s Degree', 'PhD']),
            'required_experience' => fake()->randomElement(['Entry Level', '1-3 years', '3-5 years', '5+ years']),
            'apply_options' => json_encode([
                'by_email' => fake()->boolean(),
                'by_web' => fake()->boolean(),
                'by_phone' => fake()->boolean(),
            ]),
            'naics_code' => fake()->randomNumber(6),
            'naics_name' => fake()->randomElement(['Information Technology', 'Healthcare', 'Finance', 'Retail']),
            'scraped_job_link' => fake()->url(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function remote(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_remote' => true,
        ]);
    }

    public function inCountry(string $country): static
    {
        return $this->state(fn (array $attributes) => [
            'country' => $country,
        ]);
    }

    public function withCategory(string $category): static
    {
        return $this->state(fn (array $attributes) => [
            'job_category' => $category,
        ]);
    }
}