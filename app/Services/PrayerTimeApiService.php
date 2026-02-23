<?php

namespace App\Services;

use App\Models\PrayerTime;
use App\Models\RamadhanPeriod;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PrayerTimeApiService
{
    private string $baseUrl;

    private string $apiKey;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.co_api.url'), '/');
        $this->apiKey = config('services.co_api.key');
    }

    /**
     * Fetch prayer times for a regency covering the Ramadhan period of a given year.
     * Deletes existing records for that regency+year and inserts fresh data.
     *
     * @return array{synced: int, year: int, regency_code: string, start_date: string, end_date: string}
     *
     * @throws ConnectionException
     * @throws \RuntimeException
     */
    public function fetchAndStoreForRamadhan(
        int $year,
        string $regencyCode,
    ): array {
        $period = RamadhanPeriod::query()
            ->forYear($year)
            ->whereNull('deleted_at')
            ->first();

        if (! $period) {
            throw new \RuntimeException(
                "Periode Ramadhan untuk tahun {$year} tidak ditemukan. Tambahkan data periode terlebih dahulu."
            );
        }

        $startDate = $period->start_date->toDateString();
        $endDate = $period->end_date->toDateString();

        $result = $this->fetchAndStore($regencyCode, $startDate, $endDate);

        return array_merge($result, [
            'year' => $year,
            'regency_code' => $regencyCode,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);
    }

    /**
     * Fetch ALL prayer times for a regency within a date range from the API,
     * delete existing records for that regency+date range, and insert fresh data.
     *
     * @return array{synced: int}
     *
     * @throws ConnectionException
     * @throws \RuntimeException
     */
    public function fetchAndStore(
        string $regencyCode = '3171',
        ?string $startDate = null,
        ?string $endDate = null,
    ): array {
        $allItems = [];
        $page = 1;

        do {
            $response = Http::withHeader('x-api-co-id', $this->apiKey)
                ->timeout(30)
                ->get("{$this->baseUrl}/regional/indonesia/prayer-times", array_filter([
                    'regency_code' => $regencyCode,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'page' => $page,
                ]));

            $json = $response->json();

            if ($response->failed() || ! ($json['is_success'] ?? false)) {
                Log::error('PrayerTimeApiService: API request failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                throw new \RuntimeException(
                    "API request failed with status {$response->status()}: {$response->body()}"
                );
            }

            $allItems = array_merge($allItems, data_get($json, 'data', []));

            $totalPages = data_get($json, 'paging.total_page', 1);
            $page++;
        } while ($page <= $totalPages);

        if (empty($allItems)) {
            return ['synced' => 0];
        }

        // Hapus data lama untuk regency + tanggal yang sama
        if ($startDate && $endDate) {
            PrayerTime::query()
                ->where('regency_code', $regencyCode)
                ->whereBetween('date', [$startDate, $endDate])
                ->delete();
        } else {
            PrayerTime::query()->where('regency_code', $regencyCode)->delete();
        }

        $now = now();

        PrayerTime::query()->insert(
            array_map(fn(array $item) => [
                'regency_code' => $item['regency_code'],
                'regency_name' => $item['regency_name'],
                'gmt' => $item['gmt'],
                'date' => $item['date'],
                'year' => $item['year'],
                'month' => $item['month'],
                'day' => $item['day'],
                'imsyak' => $item['imsyak'],
                'shubuh' => $item['shubuh'],
                'terbit' => $item['terbit'],
                'dhuha' => $item['dhuha'],
                'dzuhur' => $item['dzuhur'],
                'ashr' => $item['ashr'],
                'maghrib' => $item['maghrib'],
                'isya' => $item['isya'],
                'created_at' => $now,
                'updated_at' => $now,
            ], $allItems)
        );

        Log::info('PrayerTimeApiService: Sync complete', [
            'regency_code' => $regencyCode,
            'synced' => count($allItems),
        ]);

        return ['synced' => count($allItems)];
    }

    /**
     * Fetch all regencies from the API (all pages).
     *
     * @return list<array{code: string, name: string}>
     *
     * @throws ConnectionException
     * @throws \RuntimeException
     */
    public function fetchAllRegencies(): array
    {
        $allItems = [];
        $page = 1;

        do {
            $response = Http::withHeader('x-api-co-id', $this->apiKey)
                ->timeout(30)
                ->get("{$this->baseUrl}/regional/indonesia/prayer-times/regencies", [
                    'page' => $page,
                ]);

            $json = $response->json();

            if ($response->failed() || ! ($json['is_success'] ?? false)) {
                Log::error('PrayerTimeApiService: Regency API request failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                throw new \RuntimeException(
                    "Regency API request failed with status {$response->status()}: {$response->body()}"
                );
            }

            $items = array_map(fn(array $item) => [
                'code' => $item['code'],
                'name' => $item['name'],
            ], data_get($json, 'data', []));

            $allItems = array_merge($allItems, $items);

            $totalPages = data_get($json, 'paging.total_page', 1);
            $page++;
        } while ($page <= $totalPages);

        Log::info('PrayerTimeApiService: Regencies fetched', ['total' => count($allItems)]);

        return $allItems;
    }
}
