<?php

namespace App\Filament\Resources\SyncLogs\Pages;

use App\Filament\Resources\SyncLogs\SyncLogResource;
use Filament\Resources\Pages\ListRecords;

class ListSyncLogs extends ListRecords
{
    protected static string $resource = SyncLogResource::class;
}
