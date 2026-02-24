<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    Role::create(['name' => 'super_admin', 'guard_name' => 'web']);
    Role::create(['name' => 'user', 'guard_name' => 'web']);
});

describe('unauthenticated access', function (): void {
    it('redirects from dashboard to login', function (): void {
        $this->get('/')->assertRedirectToRoute('filament.app.auth.login');
    });

    it('redirects from user-activities to login', function (): void {
        $this->get('/user-activities')->assertRedirect();
    });

    it('redirects from activity-types to login', function (): void {
        $this->get('/activity-types')->assertRedirect();
    });

    it('shows the login page', function (): void {
        $this->get('/login')->assertOk();
    });
});

describe('super_admin access', function (): void {
    beforeEach(function (): void {
        $this->admin = User::factory()->create();
        $this->admin->assignRole('super_admin');
    });

    it('can access the dashboard', function (): void {
        $this->actingAs($this->admin)->get('/')->assertOk();
    });

    it('can access user-activities index', function (): void {
        $this->actingAs($this->admin)->get('/user-activities')->assertOk();
    });

    it('can access activity-types index', function (): void {
        $this->actingAs($this->admin)->get('/activity-types')->assertOk();
    });

    it('can access users index', function (): void {
        $this->actingAs($this->admin)->get('/users')->assertOk();
    });

    it('can access ramadhan-periods index', function (): void {
        $this->actingAs($this->admin)->get('/ramadhan-periods')->assertOk();
    });

    it('can access sync-logs index', function (): void {
        $this->actingAs($this->admin)->get('/sync-logs')->assertOk();
    });
});
