<?php

namespace App\Filament\Resources\RamadhanPeriods;

use App\Filament\Resources\RamadhanPeriods\Pages\CreateRamadhanPeriod;
use App\Filament\Resources\RamadhanPeriods\Pages\EditRamadhanPeriod;
use App\Filament\Resources\RamadhanPeriods\Pages\ListRamadhanPeriods;
use App\Filament\Resources\RamadhanPeriods\Schemas\RamadhanPeriodForm;
use App\Filament\Resources\RamadhanPeriods\Tables\RamadhanPeriodsTable;
use App\Models\RamadhanPeriod;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RamadhanPeriodResource extends Resource
{
    protected static ?string $model = RamadhanPeriod::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMoon;

    protected static ?string $navigationLabel = 'Periode Ramadhan';

    protected static ?string $modelLabel = 'Periode Ramadhan';

    protected static ?string $pluralModelLabel = 'Periode Ramadhan';

    protected static \UnitEnum|string|null $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return RamadhanPeriodForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RamadhanPeriodsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRamadhanPeriods::route('/'),
            'create' => CreateRamadhanPeriod::route('/create'),
            'edit' => EditRamadhanPeriod::route('/{record}/edit'),
        ];
    }
}
