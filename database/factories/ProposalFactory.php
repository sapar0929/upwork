<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Proposal>
 */
class ProposalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $work = Client::inRandomOrder()->first();
        $profile = Profile::inRandomOrder()->first();

        return [
            'uuid' => fake()->uuid(),
            'work_id' => $work->id,
            'freelancer_id' => $profile->freelancer_id,
            'profile_id' => $profile->id,
            'cover_letter' => fake()->paragraphs(3, true),
            'status' => fake()->numberBetween(0, 2),
        ];
    }
}
