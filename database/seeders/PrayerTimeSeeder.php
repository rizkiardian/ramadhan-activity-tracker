<?php

namespace Database\Seeders;

use App\Models\PrayerTime;
use App\Models\Regency;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PrayerTimeSeeder extends Seeder
{
    public function run(): void
    {
        PrayerTime::query()->truncate();

        $regencies = Regency::all();

        /** @var array<int, array{date: string, year: int, month: int, day: int}> $marchDateStates */
        $marchDateStates = collect(range(1, 31))
            ->map(function (int $day): array {
                $date = Carbon::create(2026, 3, $day);

                return [
                    'date' => $date->toDateString(),
                    'year' => $date->year,
                    'month' => $date->month,
                    'day' => $date->day,
                ];
            })
            ->all();

        $regencies->each(function (Regency $regency) use ($marchDateStates): void {
            PrayerTime::factory()
                ->count(31)
                ->forRegency($regency)
                ->sequence(...$marchDateStates)
                ->create();
        });

        $count = PrayerTime::query()->count();
        $this->command?->info("Seeded {$count} prayer time records.");
    }
}
