<?php

namespace App\Filament\Resources\Regencies;

use App\Filament\Resources\Regencies\Pages\ListRegencies;
use App\Filament\Resources\Regencies\Schemas\RegencyForm;
use App\Filament\Resources\Regencies\Tables\RegenciesTable;
use App\Models\Regency;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RegencyResource extends Resource
{
    protected static ?string $model = Regency::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMapPin;

    protected static ?string $navigationLabel = 'Kabupaten/Kota';

    protected static ?string $modelLabel = 'Kabupaten/Kota';

    protected static ?string $pluralModelLabel = 'Kabupaten/Kota';

    protected static \UnitEnum|string|null $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 1;

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
        return RegencyForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RegenciesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRegencies::route('/'),
        ];
    }
}
