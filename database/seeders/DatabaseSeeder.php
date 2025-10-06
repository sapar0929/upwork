<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Freelancer;
use App\Models\Profile;
use App\Models\Proposal;
use App\Models\Review;
use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Verification;
use App\Models\Work;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()
            ->create([
                'name' => 'Administrator',
                'username' => 'admin',
                'password' => 'upwork2025',
            ]);

        User::factory()
            ->count(5)
            ->create();

        $this->call([
            LocationSeeder::class,
            SkillSeeder::class,
        ]);

        Freelancer::factory()
            ->count(50)
            ->has(Profile::factory()->count(2))
            ->create();

        Client::factory()
            ->count(10)
            ->create();

        Work::factory()
            ->count(50)
            ->create();

        Proposal::factory()
            ->count(100)
            ->create();

        Review::factory()
            ->count(50)
            ->create();

        Verification::factory()
            ->count(10)
            ->create();
    }
}
