<x-filament-widgets::widget class="fi-wi-ramadhan-timer">
  <x-filament::section>
    @if ($isRamadhan)
      <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
        {{-- Hari Ramadhan --}}
        <div
          class="flex flex-col items-center justify-center rounded-xl bg-amber-50 dark:bg-amber-950/30 p-5 text-center">
          <div class="mb-1 text-3xl">🌙</div>
          <div class="text-xs font-semibold uppercase tracking-widest text-amber-600 dark:text-amber-400">
            Ramadhan
          </div>
          <div class="mt-1 text-2xl font-extrabold text-amber-700 dark:text-amber-300">
            {{ $ramadhanDay }}
          </div>
          <div class="mt-1 text-sm text-amber-500 dark:text-amber-400">{{ now()->isoFormat('dddd, D MMMM Y') }}</div>
        </div>

        @if ($prayerData)
          {{-- Sahur / Imsak --}}
          <div
            class="flex flex-col items-center justify-center rounded-xl bg-indigo-50 dark:bg-indigo-950/30 p-5 text-center">
            <div class="mb-1 text-3xl">🌅</div>
            <div class="text-xs font-semibold uppercase tracking-widest text-indigo-600 dark:text-indigo-400">Sahur /
              Imsak</div>
            <div class="mt-1 text-2xl font-extrabold text-indigo-700 dark:text-indigo-300">
              {{ $prayerData['imsyak'] }}
            </div>
            <div class="mt-1 text-sm text-indigo-500">Shubuh: {{ $prayerData['shubuh'] }}</div>
          </div>

          {{-- Buka Puasa --}}
          <div
            class="flex flex-col items-center justify-center rounded-xl bg-orange-50 dark:bg-orange-950/30 p-5 text-center">
            <div class="mb-1 text-3xl">🍊</div>
            <div class="text-xs font-semibold uppercase tracking-widest text-orange-600 dark:text-orange-400">Buka Puasa
              (Maghrib)</div>
            <div class="mt-1 text-2xl font-extrabold text-orange-700 dark:text-orange-300">
              {{ $prayerData['maghrib'] }}
            </div>
            <div class="mt-1 text-sm text-orange-500">Isya: {{ $prayerData['isya'] }}</div>
          </div>

          {{-- Countdown --}}
          @php
            $countdown = $this->getCountdownToIftar();
          @endphp
          <div
            class="flex flex-col items-center justify-center rounded-xl bg-green-50 dark:bg-green-950/30 p-5 text-center">
            <div class="mb-1 text-3xl">⏳</div>
            <div class="text-xs font-semibold uppercase tracking-widest text-green-600 dark:text-green-400">
              {{ $countdown['passed'] ? 'Status' : 'Hitung Mundur Buka Puasa' }}
            </div>
            <div
              class="mt-1 font-extrabold text-green-700 dark:text-green-300 {{ $countdown['passed'] ? 'text-xl' : 'text-2xl font-mono' }}">
              {{ $countdown['label'] }}
            </div>
            @if (!$countdown['passed'])
              <div class="mt-1 text-sm text-green-500">Sisa waktu hingga buka</div>
            @endif
          </div>
        @else
          <div
            class="col-span-3 flex flex-col items-center justify-center rounded-xl bg-gray-50 dark:bg-gray-900 p-6 text-center">
            <div class="text-3xl">📅</div>
            <div class="mt-2 font-semibold text-gray-600 dark:text-gray-400">Data jadwal sholat belum tersedia untuk
              hari ini</div>
            <div class="mt-1 text-sm text-gray-400">Silakan lakukan sinkronisasi jadwal sholat terlebih dahulu.</div>
          </div>
        @endif
      </div>
    @else
      {{-- Bukan bulan Ramadhan --}}
      @php
        $nextPeriod = \App\Models\RamadhanPeriod::query()
            ->whereNull('deleted_at')
            ->where('start_date', '>', now()->toDateString())
            ->orderBy('start_date')
            ->first();
      @endphp
      <div class="flex flex-col items-center justify-center rounded-xl bg-gray-50 dark:bg-gray-900 py-10 text-center">
        <div class="text-5xl">🌙</div>
        <div class="mt-4 text-xl font-bold text-gray-700 dark:text-gray-300">Ramadhan belum dimulai</div>
        @if ($nextPeriod)
          <div class="mt-2 text-gray-500 dark:text-gray-400">
            Ramadhan {{ $nextPeriod->year }} akan dimulai pada
            <span class="font-semibold text-amber-600">{{ $nextPeriod->start_date->isoFormat('D MMMM Y') }}</span>
            ({{ now()->diffInDays($nextPeriod->start_date) }} hari lagi)
          </div>
        @else
          <div class="mt-2 text-gray-500">Tambahkan data periode Ramadhan di menu Master Data.</div>
        @endif
      </div>
    @endif
  </x-filament::section>
</x-filament-widgets::widget>
