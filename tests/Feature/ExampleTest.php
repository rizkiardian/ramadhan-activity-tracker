<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    Role::create(['name' => 'super_admin', 'guard_name' => 'web']);
    Role::create(['name' => 'user', 'guard_name' => 'web']);
});

it('redirects unauthenticated users from dashboard to login', function (): void {
    $this->get('/')->assertRedirectToRoute('filament.app.auth.login');
});

it('shows the login page successfully', function (): void {
    $this->get('/login')->assertOk();
});

it('allows super_admin to access the dashboard', function (): void {
    $admin = \App\Models\User::factory()->create();
    $admin->assignRole('super_admin');

    $this->actingAs($admin)->get('/')->assertOk();
});
