<?php

namespace App\Http\Controllers;

use App\Models\ActivityType;
use App\Models\PrayerTime;
use App\Models\SyncLog;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();

        $totalPrayerRecords = PrayerTime::query()->count();
        $activitiesThisMonth = UserActivity::query()
            ->where('user_id', $user->id)
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->count();
        $totalActivityTypes = ActivityType::query()->count();
        $lastSync = SyncLog::query()
            ->where('status', 'Success')
            ->latest('sync_time')
            ->first();

        // Pie chart: activities by type (last 30 days)
        $pieData = UserActivity::query()
            ->where('user_id', $user->id)
            ->where('date', '>=', now()->subDays(30))
            ->with('activityType')
            ->get()
            ->groupBy(fn ($a) => $a->activityType?->name ?? 'Unknown')
            ->map(fn ($group) => $group->count())
            ->sortDesc();

        // Bar chart: activities per day (last 30 days)
        $barRaw = UserActivity::query()
            ->where('user_id', $user->id)
            ->where('date', '>=', now()->subDays(30))
            ->selectRaw('DATE(date) as activity_date, COUNT(*) as total')
            ->groupBy('activity_date')
            ->orderBy('activity_date')
            ->pluck('total', 'activity_date');

        $barLabels = $barRaw->keys()->map(fn ($d) => \Carbon\Carbon::parse($d)->format('d M'))->values();
        $barData = $barRaw->values();

        return view('dashboard.index', compact(
            'totalPrayerRecords',
            'activitiesThisMonth',
            'totalActivityTypes',
            'lastSync',
            'pieData',
            'barLabels',
            'barData',
        ));
    }
}
