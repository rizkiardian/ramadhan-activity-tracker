<?php

namespace App\Filament\Resources\RamadhanPeriods\Pages;

use App\Filament\Resources\RamadhanPeriods\RamadhanPeriodResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRamadhanPeriods extends ListRecords
{
    protected static string $resource = RamadhanPeriodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
