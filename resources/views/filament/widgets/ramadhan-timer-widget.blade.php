<x-filament-widgets::widget class="fi-wi-ramadhan-timer">
  <x-filament::section>
    @if ($isRamadhan)
      <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">

        {{-- Hari Ramadhan --}}
        <div
          class="flex flex-col items-center justify-center rounded-xl bg-primary-50 dark:bg-primary-950/30 p-4 text-center border border-primary-100 dark:border-primary-900">
          <div class="text-2xl mb-1">🌙</div>
          <div class="text-xs font-semibold uppercase tracking-widest text-primary-600 dark:text-primary-400 mb-1">
            Ramadhan
          </div>
          <div class="text-xl font-extrabold text-primary-700 dark:text-primary-300">
            {{ $ramadhanDay }}
          </div>
          <div class="mt-1 text-xs text-primary-500 dark:text-primary-400">
            {{ now()->isoFormat('D MMMM Y') }}
          </div>
        </div>

        @if ($prayerData)
          {{-- Imsak / Sahur --}}
          <div
            class="flex flex-col items-center justify-center rounded-xl bg-indigo-50 dark:bg-indigo-950/30 p-4 text-center border border-indigo-100 dark:border-indigo-900">
            <div class="text-2xl mb-1">🌅</div>
            <div class="text-xs font-semibold uppercase tracking-widest text-indigo-600 dark:text-indigo-400 mb-1">
              Sahur / Imsak
            </div>
            <div class="text-xl font-extrabold font-mono text-indigo-700 dark:text-indigo-300">
              {{ $prayerData['imsyak'] }}
            </div>
            <div class="mt-1 text-xs text-indigo-500">Shubuh: {{ $prayerData['shubuh'] }}</div>
          </div>

          {{-- Buka Puasa --}}
          <div
            class="flex flex-col items-center justify-center rounded-xl bg-orange-50 dark:bg-orange-950/30 p-4 text-center border border-orange-100 dark:border-orange-900">
            <div class="text-2xl mb-1">🍊</div>
            <div class="text-xs font-semibold uppercase tracking-widest text-orange-600 dark:text-orange-400 mb-1">
              Buka Puasa
            </div>
            <div class="text-xl font-extrabold font-mono text-orange-700 dark:text-orange-300">
              {{ $prayerData['maghrib'] }}
            </div>
            <div class="mt-1 text-xs text-orange-500">Isya: {{ $prayerData['isya'] }}</div>
          </div>

          {{-- Countdown --}}
          <div
            class="flex flex-col items-center justify-center rounded-xl bg-emerald-50 dark:bg-emerald-950/30 p-4 text-center border border-emerald-100 dark:border-emerald-900">
            <div class="text-2xl mb-1" id="cd-emoji">⏳</div>
            <div class="text-xs font-semibold uppercase tracking-widest text-emerald-600 dark:text-emerald-400 mb-1"
              id="cd-label">
              Hitung Mundur
            </div>
            <div class="text-xl font-extrabold font-mono text-emerald-700 dark:text-emerald-300" id="cd-value">
              --:--:--
            </div>
            <div class="mt-1 text-xs text-emerald-500" id="cd-sub">Sisa waktu buka puasa</div>
          </div>
        @else
          <div
            class="col-span-3 flex flex-col items-center justify-center rounded-xl bg-gray-50 dark:bg-gray-900 p-6 text-center border border-gray-100 dark:border-gray-800">
            <div class="text-3xl mb-2">📅</div>
            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Jadwal sholat belum tersedia untuk hari ini
            </p>
            <p class="text-xs text-gray-400 mt-1">Lakukan sinkronisasi jadwal sholat terlebih dahulu</p>
          </div>
        @endif

      </div>
    @else
      {{-- Di luar Ramadhan --}}
      @php
        $nextPeriod = \App\Models\RamadhanPeriod::query()
            ->whereNull('deleted_at')
            ->where('start_date', '>', now()->toDateString())
            ->orderBy('start_date')
            ->first();
      @endphp
      <div class="flex flex-col items-center justify-center py-10 text-center">
        <div class="text-5xl mb-4">🌙</div>
        <div class="text-xl font-bold text-gray-700 dark:text-gray-300 mb-2">Ramadhan belum dimulai</div>
        @if ($nextPeriod)
          <div class="text-sm text-gray-500 dark:text-gray-400">
            Ramadhan {{ $nextPeriod->year }} dimulai
            <span class="font-semibold text-primary-600">{{ $nextPeriod->start_date->isoFormat('D MMMM Y') }}</span>
          </div>
          <div
            class="mt-2 inline-flex items-center gap-1.5 bg-primary-50 dark:bg-primary-950/30 text-primary-700 dark:text-primary-300 text-sm font-semibold px-4 py-2 rounded-full border border-primary-100 dark:border-primary-900">
            ⏳ {{ now()->diffInDays($nextPeriod->start_date) }} hari lagi
          </div>
        @else
          <p class="text-sm text-gray-400">Tambahkan data periode Ramadhan di menu Master Data.</p>
        @endif
      </div>
    @endif
  </x-filament::section>
</x-filament-widgets::widget>

@if ($isRamadhan && $maghribTimestamp)
  @script
    <script>
      (function() {
        const maghribTs = {{ $maghribTimestamp }};
        const valueEl = document.getElementById('cd-value');
        const labelEl = document.getElementById('cd-label');
        const subEl = document.getElementById('cd-sub');
        const emojiEl = document.getElementById('cd-emoji');

        if (!valueEl) return;

        function tick() {
          const now = Math.floor(Date.now() / 1000);
          const diff = maghribTs - now;

          if (diff <= 0) {
            emojiEl.textContent = '🌙';
            labelEl.textContent = 'Status';
            valueEl.textContent = 'Sudah buka!';
            valueEl.classList.remove('font-mono');
            subEl.textContent = 'Selamat berbuka puasa 🎉';
            return;
          }

          const h = Math.floor(diff / 3600);
          const m = Math.floor((diff % 3600) / 60);
          const s = diff % 60;

          valueEl.textContent =
            String(h).padStart(2, '0') + ':' +
            String(m).padStart(2, '0') + ':' +
            String(s).padStart(2, '0');

          setTimeout(tick, 1000);
        }

        tick();
      })();
    </script>
  @endscript
@endif
