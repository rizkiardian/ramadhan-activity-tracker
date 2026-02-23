<?php

namespace App\Http\Controllers;

use App\Models\ActivityType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ActivityTypeController extends Controller
{
    public function index(): View
    {
        $activityTypes = ActivityType::query()
            ->with('createdBy')
            ->withCount('userActivities')
            ->orderBy('name')
            ->paginate(15);

        return view('activity-types.index', compact('activityTypes'));
    }

    public function create(): View
    {
        return view('activity-types.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:activity_types,name'],
        ]);

        ActivityType::query()->create([
            'name' => $validated['name'],
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('activity-types.index')
            ->with('success', 'Tipe aktivitas berhasil ditambahkan.');
    }

    public function edit(ActivityType $activityType): View
    {
        return view('activity-types.edit', compact('activityType'));
    }

    public function update(Request $request, ActivityType $activityType): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:activity_types,name,'.$activityType->id],
        ]);

        $activityType->update($validated);

        return redirect()->route('activity-types.index')
            ->with('success', 'Tipe aktivitas berhasil diperbarui.');
    }

    public function destroy(ActivityType $activityType): RedirectResponse
    {
        $activityType->delete();

        return redirect()->route('activity-types.index')
            ->with('success', 'Tipe aktivitas berhasil dihapus.');
    }
}
