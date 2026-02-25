<?php

namespace App\Filament\Widgets;

use App\Models\RamadhanPeriod;
use App\Models\UserActivity;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Reactive;

class ActivityStatsWidget extends StatsOverviewWidget
{
    use HasWidgetShield;

    protected static ?int $sort = 2;

    #[Reactive]
    public ?array $pageFilters = null;

    protected function getStats(): array
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $isAdmin = $user->hasRole('super_admin');
        $today = now()->toDateString();

        $period = RamadhanPeriod::query()
            ->whereNull('deleted_at')
            ->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->first();

        $filterFrom = $this->pageFilters['date_from'] ?? null;
        $filterTo = $this->pageFilters['date_to'] ?? null;
        $hasCustomFilter = $filterFrom || $filterTo;

        if (! $hasCustomFilter && ! $period) {
            return [
                Stat::make('Status', 'Di luar Ramadhan')
                    ->description('Data akan tampil selama Ramadhan berlangsung')
                    ->icon('heroicon-o-moon')
                    ->color('gray'),
            ];
        }

        $startDate = $filterFrom ?? $period?->start_date->toDateString() ?? now()->startOfMonth()->toDateString();
        $endDate = $filterTo ?? $period?->end_date->toDateString() ?? $today;

        $baseQuery = fn() => UserActivity::query()
            ->when(! $isAdmin, fn($q) => $q->where('user_id', $user->id));

        $todayPlanned = $baseQuery()->where('date', $today)->count();
        $todayDone = $baseQuery()->where('date', $today)->where('status', 'Done')->count();
        $totalInRange = $baseQuery()->whereBetween('date', [$startDate, $endDate])->count();
        $doneInRange = $baseQuery()->whereBetween('date', [$startDate, $endDate])->where('status', 'Done')->count();
        $skippedInRange = $baseQuery()->whereBetween('date', [$startDate, $endDate])->where('status', 'Skipped')->count();

        $completionRate = $totalInRange > 0 ? round(($doneInRange / $totalInRange) * 100) : 0;

        $stats = [
            Stat::make('Aktivitas Selesai Hari Ini', $todayDone)
                ->description($isAdmin
                    ? "Total semua pengguna dari {$todayPlanned} rencana"
                    : "Dari {$todayPlanned} yang direncanakan")
                ->descriptionIcon('heroicon-o-calendar-days')
                ->icon('heroicon-o-star')
                ->color('success'),

            Stat::make('Total Aktivitas Selesai', "{$doneInRange} / {$totalInRange}")
                ->description("Tingkat penyelesaian: {$completionRate}%")
                ->descriptionIcon('heroicon-o-check-circle')
                ->icon('heroicon-o-clipboard-document-check')
                ->color('primary'),

            Stat::make('Aktivitas Dilewati', $skippedInRange)
                ->description($isAdmin ? 'Seluruh pengguna pada periode ini' : 'Selama periode ini')
                ->descriptionIcon('heroicon-o-x-circle')
                ->icon('heroicon-o-arrow-path-rounded-square')
                ->color($skippedInRange > 0 ? 'warning' : 'success'),
        ];

        if ($period && ! $hasCustomFilter) {
            $totalDays = (int) $period->start_date->diffInDays($period->end_date) + 1;
            $daysPassed = (int) min((int) $period->start_date->diffInDays(now()) + 1, $totalDays);

            $stats[] = Stat::make('Hari Ramadhan', "{$daysPassed} / {$totalDays}")
                ->description("{$period->hijri_year}")
                ->descriptionIcon('heroicon-o-moon')
                ->icon('heroicon-o-moon')
                ->color('warning');
        }

        if ($isAdmin) {
            $activeUsers = UserActivity::query()
                ->whereBetween('date', [$startDate, $endDate])
                ->distinct('user_id')
                ->count('user_id');

            $stats[] = Stat::make('Pengguna Aktif', $activeUsers)
                ->description('Pengguna dengan aktivitas di periode ini')
                ->descriptionIcon('heroicon-o-users')
                ->icon('heroicon-o-users')
                ->color('info');
        }

        return $stats;
    }
}
