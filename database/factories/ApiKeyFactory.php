<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ApiKey>
 */
class ApiKeyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'api_key' => fake()->uuid(),
            'api_secret' => fake()->sha256(),
            'api_name' => 'job',
            'request_remaining' => fake()->numberBetween(0, 1000),
            'sent_request' => fake()->numberBetween(0, 500),
            'request_sent_at' => fake()->optional()->dateTimeThisMonth(),
            'rate_limit_reset' => fake()->optional()->dateTimeThisMonth(),
        ];
    }
}
