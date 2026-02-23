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

        // Cover Ramadhan 2026: 18 Feb – 19 Mar (30 days)
        $ramadhanStart = Carbon::create(2026, 2, 18);
        $ramadhanEnd = Carbon::create(2026, 3, 19);

        /** @var array<int, array{date: string, year: int, month: int, day: int}> $dateStates */
        $dateStates = collect()
            ->pad(0, [])
            ->concat(
                collect(range(0, $ramadhanStart->diffInDays($ramadhanEnd)))
                    ->map(function (int $offset) use ($ramadhanStart): array {
                        $date = $ramadhanStart->copy()->addDays($offset);

                        return [
                            'date' => $date->toDateString(),
                            'year' => $date->year,
                            'month' => $date->month,
                            'day' => $date->day,
                        ];
                    })
            )
            ->values()
            ->all();

        $count = count($dateStates);

        $regencies->each(function (Regency $regency) use ($dateStates, $count): void {
            PrayerTime::factory()
                ->count($count)
                ->forRegency($regency)
                ->sequence(...$dateStates)
                ->create();
        });

        $total = PrayerTime::query()->count();
        $this->command?->info("Seeded {$total} prayer time records.");
    }
}
