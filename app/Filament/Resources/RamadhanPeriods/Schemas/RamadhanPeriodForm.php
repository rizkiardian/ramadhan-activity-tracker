<?php

namespace App\Filament\Resources\RamadhanPeriods\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RamadhanPeriodForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Periode Ramadhan')
                    ->icon('heroicon-o-moon')
                    ->columns(2)
                    ->schema([
                        TextInput::make('year')
                            ->label('Tahun Masehi')
                            ->required()
                            ->numeric()
                            ->minValue(2000)
                            ->maxValue(2100)
                            ->unique(ignoreRecord: true),
                        TextInput::make('hijri_year')
                            ->label('Tahun Hijriyah')
                            ->placeholder('contoh: 1447H')
                            ->maxLength(10),
                        DatePicker::make('start_date')
                            ->label('Tanggal Mulai Ramadhan')
                            ->required()
                            ->native(false)
                            ->displayFormat('d M Y')
                            ->before('end_date'),
                        DatePicker::make('end_date')
                            ->label('Tanggal Akhir Ramadhan')
                            ->required()
                            ->native(false)
                            ->displayFormat('d M Y')
                            ->after('start_date'),
                        Textarea::make('notes')
                            ->label('Catatan')
                            ->columnSpanFull()
                            ->rows(2),
                    ]),
            ]);
    }
}
