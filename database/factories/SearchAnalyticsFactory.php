<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SearchAnalytics>
 */
class SearchAnalyticsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'query' => fake()->words(2, true),
            'user_id' => fake()->optional(0.3)->numberBetween(1, 100),
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
            'results_count' => fake()->numberBetween(0, 100),
            'filters_applied' => fake()->optional(0.5)->randomElements([
                'is_remote' => fake()->boolean(),
                'employment_type' => fake()->randomElement(['fulltime', 'parttime', 'contract']),
                'country' => fake()->countryCode(),
            ], fake()->numberBetween(1, 3)),
            'session_id' => fake()->uuid(),
            'searched_at' => fake()->dateTimeBetween('-30 days', 'now'),
        ];
    }
}
