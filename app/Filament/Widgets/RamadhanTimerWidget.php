<?php

namespace App\Filament\Widgets;

use App\Models\PrayerTime;
use App\Models\RamadhanPeriod;
use Carbon\Carbon;
use Filament\Widgets\Widget;

class RamadhanTimerWidget extends Widget
{
    protected static ?int $sort = 1;

    protected string $view = 'filament.widgets.ramadhan-timer-widget';

    protected int|string|array $columnSpan = 'full';

    public ?array $prayerData = null;

    public bool $isRamadhan = false;

    public ?string $ramadhanDay = null;

    public ?int $maghribTimestamp = null;

    public function mount(): void
    {
        $this->loadPrayerData();
    }

    private function loadPrayerData(): void
    {
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
        $dayNumber = $period->start_date->diffInDays(now()) + 1;
        $this->ramadhanDay = "Hari ke-{$dayNumber}";

        $prayer = PrayerTime::query()
            ->where('date', $today)
            ->first();

        if ($prayer) {
            $this->prayerData = [
                'imsyak' => $prayer->imsyak,
                'shubuh' => $prayer->shubuh,
                'maghrib' => $prayer->maghrib,
                'isya' => $prayer->isya,
            ];
            $this->maghribTimestamp = now()->startOfDay()
                ->addHours((int) substr($prayer->maghrib, 0, 2))
                ->addMinutes((int) substr($prayer->maghrib, 3, 2))
                ->timestamp;
        }
    }

    /**
     * Get countdown info to Iftar (Maghrib) today.
     *
     * @return array{label: string, passed: bool}
     */
    public function getCountdownToIftar(): array
    {
        if (! $this->prayerData) {
            return ['label' => '--:--:--', 'passed' => false];
        }

        $now = now();
        $maghribTime = Carbon::parse(now()->toDateString().' '.$this->prayerData['maghrib']);

        if ($now->greaterThan($maghribTime)) {
            return ['label' => 'Sudah buka puasa 🌙', 'passed' => true];
        }

        $diff = $now->diffInSeconds($maghribTime);
        $hours = (int) floor($diff / 3600);
        $minutes = (int) floor(($diff % 3600) / 60);
        $seconds = $diff % 60;

        return ['label' => sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds), 'passed' => false];
    }
}
