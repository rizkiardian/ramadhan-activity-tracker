@extends('layouts.app')

@section('title', 'Edit Tipe Aktivitas — Ramadhan Activity Tracker')
@section('page-title', 'Edit Tipe Aktivitas')
@section('page-subtitle', 'Perbarui nama tipe aktivitas')

@section('content')
  <div class="pt-2 max-w-lg">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
      <form method="POST" action="{{ route('activity-types.update', $activityType) }}" class="space-y-5">
        @csrf
        @method('PUT')

        <div>
          <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">
            Nama Tipe <span class="text-red-500">*</span>
          </label>
          <input id="name" type="text" name="name" value="{{ old('name', $activityType->name) }}"
            placeholder="cth. Tarawih, Tadarus, Sedekah..." autofocus
            class="w-full text-sm border rounded-lg px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 {{ $errors->has('name') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
          @error('name')
            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div class="flex items-center gap-3 pt-2">
          <button type="submit"
            class="bg-primary-700 hover:bg-primary-800 text-white text-sm font-semibold py-2.5 px-6 rounded-lg transition-colors duration-150 shadow-sm">
            Perbarui
          </button>
          <a href="{{ route('activity-types.index') }}"
            class="text-sm font-medium text-gray-600 hover:text-gray-800 py-2.5 px-4">
            Batal
          </a>
        </div>
      </form>
    </div>
  </div>
@endsection
