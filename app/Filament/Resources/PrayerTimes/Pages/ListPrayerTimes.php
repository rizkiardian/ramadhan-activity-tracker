<?php

namespace App\Filament\Resources\PrayerTimes\Pages;

use App\Filament\Resources\PrayerTimes\PrayerTimeResource;
use App\Services\PrayerTimeApiService;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;
use Illuminate\Http\Client\ConnectionException;

class ListPrayerTimes extends ListRecords
{
    protected static string $resource = PrayerTimeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('fetchFromApi')
                ->label('Sinkronisasi API')
                ->icon(Heroicon::OutlinedArrowPath)
                ->color('warning')
                ->requiresConfirmation()
                ->modalIcon(Heroicon::OutlinedCloudArrowDown)
                ->modalHeading('Sinkronisasi Jadwal Sholat')
                ->modalDescription('Data jadwal sholat akan diambil dari API dan disimpan ke database. Proses ini mungkin memerlukan beberapa saat.')
                ->modalSubmitActionLabel('Ya, Sinkronisasi Sekarang')
                ->action(function (PrayerTimeApiService $service): void {
                    try {
                        $result = $service->fetchAndStore();

                        Notification::make()
                            ->title('Sinkronisasi Berhasil')
                            ->body(
                                "{$result['inserted']} data baru ditambahkan, "
                                    ."{$result['updated']} data diperbarui."
                            )
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
