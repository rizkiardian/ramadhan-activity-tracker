<?php

namespace Database\Seeders;

use App\Models\SyncLog;
use App\Models\User;
use Illuminate\Database\Seeder;

class SyncLogSeeder extends Seeder
{
    public function run(): void
    {
        SyncLog::query()->truncate();

        $admin = User::query()->first();

        $logs = [
            [
                'sync_type' => 'prayer_times',
                'start_date' => '2026-03-01',
                'end_date' => '2026-03-31',
                'sync_time' => '2026-03-01 01:00:00',
                'status' => 'Success',
                'notes' => null,
                'synced_by' => $admin->id,
            ],
            [
                'sync_type' => 'prayer_times',
                'start_date' => '2026-03-01',
                'end_date' => '2026-03-31',
                'sync_time' => '2026-03-02 01:00:00',
                'status' => 'Success',
                'notes' => null,
                'synced_by' => $admin->id,
            ],
        ];

        foreach ($logs as $log) {
            SyncLog::factory()->create($log);
        }

        $this->command?->info('Seeded '.count($logs).' sync logs.');
    }
}
