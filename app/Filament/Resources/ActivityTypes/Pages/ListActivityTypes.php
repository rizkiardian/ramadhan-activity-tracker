<?php

namespace App\Filament\Resources\ActivityTypes\Pages;

use App\Filament\Resources\ActivityTypes\ActivityTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListActivityTypes extends ListRecords
{
    protected static string $resource = ActivityTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
