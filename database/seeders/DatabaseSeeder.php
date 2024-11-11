<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Enums\SkillProficiency;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@geezap.com',
            'role' => Role::ADMIN->value,
        ]);
        User::factory(300)->create()->each(function ($user) {
            $skillsList = [
                'Frontend' => ['React', 'Vue.js', 'Angular', 'TypeScript', 'Tailwind CSS'],
                'Backend' => ['Laravel', 'Node.js', 'Django', 'Spring Boot', 'Express.js'],
                'Database' => ['MySQL', 'PostgreSQL', 'MongoDB', 'Redis', 'Elasticsearch'],
                'DevOps' => ['Docker', 'Kubernetes', 'AWS', 'Jenkins', 'Terraform'],
                'Mobile' => ['React Native', 'Flutter', 'Swift', 'Kotlin', 'iOS Development']
            ];

            $numberOfSkills = rand(3, 6);
            $skills = [];
            $skill_levels = [];


            foreach (array_rand($skillsList, min(count($skillsList), $numberOfSkills)) as $category) {
                $skill = $skillsList[$category][array_rand($skillsList[$category])];
                $skills[] = $skill;
                $skill_levels[] = fake()->randomElement([
                    SkillProficiency::PROFICIENT->value,
                    SkillProficiency::INTERMEDIATE->value,
                    SkillProficiency::BEGINNER->value
                ]);
            }

            $user->skills = json_encode([
                'skill' => $skills,
                'skill_level' => $skill_levels
            ]);

            $user->save();
        });
    }

}
