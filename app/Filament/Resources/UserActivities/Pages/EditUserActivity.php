<?php

namespace App\Filament\Resources\UserActivities\Pages;

use App\Filament\Resources\UserActivities\UserActivityResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditUserActivity extends EditRecord
{
    protected static string $resource = UserActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
