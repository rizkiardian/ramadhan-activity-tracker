<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-50">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login — Ramadhan Activity Tracker</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full font-sans antialiased">
  <div class="min-h-full flex">
    {{-- Left branding panel --}}
    <div class="hidden lg:flex lg:w-1/2 bg-primary-800 flex-col justify-between p-12">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        <span class="text-white text-lg font-bold">Ramadhan Tracker</span>
      </div>
      <div>
        <h2 class="text-4xl font-bold text-white leading-tight mb-4">
          Pantau ibadah<br>Ramadhan-mu
        </h2>
        <p class="text-primary-200 text-lg leading-relaxed">
          Catat aktivitas ibadah, jadwal sholat, dan progress Ramadhan kamu dalam satu tempat yang mudah digunakan.
        </p>
      </div>
      <div class="flex gap-6">
        <div class="text-center">
          <p class="text-3xl font-bold text-white">30</p>
          <p class="text-primary-300 text-sm mt-1">Hari Ramadhan</p>
        </div>
        <div class="text-center">
          <p class="text-3xl font-bold text-white">5</p>
          <p class="text-primary-300 text-sm mt-1">Waktu Sholat</p>
        </div>
        <div class="text-center">
          <p class="text-3xl font-bold text-white">∞</p>
          <p class="text-primary-300 text-sm mt-1">Pahala</p>
        </div>
      </div>
    </div>

    {{-- Right login form --}}
    <div class="flex-1 flex items-center justify-center p-8 bg-white">
      <div class="w-full max-w-sm">
        <div class="mb-8">
          <div class="w-12 h-12 rounded-xl bg-primary-100 flex items-center justify-center mb-6 lg:hidden">
            <svg class="w-7 h-7 text-primary-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <h1 class="text-2xl font-bold text-gray-900">Selamat Datang</h1>
          <p class="text-gray-500 mt-1 text-sm">Masuk untuk melanjutkan ke dashboard</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
          @csrf

          <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Alamat Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
              autocomplete="email" placeholder="admin@example.com"
              class="w-full px-3.5 py-2.5 text-sm border rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 {{ $errors->has('email') ? 'border-red-400 bg-red-50' : 'border-gray-300 bg-white' }}">
            @error('email')
              <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Kata Sandi</label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
              placeholder="••••••••"
              class="w-full px-3.5 py-2.5 text-sm border rounded-lg shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 {{ $errors->has('password') ? 'border-red-400 bg-red-50' : 'border-gray-300 bg-white' }}">
            @error('password')
              <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 cursor-pointer">
              <input type="checkbox" name="remember"
                class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 w-4 h-4">
              <span class="text-sm text-gray-600">Ingat saya</span>
            </label>
          </div>

          <button type="submit"
            class="w-full bg-primary-700 hover:bg-primary-800 text-white font-semibold py-2.5 px-4 rounded-lg text-sm transition-colors duration-150 shadow-sm">
            Masuk
          </button>
        </form>

        <p class="mt-6 text-center text-xs text-gray-400">
          Ramadhan Activity Tracker &copy; {{ date('Y') }}
        </p>
      </div>
    </div>
  </div>
</body>

</html>
