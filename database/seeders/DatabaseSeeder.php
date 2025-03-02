<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Enums\SkillProficiency;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
              CountrySeeder::class,
        ]);
        /*
                User::factory()->create([
                    'name' => 'Admin User',
                    'email' => 'admin@geezap.com',
                    'role' => Role::ADMIN->value,
                    'password' => Hash::make('password'),
                ]);
        */
    }

}
