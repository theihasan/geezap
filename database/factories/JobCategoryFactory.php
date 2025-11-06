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
            'Frontend Developer',
            'Backend Developer',
            'Full Stack Developer',
            'Mobile Developer',
            'QA Engineer',
            'Security Analyst',
            'Network Administrator',
            'Database Administrator',
            'System Administrator',
            'Technical Writer',
            'UX Designer',
            'UI Designer',
            'Graphics Designer',
            'Content Creator',
            'Social Media Manager',
            'SEO Specialist',
            'Digital Marketer',
            'Account Manager',
            'HR Manager',
            'Finance Manager',
            'Operations Manager',
            'Customer Success Manager',
        ];
        $name = $this->faker->unique()->randomElement($names);

        return [
            'name' => $name,
            'slug' => \Illuminate\Support\Str::slug($name).'-'.$this->faker->numberBetween(1000, 9999),
            'query_name' => $name,
            'page' => 1,
            'num_page' => 5,
            'timeframe' => 'week',
            'category_image' => $this->faker->imageUrl(200, 200),
        ];
    }
}
