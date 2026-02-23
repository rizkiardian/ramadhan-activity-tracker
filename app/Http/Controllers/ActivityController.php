<?php

namespace App\Http\Controllers;

use App\Models\ActivityType;
use App\Models\UserActivity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ActivityController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();

        $query = UserActivity::query()
            ->where('user_id', $user->id)
            ->with('activityType');

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('activity_type_id')) {
            $query->where('activity_type_id', $request->input('activity_type_id'));
        }

        $activities = $query->orderByDesc('date')->paginate(15)->withQueryString();
        $activityTypes = ActivityType::query()->orderBy('name')->get();

        return view('activities.index', compact('activities', 'activityTypes'));
    }

    public function create(): View
    {
        $activityTypes = ActivityType::query()->orderBy('name')->get();

        return view('activities.create', compact('activityTypes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'activity_type_id' => ['required', 'exists:activity_types,id'],
            'date' => ['required', 'date'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i', 'after_or_equal:start_time'],
            'status' => ['required', 'in:Pending,Done,Skipped'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        UserActivity::query()->create([
            ...$validated,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('activities.index')
            ->with('success', 'Aktivitas berhasil ditambahkan.');
    }

    public function edit(UserActivity $activity): View
    {
        abort_if($activity->user_id !== Auth::id(), 403);

        $activityTypes = ActivityType::query()->orderBy('name')->get();

        return view('activities.edit', compact('activity', 'activityTypes'));
    }

    public function update(Request $request, UserActivity $activity): RedirectResponse
    {
        abort_if($activity->user_id !== Auth::id(), 403);

        $validated = $request->validate([
            'activity_type_id' => ['required', 'exists:activity_types,id'],
            'date' => ['required', 'date'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i', 'after_or_equal:start_time'],
            'status' => ['required', 'in:Pending,Done,Skipped'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $activity->update($validated);

        return redirect()->route('activities.index')
            ->with('success', 'Aktivitas berhasil diperbarui.');
    }

    public function destroy(UserActivity $activity): RedirectResponse
    {
        abort_if($activity->user_id !== Auth::id(), 403);

        $activity->delete();

        return redirect()->route('activities.index')
            ->with('success', 'Aktivitas berhasil dihapus.');
    }
}
