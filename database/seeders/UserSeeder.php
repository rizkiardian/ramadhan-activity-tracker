<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(['email' => 'admin@example.com'], [
            'name' => 'Admin User',
            'gender' => 'Male',
            'password' => bcrypt('password'),
        ]);

        User::updateOrCreate(['email' => 'siti@example.com'], [
            'name' => 'Siti Rahmawati',
            'gender' => 'Female',
            'password' => bcrypt('password'),
        ]);

        User::updateOrCreate(['email' => 'budi@example.com'], [
            'name' => 'Budi Santoso',
            'gender' => 'Male',
            'password' => bcrypt('password'),
        ]);
    }
}
