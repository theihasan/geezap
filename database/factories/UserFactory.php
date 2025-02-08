<?php

namespace Database\Factories;

use App\Enums\Role;
use App\Enums\SkillProficiency;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => Str::uuid(),
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // password
            'address' => fake()->streetAddress(),
            'dob' => fake()->date('Y-m-d', '-25 years'),
            'state' => fake()->state(),
            'country' => fake()->country(),
            'postcode' => fake()->postcode(),
            'occupation' => fake()->jobTitle(),
            'role' => Role::USER->value,
            'locale' => 'en',
            'timezone' => fake()->randomElement(['UTC', 'America/New_York', 'Europe/London', 'Asia/Tokyo']),
            'phone' => fake()->phoneNumber(),
            'facebook' => 'https://facebook.com/' . fake()->userName(),
            'twitter' => 'https://twitter.com/' . fake()->userName(),
            'linkedin' => 'https://linkedin.com/in/' . fake()->userName(),
            'github' => 'https://github.com/' . fake()->userName(),
            'website' => 'https://' . fake()->domainName(),
            'bio' => fake()->paragraph(),
            'skills' => json_encode([
                'skill' => ['PHP', 'Laravel', 'MySQL', 'Docker', 'AWS', 'DevOps'],
                'skill_level' => [
                    SkillProficiency::PROFICIENT->value,
                    SkillProficiency::PROFICIENT->value,
                    SkillProficiency::PROFICIENT->value,
                    SkillProficiency::INTERMEDIATE->value,
                    SkillProficiency::PROFICIENT->value,
                    SkillProficiency::INTERMEDIATE->value,
                ]
            ]),
            'experience' => json_encode([
                [
                    'title' => 'Senior System Administrator',
                    'company' => 'Tech Corp',
                    'location' => 'San Francisco, CA',
                    'start_date' => '2018-01',
                    'end_date' => null,
                    'current' => true,
                    'description' => 'Managing cloud infrastructure and implementing DevOps practices.'
                ],
                [
                    'title' => 'DevOps Engineer',
                    'company' => 'Startup Inc',
                    'location' => 'New York, NY',
                    'start_date' => '2015-03',
                    'end_date' => '2017-12',
                    'current' => false,
                    'description' => 'Implemented CI/CD pipelines and managed cloud resources.'
                ]
            ])
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
