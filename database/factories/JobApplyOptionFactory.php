<?php

namespace Database\Factories;

use App\Models\JobApplyOption;
use App\Models\JobListing;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobApplyOption>
 */
class JobApplyOptionFactory extends Factory
{
    protected $model = JobApplyOption::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'job_listing_id' => JobListing::factory(),
            'publisher' => $this->faker->randomElement(['LinkedIn', 'Indeed', 'Glassdoor', 'Monster', 'ZipRecruiter']),
            'apply_link' => $this->faker->url(),
            'is_direct' => $this->faker->boolean(70),
        ];
    }
}