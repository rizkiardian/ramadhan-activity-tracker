<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\RamadhanPeriod;
use Illuminate\Auth\Access\HandlesAuthorization;

class RamadhanPeriodPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:RamadhanPeriod');
    }

    public function view(AuthUser $authUser, RamadhanPeriod $ramadhanPeriod): bool
    {
        return $authUser->can('View:RamadhanPeriod');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:RamadhanPeriod');
    }

    public function update(AuthUser $authUser, RamadhanPeriod $ramadhanPeriod): bool
    {
        return $authUser->can('Update:RamadhanPeriod');
    }

    public function delete(AuthUser $authUser, RamadhanPeriod $ramadhanPeriod): bool
    {
        return $authUser->can('Delete:RamadhanPeriod');
    }

    public function restore(AuthUser $authUser, RamadhanPeriod $ramadhanPeriod): bool
    {
        return $authUser->can('Restore:RamadhanPeriod');
    }

    public function forceDelete(AuthUser $authUser, RamadhanPeriod $ramadhanPeriod): bool
    {
        return $authUser->can('ForceDelete:RamadhanPeriod');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:RamadhanPeriod');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:RamadhanPeriod');
    }

    public function replicate(AuthUser $authUser, RamadhanPeriod $ramadhanPeriod): bool
    {
        return $authUser->can('Replicate:RamadhanPeriod');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:RamadhanPeriod');
    }

}