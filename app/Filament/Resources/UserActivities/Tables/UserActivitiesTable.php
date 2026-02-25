<?php

namespace App\Filament\Resources\UserActivities\Tables;

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
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
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
                TextColumn::make('ramadhan_day')
                    ->label('Hari Ramadhan')
                    ->badge()
                    ->color('success')
                    ->formatStateUsing(fn($state) => $state ? "Hari ke-{$state}" : '-'),
                TextColumn::make('date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('start_time')
                    ->label('Mulai')
                    ->time('H:i'),
                TextColumn::make('end_time')
                    ->label('Selesai')
                    ->time('H:i')
                    ->placeholder('-'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Done' => 'success',
                        'Pending' => 'warning',
                        'Skipped' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'Done' => 'Selesai',
                        'Skipped' => 'Dilewati',
                        default => $state,
                    })
                    ->sortable(),
                TextColumn::make('notes')
                    ->label('Catatan')
                    ->limit(40)
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->placeholder('-'),
            ])
            ->filters([
                Filter::make('date')
                    ->label('Tanggal')
                    ->schema([
                        DatePicker::make('date_from')
                            ->label('Dari')
                            ->date()
                            ->placeholder('Tanggal mulai')
                            ->native(false)
                            ->live()
                            ->minDate(fn($get) => RamadhanPeriod::current()?->start_date)
                            ->maxDate(fn($get) => $get('date_to') ?: RamadhanPeriod::current()?->end_date),
                        DatePicker::make('date_to')
                            ->label('Sampai')
                            ->date()
                            ->placeholder('Tanggal akhir')
                            ->native(false)
                            ->live()
                            ->minDate(fn($get) => $get('date_from') ?: RamadhanPeriod::current()?->start_date)
                            ->maxDate(fn($get) => RamadhanPeriod::current()?->end_date),
                    ])
                    ->query(function ($query, $data) {
                        if ($data['date_from']) {
                            $query->whereDate('date', '>=', $data['date_from']);
                        }
                        if ($data['date_to']) {
                            $query->whereDate('date', '<=', $data['date_to']);
                        }
                    }),
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'Pending' => 'Pending',
                        'Done' => 'Selesai',
                        'Skipped' => 'Dilewati',
                    ]),
                SelectFilter::make('activity_type_id')
                    ->label('Jenis Aktivitas')
                    ->relationship('activityType', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('user_id')
                    ->label('Pengguna')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->visible(fn() => auth()->user()->hasRole('super_admin')),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make()
                        ->visible(fn($record): bool => $record->user_id === auth()->id()),
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
            ->defaultSort('created_at', 'desc')
            ->emptyStateIcon('heroicon-o-clipboard-document-list')
            ->emptyStateHeading('Belum Ada Aktivitas')
            ->emptyStateDescription('Mulai tambahkan aktivitas harian Ramadhan Anda.');
    }
}
