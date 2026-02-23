<?php

namespace App\Filament\Widgets;

use App\Models\RamadhanPeriod;
use App\Models\UserActivity;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class ActivityDistributionWidget extends ChartWidget
{
    protected static ?int $sort = 3;

    protected ?string $heading = 'Distribusi Aktivitas Ramadhan';

    protected int|string|array $columnSpan = 1;

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getData(): array
    {
        $userId = Auth::id();
        $today = now()->toDateString();

        $period = RamadhanPeriod::query()
            ->whereNull('deleted_at')
            ->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->first();

        if (! $period) {
            return [
                'datasets' => [
                    [
                        'data' => [1],
                        'backgroundColor' => ['#e5e7eb'],
                        'label' => 'Tidak ada data',
                    ],
                ],
                'labels' => ['Di luar Ramadhan'],
            ];
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

        if ($data->isEmpty()) {
            return [
                'datasets' => [
                    [
                        'data' => [1],
                        'backgroundColor' => ['#e5e7eb'],
                        'label' => 'Belum ada aktivitas',
                    ],
                ],
                'labels' => ['Belum ada data'],
            ];
        }

        $colors = ['#f59e0b', '#10b981', '#3b82f6', '#8b5cf6', '#ef4444', '#06b6d4', '#84cc16'];

        return [
            'datasets' => [
                [
                    'data' => $data->values()->toArray(),
                    'backgroundColor' => array_slice($colors, 0, $data->count()),
                    'hoverOffset' => 4,
                ],
            ],
            'labels' => $data->keys()->toArray(),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
            ],
            'maintainAspectRatio' => false,
        ];
    }
}
