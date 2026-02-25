<?php

namespace App\Filament\Resources\ActivityTypes\Pages;

use App\Filament\Resources\ActivityTypes\ActivityTypeResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateActivityType extends CreateRecord
{
    protected static string $resource = ActivityTypeResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = Auth::id();

        return $data;
    }
}
