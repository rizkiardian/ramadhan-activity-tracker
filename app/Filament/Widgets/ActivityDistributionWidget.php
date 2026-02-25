<?php

namespace App\Filament\Widgets;

use App\Models\RamadhanPeriod;
use App\Models\UserActivity;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Reactive;

class ActivityDistributionWidget extends Widget
{
    use HasWidgetShield;

    protected static ?int $sort = 3;

    protected string $view = 'filament.widgets.activity-distribution-widget';

    protected int|string|array $columnSpan = 1;

    #[Reactive]
    public ?array $pageFilters = null;

    /**
     * @return array<string, mixed>
     */
    protected function getViewData(): array
    {
        [$chartData, $chartLabels, $chartColors] = $this->computeChartData();

        /** @var \App\Models\User $user */
        $user = Auth::user();

        return [
            'chartData' => $chartData,
            'chartLabels' => $chartLabels,
            'chartColors' => $chartColors,
            'isAdmin' => $user->hasRole('super_admin'),
        ];
    }

    /**
     * @return array{list<int>, list<string>, list<string>}
     */
    private function computeChartData(): array
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $isAdmin = $user->hasRole('super_admin');
        $today = now()->toDateString();

        $filterFrom = $this->pageFilters['date_from'] ?? null;
        $filterTo = $this->pageFilters['date_to'] ?? null;

        if (! $filterFrom || ! $filterTo) {
            $period = RamadhanPeriod::query()
                ->whereNull('deleted_at')
                ->where('start_date', '<=', $today)
                ->where('end_date', '>=', $today)
                ->first();

            if (! $period && ! $filterFrom) {
                return [[], [], []];
            }

            $filterFrom ??= $period?->start_date->toDateString() ?? now()->startOfMonth()->toDateString();
            $filterTo ??= $period?->end_date->toDateString() ?? $today;
        }

        $palette = ['#10b981', '#3b82f6', '#f59e0b', '#8b5cf6', '#ef4444', '#06b6d4', '#84cc16', '#f97316'];

        $data = UserActivity::query()
            ->when(! $isAdmin, fn($q) => $q->where('user_id', $user->id))
            ->whereBetween('date', [$filterFrom, $filterTo])
            ->with('activityType')
            ->get()
            ->groupBy('activityType.name')
            ->map(fn($activities) => $activities->count());

        return [
            $data->values()->toArray(),
            $data->keys()->toArray(),
            array_slice($palette, 0, $data->count()),
        ];
    }
}
