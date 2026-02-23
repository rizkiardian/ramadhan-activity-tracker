<?php

namespace App\Filament\Resources\UserActivities;

use App\Filament\Resources\UserActivities\Pages\CreateUserActivity;
use App\Filament\Resources\UserActivities\Pages\EditUserActivity;
use App\Filament\Resources\UserActivities\Pages\ListUserActivities;
use App\Filament\Resources\UserActivities\Schemas\UserActivityForm;
use App\Filament\Resources\UserActivities\Tables\UserActivitiesTable;
use App\Models\UserActivity;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class UserActivityResource extends Resource
{
    protected static ?string $model = UserActivity::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $navigationLabel = 'Aktivitas Pengguna';

    protected static ?string $modelLabel = 'Aktivitas';

    protected static ?string $pluralModelLabel = 'Aktivitas Pengguna';

    protected static \UnitEnum|string|null $navigationGroup = 'Kegiatan';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return UserActivityForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UserActivitiesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUserActivities::route('/'),
            'create' => CreateUserActivity::route('/create'),
            'edit' => EditUserActivity::route('/{record}/edit'),
        ];
    }
}
