<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\SyncLog;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class SyncLogPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:SyncLog');
    }

    public function view(AuthUser $authUser, SyncLog $syncLog): bool
    {
        return $authUser->can('View:SyncLog');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:SyncLog');
    }

    public function update(AuthUser $authUser, SyncLog $syncLog): bool
    {
        return $authUser->can('Update:SyncLog');
    }

    public function delete(AuthUser $authUser, SyncLog $syncLog): bool
    {
        return $authUser->can('Delete:SyncLog');
    }

    public function restore(AuthUser $authUser, SyncLog $syncLog): bool
    {
        return $authUser->can('Restore:SyncLog');
    }

    public function forceDelete(AuthUser $authUser, SyncLog $syncLog): bool
    {
        return $authUser->can('ForceDelete:SyncLog');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:SyncLog');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:SyncLog');
    }

    public function replicate(AuthUser $authUser, SyncLog $syncLog): bool
    {
        return $authUser->can('Replicate:SyncLog');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:SyncLog');
    }
}
