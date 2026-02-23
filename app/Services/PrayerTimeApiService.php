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
     * Fetch prayer times from the API and upsert into the database.
     *
     * @return array{inserted: int, updated: int}
     *
     * @throws ConnectionException
     * @throws \RuntimeException
     */
    public function fetchAndStore(
        string $regencyCode = '3171', // 3171 = Jakarta Selatan; see /regional/indonesia/prayer-times/regencies
        ?string $startDate = null,
        ?string $endDate = null,
    ): array {
        $response = Http::withHeader('x-api-co-id', $this->apiKey)
            ->timeout(30)
            ->get("{$this->baseUrl}/regional/indonesia/prayer-times", array_filter([
                'regency_code' => $regencyCode,
                'start_date' => $startDate,
                'end_date' => $endDate,
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

        $items = data_get($json, 'data', []);

        if (empty($items)) {
            return ['inserted' => 0, 'updated' => 0];
        }

        $countBefore = PrayerTime::query()->count();

        PrayerTime::query()->upsert(
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
            ], $items),
            uniqueBy: ['regency_code', 'date'],
            update: ['imsyak', 'shubuh', 'terbit', 'dhuha', 'dzuhur', 'ashr', 'maghrib', 'isya', 'updated_at']
        );

        $countAfter = PrayerTime::query()->count();

        $inserted = max(0, $countAfter - $countBefore);
        $updated = count($items) - $inserted;

        Log::info('PrayerTimeApiService: Sync complete', [
            'total' => count($items),
            'inserted' => $inserted,
            'updated' => $updated,
        ]);

        return ['inserted' => $inserted, 'updated' => $updated];
    }
}
