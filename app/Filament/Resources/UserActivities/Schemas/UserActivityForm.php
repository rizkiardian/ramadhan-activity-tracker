<?php

namespace App\Filament\Resources\UserActivities\Schemas;

use App\Models\RamadhanPeriod;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Carbon;

class UserActivityForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Aktivitas')
                    ->description('Pilih pengguna dan jenis kegiatan Ramadhan.')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->columns(2)
                    ->schema([
                        Select::make('user_id')
                            ->label('Pengguna')
                            ->relationship('user', 'name')
                            ->default(fn () => auth()->id())
                            ->disabled()
                            ->dehydrated()
                            ->required()
                            ->prefixIcon('heroicon-o-user'),
                        Select::make('activity_type_id')
                            ->label('Jenis Aktivitas')
                            ->relationship('activityType', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->prefixIcon('heroicon-o-tag'),
                    ]),

                Section::make('Waktu & Hari Ramadhan')
                    ->description('Tentukan tanggal dan waktu pelaksanaan aktivitas.')
                    ->icon('heroicon-o-calendar-days')
                    ->columns(2)
                    ->schema([
                        Select::make('ramadhan_day')
                            ->label('Hari Ramadhan')
                            ->required()
                            ->options(function (Get $get) {
                                $year = $get('date')
                                    ? Carbon::parse($get('date'))->year
                                    : now()->year;
                                $period = RamadhanPeriod::where('year', $year)->first();
                                if (! $period) {
                                    return [];
                                }
                                $start = Carbon::parse($period->start_date);
                                $end = Carbon::parse($period->end_date);
                                $totalDays = $start->diffInDays($end) + 1;

                                return collect(range(1, $totalDays))
                                    ->mapWithKeys(fn ($i) => [$i => "Hari ke-{$i} Ramadhan"])
                                    ->toArray();
                            })
                            ->live()
                            ->afterStateUpdated(function ($state, Set $set) {
                                $period = RamadhanPeriod::where('year', now()->year)->first();
                                if ($period && $state) {
                                    $date = Carbon::parse($period->start_date)
                                        ->addDays((int) $state - 1)
                                        ->format('Y-m-d');
                                    $set('date', $date);
                                }
                            })
                            ->afterStateHydrated(function ($state, Set $set, Get $get) {
                                $recordDate = $get('date');
                                if (! $recordDate) {
                                    return;
                                }
                                $date = Carbon::parse($recordDate);
                                $year = $date->year;
                                $period = RamadhanPeriod::where('year', $year)->first();
                                if (! $period) {
                                    return;
                                }
                                $start = Carbon::parse($period->start_date);
                                $ramadhanDay = $start->diffInDays($date) + 2;
                                $set('ramadhan_day', $ramadhanDay);
                            })
                            ->prefixIcon('heroicon-o-moon'),

                        DatePicker::make('date')
                            ->label('Tanggal')
                            ->required()
                            ->native(false)
                            ->displayFormat('d M Y')
                            ->readonly()
                            ->prefixIcon('heroicon-o-calendar'),

                        TimePicker::make('start_time')
                            ->label('Waktu Mulai')
                            ->required()
                            ->seconds(false)
                            ->prefixIcon('heroicon-o-clock'),

                        TimePicker::make('end_time')
                            ->label('Waktu Selesai')
                            ->seconds(false)
                            ->prefixIcon('heroicon-o-clock'),
                    ]),

                Section::make('Status & Catatan')
                    ->description('Tandai status kegiatan dan tambahkan catatan jika diperlukan.')
                    ->icon('heroicon-o-pencil-square')
                    ->schema([
                        Select::make('status')
                            ->label('Status Kegiatan')
                            ->options([
                                'Pending' => 'Pending',
                                'Done' => 'Selesai',
                                'Skipped' => 'Dilewati',
                            ])
                            ->default('Pending')
                            ->required()
                            ->native(false)
                            ->prefixIcon('heroicon-o-check-circle'),
                        Textarea::make('notes')
                            ->label('Catatan')
                            ->columnSpanFull()
                            ->rows(4)
                            ->placeholder('Tulis catatan atau refleksi kegiatan di sini...'),
                    ]),
            ]);
    }
}
