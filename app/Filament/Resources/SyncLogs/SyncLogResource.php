<?php

namespace App\Filament\Resources\SyncLogs;

use App\Filament\Resources\SyncLogs\Pages\ListSyncLogs;
use App\Filament\Resources\SyncLogs\Tables\SyncLogsTable;
use App\Models\SyncLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SyncLogResource extends Resource
{
    protected static ?string $model = SyncLog::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowPathRoundedSquare;

    protected static ?string $navigationLabel = 'Log Sinkronisasi';

    protected static ?string $modelLabel = 'Log Sinkronisasi';

    protected static ?string $pluralModelLabel = 'Log Sinkronisasi';

    protected static \UnitEnum|string|null $navigationGroup = 'Sistem';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema;
    }

    public static function table(Table $table): Table
    {
        return SyncLogsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSyncLogs::route('/'),
        ];
    }
}
