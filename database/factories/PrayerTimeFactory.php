<?php

namespace Database\Factories;

use App\Models\PrayerTime;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PrayerTime>
 */
class PrayerTimeFactory extends Factory
{
    protected $model = PrayerTime::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $date = Carbon::parse(fake()->dateTimeBetween('2026-03-01', '2026-03-30'));

        return [
            'regency_code' => fake()->numerify('####'),
            'regency_name' => 'KOTA '.strtoupper(fake()->city()),
            'gmt' => fake()->randomElement([7, 8, 9]),
            'date' => $date->toDateString(),
            'year' => $date->year,
            'month' => $date->month,
            'day' => $date->day,
            'imsyak' => $date->copy()->setTime(4, fake()->numberBetween(20, 40))->format('H:i'),
            'shubuh' => $date->copy()->setTime(4, fake()->numberBetween(35, 50))->format('H:i'),
            'terbit' => $date->copy()->setTime(5, fake()->numberBetween(50, 59))->format('H:i'),
            'dhuha' => $date->copy()->setTime(6, fake()->numberBetween(15, 30))->format('H:i'),
            'dzuhur' => $date->copy()->setTime(12, fake()->numberBetween(0, 10))->format('H:i'),
            'ashr' => $date->copy()->setTime(15, fake()->numberBetween(15, 30))->format('H:i'),
            'maghrib' => $date->copy()->setTime(18, fake()->numberBetween(10, 20))->format('H:i'),
            'isya' => $date->copy()->setTime(19, fake()->numberBetween(25, 35))->format('H:i'),
        ];
    }
}
