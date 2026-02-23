<?php

namespace App\Filament\Resources\PrayerTimes;

use App\Filament\Resources\PrayerTimes\Pages\ListPrayerTimes;
use App\Filament\Resources\PrayerTimes\Schemas\PrayerTimeForm;
use App\Filament\Resources\PrayerTimes\Tables\PrayerTimesTable;
use App\Models\PrayerTime;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PrayerTimeResource extends Resource
{
    protected static ?string $model = PrayerTime::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    protected static ?string $navigationLabel = 'Jadwal Sholat';

    protected static ?string $modelLabel = 'Jadwal Sholat';

    protected static ?string $pluralModelLabel = 'Jadwal Sholat';

    protected static \UnitEnum|string|null $navigationGroup = 'Master Data';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return PrayerTimeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PrayerTimesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPrayerTimes::route('/'),
        ];
    }
}
