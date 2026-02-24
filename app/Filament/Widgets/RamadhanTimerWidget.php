<?php

namespace App\Filament\Widgets;

use App\Models\PrayerTime;
use App\Models\RamadhanPeriod;
use App\Models\Regency;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Carbon\Carbon;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class RamadhanTimerWidget extends Widget
{
    use HasWidgetShield;

    protected static ?int $sort = 1;

    protected string $view = 'filament.widgets.ramadhan-timer-widget';

    protected int|string|array $columnSpan = 'full';

    public ?array $prayerData = null;

    public bool $isRamadhan = false;

    public ?string $ramadhanDay = null;

    public ?int $maghribTimestamp = null;

    public ?string $selectedRegencyCode = null;

    /** @var array<string, string> */
    public array $regencies = [];

    public function mount(): void
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $this->selectedRegencyCode = $user->regency_code;

        $this->regencies = Regency::query()
            ->orderBy('name')
            ->pluck('name', 'code')
            ->toArray();

        $this->loadPrayerData();
    }

    public function updatedSelectedRegencyCode(): void
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->update(['regency_code' => $this->selectedRegencyCode ?: null]);

        $this->loadPrayerData();
    }

    private function loadPrayerData(): void
    {
        $this->prayerData = null;
        $this->maghribTimestamp = null;
        $this->isRamadhan = false;
        $this->ramadhanDay = null;

        $today = now()->toDateString();

        $period = RamadhanPeriod::query()
            ->whereNull('deleted_at')
            ->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->first();

        if (! $period) {
            return;
        }

        $this->isRamadhan = true;
        $dayNumber = (int) $period->start_date->diffInDays(now()) + 1;
        $this->ramadhanDay = "Hari ke-{$dayNumber}";

        if (! $this->selectedRegencyCode) {
            return;
        }

        $prayer = PrayerTime::query()
            ->where('date', $today)
            ->where('regency_code', $this->selectedRegencyCode)
            ->first();

        if ($prayer) {
            $this->prayerData = [
                'imsyak' => $prayer->imsyak,
                'shubuh' => $prayer->shubuh,
                'maghrib' => $prayer->maghrib,
                'isya' => $prayer->isya,
            ];

            $this->maghribTimestamp = Carbon::parse(now()->toDateString() . ' ' . $prayer->maghrib)->timestamp;
        }
    }
}
