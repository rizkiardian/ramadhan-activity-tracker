@extends('layouts.app')

@section('title', 'Tipe Aktivitas — Ramadhan Activity Tracker')
@section('page-title', 'Tipe Aktivitas')
@section('page-subtitle', 'Kelola jenis-jenis aktivitas ibadah')

@section('page-actions')
  <a href="{{ route('activity-types.create') }}"
    class="inline-flex items-center gap-2 bg-primary-700 hover:bg-primary-800 text-white text-sm font-medium py-2 px-4 rounded-lg transition-colors duration-150 shadow-sm">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
    </svg>
    Tambah Tipe
  </a>
@endsection

@section('content')
  <div class="pt-2">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Nama</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Dibuat Oleh</th>
              <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wide">Jumlah
                Aktivitas</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Dibuat</th>
              <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            @forelse($activityTypes as $type)
              <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-4 py-3 font-medium text-gray-900">{{ $type->name }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $type->createdBy?->name ?? '–' }}</td>
                <td class="px-4 py-3 text-center">
                  <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                    {{ number_format($type->user_activities_count) }}
                  </span>
                </td>
                <td class="px-4 py-3 text-gray-500 text-xs">{{ $type->created_at->translatedFormat('d M Y') }}</td>
                <td class="px-4 py-3 text-right whitespace-nowrap">
                  <a href="{{ route('activity-types.edit', $type) }}"
                    class="inline-flex items-center gap-1 text-xs font-medium text-primary-700 hover:text-primary-900 mr-3">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                  </a>
                  <form method="POST" action="{{ route('activity-types.destroy', $type) }}" class="inline"
                    onsubmit="return confirm('Hapus tipe aktivitas ini? Semua aktivitas terkait mungkin terpengaruh.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                      class="inline-flex items-center gap-1 text-xs font-medium text-red-600 hover:text-red-800">
                      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                      </svg>
                      Hapus
                    </button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="px-4 py-12 text-center text-gray-400">
                  <svg class="w-10 h-10 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z" />
                  </svg>
                  <p class="text-sm">Belum ada tipe aktivitas</p>
                  <a href="{{ route('activity-types.create') }}"
                    class="mt-2 inline-block text-primary-600 hover:underline text-sm">Tambah tipe pertama →</a>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @if ($activityTypes->hasPages())
        <div class="px-4 py-3 border-t border-gray-100">
          {{ $activityTypes->links() }}
        </div>
      @endif
    </div>
  </div>
@endsection
