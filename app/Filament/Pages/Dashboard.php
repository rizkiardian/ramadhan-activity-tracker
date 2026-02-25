<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\DatePicker;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Schemas\Schema;

class Dashboard extends \Filament\Pages\Dashboard
{
  use HasFiltersForm;

  public function filtersForm(Schema $schema): Schema
  {
    return $schema->components([
      DatePicker::make('date_from')
        ->label('Dari Tanggal')
        ->native(false)
        ->displayFormat('d/m/Y')
        ->placeholder('Pilih tanggal awal')
        ->maxDate(now()),

      DatePicker::make('date_to')
        ->label('Sampai Tanggal')
        ->native(false)
        ->displayFormat('d/m/Y')
        ->placeholder('Pilih tanggal akhir')
        ->maxDate(now()),
    ]);
  }
}
