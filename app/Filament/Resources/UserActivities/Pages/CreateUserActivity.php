<?php

namespace App\Filament\Resources\UserActivities\Pages;

use App\Filament\Resources\UserActivities\UserActivityResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateUserActivity extends CreateRecord
{
    protected static string $resource = UserActivityResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::id();

        return $data;
    }
}
