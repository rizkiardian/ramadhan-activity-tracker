<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Generate Shield permissions and ensure required roles exist.
     */
    public function run(): void
    {
        $this->command->call('shield:generate', ['--all' => true, '--no-interaction' => true]);

        Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
    }
}
