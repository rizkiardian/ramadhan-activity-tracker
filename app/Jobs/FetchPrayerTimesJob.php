<?php

namespace App\Jobs;

use App\Services\PrayerTimeApiService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class FetchPrayerTimesJob implements ShouldQueue
{
    use Queueable;

    /** @var int Maximum number of attempts before failing */
    public int $tries = 3;

    /** @var int Timeout in seconds */
    public int $timeout = 60;

    public function handle(PrayerTimeApiService $service): void
    {
        try {
            $result = $service->fetchAndStore();

            Log::info('FetchPrayerTimesJob: Completed', $result);
        } catch (\Throwable $e) {
            Log::error('FetchPrayerTimesJob: Failed', ['error' => $e->getMessage()]);

            throw $e;
        }
    }
}
