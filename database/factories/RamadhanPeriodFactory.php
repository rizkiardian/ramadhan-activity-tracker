<?php

namespace Database\Factories;

use App\Models\RamadhanPeriod;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RamadhanPeriod>
 */
class RamadhanPeriodFactory extends Factory
{
    protected $model = RamadhanPeriod::class;

    public function definition(): array
    {
        $year = fake()->numberBetween(2020, 2030);
        $startDate = Carbon::parse("{$year}-03-01");

        return [
            'year' => $year,
            'start_date' => $startDate->toDateString(),
            'end_date' => $startDate->copy()->addDays(29)->toDateString(),
            'hijri_year' => null,
            'notes' => null,
        ];
    }
}
