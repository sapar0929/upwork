<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Freelancer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $freelancer = Freelancer::inRandomOrder()->first();
        $client = Client::inRandomOrder()->first();

        return [
            'uuid' => fake()->uuid(),
            'freelancer_id' => $freelancer->id,
            'client_id' => $client->id,
            'from' => fake()->boolean() ? 'freelancer' : 'client',
            'rating' => fake()->numberBetween(1, 5),
            'comment' => fake()->paragraphs(2, true),
        ];
    }
}
