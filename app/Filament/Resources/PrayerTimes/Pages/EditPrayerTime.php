<?php

namespace App\Filament\Resources\PrayerTimes\Pages;

use App\Filament\Resources\PrayerTimes\PrayerTimeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPrayerTime extends EditRecord
{
    protected static string $resource = PrayerTimeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
