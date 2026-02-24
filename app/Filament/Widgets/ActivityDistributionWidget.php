<?php

namespace App\Filament\Widgets;

use App\Models\RamadhanPeriod;
use App\Models\UserActivity;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class ActivityDistributionWidget extends Widget
{
    protected static ?int $sort = 3;

    protected string $view = 'filament.widgets.activity-distribution-widget';

    protected int|string|array $columnSpan = 1;

    /** @var list<int> */
    public array $chartData = [];

    /** @var list<string> */
    public array $chartLabels = [];

    /** @var list<string> */
    public array $chartColors = [];

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

        $startDate = $period->start_date->toDateString();
        $endDate = $period->end_date->toDateString();

        $data = UserActivity::query()
            ->where('user_id', $userId)
            ->whereBetween('date', [$startDate, $endDate])
            ->with('activityType')
            ->get()
            ->groupBy('activityType.name')
            ->map(fn ($activities) => $activities->count());

        $palette = ['#10b981', '#3b82f6', '#f59e0b', '#8b5cf6', '#ef4444', '#06b6d4', '#84cc16', '#f97316'];

        $this->chartData = $data->values()->toArray();
        $this->chartLabels = $data->keys()->toArray();
        $this->chartColors = array_slice($palette, 0, $data->count());
    }
}
