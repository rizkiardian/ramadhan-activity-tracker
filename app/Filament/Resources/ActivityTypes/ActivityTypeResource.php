<?php

namespace App\Filament\Resources\ActivityTypes;

use App\Filament\Resources\ActivityTypes\Pages\CreateActivityType;
use App\Filament\Resources\ActivityTypes\Pages\EditActivityType;
use App\Filament\Resources\ActivityTypes\Pages\ListActivityTypes;
use App\Filament\Resources\ActivityTypes\Schemas\ActivityTypeForm;
use App\Filament\Resources\ActivityTypes\Tables\ActivityTypesTable;
use App\Models\ActivityType;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ActivityTypeResource extends Resource
{
    protected static ?string $model = ActivityType::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static ?string $navigationLabel = 'Jenis Aktivitas';

    protected static ?string $modelLabel = 'Jenis Aktivitas';

    protected static ?string $pluralModelLabel = 'Jenis Aktivitas';

    protected static \UnitEnum|string|null $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return ActivityTypeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ActivityTypesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListActivityTypes::route('/'),
            'create' => CreateActivityType::route('/create'),
            'edit' => EditActivityType::route('/{record}/edit'),
        ];
    }
}
