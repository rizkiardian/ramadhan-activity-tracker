<?php

namespace App\Filament\Widgets;

use App\Models\RamadhanPeriod;
use App\Models\UserActivity;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class DailyActivityWidget extends Widget
{
    use HasWidgetShield;

    protected static ?int $sort = 4;

    protected string $view = 'filament.widgets.daily-activity-widget';

    protected int|string|array $columnSpan = 1;

    public array $chartData = [];

    public array $chartLabels = [];

    public function mount(): void
    {
        $this->loadData();
    }

    private function loadData(): void
    {
        $userId = Auth::id();
        $today = now()->toDateString();

        $period = RamadhanPeriod::query()
            ->whereNull('deleted_at')
            ->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->first();

        if (! $period) {
            return;
        }

        $startDate = $period->start_date;
        $endDate = $period->end_date->min(now());

        $activities = UserActivity::query()
            ->where('user_id', $userId)
            ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
            ->where('status', 'Done')
            ->get()
            ->groupBy(fn ($a) => $a->date->toDateString());

        $labels = [];
        $data = [];

        $current = $startDate->copy();
        while ($current->lte($endDate)) {
            $dateString = $current->toDateString();
            $dayNum = $startDate->diffInDays($current) + 1;
            $labels[] = 'H-'.$dayNum;
            $data[] = $activities->get($dateString)?->count() ?? 0;
            $current->addDay();
        }

        $this->chartData = $data;
        $this->chartLabels = $labels;
    }
}
