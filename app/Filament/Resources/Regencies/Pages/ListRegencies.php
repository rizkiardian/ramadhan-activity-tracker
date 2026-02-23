<?php

namespace App\Filament\Resources\Regencies\Pages;

use App\Filament\Resources\Regencies\RegencyResource;
use App\Services\PrayerTimeApiService;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\DB;

class ListRegencies extends ListRecords
{
    protected static string $resource = RegencyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('syncFromApi')
                ->label('Sinkronisasi API')
                ->icon(Heroicon::OutlinedArrowPath)
                ->color('warning')
                ->requiresConfirmation()
                ->modalIcon(Heroicon::OutlinedCloudArrowDown)
                ->modalHeading('Sinkronisasi Kabupaten/Kota')
                ->modalDescription('Data kabupaten/kota akan diambil dari API dan menggantikan seluruh data yang ada.')
                ->modalSubmitActionLabel('Ya, Sinkronisasi Sekarang')
                ->action(function (PrayerTimeApiService $service): void {
                    try {
                        $regencies = $service->fetchAllRegencies();

                        DB::table('regencies')->truncate();
                        DB::table('regencies')->insert($regencies);

                        Notification::make()
                            ->title('Sinkronisasi Berhasil')
                            ->body(count($regencies).' kabupaten/kota berhasil disinkronisasi.')
                            ->icon(Heroicon::OutlinedCheckCircle)
                            ->success()
                            ->duration(6000)
                            ->send();
                    } catch (ConnectionException $e) {
                        Notification::make()
                            ->title('Gagal Terhubung ke API')
                            ->body('Periksa koneksi internet atau coba beberapa saat lagi.')
                            ->icon(Heroicon::OutlinedExclamationCircle)
                            ->danger()
                            ->persistent()
                            ->send();
                    } catch (\RuntimeException $e) {
                        Notification::make()
                            ->title('Sinkronisasi Gagal')
                            ->body($e->getMessage())
                            ->icon(Heroicon::OutlinedExclamationTriangle)
                            ->danger()
                            ->persistent()
                            ->send();
                    }
                }),

            CreateAction::make(),
        ];
    }
}
