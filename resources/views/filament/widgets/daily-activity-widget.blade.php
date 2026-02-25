<x-filament-widgets::widget class="fi-wi-daily-activity">
  <x-filament::section>
    <x-slot name="heading">Aktivitas Per Hari</x-slot>
    <x-slot name="description">
      {{ $isAdmin ? 'Jumlah ibadah selesai tiap hari (semua pengguna)' : 'Jumlah ibadah selesai tiap hari Ramadhan' }}
    </x-slot>

    @if (count($chartData) > 0)
      <div class="relative" style="height: 220px;" x-data="{
          chart: null,
          labels: {{ Js::from($chartLabels) }},
          data: {{ Js::from($chartData) }},
          init() {
              this.$nextTick(() => this.renderChart());
          },
          renderChart() {
              if (typeof window.Chart === 'undefined') {
                  setTimeout(() => this.renderChart(), 50);
                  return;
              }
              const canvas = this.$el.querySelector('canvas');
              if (!canvas) return;
              if (this.chart) {
                  this.chart.destroy();
                  this.chart = null;
              }
              const isDark = document.documentElement.classList.contains('dark');
              const gridColor = isDark ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.05)';
              const tickColor = isDark ? '#9ca3af' : '#6b7280';
              this.chart = new window.Chart(canvas, {
                  type: 'bar',
                  data: {
                      labels: this.labels,
                      datasets: [{
                          label: 'Aktivitas Selesai',
                          data: this.data,
                          backgroundColor: 'rgba(16, 185, 129, 0.75)',
                          borderColor: 'rgb(16, 185, 129)',
                          borderWidth: 1,
                          borderRadius: 5,
                          borderSkipped: false,
                      }],
                  },
                  options: {
                      responsive: true,
                      maintainAspectRatio: false,
                      plugins: {
                          legend: { display: false },
                          tooltip: {
                              callbacks: {
                                  title: (items) => `Hari ${items[0].label}`,
                                  label: (ctx) => ` ${ctx.raw} aktivitas selesai`,
                              },
                          },
                      },
                      scales: {
                          x: {
                              ticks: { font: { size: 10 }, color: tickColor, maxRotation: 0, autoSkip: true, maxTicksLimit: 15 },
                              grid: { display: false },
                              border: { display: false },
                          },
                          y: {
                              beginAtZero: true,
                              ticks: { stepSize: 1, font: { size: 11 }, color: tickColor },
                              grid: { color: gridColor },
                              border: { display: false },
                          },
                      },
                  },
              });
          },
      }">
        <canvas></canvas>
      </div>
    @else
      <div class="flex flex-col items-center justify-center py-10 text-center">
        <div class="w-12 h-12 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center mb-3">
          <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
          </svg>
        </div>
        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Belum ada data aktivitas</p>
        <p class="text-xs text-gray-400 mt-1">Catat aktivitas ibadah kamu selama Ramadhan</p>
      </div>
    @endif
  </x-filament::section>
</x-filament-widgets::widget>
