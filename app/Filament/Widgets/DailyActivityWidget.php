<?php

namespace App\Filament\Widgets;

use App\Models\RamadhanPeriod;
use App\Models\UserActivity;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class DailyActivityWidget extends ChartWidget
{
    protected static ?int $sort = 4;

    protected ?string $heading = 'Aktivitas Per Hari';

    protected int|string|array $columnSpan = 1;

    protected function getType(): string
    {
        return 'bar';
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
                        'label' => 'Tidak ada data',
                        'data' => [],
                        'backgroundColor' => 'rgba(245, 158, 11, 0.6)',
                    ],
                ],
                'labels' => [],
            ];
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
            $labels[] = "H-{$dayNum}";
            $data[] = $activities->get($dateString)?->count() ?? 0;
            $current->addDay();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Aktivitas Selesai',
                    'data' => $data,
                    'backgroundColor' => 'rgba(245, 158, 11, 0.7)',
                    'borderColor' => 'rgba(245, 158, 11, 1)',
                    'borderWidth' => 1,
                    'borderRadius' => 4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
            'maintainAspectRatio' => false,
        ];
    }
}
