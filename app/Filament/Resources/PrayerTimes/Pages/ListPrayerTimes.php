<?php

namespace App\Filament\Resources\PrayerTimes\Pages;

use App\Filament\Resources\PrayerTimes\PrayerTimeResource;
use App\Models\SyncLog;
use App\Services\PrayerTimeApiService;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Auth;

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
                    $syncedAt = now();

                    try {
                        $result = $service->fetchAndStore();

                        SyncLog::query()->create([
                            'sync_type' => 'prayer_times',
                            'start_date' => now()->startOfMonth()->toDateString(),
                            'end_date' => now()->endOfMonth()->toDateString(),
                            'sync_time' => $syncedAt,
                            'status' => 'Success',
                            'notes' => "{$result['synced']} records synced.",
                            'synced_by' => Auth::id(),
                        ]);

                        Notification::make()
                            ->title('Sinkronisasi Berhasil')
                            ->body("{$result['synced']} data jadwal sholat berhasil disinkronisasi.")
                            ->icon(Heroicon::OutlinedCheckCircle)
                            ->success()
                            ->duration(6000)
                            ->send();
                    } catch (ConnectionException $e) {
                        SyncLog::query()->create([
                            'sync_type' => 'prayer_times',
                            'start_date' => now()->startOfMonth()->toDateString(),
                            'end_date' => now()->endOfMonth()->toDateString(),
                            'sync_time' => $syncedAt,
                            'status' => 'Failed',
                            'notes' => 'Connection error: '.$e->getMessage(),
                            'synced_by' => Auth::id(),
                        ]);

                        Notification::make()
                            ->title('Gagal Terhubung ke API')
                            ->body('Periksa koneksi internet atau coba beberapa saat lagi.')
                            ->icon(Heroicon::OutlinedExclamationCircle)
                            ->danger()
                            ->persistent()
                            ->send();
                    } catch (\RuntimeException $e) {
                        SyncLog::query()->create([
                            'sync_type' => 'prayer_times',
                            'start_date' => now()->startOfMonth()->toDateString(),
                            'end_date' => now()->endOfMonth()->toDateString(),
                            'sync_time' => $syncedAt,
                            'status' => 'Failed',
                            'notes' => $e->getMessage(),
                            'synced_by' => Auth::id(),
                        ]);

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
