<?php

namespace App\Filament\Resources\ActivityTypes\Pages;

use App\Filament\Resources\ActivityTypes\ActivityTypeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditActivityType extends EditRecord
{
    protected static string $resource = ActivityTypeResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
