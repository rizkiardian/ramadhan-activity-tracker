<?php

namespace Database\Factories;

use App\Models\ActivityType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserActivity>
 */
class UserActivityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array{user_id: mixed, activity_type_id: mixed, date: string, start_time: string, end_time: string, status: string, notes: string|null}
     */
    public function definition(): array
    {
        $start = Carbon::createFromTime(fake()->numberBetween(4, 20), fake()->numberBetween(0, 59));
        $end = $start->copy()->addMinutes(fake()->numberBetween(15, 90));

        return [
            'user_id' => User::factory(),
            'activity_type_id' => ActivityType::factory(),
            'date' => fake()->dateTimeBetween('2026-03-01', '2026-03-31')->format('Y-m-d'),
            'start_time' => $start->format('H:i'),
            'end_time' => $end->format('H:i'),
            'status' => fake()->randomElement(['Pending', 'Done', 'Skipped']),
            'notes' => fake()->optional()->sentence(),
        ];
    }

    /**
     * Link the activity to an existing User.
     */
    public function forUser(User $user): static
    {
        return $this->state(['user_id' => $user->id]);
    }
}
