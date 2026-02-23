<?php

namespace Database\Factories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SyncLog>
 */
class SyncLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array{sync_type: string, start_date: string, end_date: string, sync_time: string, status: string, notes: string|null, synced_by: mixed}
     */
    public function definition(): array
    {
        $syncTime = Carbon::parse(fake()->dateTimeBetween('2026-03-01', '2026-03-31'));

        return [
            'sync_type' => fake()->randomElement(['prayer_times', 'regencies']),
            'start_date' => '2026-03-01',
            'end_date' => '2026-03-31',
            'sync_time' => $syncTime->format('Y-m-d H:i:s'),
            'status' => fake()->randomElement(['Success', 'Failed', 'Pending']),
            'notes' => fake()->optional()->sentence(),
            'synced_by' => User::factory(),
        ];
    }
}
