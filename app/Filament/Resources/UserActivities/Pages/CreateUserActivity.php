<?php

namespace App\Filament\Resources\UserActivities\Pages;

use App\Filament\Resources\UserActivities\UserActivityResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUserActivity extends CreateRecord
{
    protected static string $resource = UserActivityResource::class;
}
