<?php

namespace App\Filament\Resources\Regencies\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RegencyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Wilayah')
                    ->columns(3)
                    ->schema([
                        TextInput::make('code')
                            ->label('Kode')
                            ->required()
                            ->maxLength(10)
                            ->unique(ignoreRecord: true),
                        TextInput::make('name')
                            ->label('Nama Kabupaten/Kota')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(2),
                    ]),
            ]);
    }
}
