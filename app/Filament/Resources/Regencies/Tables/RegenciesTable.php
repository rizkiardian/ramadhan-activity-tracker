<?php

namespace App\Filament\Resources\Regencies\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
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
                    ->color('info'),
            ])
            ->defaultSort('code')
            ->recordAction(null)
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
