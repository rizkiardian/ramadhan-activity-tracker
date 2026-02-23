<?php

namespace Database\Seeders;

use App\Models\ActivityType;
use App\Models\User;
use App\Models\UserActivity;
use Illuminate\Database\Seeder;

class UserActivitySeeder extends Seeder
{
    public function run(): void
    {
        UserActivity::query()->truncate();

        $siti = User::query()->where('email', 'siti@example.com')->first();
        $budi = User::query()->where('email', 'budi@example.com')->first();

        $tarawih = ActivityType::query()->where('name', 'Tarawih')->first();
        $tadarus = ActivityType::query()->where('name', 'Tadarus')->first();
        $sedekah = ActivityType::query()->where('name', 'Sedekah')->first();

        $activities = [
            [
                'user_id' => $siti->id,
                'activity_type_id' => $tarawih->id,
                'date' => '2026-03-01',
                'start_time' => '19:30',
                'end_time' => '20:30',
                'status' => 'Done',
                'notes' => 'Tarawih Masjid',
            ],
            [
                'user_id' => $siti->id,
                'activity_type_id' => $tadarus->id,
                'date' => '2026-03-01',
                'start_time' => '20:45',
                'end_time' => '21:15',
                'status' => 'Done',
                'notes' => 'Tadarus 1 juz',
            ],
            [
                'user_id' => $budi->id,
                'activity_type_id' => $sedekah->id,
                'date' => '2026-03-01',
                'start_time' => '17:50',
                'end_time' => '18:10',
                'status' => 'Done',
                'notes' => 'Sedekah online',
            ],
        ];

        foreach ($activities as $activity) {
            UserActivity::factory()->create($activity);
        }

        $this->command?->info('Seeded '.count($activities).' user activities.');
    }
}
