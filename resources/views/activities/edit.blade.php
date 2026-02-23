@extends('layouts.app')

@section('title', 'Edit Aktivitas — Ramadhan Activity Tracker')
@section('page-title', 'Edit Aktivitas')
@section('page-subtitle', 'Perbarui catatan aktivitas ibadah')

@section('content')
  <div class="pt-2 max-w-2xl">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
      <form method="POST" action="{{ route('activities.update', $activity) }}" class="space-y-5">
        @csrf
        @method('PUT')

        <div>
          <label for="activity_type_id" class="block text-sm font-medium text-gray-700 mb-1.5">
            Tipe Aktivitas <span class="text-red-500">*</span>
          </label>
          <select id="activity_type_id" name="activity_type_id"
            class="w-full text-sm border rounded-lg px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 {{ $errors->has('activity_type_id') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
            <option value="">Pilih tipe aktivitas</option>
            @foreach ($activityTypes as $type)
              <option value="{{ $type->id }}"
                {{ old('activity_type_id', $activity->activity_type_id) == $type->id ? 'selected' : '' }}>
                {{ $type->name }}
              </option>
            @endforeach
          </select>
          @error('activity_type_id')
            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label for="date" class="block text-sm font-medium text-gray-700 mb-1.5">
            Tanggal <span class="text-red-500">*</span>
          </label>
          <input id="date" type="date" name="date" value="{{ old('date', $activity->date->format('Y-m-d')) }}"
            class="w-full text-sm border rounded-lg px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 {{ $errors->has('date') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
          @error('date')
            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label for="start_time" class="block text-sm font-medium text-gray-700 mb-1.5">Waktu Mulai</label>
            <input id="start_time" type="time" name="start_time" value="{{ old('start_time', $activity->start_time) }}"
              class="w-full text-sm border rounded-lg px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 {{ $errors->has('start_time') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
            @error('start_time')
              <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
            @enderror
          </div>
          <div>
            <label for="end_time" class="block text-sm font-medium text-gray-700 mb-1.5">Waktu Selesai</label>
            <input id="end_time" type="time" name="end_time" value="{{ old('end_time', $activity->end_time) }}"
              class="w-full text-sm border rounded-lg px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 {{ $errors->has('end_time') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
            @error('end_time')
              <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
            @enderror
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1.5">
            Status <span class="text-red-500">*</span>
          </label>
          <div class="grid grid-cols-3 gap-3">
            @foreach (['Pending' => 'Pending', 'Done' => 'Selesai', 'Skipped' => 'Dilewati'] as $value => $label)
              <label
                class="flex items-center gap-2 cursor-pointer border rounded-lg px-3 py-2.5 transition-colors {{ old('status', $activity->status) === $value ? 'border-primary-500 bg-primary-50' : 'border-gray-300 hover:border-gray-400' }}">
                <input type="radio" name="status" value="{{ $value }}"
                  {{ old('status', $activity->status) === $value ? 'checked' : '' }}
                  class="text-primary-600 focus:ring-primary-500">
                <span class="text-sm font-medium text-gray-700">{{ $label }}</span>
              </label>
            @endforeach
          </div>
          @error('status')
            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label for="notes" class="block text-sm font-medium text-gray-700 mb-1.5">Catatan</label>
          <textarea id="notes" name="notes" rows="3" placeholder="Tambahkan catatan (opsional)..."
            class="w-full text-sm border rounded-lg px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 resize-none {{ $errors->has('notes') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">{{ old('notes', $activity->notes) }}</textarea>
          @error('notes')
            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div class="flex items-center gap-3 pt-2">
          <button type="submit"
            class="bg-primary-700 hover:bg-primary-800 text-white text-sm font-semibold py-2.5 px-6 rounded-lg transition-colors duration-150 shadow-sm">
            Perbarui Aktivitas
          </button>
          <a href="{{ route('activities.index') }}"
            class="text-sm font-medium text-gray-600 hover:text-gray-800 py-2.5 px-4">
            Batal
          </a>
        </div>
      </form>
    </div>
  </div>
@endsection
