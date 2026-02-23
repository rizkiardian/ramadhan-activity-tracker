@extends('layouts.app')

@section('title', 'Aktivitas — Ramadhan Activity Tracker')
@section('page-title', 'Aktivitas Saya')
@section('page-subtitle', 'Catatan ibadah harian kamu')

@section('page-actions')
  <a href="{{ route('activities.create') }}"
    class="inline-flex items-center gap-2 bg-primary-700 hover:bg-primary-800 text-white text-sm font-medium py-2 px-4 rounded-lg transition-colors duration-150 shadow-sm">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
    </svg>
    Tambah Aktivitas
  </a>
@endsection

@section('content')
  <div class="pt-2">
    {{-- Filter --}}
    <form method="GET" action="{{ route('activities.index') }}"
      class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 mb-4">
      <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <div>
          <label class="block text-xs font-medium text-gray-600 mb-1">Tipe Aktivitas</label>
          <select name="activity_type_id"
            class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
            <option value="">Semua</option>
            @foreach ($activityTypes as $type)
              <option value="{{ $type->id }}" {{ request('activity_type_id') == $type->id ? 'selected' : '' }}>
                {{ $type->name }}
              </option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
          <select name="status"
            class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
            <option value="">Semua</option>
            <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>Pending</option>
            <option value="Done" {{ request('status') === 'Done' ? 'selected' : '' }}>Done</option>
            <option value="Skipped" {{ request('status') === 'Skipped' ? 'selected' : '' }}>Skipped</option>
          </select>
        </div>
        <div class="flex items-end gap-2 col-span-2 md:col-span-2">
          <button type="submit"
            class="bg-primary-700 hover:bg-primary-800 text-white text-sm font-medium py-2 px-4 rounded-lg transition-colors duration-150">
            Filter
          </button>
          <a href="{{ route('activities.index') }}"
            class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium py-2 px-4 rounded-lg transition-colors duration-150">
            Reset
          </a>
        </div>
      </div>
    </form>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Tanggal</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Tipe</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Waktu</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Catatan</th>
              <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            @forelse($activities as $activity)
              <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-4 py-3 text-gray-900 font-medium whitespace-nowrap">
                  {{ $activity->date->translatedFormat('d M Y') }}
                </td>
                <td class="px-4 py-3 text-gray-700">
                  {{ $activity->activityType?->name ?? '–' }}
                </td>
                <td class="px-4 py-3 text-gray-600 font-mono text-xs whitespace-nowrap">
                  @if ($activity->start_time)
                    {{ $activity->start_time }} – {{ $activity->end_time ?? '...' }}
                  @else
                    –
                  @endif
                </td>
                <td class="px-4 py-3">
                  @php
                    $statusClasses = match ($activity->status) {
                        'Done' => 'bg-green-100 text-green-800',
                        'Skipped' => 'bg-red-100 text-red-800',
                        default => 'bg-yellow-100 text-yellow-800',
                    };
                  @endphp
                  <span
                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $statusClasses }}">
                    {{ $activity->status }}
                  </span>
                </td>
                <td class="px-4 py-3 text-gray-500 text-xs max-w-xs truncate">
                  {{ $activity->notes ?? '–' }}
                </td>
                <td class="px-4 py-3 text-right whitespace-nowrap">
                  <a href="{{ route('activities.edit', $activity) }}"
                    class="inline-flex items-center gap-1 text-xs font-medium text-primary-700 hover:text-primary-900 mr-3">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                  </a>
                  <form method="POST" action="{{ route('activities.destroy', $activity) }}" class="inline"
                    onsubmit="return confirm('Hapus aktivitas ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                      class="inline-flex items-center gap-1 text-xs font-medium text-red-600 hover:text-red-800">
                      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                      </svg>
                      Hapus
                    </button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="px-4 py-12 text-center text-gray-400">
                  <svg class="w-10 h-10 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M13 10V3L4 14h7v7l9-11h-7z" />
                  </svg>
                  <p class="text-sm">Belum ada aktivitas tercatat</p>
                  <a href="{{ route('activities.create') }}"
                    class="mt-2 inline-block text-primary-600 hover:underline text-sm">Tambah aktivitas pertama →</a>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @if ($activities->hasPages())
        <div class="px-4 py-3 border-t border-gray-100">
          {{ $activities->links() }}
        </div>
      @endif
    </div>
  </div>
@endsection
