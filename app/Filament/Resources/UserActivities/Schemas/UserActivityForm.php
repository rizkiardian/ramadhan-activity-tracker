<?php

namespace App\Filament\Resources\UserActivities\Schemas;

use App\Models\RamadhanPeriod;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Carbon;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Utilities\Get;

class UserActivityForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail Aktivitas')
                    ->columns(2)
                    ->schema([
                        Select::make('user_id')
                            ->label('Pengguna')
                            ->relationship('user', 'name')
                            ->default(fn() => auth()->id())
                            ->disabled()
                            ->dehydrated()
                            ->required(),
                        Select::make('activity_type_id')
                            ->label('Jenis Aktivitas')
                            ->relationship('activityType', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        // 1. Field Pilih Periode (Select)
                        Select::make('ramadhan_day')
                            ->label('Periode Ramadhan')
                            ->required()
                            ->options(function (Get $get) {
                                $year = $get('date')
                                    ? Carbon::parse($get('date'))->year
                                    : now()->year;
                                $period = RamadhanPeriod::where('year', $year)->first();
                                if (!$period) return [];
                                $start = Carbon::parse($period->start_date);
                                $end = Carbon::parse($period->end_date);
                                $totalDays = $start->diffInDays($end) + 1;
                                return collect(range(1, $totalDays))
                                    ->mapWithKeys(fn($i) => [$i => "Hari ke-{$i} Ramadhan"])
                                    ->toArray();
                            })
                            ->live()
                            ->afterStateUpdated(function ($state, Set $set) {
                                $period = RamadhanPeriod::where('year', now()->year)->first();
                                if ($period && $state) {
                                    $date = Carbon::parse($period->start_date)
                                        ->addDays((int)$state - 1)
                                        ->format('Y-m-d');
                                    $set('date', $date);
                                }
                            })
                            ->afterStateHydrated(function ($state, Set $set, Get $get) {
                                $recordDate = $get('date');
                                if (!$recordDate) return;
                                $date = Carbon::parse($recordDate);
                                $year = $date->year;
                                $period = RamadhanPeriod::where('year', $year)->first();
                                if (!$period) return;
                                $start = Carbon::parse($period->start_date);
                                // mapping: tanggal → hari ke berapa
                                $ramadhanDay = $start->diffInDays($date) + 2;
                                $set('ramadhan_day', $ramadhanDay);
                            }),
                        DatePicker::make('date')
                            ->label('Tanggal')
                            ->required()
                            ->native(false)
                            ->displayFormat('d M Y')
                            ->readonly(),
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'Pending' => 'Pending',
                                'Done' => 'Selesai',
                                'Skipped' => 'Dilewati',
                            ])
                            ->default('Pending')
                            ->required()
                            ->native(false),
                        TimePicker::make('start_time')
                            ->label('Waktu Mulai')
                            ->required()
                            ->seconds(false),
                        TimePicker::make('end_time')
                            ->label('Waktu Selesai')
                            ->seconds(false),
                        Textarea::make('notes')
                            ->label('Catatan')
                            ->columnSpanFull()
                            ->rows(3),
                    ]),
            ]);
    }
}
