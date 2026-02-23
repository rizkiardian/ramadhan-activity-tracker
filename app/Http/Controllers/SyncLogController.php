<?php

namespace App\Http\Controllers;

use App\Models\SyncLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SyncLogController extends Controller
{
    public function index(Request $request): View
    {
        $query = SyncLog::query()->with('syncedBy');

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('sync_type')) {
            $query->where('sync_type', $request->input('sync_type'));
        }

        $syncLogs = $query->latest('sync_time')->paginate(15)->withQueryString();

        return view('sync-logs.index', compact('syncLogs'));
    }
}
