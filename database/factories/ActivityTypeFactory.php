<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ActivityType>
 */
class ActivityTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array{name: string, created_by: mixed}
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['Tarawih', 'Tadarus', 'Sedekah', 'Kajian', 'Olahraga', 'Sahur', 'Buka Puasa']),
            'created_by' => User::factory(),
        ];
    }
}
