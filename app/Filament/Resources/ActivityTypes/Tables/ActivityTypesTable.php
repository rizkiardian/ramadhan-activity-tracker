<?php

namespace App\Filament\Resources\ActivityTypes\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ActivityTypesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Aktivitas')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('createdBy.name')
                    ->label('Dibuat Oleh')
                    ->sortable(),
                TextColumn::make('user_activities_count')
                    ->label('Jumlah Aktivitas')
                    ->counts('userActivities')
                    ->sortable()
                    ->badge()
                    ->color('success'),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->label('Dihapus')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make(),
                    ForceDeleteAction::make(),
                    RestoreAction::make(),
                ])->tooltip(__('Actions')),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->recordUrl(null)
            ->defaultSort('created_at', 'desc')
            ->emptyStateIcon('heroicon-o-tag')
            ->emptyStateHeading('Belum Ada Jenis Aktivitas')
            ->emptyStateDescription('Tambahkan jenis aktivitas terlebih dahulu untuk mulai merekam kegiatan Ramadhan.');
    }
}
