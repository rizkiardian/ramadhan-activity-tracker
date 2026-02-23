<?php

namespace App\Filament\Resources\UserActivities\Pages;

use App\Filament\Resources\UserActivities\UserActivityResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListUserActivities extends ListRecords
{
    protected static string $resource = UserActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
