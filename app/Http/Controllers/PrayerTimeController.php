<?php

namespace App\Http\Controllers;

use App\Models\PrayerTime;
use App\Models\Regency;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PrayerTimeController extends Controller
{
    public function index(Request $request): View
    {
        $regencies = Regency::query()->orderBy('name')->get();

        $query = PrayerTime::query()->with('regency');

        if ($request->filled('regency_code')) {
            $query->where('regency_code', $request->input('regency_code'));
        }

        if ($request->filled('month')) {
            $query->where('month', $request->input('month'));
        }

        if ($request->filled('year')) {
            $query->where('year', $request->input('year'));
        }

        $prayerTimes = $query->orderBy('date')->orderBy('regency_code')->paginate(25)->withQueryString();

        return view('prayer-times.index', compact('prayerTimes', 'regencies'));
    }
}
