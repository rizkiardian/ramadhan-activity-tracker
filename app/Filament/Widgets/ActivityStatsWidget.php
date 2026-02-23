<?php

namespace App\Filament\Widgets;

use App\Models\RamadhanPeriod;
use App\Models\UserActivity;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class ActivityStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 2;

    protected function getStats(): array
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
                Stat::make('Status', 'Di luar Ramadhan')
                    ->description('Data akan tampil selama Ramadhan berlangsung')
                    ->icon('heroicon-o-moon')
                    ->color('gray'),
            ];
        }

        $startDate = $period->start_date->toDateString();
        $endDate = $period->end_date->toDateString();
        $totalDays = $period->start_date->diffInDays($period->end_date) + 1;
        $daysPassed = min($period->start_date->diffInDays(now()) + 1, $totalDays);

        $totalActivities = UserActivity::query()
            ->where('user_id', $userId)
            ->whereBetween('date', [$startDate, $endDate])
            ->count();

        $doneActivities = UserActivity::query()
            ->where('user_id', $userId)
            ->where('status', 'Done')
            ->whereBetween('date', [$startDate, $endDate])
            ->count();

        $todayCount = UserActivity::query()
            ->where('user_id', $userId)
            ->where('date', $today)
            ->where('status', 'Done')
            ->count();

        $skippedCount = UserActivity::query()
            ->where('user_id', $userId)
            ->where('status', 'Skipped')
            ->whereBetween('date', [$startDate, $endDate])
            ->count();

        $completionRate = $totalActivities > 0
          ? round(($doneActivities / $totalActivities) * 100)
          : 0;

        return [
            Stat::make('Aktivitas Hari Ini', $todayCount)
                ->description("Ramadhan Hari ke-{$daysPassed}")
                ->descriptionIcon('heroicon-o-calendar-days')
                ->icon('heroicon-o-star')
                ->color('success'),

            Stat::make('Total Aktivitas Selesai', "{$doneActivities} / {$totalActivities}")
                ->description("Tingkat penyelesaian: {$completionRate}%")
                ->descriptionIcon('heroicon-o-check-circle')
                ->icon('heroicon-o-clipboard-document-check')
                ->color('primary'),

            Stat::make('Aktivitas Dilewati', $skippedCount)
                ->description('Selama bulan Ramadhan ini')
                ->descriptionIcon('heroicon-o-x-circle')
                ->icon('heroicon-o-arrow-path-rounded-square')
                ->color($skippedCount > 0 ? 'warning' : 'success'),

            Stat::make('Hari Ramadhan', "{$daysPassed} / {$totalDays}")
                ->description("{$period->hijri_year}")
                ->descriptionIcon('heroicon-o-moon')
                ->icon('heroicon-o-moon')
                ->color('warning'),
        ];
    }
}
