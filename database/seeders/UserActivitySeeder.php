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
        UserActivity::query()->forceDelete();

        $siti = User::query()->where('email', 'siti@example.com')->first();
        $budi = User::query()->where('email', 'budi@example.com')->first();

        if (! $siti || ! $budi) {
            $this->command?->warn('User siti atau budi tidak ditemukan, melewati seeder.');

            return;
        }

        $tarawih = ActivityType::query()->where('name', 'Tarawih')->first();
        $tadarus = ActivityType::query()->where('name', 'Tadarus')->first();
        $sedekah = ActivityType::query()->where('name', 'Sedekah')->first();
        $kajian = ActivityType::query()->where('name', 'Kajian')->first();
        $olahraga = ActivityType::query()->where('name', 'Olahraga')->first();

        // Tanggal Ramadhan 2026: 18 Feb - 19 Mar
        $activities = [
            // Hari 1 - 18 Feb 2026
            ['user_id' => $siti->id, 'activity_type_id' => $tarawih->id, 'date' => '2026-02-18', 'start_time' => '19:30', 'end_time' => '20:30', 'status' => 'Done', 'notes' => 'Tarawih di masjid dekat rumah'],
            ['user_id' => $siti->id, 'activity_type_id' => $tadarus->id, 'date' => '2026-02-18', 'start_time' => '20:45', 'end_time' => '21:15', 'status' => 'Done', 'notes' => 'Tadarus Al-Baqarah'],
            ['user_id' => $budi->id, 'activity_type_id' => $sedekah->id, 'date' => '2026-02-18', 'start_time' => '17:50', 'end_time' => '18:10', 'status' => 'Done', 'notes' => 'Sedekah untuk takjil'],
            // Hari 2 - 19 Feb 2026
            ['user_id' => $siti->id, 'activity_type_id' => $tarawih->id, 'date' => '2026-02-19', 'start_time' => '19:30', 'end_time' => '20:30', 'status' => 'Done', 'notes' => ''],
            ['user_id' => $budi->id, 'activity_type_id' => $tarawih->id, 'date' => '2026-02-19', 'start_time' => '19:30', 'end_time' => '20:30', 'status' => 'Done', 'notes' => ''],
            ['user_id' => $budi->id, 'activity_type_id' => $olahraga?->id, 'date' => '2026-02-19', 'start_time' => '05:30', 'end_time' => '06:00', 'status' => 'Done', 'notes' => 'Jalan pagi setelah shubuh'],
            // Hari 3 - 20 Feb 2026
            ['user_id' => $siti->id, 'activity_type_id' => $kajian?->id, 'date' => '2026-02-20', 'start_time' => '08:00', 'end_time' => '09:30', 'status' => 'Done', 'notes' => 'Kajian online'],
            ['user_id' => $siti->id, 'activity_type_id' => $tadarus->id, 'date' => '2026-02-20', 'start_time' => '21:00', 'end_time' => '21:45', 'status' => 'Done', 'notes' => '2 juz'],
            ['user_id' => $budi->id, 'activity_type_id' => $sedekah->id, 'date' => '2026-02-20', 'start_time' => '12:00', 'end_time' => '12:30', 'status' => 'Done', 'notes' => 'Zakat online'],
            // Hari 4 - 21 Feb 2026
            ['user_id' => $siti->id, 'activity_type_id' => $tarawih->id, 'date' => '2026-02-21', 'start_time' => '19:30', 'end_time' => '20:30', 'status' => 'Skipped', 'notes' => 'Tidak hadir'],
            ['user_id' => $budi->id, 'activity_type_id' => $tarawih->id, 'date' => '2026-02-21', 'start_time' => '19:30', 'end_time' => '20:30', 'status' => 'Done', 'notes' => ''],
            // Hari 5 - 22 Feb 2026
            ['user_id' => $siti->id, 'activity_type_id' => $sedekah->id, 'date' => '2026-02-22', 'start_time' => '10:00', 'end_time' => '10:30', 'status' => 'Done', 'notes' => 'Infaq jumat'],
            ['user_id' => $budi->id, 'activity_type_id' => $kajian?->id, 'date' => '2026-02-22', 'start_time' => '09:00', 'end_time' => '10:00', 'status' => 'Done', 'notes' => 'Kajian subuh'],
            // Current date (24 Feb 2026 = Hari 7)
            ['user_id' => $siti->id, 'activity_type_id' => $tarawih->id, 'date' => '2026-02-24', 'start_time' => '19:30', 'end_time' => '20:30', 'status' => 'Pending', 'notes' => ''],
            ['user_id' => $budi->id, 'activity_type_id' => $tadarus->id, 'date' => '2026-02-24', 'start_time' => '20:00', 'end_time' => '21:00', 'status' => 'Pending', 'notes' => ''],
        ];

        foreach ($activities as $activity) {
            if (! $activity['activity_type_id']) {
                continue;
            }
            UserActivity::factory()->create($activity);
        }

        $count = collect($activities)->filter(fn($a) => $a['activity_type_id'])->count();
        $this->command?->info("Seeded {$count} user activities.");
    }
}
