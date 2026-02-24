<?php

namespace Database\Seeders;

use App\Enums\SyncCategory;
use App\Models\SyncLog;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SyncLogSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        SyncLog::query()->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $admin = User::query()->first();

        $logs = [
            [
                'sync_type' => 'regencies',
                'sync_category' => SyncCategory::Regency,
                'start_date' => '2026-02-18',
                'end_date' => '2026-02-18',
                'sync_time' => '2026-02-18 00:30:00',
                'status' => 'Success',
                'notes' => '514 regencies synced.',
                'synced_by' => $admin->id,
            ],
            [
                'sync_type' => 'prayer_times',
                'sync_category' => SyncCategory::PrayerTime,
                'start_date' => '2026-02-18',
                'end_date' => '2026-03-19',
                'sync_time' => '2026-02-18 01:00:00',
                'status' => 'Success',
                'notes' => '30 records synced for regency 3171 (Ramadhan 2026).',
                'synced_by' => $admin->id,
            ],
        ];

        foreach ($logs as $log) {
            SyncLog::factory()->create($log);
        }

        $this->command?->info('Seeded '.count($logs).' sync logs.');
    }
}
