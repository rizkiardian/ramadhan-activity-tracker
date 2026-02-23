<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Regency;
use Illuminate\Auth\Access\HandlesAuthorization;

class RegencyPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Regency');
    }

    public function view(AuthUser $authUser, Regency $regency): bool
    {
        return $authUser->can('View:Regency');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Regency');
    }

    public function update(AuthUser $authUser, Regency $regency): bool
    {
        return $authUser->can('Update:Regency');
    }

    public function delete(AuthUser $authUser, Regency $regency): bool
    {
        return $authUser->can('Delete:Regency');
    }

    public function restore(AuthUser $authUser, Regency $regency): bool
    {
        return $authUser->can('Restore:Regency');
    }

    public function forceDelete(AuthUser $authUser, Regency $regency): bool
    {
        return $authUser->can('ForceDelete:Regency');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Regency');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Regency');
    }

    public function replicate(AuthUser $authUser, Regency $regency): bool
    {
        return $authUser->can('Replicate:Regency');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Regency');
    }

}