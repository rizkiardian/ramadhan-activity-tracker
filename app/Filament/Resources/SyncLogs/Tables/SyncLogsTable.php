<?php

namespace App\Filament\Resources\SyncLogs\Tables;

use App\Enums\SyncCategory;
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
                TextColumn::make('sync_category')
                    ->label('Kategori Sinkronisasi')
                    ->badge()
                    ->getStateUsing(fn ($record): string => $record->sync_category?->label() ?? '-')
                    ->color(fn ($record): string => $record->sync_category?->color() ?? 'gray')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Success' => 'success',
                        'Failed' => 'danger',
                        'Pending' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'Success' => 'Berhasil',
                        'Failed' => 'Gagal',
                        'Pending' => 'Menunggu',
                        default => $state,
                    })
                    ->sortable(),
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
                TextColumn::make('syncedBy.name')
                    ->label('Oleh')
                    ->sortable(),
                TextColumn::make('notes')
                    ->label('Catatan')
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sync_time', 'desc')
            ->filters([
                SelectFilter::make('sync_category')
                    ->label('Kategori')
                    ->options([
                        SyncCategory::Regency->value => 'Sinkronisasi Kota',
                        SyncCategory::PrayerTime->value => 'Sinkronisasi Jadwal Sholat',
                    ]),
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'Success' => 'Berhasil',
                        'Failed' => 'Gagal',
                        'Pending' => 'Menunggu',
                    ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateIcon('heroicon-o-arrow-path-rounded-square')
            ->emptyStateHeading('Belum Ada Log Sinkronisasi')
            ->emptyStateDescription('Log akan muncul setelah sinkronisasi kota atau jadwal sholat dilakukan.');
    }
}
