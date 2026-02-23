<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            RegencySeeder::class,
            ActivityTypeSeeder::class,
            PrayerTimeSeeder::class,
            UserActivitySeeder::class,
            SyncLogSeeder::class,
        ]);
    }
}
