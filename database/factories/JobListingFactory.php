<?php

namespace Database\Factories;

use App\Models\JobCategory;
use App\Models\JobListing;
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
        $jobTitle = fake()->words(3, true);

        return [
            'job_id' => 'job-' . $this->faker->unique()->randomNumber(7),
            'uuid' => \Illuminate\Support\Str::uuid(),
            'employer_name' => fake()->company(),
            'employer_logo' => fake()->imageUrl(100, 100),
            'employer_website' => fake()->url(),
            'employer_company_type' => $this->faker->randomElement(['Private', 'Public', 'Non-profit']),
            'publisher' => $this->faker->randomElement(['LinkedIn', 'Indeed', 'Glassdoor', 'Monster']),
            'employment_type' => $this->faker->randomElement(['Full-time', 'Part-time', 'Contract', 'Freelance']),
            'job_title' => $jobTitle,
            'slug' => \Illuminate\Support\Str::slug($jobTitle.'-'.$this->faker->randomNumber(6)),
            'job_category' => function () {
                // Try to use an existing category first, create one if none exists
                return JobCategory::inRandomOrder()->first()?->id ?? JobCategory::factory()->create()->id;
            },
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
            'posted_at' => $this->faker->dateTimeBetween('-30 days', 'now')->format('Y-m-d H:i:s'),
            'expired_at' => $this->faker->dateTimeBetween('now', '+30 days')->format('Y-m-d H:i:s'),
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
                'Professional Development',
            ], $this->faker->numberBetween(2, 5)),
            'qualifications' => $this->faker->sentences(5),
            'responsibilities' => $this->faker->sentences(8),
            'required_experience' => $this->faker->numberBetween(0, 10),
        ];
    }
}
