<?php

namespace Database\Seeders;

use App\Models\RamadhanPeriod;
use Illuminate\Database\Seeder;

class RamadhanPeriodSeeder extends Seeder
{
    /** @var list<array{year: int, start_date: string, end_date: string, hijri_year: string, notes: ?string}> */
    private array $periods = [
        [
            'year' => 2023,
            'start_date' => '2023-03-23',
            'end_date' => '2023-04-21',
            'hijri_year' => '1444H',
            'notes' => null,
        ],
        [
            'year' => 2024,
            'start_date' => '2024-03-11',
            'end_date' => '2024-04-09',
            'hijri_year' => '1445H',
            'notes' => null,
        ],
        [
            'year' => 2025,
            'start_date' => '2025-03-01',
            'end_date' => '2025-03-30',
            'hijri_year' => '1446H',
            'notes' => null,
        ],
        [
            'year' => 2026,
            'start_date' => '2026-02-18',
            'end_date' => '2026-03-19',
            'hijri_year' => '1447H',
            'notes' => 'Periode Ramadhan aktif',
        ],
        [
            'year' => 2027,
            'start_date' => '2027-02-07',
            'end_date' => '2027-03-08',
            'hijri_year' => '1448H',
            'notes' => null,
        ],
    ];

    public function run(): void
    {
        RamadhanPeriod::query()->forceDelete();

        foreach ($this->periods as $period) {
            RamadhanPeriod::factory()->create($period);
        }

        $this->command?->info('Seeded '.count($this->periods).' Ramadhan periods.');
    }
}
