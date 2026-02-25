<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(['email' => 'admin@example.com'], [
            'name' => 'Admin',
            'gender' => 'Male',
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole('super_admin');

        $siti = User::updateOrCreate(['email' => 'siti@example.com'], [
            'name' => 'Siti Rahmawati',
            'gender' => 'Female',
            'password' => bcrypt('password'),
        ]);
        $siti->assignRole('user');

        $budi = User::updateOrCreate(['email' => 'budi@example.com'], [
            'name' => 'Budi Santoso',
            'gender' => 'Male',
            'password' => bcrypt('password'),
        ]);
        $budi->assignRole('user');
    }
}
