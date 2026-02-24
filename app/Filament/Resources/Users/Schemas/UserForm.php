<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Pengguna')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama')
                            ->required()
                            ->maxLength(255),
                        Select::make('gender')
                            ->label('Jenis Kelamin')
                            ->options([
                                'Male' => 'Laki-laki',
                                'Female' => 'Perempuan',
                            ])
                            ->native(false)
                            ->required(),
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->revealable()
                            ->dehydrateStateUsing(fn(string $state) => bcrypt($state))
                            ->dehydrated(fn(?string $state) => filled($state))
                            ->required(fn(string $operation) => $operation === 'create'),
                        Select::make('roles')
                            ->label('Role')
                            ->relationship('roles', 'name')
                            ->preload()
                            ->native(false)
                            ->required()
                            ->disabled(fn(string $operation) => $operation === 'edit'),
                    ]),
            ]);
    }
}
