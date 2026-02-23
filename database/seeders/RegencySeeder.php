<?php

namespace Database\Seeders;

use App\Models\Regency;
use Illuminate\Database\Seeder;

class RegencySeeder extends Seeder
{
    public function run(): void
    {
        Regency::query()->truncate();
        Regency::factory()->count(20)->create();

        $this->command?->info('Seeded 20 regency records.');
    }
}
