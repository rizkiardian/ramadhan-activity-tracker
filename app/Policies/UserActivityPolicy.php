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
        return $userActivity->user_id === $authUser->id
            || $authUser->hasRole('super_admin');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:UserActivity');
    }

    public function update(AuthUser $authUser, UserActivity $userActivity): bool
    {
        return $userActivity->user_id === $authUser->id;
    }

    public function delete(AuthUser $authUser, UserActivity $userActivity): bool
    {
        return $userActivity->user_id === $authUser->id || $authUser->hasRole('super_admin');
    }

    public function restore(AuthUser $authUser, UserActivity $userActivity): bool
    {
        return $userActivity->user_id === $authUser->id || $authUser->hasRole('super_admin');
    }

    public function forceDelete(AuthUser $authUser, UserActivity $userActivity): bool
    {
        return $userActivity->user_id === $authUser->id || $authUser->hasRole('super_admin');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:UserActivity') || $authUser->hasRole('super_admin');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:UserActivity') || $authUser->hasRole('super_admin');
    }

    public function replicate(AuthUser $authUser, UserActivity $userActivity): bool
    {
        return $userActivity->user_id === $authUser->id;
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:UserActivity');
    }
}
