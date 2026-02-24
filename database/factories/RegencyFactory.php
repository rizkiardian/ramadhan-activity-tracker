<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Regency>
 */
class RegencyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array{code: string, name: string}
     */
    public function definition(): array
    {
        $prefix = fake()->randomElement(['KABUPATEN', 'KOTA']);

        return [
            'code' => fake()->unique()->numerify('####'),
            'name' => $prefix.' '.strtoupper(fake()->city()),
            'last_synced_at' => now(),
        ];
    }
}
