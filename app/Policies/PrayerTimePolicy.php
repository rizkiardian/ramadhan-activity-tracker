<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\PrayerTime;
use Illuminate\Auth\Access\HandlesAuthorization;

class PrayerTimePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:PrayerTime');
    }

    public function view(AuthUser $authUser, PrayerTime $prayerTime): bool
    {
        return $authUser->can('View:PrayerTime');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:PrayerTime');
    }

    public function update(AuthUser $authUser, PrayerTime $prayerTime): bool
    {
        return $authUser->can('Update:PrayerTime');
    }

    public function delete(AuthUser $authUser, PrayerTime $prayerTime): bool
    {
        return $authUser->can('Delete:PrayerTime');
    }

    public function restore(AuthUser $authUser, PrayerTime $prayerTime): bool
    {
        return $authUser->can('Restore:PrayerTime');
    }

    public function forceDelete(AuthUser $authUser, PrayerTime $prayerTime): bool
    {
        return $authUser->can('ForceDelete:PrayerTime');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:PrayerTime');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:PrayerTime');
    }

    public function replicate(AuthUser $authUser, PrayerTime $prayerTime): bool
    {
        return $authUser->can('Replicate:PrayerTime');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:PrayerTime');
    }

}