<?php

namespace Database\Factories;

use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
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
        $location = fake()->boolean(90) ? Location::inRandomOrder()->first() : null;

        return [
            'uuid' => fake()->uuid(),
            'location_id' => isset($location) ? $location->id : null,
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'avatar' => null,
            'username' => fake()->unique()->safeEmail(),
            'password' => bcrypt('password'),
            'rating' => fake()->randomFloat(1, 0, 5),
            'phone_number_verified' => fake()->boolean(),
            'payment_method_verified' => fake()->boolean(),
            'total_jobs' => fake()->numberBetween(0, 200),
            'total_spent' => fake()->numberBetween(0, 100000),
            'previous_freelancers' => [],
            'remember_token' => Str::random(10),
            'deleted_at' => fake()->boolean(10) ? fake()->dateTimeBetween('-90 days', 'now') : null,
        ];
    }
}
