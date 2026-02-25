<?php

namespace App\Filament\Resources\RamadhanPeriods\Pages;

use App\Filament\Resources\RamadhanPeriods\RamadhanPeriodResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRamadhanPeriod extends CreateRecord
{
    protected static string $resource = RamadhanPeriodResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
