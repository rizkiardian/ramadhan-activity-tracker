<?php

namespace App\Filament\Resources\PrayerTimes\Tables;

use App\Models\PrayerTime;
use App\Models\RamadhanPeriod;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PrayerTimesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('regency_name')
                    ->label('Kabupaten/Kota')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                TextColumn::make('date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('ramadhan_day')
                    ->label('Hari Ramadhan Ke-')
                    ->getStateUsing(function (PrayerTime $record): string {
                        $period = RamadhanPeriod::query()
                            ->forYear($record->year)
                            ->whereNull('deleted_at')
                            ->first();

                        if (! $period) {
                            return '-';
                        }

                        $day = $period->start_date->diffInDays($record->date) + 1;

                        if ($day < 1 || $day > 30) {
                            return '-';
                        }

                        return "Hari ke-{$day}";
                    })
                    ->badge()
                    ->color('warning'),
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
                    ->label('Maghrib / Buka Puasa')
                    ->toggleable(),
                TextColumn::make('isya')
                    ->label('Isya')
                    ->toggleable(),
                TextColumn::make('regency_code')
                    ->label('Kode')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('year')
                    ->label('Tahun')
                    ->options(
                        fn(): array => PrayerTime::query()
                            ->distinct()
                            ->orderBy('year', 'desc')
                            ->pluck('year', 'year')
                            ->toArray()
                    ),
                SelectFilter::make('regency_code')
                    ->label('Kabupaten/Kota')
                    ->options(
                        fn(): array => PrayerTime::query()
                            ->distinct()
                            ->orderBy('regency_name')
                            ->pluck('regency_name', 'regency_code')
                            ->toArray()
                    )
                    ->searchable(),
            ])
            ->defaultSort('date')
            ->striped()
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateIcon('heroicon-o-clock')
            ->emptyStateHeading('Belum Ada Data Jadwal Sholat')
            ->emptyStateDescription('Gunakan tombol "Sinkronisasi Jadwal Sholat" untuk mengambil data dari API.')
            ->emptyStateActions([]);
    }
}
