<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\JobApplyOption;
use App\Models\JobListing;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobApplyOptionFactory extends Factory
{
    protected $model = JobApplyOption::class;

    public function definition(): array
    {
        return [
            'job_listing_id' => JobListing::factory(),
            'publisher' => $this->faker->randomElement(['LinkedIn', 'Indeed', 'Glassdoor', 'Direct']),
            'apply_link' => $this->faker->url(),
            'is_direct' => $this->faker->boolean(),
        ];
    }
    
    public function direct(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'is_direct' => true,
            ];
        });
    }
}