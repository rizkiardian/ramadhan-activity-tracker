@extends('layouts.app')

@section('title', 'Dashboard — Ramadhan Activity Tracker')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Ringkasan aktivitas ibadah Ramadhan kamu')

@section('content')
  <div class="pt-2">
    {{-- Stats cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
      {{-- Total Prayer Records --}}
      <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
        <div class="flex items-center justify-between mb-3">
          <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Jadwal Sholat</p>
          <div class="w-8 h-8 rounded-lg bg-primary-100 flex items-center justify-center">
            <svg class="w-4 h-4 text-primary-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </div>
        <p class="text-2xl font-bold text-gray-900">{{ number_format($totalPrayerRecords) }}</p>
        <p class="text-xs text-gray-500 mt-1">Total data tersimpan</p>
      </div>

      {{-- Activities This Month --}}
      <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
        <div class="flex items-center justify-between mb-3">
          <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Aktivitas Bulan Ini</p>
          <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center">
            <svg class="w-4 h-4 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
          </div>
        </div>
        <p class="text-2xl font-bold text-gray-900">{{ number_format($activitiesThisMonth) }}</p>
        <p class="text-xs text-gray-500 mt-1">{{ now()->translatedFormat('F Y') }}</p>
      </div>

      {{-- Total Activity Types --}}
      <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
        <div class="flex items-center justify-between mb-3">
          <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Tipe Aktivitas</p>
          <div class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center">
            <svg class="w-4 h-4 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z" />
            </svg>
          </div>
        </div>
        <p class="text-2xl font-bold text-gray-900">{{ number_format($totalActivityTypes) }}</p>
        <p class="text-xs text-gray-500 mt-1">Jenis ibadah tercatat</p>
      </div>

      {{-- Last Sync --}}
      <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
        <div class="flex items-center justify-between mb-3">
          <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Sinkronisasi Terakhir</p>
          <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center">
            <svg class="w-4 h-4 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
          </div>
        </div>
        <p class="text-2xl font-bold text-gray-900">
          @if ($lastSync)
            {{ $lastSync->sync_time->diffForHumans() }}
          @else
            Belum ada
          @endif
        </p>
        <p class="text-xs text-gray-500 mt-1">
          @if ($lastSync)
            {{ $lastSync->sync_time->format('d M Y H:i') }}
          @else
            Lakukan sinkronisasi pertama
          @endif
        </p>
      </div>
    </div>

    {{-- Charts --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
      {{-- Pie chart --}}
      <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
        <div class="flex items-center justify-between mb-4">
          <div>
            <h3 class="text-sm font-semibold text-gray-900">Distribusi Aktivitas</h3>
            <p class="text-xs text-gray-500 mt-0.5">30 hari terakhir berdasarkan tipe</p>
          </div>
        </div>
        @if ($pieData->isNotEmpty())
          <div class="relative h-56">
            <canvas id="pieChart"></canvas>
          </div>
          <div class="mt-4 grid grid-cols-2 gap-2">
            @foreach ($pieData as $label => $count)
              <div class="flex items-center gap-2">
                <div class="w-2.5 h-2.5 rounded-full flex-shrink-0"
                  style="background-color: hsl({{ $loop->index * 50 + 120 }}, 60%, 45%)"></div>
                <span class="text-xs text-gray-600 truncate">{{ $label }}</span>
                <span class="text-xs font-semibold text-gray-800 ml-auto">{{ $count }}</span>
              </div>
            @endforeach
          </div>
        @else
          <div class="h-56 flex items-center justify-center">
            <div class="text-center">
              <svg class="w-10 h-10 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
              </svg>
              <p class="text-sm text-gray-400">Belum ada aktivitas</p>
            </div>
          </div>
        @endif
      </div>

      {{-- Bar chart --}}
      <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
        <div class="flex items-center justify-between mb-4">
          <div>
            <h3 class="text-sm font-semibold text-gray-900">Aktivitas per Hari</h3>
            <p class="text-xs text-gray-500 mt-0.5">30 hari terakhir</p>
          </div>
        </div>
        @if ($barData->isNotEmpty())
          <div class="relative h-64">
            <canvas id="barChart"></canvas>
          </div>
        @else
          <div class="h-64 flex items-center justify-center">
            <div class="text-center">
              <svg class="w-10 h-10 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
              </svg>
              <p class="text-sm text-gray-400">Belum ada aktivitas</p>
            </div>
          </div>
        @endif
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    @if ($pieData->isNotEmpty())
      const pieLabels = @json($pieData->keys());
      const pieValues = @json($pieData->values());
      const pieColors = pieLabels.map((_, i) => `hsl(${i * 50 + 120}, 60%, 45%)`);

      new Chart(document.getElementById('pieChart'), {
        type: 'doughnut',
        data: {
          labels: pieLabels,
          datasets: [{
            data: pieValues,
            backgroundColor: pieColors,
            borderWidth: 2,
            borderColor: '#fff',
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: false
            },
            tooltip: {
              callbacks: {
                label: function(ctx) {
                  const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                  const pct = ((ctx.raw / total) * 100).toFixed(1);
                  return ` ${ctx.label}: ${ctx.raw} (${pct}%)`;
                }
              }
            }
          },
          cutout: '65%',
        }
      });
    @endif

    @if ($barData->isNotEmpty())
      const barLabels = @json($barLabels);
      const barValues = @json($barData);

      new Chart(document.getElementById('barChart'), {
        type: 'bar',
        data: {
          labels: barLabels,
          datasets: [{
            label: 'Aktivitas',
            data: barValues,
            backgroundColor: 'rgba(22, 163, 74, 0.7)',
            borderColor: 'rgb(22, 163, 74)',
            borderWidth: 1,
            borderRadius: 4,
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: false
            },
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                stepSize: 1,
                font: {
                  size: 11
                }
              },
              grid: {
                color: '#f3f4f6'
              }
            },
            x: {
              ticks: {
                font: {
                  size: 10
                },
                maxRotation: 45
              },
              grid: {
                display: false
              }
            }
          }
        }
      });
    @endif
  </script>
@endpush
