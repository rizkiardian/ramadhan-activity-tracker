<x-filament-widgets::widget class="fi-wi-activity-distribution">
  <x-filament::section>
    <x-slot name="heading">Distribusi Aktivitas</x-slot>
    <x-slot name="description">Berdasarkan jenis ibadah selama Ramadhan</x-slot>

    @if (count($chartData) > 0)
      <div class="relative" style="height: 220px;" x-data="{
          chart: null,
          labels: {{ Js::from($chartLabels) }},
          data: {{ Js::from($chartData) }},
          colors: {{ Js::from($chartColors) }},
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
              if (this.chart) { this.chart.destroy();
                  this.chart = null; }
              const isDark = document.documentElement.classList.contains('dark');
              this.chart = new window.Chart(canvas, {
                  type: 'doughnut',
                  data: {
                      labels: this.labels,
                      datasets: [{
                          data: this.data,
                          backgroundColor: this.colors,
                          borderWidth: 2,
                          borderColor: isDark ? '#1f2937' : '#ffffff',
                          hoverOffset: 6,
                      }],
                  },
                  options: {
                      responsive: true,
                      maintainAspectRatio: false,
                      plugins: {
                          legend: { display: false },
                          tooltip: {
                              callbacks: {
                                  label: (ctx) => {
                                      const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                      const pct = ((ctx.raw / total) * 100).toFixed(1);
                                      return ` ${ctx.label}: ${ctx.raw} (${pct}%)`;
                                  },
                              },
                          },
                      },
                      cutout: '68%',
                  },
              });
          },
      }">
        <canvas></canvas>
      </div>

      {{-- Legend --}}
      <div class="mt-4 grid grid-cols-2 gap-x-4 gap-y-2">
        @foreach ($chartLabels as $i => $label)
          <div class="flex items-center gap-2 min-w-0">
            <span class="w-2.5 h-2.5 rounded-sm flex-shrink-0"
              style="background-color: {{ $chartColors[$i] ?? '#e5e7eb' }}"></span>
            <span class="text-xs text-gray-600 dark:text-gray-400 truncate">{{ $label }}</span>
            <span class="text-xs font-semibold text-gray-800 dark:text-gray-200 ml-auto flex-shrink-0">
              {{ $chartData[$i] }}
            </span>
          </div>
        @endforeach
      </div>
    @else
      <div class="flex flex-col items-center justify-center py-10 text-center">
        <div class="w-12 h-12 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center mb-3">
          <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
          </svg>
        </div>
        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Belum ada data aktivitas</p>
        <p class="text-xs text-gray-400 mt-1">Catat aktivitas ibadah kamu selama Ramadhan</p>
      </div>
    @endif
  </x-filament::section>
</x-filament-widgets::widget>
