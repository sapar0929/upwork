<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Verification>
 */
class VerificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $method = fake()->numberBetween(0, 1);

        return [
            'username' => $method ? fake()->unique()->safeEmail() : fake()->unique()->numberBetween(60000000, 65999999),
            'code' => fake()->numberBetween(10000, 99999),
            'method' => $method,
            'status' => fake()->numberBetween(0, 2),
        ];
    }
}
