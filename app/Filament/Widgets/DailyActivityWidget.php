<?php

namespace App\Filament\Widgets;

use App\Models\RamadhanPeriod;
use App\Models\UserActivity;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Carbon\Carbon;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Reactive;

class DailyActivityWidget extends Widget
{
    use HasWidgetShield;

    protected static ?int $sort = 4;

    protected string $view = 'filament.widgets.daily-activity-widget';

    protected int|string|array $columnSpan = 1;

    #[Reactive]
    public ?array $pageFilters = null;

    /**
     * @return array<string, mixed>
     */
    protected function getViewData(): array
    {
        [$chartData, $chartLabels] = $this->computeChartData();

        /** @var \App\Models\User $user */
        $user = Auth::user();

        return [
            'chartData' => $chartData,
            'chartLabels' => $chartLabels,
            'isAdmin' => $user->hasRole('super_admin'),
        ];
    }

    /**
     * @return array{list<int>, list<string>}
     */
    private function computeChartData(): array
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $isAdmin = $user->hasRole('super_admin');
        $today = now()->toDateString();

        $filterFrom = $this->pageFilters['date_from'] ?? null;
        $filterTo = $this->pageFilters['date_to'] ?? null;
        $hasCustomFilter = $filterFrom || $filterTo;

        if (! $hasCustomFilter) {
            $period = RamadhanPeriod::query()
                ->whereNull('deleted_at')
                ->where('start_date', '<=', $today)
                ->where('end_date', '>=', $today)
                ->first();

            if (! $period) {
                return [[], []];
            }

            $startDate = $period->start_date;
            $endDate = $period->end_date->min(now());

            $activities = UserActivity::query()
                ->when(! $isAdmin, fn($q) => $q->where('user_id', $user->id))
                ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
                ->where('status', 'Done')
                ->get()
                ->groupBy(fn($a) => $a->date->toDateString());

            $labels = [];
            $data = [];
            $current = $startDate->copy();

            while ($current->lte($endDate)) {
                $dateString = $current->toDateString();
                $dayNum = $startDate->diffInDays($current) + 1;
                $labels[] = 'H-' . $dayNum;
                $data[] = $activities->get($dateString)?->count() ?? 0;
                $current->addDay();
            }

            return [$data, $labels];
        }

        $startDate = Carbon::parse($filterFrom ?? now()->startOfMonth()->toDateString());
        $endDate = Carbon::parse($filterTo ?? $today)->min(now());

        $activities = UserActivity::query()
            ->when(! $isAdmin, fn($q) => $q->where('user_id', $user->id))
            ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
            ->where('status', 'Done')
            ->get()
            ->groupBy(fn($a) => $a->date->toDateString());

        $labels = [];
        $data = [];
        $current = $startDate->copy();

        while ($current->lte($endDate)) {
            $dateString = $current->toDateString();
            $labels[] = $current->format('d/m');
            $data[] = $activities->get($dateString)?->count() ?? 0;
            $current->addDay();
        }

        return [$data, $labels];
    }
}
