<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-50">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Ramadhan Activity Tracker')</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full font-sans antialiased">
  <div class="flex h-full min-h-screen">
    {{-- Sidebar --}}
    <aside class="hidden lg:flex lg:flex-col lg:w-64 lg:fixed lg:inset-y-0 bg-primary-800 text-white">
      {{-- Logo --}}
      <div class="flex items-center gap-3 px-6 py-5 border-b border-primary-700">
        <div class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center flex-shrink-0">
          <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        <div>
          <p class="text-sm font-bold leading-tight">Ramadhan</p>
          <p class="text-xs text-primary-300 leading-tight">Activity Tracker</p>
        </div>
      </div>

      {{-- Navigation --}}
      <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
        <x-nav-link route="dashboard" icon="home">
          Dashboard
        </x-nav-link>
        <x-nav-link route="prayer-times.index" icon="clock">
          Jadwal Sholat
        </x-nav-link>
        <x-nav-link route="activities.index" icon="lightning-bolt">
          Aktivitas
        </x-nav-link>
        <x-nav-link route="activity-types.index" icon="tag">
          Tipe Aktivitas
        </x-nav-link>
        <x-nav-link route="regency-sync.index" icon="refresh">
          Sinkronisasi
        </x-nav-link>
        <x-nav-link route="sync-logs.index" icon="clipboard-list">
          Log Sinkronisasi
        </x-nav-link>
      </nav>

      {{-- User + Logout --}}
      <div class="px-3 pb-4 border-t border-primary-700 mt-auto pt-4">
        <div class="flex items-center gap-3 px-3 mb-3">
          <div
            class="w-8 h-8 rounded-full bg-primary-600 flex items-center justify-center text-sm font-semibold flex-shrink-0">
            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
          </div>
          <div class="min-w-0">
            <p class="text-sm font-medium truncate">{{ Auth::user()->name }}</p>
            <p class="text-xs text-primary-300 truncate">{{ Auth::user()->email }}</p>
          </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit"
            class="w-full flex items-center gap-2 px-3 py-2 text-sm text-primary-200 hover:text-white hover:bg-primary-700 rounded-lg transition-colors duration-150">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            Keluar
          </button>
        </form>
      </div>
    </aside>

    {{-- Mobile sidebar button --}}
    <div
      class="lg:hidden fixed top-0 left-0 right-0 z-30 flex items-center justify-between bg-primary-800 text-white px-4 py-3">
      <div class="flex items-center gap-2">
        <button id="sidebar-toggle" class="p-1 rounded-md hover:bg-primary-700">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>
        <span class="font-semibold text-sm">Ramadhan Tracker</span>
      </div>
    </div>

    {{-- Mobile sidebar overlay --}}
    <div id="sidebar-overlay" class="lg:hidden hidden fixed inset-0 z-20 bg-black/50" onclick="toggleSidebar()"></div>
    <div id="mobile-sidebar"
      class="lg:hidden hidden fixed inset-y-0 left-0 z-30 w-64 bg-primary-800 text-white flex flex-col">
      <div class="flex items-center gap-3 px-6 py-5 border-b border-primary-700">
        <div class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center flex-shrink-0">
          <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        <div>
          <p class="text-sm font-bold leading-tight">Ramadhan</p>
          <p class="text-xs text-primary-300 leading-tight">Activity Tracker</p>
        </div>
      </div>
      <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
        <x-nav-link route="dashboard" icon="home">Dashboard</x-nav-link>
        <x-nav-link route="prayer-times.index" icon="clock">Jadwal Sholat</x-nav-link>
        <x-nav-link route="activities.index" icon="lightning-bolt">Aktivitas</x-nav-link>
        <x-nav-link route="activity-types.index" icon="tag">Tipe Aktivitas</x-nav-link>
        <x-nav-link route="regency-sync.index" icon="refresh">Sinkronisasi</x-nav-link>
        <x-nav-link route="sync-logs.index" icon="clipboard-list">Log Sinkronisasi</x-nav-link>
      </nav>
    </div>

    {{-- Main content --}}
    <main class="flex-1 lg:pl-64 pt-14 lg:pt-0">
      {{-- Page header --}}
      <div class="bg-white border-b border-gray-200 px-6 py-4">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-xl font-bold text-gray-800">@yield('page-title', 'Dashboard')</h1>
            @hasSection('page-subtitle')
              <p class="text-sm text-gray-500 mt-0.5">@yield('page-subtitle')</p>
            @endif
          </div>
          @hasSection('page-actions')
            <div class="flex items-center gap-2">
              @yield('page-actions')
            </div>
          @endif
        </div>
      </div>

      {{-- Flash messages --}}
      <div class="px-6 pt-4">
        @if (session('success'))
          <div
            class="flex items-start gap-3 bg-green-50 border border-green-200 text-green-800 rounded-lg px-4 py-3 mb-4">
            <svg class="w-5 h-5 text-green-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
              viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-sm">{{ session('success') }}</p>
          </div>
        @endif
        @if (session('error'))
          <div class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-800 rounded-lg px-4 py-3 mb-4">
            <svg class="w-5 h-5 text-red-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
              viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-sm">{{ session('error') }}</p>
          </div>
        @endif
      </div>

      {{-- Page content --}}
      <div class="px-6 pb-6">
        @yield('content')
      </div>
    </main>
  </div>

  <script>
    function toggleSidebar() {
      const sidebar = document.getElementById('mobile-sidebar');
      const overlay = document.getElementById('sidebar-overlay');
      sidebar.classList.toggle('hidden');
      overlay.classList.toggle('hidden');
    }
    document.getElementById('sidebar-toggle')?.addEventListener('click', toggleSidebar);
  </script>

  @stack('scripts')
</body>

</html>
