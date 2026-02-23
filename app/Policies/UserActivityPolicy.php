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
        return $authUser->can('View:UserActivity');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:UserActivity');
    }

    public function update(AuthUser $authUser, UserActivity $userActivity): bool
    {
        return $authUser->can('Update:UserActivity');
    }

    public function delete(AuthUser $authUser, UserActivity $userActivity): bool
    {
        return $authUser->can('Delete:UserActivity');
    }

    public function restore(AuthUser $authUser, UserActivity $userActivity): bool
    {
        return $authUser->can('Restore:UserActivity');
    }

    public function forceDelete(AuthUser $authUser, UserActivity $userActivity): bool
    {
        return $authUser->can('ForceDelete:UserActivity');
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
        return $authUser->can('Replicate:UserActivity');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:UserActivity');
    }
}
