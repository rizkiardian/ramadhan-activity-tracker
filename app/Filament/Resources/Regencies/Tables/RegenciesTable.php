<?php

namespace App\Filament\Resources\Regencies\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RegenciesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('Kode')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->width('100px'),
                TextColumn::make('name')
                    ->label('Nama Kabupaten/Kota')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                TextColumn::make('prayer_times_count')
                    ->label('Data Jadwal Sholat')
                    ->counts('prayerTimes')
                    ->sortable()
                    ->badge()
                    ->color(fn(int $state): string => $state > 0 ? 'success' : 'gray'),
                TextColumn::make('last_synced_at')
                    ->label('Sinkronisasi Terakhir')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->placeholder('Belum pernah')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('code')
            ->recordAction(null)
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateIcon('heroicon-o-map-pin')
            ->emptyStateHeading('Belum Ada Data Kabupaten/Kota')
            ->emptyStateDescription('Gunakan tombol "Sinkronisasi API" untuk mengambil data kota dari API.');
    }
}
