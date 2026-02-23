<?php

namespace Database\Seeders;

use App\Models\PrayerTime;
use Illuminate\Database\Seeder;

class PrayerTimeSeeder extends Seeder
{
    public function run(): void
    {
        PrayerTime::query()->truncate();

        PrayerTime::factory()->count(31)->create();

        $this->command?->info('Seeded 31 prayer time records.');
    }
}
