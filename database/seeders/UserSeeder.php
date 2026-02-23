<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin User',
            'gender' => 'Male',
            'email' => 'admin@example.com',
        ]);

        User::factory()->create([
            'name' => 'Siti Rahmawati',
            'gender' => 'Female',
            'email' => 'siti@example.com',
        ]);

        User::factory()->create([
            'name' => 'Budi Santoso',
            'gender' => 'Male',
            'email' => 'budi@example.com',
        ]);
    }
}
