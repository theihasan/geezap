<?php

namespace Database\Factories;

use App\Models\JobListing;
use App\Models\JobCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobListing>
 */
class JobListingFactory extends Factory
{
    protected $model = JobListing::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'job_id' => $this->faker->unique()->uuid(),
            'employer_name' => $this->faker->company(),
            'employer_logo' => $this->faker->imageUrl(100, 100),
            'employer_website' => $this->faker->url(),
            'employer_company_type' => $this->faker->randomElement(['Private', 'Public', 'Non-profit']),
            'publisher' => $this->faker->randomElement(['LinkedIn', 'Indeed', 'Glassdoor', 'Monster']),
            'employment_type' => $this->faker->randomElement(['Full-time', 'Part-time', 'Contract', 'Freelance']),
            'job_title' => $this->faker->jobTitle(),
            'job_category' => JobCategory::factory(),
            'category_image' => $this->faker->imageUrl(200, 200),
            'apply_link' => $this->faker->url(),
            'description' => $this->faker->paragraphs(3, true),
            'is_remote' => $this->faker->boolean(30),
            'city' => $this->faker->city(),
            'state' => $this->faker->state(),
            'country' => $this->faker->countryCode(),
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
            'google_link' => $this->faker->url(),
            'posted_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'expired_at' => $this->faker->dateTimeBetween('now', '+30 days'),
            'min_salary' => $this->faker->numberBetween(30000, 80000),
            'max_salary' => $this->faker->numberBetween(80000, 150000),
            'salary_currency' => 'USD',
            'salary_period' => $this->faker->randomElement(['year', 'month', 'hour']),
            'benefits' => $this->faker->randomElements([
                'Health Insurance',
                'Dental Insurance',
                'Vision Insurance',
                '401k',
                'Paid Time Off',
                'Flexible Schedule',
                'Remote Work',
                'Professional Development'
            ], $this->faker->numberBetween(2, 5)),
            'qualifications' => $this->faker->sentences(5),
            'responsibilities' => $this->faker->sentences(8),
            'required_experience' => $this->faker->numberBetween(0, 10),
        ];
    }
}