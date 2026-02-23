<?php

namespace App\Filament\Resources\RamadhanPeriods\Pages;

use App\Filament\Resources\RamadhanPeriods\RamadhanPeriodResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditRamadhanPeriod extends EditRecord
{
    protected static string $resource = RamadhanPeriodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
