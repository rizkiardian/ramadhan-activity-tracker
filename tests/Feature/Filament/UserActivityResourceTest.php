<?php

use App\Models\User;
use App\Models\UserActivity;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    Role::create(['name' => 'super_admin', 'guard_name' => 'web']);
    Role::create(['name' => 'user', 'guard_name' => 'web']);

    $this->admin = User::factory()->create();
    $this->admin->assignRole('super_admin');
});

describe('UserActivity index', function (): void {
    it('lists user activities for super_admin', function (): void {
        UserActivity::factory()->forUser($this->admin)->count(3)->create();

        $this->actingAs($this->admin)
            ->get('/user-activities')
            ->assertOk();
    });

    it('shows activity count in listing', function (): void {
        UserActivity::factory()->forUser($this->admin)->count(5)->create();

        expect(UserActivity::count())->toBe(5);
    });
});

describe('UserActivity create', function (): void {
    it('renders the create page for super_admin', function (): void {
        $this->actingAs($this->admin)
            ->get('/user-activities/create')
            ->assertOk();
    });
});

describe('UserActivity edit', function (): void {
    it('renders the edit page for an existing activity', function (): void {
        $activity = UserActivity::factory()->forUser($this->admin)->create();

        $this->actingAs($this->admin)
            ->get("/user-activities/{$activity->id}/edit")
            ->assertOk();
    });

    it('returns 404 for a non-existent activity', function (): void {
        $this->actingAs($this->admin)
            ->get('/user-activities/99999/edit')
            ->assertNotFound();
    });
});

describe('UserActivity soft deletes', function (): void {
    it('soft deletes a user activity', function (): void {
        $activity = UserActivity::factory()->forUser($this->admin)->create();

        $activity->delete();

        expect(UserActivity::count())->toBe(0)
            ->and(UserActivity::withTrashed()->count())->toBe(1);
    });
});
