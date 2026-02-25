<?php

namespace App\Filament\Resources\UserActivities\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserActivityInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Aktivitas')
                    ->description('Rincian kegiatan Ramadhan')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('user.name')
                            ->label('Pengguna')
                            ->icon('heroicon-o-user')
                            ->weight(\Filament\Support\Enums\FontWeight::SemiBold),
                        TextEntry::make('activityType.name')
                            ->label('Jenis Aktivitas')
                            ->badge()
                            ->color('primary'),
                        TextEntry::make('ramadhan_day')
                            ->label('Hari Ramadhan')
                            ->badge()
                            ->color('success')
                            ->formatStateUsing(fn($state) => $state ? "Hari ke-{$state}" : '-'),
                        TextEntry::make('status')
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
                            }),
                    ]),

                Section::make('Waktu Pelaksanaan')
                    ->icon('heroicon-o-calendar-days')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('date')
                            ->label('Tanggal')
                            ->date('d F Y')
                            ->icon('heroicon-o-calendar'),
                        TextEntry::make('start_time')
                            ->label('Waktu Mulai')
                            ->formatStateUsing(fn($state) => $state ? \Illuminate\Support\Carbon::parse($state)->format('H:i') : '-')
                            ->icon('heroicon-o-clock'),
                        TextEntry::make('end_time')
                            ->label('Waktu Selesai')
                            ->formatStateUsing(fn($state) => $state ? \Illuminate\Support\Carbon::parse($state)->format('H:i') : '-')
                            ->icon('heroicon-o-clock')
                            ->placeholder('Belum diisi'),
                    ]),

                Section::make('Catatan')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->schema([
                        TextEntry::make('notes')
                            ->label('')
                            ->columnSpanFull()
                            ->placeholder('Tidak ada catatan.'),
                    ]),

                Section::make('Informasi Sistem')
                    ->icon('heroicon-o-information-circle')
                    ->columns(2)
                    ->collapsed()
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Dibuat Pada')
                            ->dateTime('d M Y, H:i'),
                        TextEntry::make('updated_at')
                            ->label('Terakhir Diperbarui')
                            ->dateTime('d M Y, H:i'),
                    ]),
            ]);
    }
}
