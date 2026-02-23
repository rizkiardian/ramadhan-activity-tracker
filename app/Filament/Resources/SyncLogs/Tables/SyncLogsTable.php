<?php

namespace App\Filament\Resources\SyncLogs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SyncLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sync_type')
                    ->label('Tipe Sinkronisasi')
                    ->badge()
                    ->color('primary')
                    ->searchable(),
                TextColumn::make('start_date')
                    ->label('Dari Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('end_date')
                    ->label('Sampai Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('sync_time')
                    ->label('Waktu Sinkronisasi')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Success' => 'success',
                        'Failed' => 'danger',
                        'Pending' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('syncedBy.name')
                    ->label('Oleh')
                    ->sortable(),
                TextColumn::make('notes')
                    ->label('Catatan')
                    ->limit(40)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sync_time', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'Success' => 'Berhasil',
                        'Failed' => 'Gagal',
                        'Pending' => 'Pending',
                    ]),
                SelectFilter::make('sync_type')
                    ->label('Tipe')
                    ->options([
                        'prayer_times' => 'Prayer Times',
                        'regencies' => 'Regencies',
                    ]),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
