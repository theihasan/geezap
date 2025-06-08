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
            'description' => $this->faker->sentence(),
            'category_image' => $this->faker->imageUrl(),
        ];
    }
}