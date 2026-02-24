<?php

namespace Database\Seeders;

use App\Models\Regency;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegencySeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Regency::query()->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        Regency::factory()->count(20)->create();

        $this->command?->info('Seeded 20 regency records.');
    }
}
