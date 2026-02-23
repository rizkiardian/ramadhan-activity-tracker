<?php

namespace App\Filament\Resources\UserActivities\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

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
                            ->required()
                            ->searchable()
                            ->preload(),
                        Select::make('activity_type_id')
                            ->label('Jenis Aktivitas')
                            ->relationship('activityType', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        DatePicker::make('date')
                            ->label('Tanggal')
                            ->required()
                            ->native(false)
                            ->displayFormat('d M Y'),
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
