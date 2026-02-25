<?php

namespace App\Filament\Resources\RamadhanPeriods\Tables;

use App\Models\RamadhanPeriod;
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

class RamadhanPeriodsTable
{
  public static function configure(Table $table): Table
  {
    return $table
      ->columns([
        TextColumn::make('year')
          ->label('Tahun')
          ->sortable()
          ->searchable()
          ->weight('bold')
          ->badge()
          ->color(fn(RamadhanPeriod $record): string => $record->year === now()->year ? 'success' : 'gray'),
        TextColumn::make('hijri_year')
          ->label('Tahun Hijriyah')
          ->sortable()
          ->badge()
          ->color('warning')
          ->placeholder('-'),
        TextColumn::make('start_date')
          ->label('Awal Ramadhan')
          ->date('d M Y')
          ->sortable(),
        TextColumn::make('end_date')
          ->label('Akhir Ramadhan')
          ->date('d M Y')
          ->sortable(),
        TextColumn::make('total_days')
          ->label('Jumlah Hari')
          ->getStateUsing(fn(RamadhanPeriod $record): int => $record->start_date->diffInDays($record->end_date) + 1)
          ->badge()
          ->color('info')
          ->suffix(' hari'),
        TextColumn::make('is_active')
          ->label('Status')
          ->getStateUsing(function (RamadhanPeriod $record): string {
            $today = now()->toDateString();
            if ($record->start_date->toDateString() <= $today && $record->end_date->toDateString() >= $today) {
              return 'Aktif';
            }
            if ($record->end_date->toDateString() < $today) {
              return 'Selesai';
            }

            return 'Mendatang';
          })
          ->badge()
          ->color(fn(string $state): string => match ($state) {
            'Aktif' => 'success',
            'Selesai' => 'gray',
            'Mendatang' => 'info',
            default => 'gray',
          }),
        TextColumn::make('notes')
          ->label('Catatan')
          ->limit(30)
          ->toggleable(isToggledHiddenByDefault: true)
          ->placeholder('-'),
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
      ->emptyStateIcon('heroicon-o-calendar')
      ->emptyStateHeading('Belum Ada Periode Ramadhan')
      ->emptyStateDescription('Tambahkan periode Ramadhan terlebih dahulu untuk mulai merekam kegiatan Ramadhan.');
  }
}
