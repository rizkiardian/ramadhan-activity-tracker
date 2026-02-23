@extends('layouts.app')

@section('title', 'Log Sinkronisasi — Ramadhan Activity Tracker')
@section('page-title', 'Log Sinkronisasi')
@section('page-subtitle', 'Riwayat sinkronisasi data dari API')

@section('content')
  <div class="pt-2">
    {{-- Filter --}}
    <form method="GET" action="{{ route('sync-logs.index') }}"
      class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 mb-4">
      <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <div>
          <label class="block text-xs font-medium text-gray-600 mb-1">Tipe</label>
          <select name="sync_type"
            class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
            <option value="">Semua</option>
            <option value="prayer_times" {{ request('sync_type') === 'prayer_times' ? 'selected' : '' }}>Jadwal Sholat
            </option>
          </select>
        </div>
        <div>
          <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
          <select name="status"
            class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
            <option value="">Semua</option>
            <option value="Success" {{ request('status') === 'Success' ? 'selected' : '' }}>Success</option>
            <option value="Failed" {{ request('status') === 'Failed' ? 'selected' : '' }}>Failed</option>
            <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>Pending</option>
          </select>
        </div>
        <div class="flex items-end gap-2 col-span-2">
          <button type="submit"
            class="bg-primary-700 hover:bg-primary-800 text-white text-sm font-medium py-2 px-4 rounded-lg transition-colors duration-150">
            Filter
          </button>
          <a href="{{ route('sync-logs.index') }}"
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
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Waktu</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Tipe</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Rentang Tanggal
              </th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Catatan</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Disinkronkan
                Oleh</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            @forelse($syncLogs as $log)
              <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-4 py-3 text-gray-900 text-xs whitespace-nowrap font-mono">
                  {{ $log->sync_time?->format('d M Y H:i:s') ?? '–' }}
                </td>
                <td class="px-4 py-3 text-gray-700">
                  @if ($log->sync_type === 'prayer_times')
                    <span
                      class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Jadwal
                      Sholat</span>
                  @else
                    {{ $log->sync_type }}
                  @endif
                </td>
                <td class="px-4 py-3 text-gray-600 text-xs whitespace-nowrap">
                  @if ($log->start_date && $log->end_date)
                    {{ $log->start_date->format('d M Y') }} – {{ $log->end_date->format('d M Y') }}
                  @else
                    –
                  @endif
                </td>
                <td class="px-4 py-3">
                  @php
                    $statusClasses = match ($log->status) {
                        'Success' => 'bg-green-100 text-green-800',
                        'Failed' => 'bg-red-100 text-red-800',
                        default => 'bg-yellow-100 text-yellow-800',
                    };
                  @endphp
                  <span
                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $statusClasses }}">
                    {{ $log->status }}
                  </span>
                </td>
                <td class="px-4 py-3 text-gray-500 text-xs max-w-xs truncate" title="{{ $log->notes }}">
                  {{ $log->notes ?? '–' }}
                </td>
                <td class="px-4 py-3 text-gray-600 text-xs">
                  {{ $log->syncedBy?->name ?? '–' }}
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="px-4 py-12 text-center text-gray-400">
                  <svg class="w-10 h-10 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                  </svg>
                  <p class="text-sm">Belum ada log sinkronisasi</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @if ($syncLogs->hasPages())
        <div class="px-4 py-3 border-t border-gray-100">
          {{ $syncLogs->links() }}
        </div>
      @endif
    </div>
  </div>
@endsection
