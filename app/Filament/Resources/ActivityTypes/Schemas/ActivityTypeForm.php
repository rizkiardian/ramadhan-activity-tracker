<?php

namespace App\Filament\Resources\ActivityTypes\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class ActivityTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Jenis Aktivitas')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Aktivitas')
                            ->required()
                            ->maxLength(255),
                        Select::make('created_by')
                            ->label('Dibuat Oleh')
                            ->relationship('createdBy', 'name')
                            ->default(fn () => Auth::id())
                            ->required()
                            ->searchable()
                            ->preload(),
                    ]),
            ]);
    }
}
