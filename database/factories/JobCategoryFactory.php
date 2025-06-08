<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\JobCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class JobCategoryFactory extends Factory
{
    protected $model = JobCategory::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->jobTitle();
        
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'query_name' => $name, 
            'page' => $this->faker->numberBetween(1, 5),
            'num_page' => $this->faker->numberBetween(10, 30),
            'timeframe' => $this->faker->randomElement(['day', 'week', 'month']),
            'category_image' => $this->faker->imageUrl(),
        ];
    }
}