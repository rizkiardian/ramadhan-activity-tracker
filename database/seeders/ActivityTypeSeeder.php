<?php

namespace Database\Seeders;

use App\Models\ActivityType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActivityTypeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('user_activities')->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        ActivityType::query()->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $admin = User::query()->first();

        $types = ['Tarawih', 'Tadarus', 'Sedekah', 'Kajian', 'Olahraga'];

        foreach ($types as $name) {
            ActivityType::factory()->create([
                'name' => $name,
                'created_by' => $admin->id,
            ]);
        }

        $this->command?->info('Seeded '.count($types).' activity types.');
    }
}
