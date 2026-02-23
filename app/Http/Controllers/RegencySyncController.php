<?php

namespace App\Http\Controllers;

use App\Models\Regency;
use App\Models\SyncLog;
use App\Services\PrayerTimeApiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class RegencySyncController extends Controller
{
    public function index(): View
    {
        $regencies = Regency::query()
            ->orderBy('name')
            ->paginate(20);

        return view('regency-sync.index', compact('regencies'));
    }

    public function sync(Request $request, Regency $regency, PrayerTimeApiService $service): RedirectResponse
    {
        $validated = $request->validate([
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
        ]);

        $syncTime = now();

        try {
            $result = $service->fetchAndStore(
                regencyCode: $regency->code,
                startDate: $validated['start_date'],
                endDate: $validated['end_date'],
            );

            $regency->update(['last_synced_at' => $syncTime]);

            SyncLog::query()->create([
                'sync_type' => 'prayer_times',
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'sync_time' => $syncTime,
                'status' => 'Success',
                'notes' => "Synced {$result['synced']} records for {$regency->name}",
                'synced_by' => Auth::id(),
            ]);

            Log::info('RegencySyncController: Sync complete', [
                'regency' => $regency->code,
                'synced' => $result['synced'],
            ]);

            return redirect()->route('regency-sync.index')
                ->with('success', "Sinkronisasi {$regency->name} berhasil ({$result['synced']} data).");
        } catch (\Throwable $e) {
            SyncLog::query()->create([
                'sync_type' => 'prayer_times',
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'sync_time' => $syncTime,
                'status' => 'Failed',
                'notes' => $e->getMessage(),
                'synced_by' => Auth::id(),
            ]);

            Log::error('RegencySyncController: Sync failed', [
                'regency' => $regency->code,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('regency-sync.index')
                ->with('error', "Sinkronisasi {$regency->name} gagal: {$e->getMessage()}");
        }
    }
}
