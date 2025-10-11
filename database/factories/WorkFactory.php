<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Work>
 */
class WorkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $client = Client::inRandomOrder()->first();
        $profile = fake()->boolean() ? Profile::inRandomOrder()->first() : null;

        return [
            'uuid' => fake()->uuid(),
            'client_id' => $client->id,
            'freelancer_id' => isset($profile) ? $profile->freelancer_id : null,
            'profile_id' => isset($profile) ? $profile->id : null,
            'title' => fake()->sentence(6, true),
            'body' => fake()->paragraphs(3, true),
            'experience_level' => fake()->numberBetween(0, 2), // 0)EntryLevel 1)Intermediate 2)Expert
            'job_type' => fake()->numberBetween(0, 1), // 0)Hourly 1)FixedPrice
            'price' => fake()->numberBetween(0, 5500),
            'number_of_proposals' => fake()->numberBetween(0, 50),
            'project_type' => fake()->numberBetween(0, 1), // 0)One-time 1)Ongoing
            'project_length' => fake()->numberBetween(0, 3), // 0)LessThan1Month 1)1to3Months 2)3to6Months 3)MoreThan6Months
            'hours_per_week' => fake()->numberBetween(0, 1), // 0)LessThan3hrs/week 1)MoreThan30hrs/week
            'last_viewed' => fake()->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
