<?php

namespace App\Filament\Resources\PrayerTimes\Pages;

use App\Enums\SyncCategory;
use App\Filament\Resources\PrayerTimes\PrayerTimeResource;
use App\Models\Regency;
use App\Models\SyncLog;
use App\Services\PrayerTimeApiService;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
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
        $availableYears = array_combine(
            range(now()->year - 2, now()->year + 2),
            range(now()->year - 2, now()->year + 2)
        );

        return [
            Action::make('fetchFromApi')
                ->label('Sinkronisasi Jadwal Sholat')
                ->icon(Heroicon::OutlinedArrowPath)
                ->color('warning')
                ->modalIcon(Heroicon::OutlinedCloudArrowDown)
                ->modalHeading('Sinkronisasi Jadwal Sholat Ramadhan')
                ->modalDescription('Data jadwal sholat akan diambil dari API berdasarkan periode Ramadhan pada tahun yang dipilih. Proses ini mungkin memerlukan beberapa saat.')
                ->modalSubmitActionLabel('Sinkronisasi Sekarang')
                ->form([
                    Select::make('year')
                        ->label('Tahun Ramadhan')
                        ->options($availableYears)
                        ->default(now()->year)
                        ->required()
                        ->native(false)
                        ->helperText('Pastikan periode Ramadhan untuk tahun ini sudah terdaftar di menu Periode Ramadhan.'),
                    Select::make('regency_code')
                        ->label('Kabupaten/Kota')
                        ->options(fn (): array => Regency::query()->orderBy('name')->pluck('name', 'code')->toArray())
                        ->required()
                        ->searchable()
                        ->preload()
                        ->helperText('Pilih kota yang jadwal sholatnya akan disinkronisasi.'),
                ])
                ->action(function (array $data, PrayerTimeApiService $service): void {
                    $syncedAt = now();
                    $year = (int) $data['year'];
                    $regencyCode = $data['regency_code'];

                    try {
                        $result = $service->fetchAndStoreForRamadhan($year, $regencyCode);

                        SyncLog::query()->create([
                            'sync_type' => 'prayer_times',
                            'sync_category' => SyncCategory::PrayerTime,
                            'start_date' => $result['start_date'],
                            'end_date' => $result['end_date'],
                            'sync_time' => $syncedAt,
                            'status' => 'Success',
                            'notes' => "{$result['synced']} records synced for regency {$regencyCode} (Ramadhan {$year}).",
                            'synced_by' => Auth::id(),
                        ]);

                        Notification::make()
                            ->title('Sinkronisasi Berhasil')
                            ->body("{$result['synced']} data jadwal sholat Ramadhan {$year} berhasil disinkronisasi.")
                            ->icon(Heroicon::OutlinedCheckCircle)
                            ->success()
                            ->duration(6000)
                            ->send();
                    } catch (ConnectionException $e) {
                        SyncLog::query()->create([
                            'sync_type' => 'prayer_times',
                            'sync_category' => SyncCategory::PrayerTime,
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
                            'sync_type' => 'prayer_times',
                            'sync_category' => SyncCategory::PrayerTime,
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
        ];
    }
}
