<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\UserActivity;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class UserActivityPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:UserActivity');
    }

    public function view(AuthUser $authUser, UserActivity $userActivity): bool
    {
        if (! $authUser->can('View:UserActivity')) {
            return false;
        }

        return $authUser->hasRole('super_admin') || $userActivity->user_id === $authUser->id;
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:UserActivity');
    }

    public function update(AuthUser $authUser, UserActivity $userActivity): bool
    {
        if (! $authUser->can('Update:UserActivity')) {
            return false;
        }

        return $authUser->hasRole('super_admin') || $userActivity->user_id === $authUser->id;
    }

    public function delete(AuthUser $authUser, UserActivity $userActivity): bool
    {
        if (! $authUser->can('Delete:UserActivity')) {
            return false;
        }

        return $authUser->hasRole('super_admin') || $userActivity->user_id === $authUser->id;
    }

    public function restore(AuthUser $authUser, UserActivity $userActivity): bool
    {
        if (! $authUser->can('Restore:UserActivity')) {
            return false;
        }

        return $authUser->hasRole('super_admin') || $userActivity->user_id === $authUser->id;
    }

    public function forceDelete(AuthUser $authUser, UserActivity $userActivity): bool
    {
        if (! $authUser->can('ForceDelete:UserActivity')) {
            return false;
        }

        return $authUser->hasRole('super_admin') || $userActivity->user_id === $authUser->id;
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:UserActivity');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:UserActivity');
    }

    public function replicate(AuthUser $authUser, UserActivity $userActivity): bool
    {
        if (! $authUser->can('Replicate:UserActivity')) {
            return false;
        }

        return $authUser->hasRole('super_admin') || $userActivity->user_id === $authUser->id;
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:UserActivity');
    }
}
