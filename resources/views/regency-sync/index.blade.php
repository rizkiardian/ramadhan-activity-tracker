@extends('layouts.app')

@section('title', 'Sinkronisasi — Ramadhan Activity Tracker')
@section('page-title', 'Sinkronisasi Jadwal Sholat')
@section('page-subtitle', 'Tarik data jadwal sholat dari API per kabupaten/kota')

@section('content')
  <div class="pt-2">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Kode</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Kabupaten/Kota
              </th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Terakhir
                Disinkronkan</th>
              <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            @forelse($regencies as $regency)
              <tr class="hover:bg-gray-50 transition-colors" id="row-{{ $regency->code }}">
                <td class="px-4 py-3 font-mono text-xs text-gray-600">{{ $regency->code }}</td>
                <td class="px-4 py-3 font-medium text-gray-900">{{ $regency->name }}</td>
                <td class="px-4 py-3 text-gray-500 text-xs">
                  @if ($regency->last_synced_at)
                    <span class="text-green-700 font-medium">{{ $regency->last_synced_at->diffForHumans() }}</span>
                    <span class="text-gray-400 ml-1">({{ $regency->last_synced_at->format('d M Y H:i') }})</span>
                  @else
                    <span class="text-gray-400 italic">Belum pernah</span>
                  @endif
                </td>
                <td class="px-4 py-3 text-right">
                  <button type="button" onclick="openSyncModal('{{ $regency->code }}', '{{ $regency->name }}')"
                    class="inline-flex items-center gap-1.5 text-xs font-medium bg-primary-700 hover:bg-primary-800 text-white py-1.5 px-3 rounded-lg transition-colors duration-150">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Sinkronisasi
                  </button>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="px-4 py-12 text-center text-gray-400">
                  <p class="text-sm">Belum ada data kabupaten/kota</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @if ($regencies->hasPages())
        <div class="px-4 py-3 border-t border-gray-100">
          {{ $regencies->links() }}
        </div>
      @endif
    </div>
  </div>

  {{-- Sync Modal --}}
  <div id="sync-modal" class="fixed inset-0 z-50 hidden" aria-modal="true" role="dialog">
    <div class="fixed inset-0 bg-black/40" onclick="closeSyncModal()"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
      <div class="relative w-full max-w-md bg-white rounded-2xl shadow-xl p-6">
        <div class="flex items-center justify-between mb-5">
          <h3 class="text-base font-semibold text-gray-900">Sinkronisasi Jadwal Sholat</h3>
          <button type="button" onclick="closeSyncModal()"
            class="p-1 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <p class="text-sm text-gray-600 mb-5">
          Sinkronisasi data untuk: <strong id="modal-regency-name" class="text-gray-900"></strong>
        </p>

        <form id="sync-form" method="POST" class="space-y-4">
          @csrf

          <div>
            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1.5">
              Tanggal Mulai <span class="text-red-500">*</span>
            </label>
            <input id="start_date" type="date" name="start_date" value="{{ now()->startOfMonth()->format('Y-m-d') }}"
              class="w-full text-sm border border-gray-300 rounded-lg px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
          </div>

          <div>
            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1.5">
              Tanggal Selesai <span class="text-red-500">*</span>
            </label>
            <input id="end_date" type="date" name="end_date" value="{{ now()->endOfMonth()->format('Y-m-d') }}"
              class="w-full text-sm border border-gray-300 rounded-lg px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
          </div>

          <div class="flex items-center gap-3 pt-2">
            <button type="submit"
              class="flex-1 bg-primary-700 hover:bg-primary-800 text-white text-sm font-semibold py-2.5 px-4 rounded-lg transition-colors duration-150">
              Mulai Sinkronisasi
            </button>
            <button type="button" onclick="closeSyncModal()"
              class="text-sm font-medium text-gray-600 hover:text-gray-800 py-2.5 px-4">
              Batal
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  @push('scripts')
    <script>
      function openSyncModal(code, name) {
        document.getElementById('modal-regency-name').textContent = name;
        document.getElementById('sync-form').action = `/regency-sync/${code}/sync`;
        document.getElementById('sync-modal').classList.remove('hidden');
      }

      function closeSyncModal() {
        document.getElementById('sync-modal').classList.add('hidden');
      }
    </script>
  @endpush
@endsection
