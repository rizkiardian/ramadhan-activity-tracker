<?php

namespace App\Filament\Resources\PrayerTimes\Schemas;

use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Set;
use Filament\Schemas\Schema;

class PrayerTimeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Wilayah')
                    ->columns(3)
                    ->collapsible()
                    ->schema([
                        TextInput::make('regency_code')
                            ->label('Kode Kabupaten/Kota')
                            ->required()
                            ->maxLength(10),
                        TextInput::make('regency_name')
                            ->label('Nama Kabupaten/Kota')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(2),
                        TextInput::make('gmt')
                            ->label('GMT')
                            ->required()
                            ->numeric()
                            ->default(7),
                    ]),

                Section::make('Tanggal')
                    ->columns(4)
                    ->schema([
                        DatePicker::make('date')
                            ->label('Tanggal')
                            ->required()
                            ->native(false)
                            ->displayFormat('d M Y')
                            ->live()
                            ->afterStateUpdated(function (?string $state, Set $set): void {
                                if (! $state) {
                                    return;
                                }

                                $date = Carbon::parse($state);
                                $set('year', $date->year);
                                $set('month', $date->month);
                                $set('day', $date->day);
                            })
                            ->columnSpan(2),
                        TextInput::make('year')
                            ->label('Tahun')
                            ->required()
                            ->numeric()
                            ->readOnly(),
                        TextInput::make('month')
                            ->label('Bulan')
                            ->required()
                            ->numeric()
                            ->readOnly(),
                        TextInput::make('day')
                            ->label('Hari')
                            ->required()
                            ->numeric()
                            ->readOnly(),
                    ]),

                Section::make('Jadwal Sholat')
                    ->columns(4)
                    ->collapsible()
                    ->schema([
                        TextInput::make('imsyak')
                            ->label('Imsyak')
                            ->required()
                            ->placeholder('HH:MM')
                            ->maxLength(5),
                        TextInput::make('shubuh')
                            ->label('Shubuh')
                            ->required()
                            ->placeholder('HH:MM')
                            ->maxLength(5),
                        TextInput::make('terbit')
                            ->label('Terbit')
                            ->required()
                            ->placeholder('HH:MM')
                            ->maxLength(5),
                        TextInput::make('dhuha')
                            ->label('Dhuha')
                            ->required()
                            ->placeholder('HH:MM')
                            ->maxLength(5),
                        TextInput::make('dzuhur')
                            ->label('Dzuhur')
                            ->required()
                            ->placeholder('HH:MM')
                            ->maxLength(5),
                        TextInput::make('ashr')
                            ->label('Ashr')
                            ->required()
                            ->placeholder('HH:MM')
                            ->maxLength(5),
                        TextInput::make('maghrib')
                            ->label('Maghrib')
                            ->required()
                            ->placeholder('HH:MM')
                            ->maxLength(5),
                        TextInput::make('isya')
                            ->label('Isya')
                            ->required()
                            ->placeholder('HH:MM')
                            ->maxLength(5),
                    ]),
            ]);
    }
}
