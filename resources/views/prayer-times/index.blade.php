@extends('layouts.app')

@section('title', 'Jadwal Sholat — Ramadhan Activity Tracker')
@section('page-title', 'Jadwal Sholat')
@section('page-subtitle', 'Data waktu sholat dari API')

@section('content')
  <div class="pt-2">
    {{-- Filter --}}
    <form method="GET" action="{{ route('prayer-times.index') }}"
      class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 mb-4">
      <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <div>
          <label class="block text-xs font-medium text-gray-600 mb-1">Kabupaten/Kota</label>
          <select name="regency_code"
            class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
            <option value="">Semua</option>
            @foreach ($regencies as $regency)
              <option value="{{ $regency->code }}" {{ request('regency_code') == $regency->code ? 'selected' : '' }}>
                {{ $regency->name }}
              </option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="block text-xs font-medium text-gray-600 mb-1">Bulan</label>
          <select name="month"
            class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
            <option value="">Semua</option>
            @foreach (range(1, 12) as $m)
              <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
              </option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="block text-xs font-medium text-gray-600 mb-1">Tahun</label>
          <select name="year"
            class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
            <option value="">Semua</option>
            @foreach (range(2024, 2027) as $y)
              <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}
              </option>
            @endforeach
          </select>
        </div>
        <div class="flex items-end gap-2">
          <button type="submit"
            class="flex-1 bg-primary-700 hover:bg-primary-800 text-white text-sm font-medium py-2 px-4 rounded-lg transition-colors duration-150">
            Filter
          </button>
          <a href="{{ route('prayer-times.index') }}"
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
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Kabupaten/Kota
              </th>
              <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wide">Imsyak</th>
              <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wide">Shubuh</th>
              <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wide">Dzuhur</th>
              <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wide">Ashr</th>
              <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wide">Maghrib</th>
              <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wide">Isya</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            @forelse($prayerTimes as $pt)
              <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-4 py-3 text-gray-900 font-medium whitespace-nowrap">
                  {{ \Carbon\Carbon::parse($pt->date)->translatedFormat('d M Y') }}
                </td>
                <td class="px-4 py-3 text-gray-700">{{ $pt->regency_name }}</td>
                <td class="px-4 py-3 text-center text-gray-600 font-mono text-xs">{{ $pt->imsyak }}</td>
                <td class="px-4 py-3 text-center text-gray-600 font-mono text-xs">{{ $pt->shubuh }}</td>
                <td class="px-4 py-3 text-center text-gray-600 font-mono text-xs">{{ $pt->dzuhur }}</td>
                <td class="px-4 py-3 text-center text-gray-600 font-mono text-xs">{{ $pt->ashr }}</td>
                <td class="px-4 py-3 text-center text-gray-600 font-mono text-xs">{{ $pt->maghrib }}</td>
                <td class="px-4 py-3 text-center text-gray-600 font-mono text-xs">{{ $pt->isya }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="8" class="px-4 py-12 text-center text-gray-400">
                  <svg class="w-10 h-10 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <p class="text-sm">Tidak ada data jadwal sholat</p>
                  <a href="{{ route('regency-sync.index') }}"
                    class="mt-2 inline-block text-primary-600 hover:underline text-sm">Lakukan sinkronisasi →</a>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @if ($prayerTimes->hasPages())
        <div class="px-4 py-3 border-t border-gray-100">
          {{ $prayerTimes->links() }}
        </div>
      @endif
    </div>
  </div>
@endsection
