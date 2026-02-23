<?php

namespace App\Filament\Resources\UserActivities\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UserActivitiesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Pengguna')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('activityType.name')
                    ->label('Jenis Aktivitas')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('primary'),
                TextColumn::make('date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('start_time')
                    ->label('Mulai')
                    ->time('H:i'),
                TextColumn::make('end_time')
                    ->label('Selesai')
                    ->time('H:i'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Done' => 'success',
                        'Pending' => 'warning',
                        'Skipped' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'Done' => 'Selesai',
                        'Skipped' => 'Dilewati',
                        default => $state,
                    }),
                TextColumn::make('notes')
                    ->label('Catatan')
                    ->limit(40)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('date', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'Pending' => 'Pending',
                        'Done' => 'Selesai',
                        'Skipped' => 'Dilewati',
                    ]),
                SelectFilter::make('activity_type_id')
                    ->label('Jenis Aktivitas')
                    ->relationship('activityType', 'name'),
            ])
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
