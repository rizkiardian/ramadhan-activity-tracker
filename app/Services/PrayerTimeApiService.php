<?php

namespace App\Services;

use App\Models\PrayerTime;
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
     * Fetch ALL prayer times for a regency from the API (all pages),
     * delete existing records for that regency, and insert fresh data.
     *
     * @return array{synced: int}
     *
     * @throws ConnectionException
     * @throws \RuntimeException
     */
    public function fetchAndStore(
        string $regencyCode = '3171', // 3171 = Jakarta Selatan; see /regional/indonesia/prayer-times/regencies
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

        // Hapus semua data lama, ganti dengan data sinkronisasi baru
        PrayerTime::query()->truncate();

        $now = now();

        PrayerTime::query()->insert(
            array_map(fn (array $item) => [
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

            $items = array_map(fn (array $item) => [
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
