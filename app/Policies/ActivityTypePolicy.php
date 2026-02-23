<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\ActivityType;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class ActivityTypePolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ActivityType');
    }

    public function view(AuthUser $authUser, ActivityType $activityType): bool
    {
        return $authUser->can('View:ActivityType');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ActivityType');
    }

    public function update(AuthUser $authUser, ActivityType $activityType): bool
    {
        return $authUser->can('Update:ActivityType');
    }

    public function delete(AuthUser $authUser, ActivityType $activityType): bool
    {
        return $authUser->can('Delete:ActivityType');
    }

    public function restore(AuthUser $authUser, ActivityType $activityType): bool
    {
        return $authUser->can('Restore:ActivityType');
    }

    public function forceDelete(AuthUser $authUser, ActivityType $activityType): bool
    {
        return $authUser->can('ForceDelete:ActivityType');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ActivityType');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ActivityType');
    }

    public function replicate(AuthUser $authUser, ActivityType $activityType): bool
    {
        return $authUser->can('Replicate:ActivityType');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ActivityType');
    }
}
