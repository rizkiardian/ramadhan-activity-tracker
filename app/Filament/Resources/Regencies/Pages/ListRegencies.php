<?php

namespace App\Filament\Resources\Regencies\Pages;

use App\Filament\Resources\Regencies\RegencyResource;
use App\Models\SyncLog;
use App\Services\PrayerTimeApiService;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Auth;
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
                    $syncedAt = now();

                    try {
                        $regencies = $service->fetchAllRegencies();

                        DB::table('regencies')->truncate();
                        DB::table('regencies')->insert($regencies);

                        SyncLog::query()->create([
                            'sync_type' => 'regencies',
                            'start_date' => now()->toDateString(),
                            'end_date' => now()->toDateString(),
                            'sync_time' => $syncedAt,
                            'status' => 'Success',
                            'notes' => count($regencies).' regencies synced.',
                            'synced_by' => Auth::id(),
                        ]);

                        Notification::make()
                            ->title('Sinkronisasi Berhasil')
                            ->body(count($regencies).' kabupaten/kota berhasil disinkronisasi.')
                            ->icon(Heroicon::OutlinedCheckCircle)
                            ->success()
                            ->duration(6000)
                            ->send();
                    } catch (ConnectionException $e) {
                        SyncLog::query()->create([
                            'sync_type' => 'regencies',
                            'start_date' => now()->toDateString(),
                            'end_date' => now()->toDateString(),
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
                            'sync_type' => 'regencies',
                            'start_date' => now()->toDateString(),
                            'end_date' => now()->toDateString(),
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
