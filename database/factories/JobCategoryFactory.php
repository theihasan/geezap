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
        $names = [
            'Software Engineer',
            'Data Scientist',
            'Product Manager',
            'Marketing Manager',
            'Sales Representative',
            'Designer',
            'DevOps Engineer',
            'Business Analyst',
        ];
        $name = $this->faker->unique()->randomElement($names);

        return [
            'name' => $name,
            'slug' => \Illuminate\Support\Str::slug($name).'-'.$this->faker->unique()->numberBetween(1000, 9999),
            'query_name' => $name,
            'page' => 1,
            'num_page' => 5,
            'timeframe' => 'week',
            'category_image' => $this->faker->imageUrl(200, 200),
        ];
    }
}
