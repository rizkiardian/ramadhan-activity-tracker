<?php

namespace App\Filament\Resources\PrayerTimes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PrayerTimesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('regency_code')
                    ->label('Kode')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                TextColumn::make('regency_name')
                    ->label('Kabupaten/Kota')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                TextColumn::make('date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('imsyak')
                    ->label('Imsyak')
                    ->toggleable(),
                TextColumn::make('shubuh')
                    ->label('Shubuh')
                    ->toggleable(),
                TextColumn::make('terbit')
                    ->label('Terbit')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('dhuha')
                    ->label('Dhuha')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('dzuhur')
                    ->label('Dzuhur')
                    ->toggleable(),
                TextColumn::make('ashr')
                    ->label('Ashr')
                    ->toggleable(),
                TextColumn::make('maghrib')
                    ->label('Maghrib')
                    ->toggleable(),
                TextColumn::make('isya')
                    ->label('Isya')
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('month')
                    ->label('Bulan')
                    ->options([
                        1 => 'Januari',
                        2 => 'Februari',
                        3 => 'Maret',
                        4 => 'April',
                        5 => 'Mei',
                        6 => 'Juni',
                        7 => 'Juli',
                        8 => 'Agustus',
                        9 => 'September',
                        10 => 'Oktober',
                        11 => 'November',
                        12 => 'Desember',
                    ]),
                SelectFilter::make('year')
                    ->label('Tahun')
                    ->options(
                        fn (): array => \App\Models\PrayerTime::query()
                            ->distinct()
                            ->orderBy('year')
                            ->pluck('year', 'year')
                            ->toArray()
                    ),
            ])
            ->defaultSort('date')
            ->striped()
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
