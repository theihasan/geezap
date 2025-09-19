<?php

namespace Database\Factories;

use App\Models\JobCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobCategory>
 */
class JobCategoryFactory extends Factory
{
    protected $model = JobCategory::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->randomElement([
            'Software Engineer',
            'Data Scientist',
            'Product Manager',
            'Marketing Manager',
            'Sales Representative',
            'Designer',
            'DevOps Engineer',
            'Business Analyst'
        ]);

        return [
            'name' => $name,
            'slug' => \Illuminate\Support\Str::slug($name),
            'query_name' => $name,
            'page' => 1,
            'num_page' => 5,
            'timeframe' => 'week',
            'category_image' => $this->faker->imageUrl(200, 200),
        ];
    }
}
