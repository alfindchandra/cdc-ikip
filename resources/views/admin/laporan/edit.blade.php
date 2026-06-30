@extends('layouts.app')

@section('title', 'Edit Laporan')
@section('page-title', 'Edit Laporan')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <a href="{{ route('admin.laporan.show', $laporan->id) }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 transition-colors duration-200 font-medium group">
        <svg class="w-5 h-5 mr-2 transition-transform duration-200 group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali ke Detail Laporan
    </a>

    <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
            <svg class="w-6 h-6 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Edit Laporan: {{ Str::limit($laporan->judul, 50) }}
        </h2>

        <form action="{{ route('admin.laporan.update', $laporan->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Judul --}}
            <div>
                <label for="judul" class="block text-sm font-medium text-gray-700 mb-1">
                    Judul Laporan <span class="text-red-500">*</span>
                </label>
                <input type="text" id="judul" name="judul" value="{{ old('judul', $laporan->judul) }}"
                       placeholder="Contoh: Laporan Rekrutmen Semester Ganjil 2024"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition @error('judul') border-red-500 @enderror">
                @error('judul')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Jenis & Status row --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="jenis" class="block text-sm font-medium text-gray-700 mb-1">
                        Jenis Laporan <span class="text-red-500">*</span>
                    </label>
                    <select id="jenis" name="jenis"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition @error('jenis') border-red-500 @enderror">
                        <option value="">-- Pilih Jenis --</option>
                        <option value="pkl" {{ old('jenis', $laporan->jenis) == 'pkl' ? 'selected' : '' }}>PKL</option>
                        <option value="pelatihan" {{ old('jenis', $laporan->jenis) == 'pelatihan' ? 'selected' : '' }}>Pelatihan</option>
                        <option value="rekrutmen" {{ old('jenis', $laporan->jenis) == 'rekrutmen' ? 'selected' : '' }}>Rekrutmen</option>
                        <option value="kerjasama" {{ old('jenis', $laporan->jenis) == 'kerjasama' ? 'selected' : '' }}>Kerjasama</option>
                        <option value="tahunan" {{ old('jenis', $laporan->jenis) == 'tahunan' ? 'selected' : '' }}>Tahunan</option>
                        <option value="lainnya" {{ old('jenis', $laporan->jenis) == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @error('jenis')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status Saat Ini</label>
                    <div class="px-4 py-2.5 border border-gray-200 rounded-lg bg-gray-50 flex items-center space-x-2">
                        @if($laporan->status == 'published')
                        <span class="w-2.5 h-2.5 rounded-full bg-green-500 inline-block"></span>
                        <span class="text-sm font-medium text-green-700">Dipublikasikan</span>
                        @else
                        <span class="w-2.5 h-2.5 rounded-full bg-yellow-500 inline-block"></span>
                        <span class="text-sm font-medium text-yellow-700">Draft</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Periode --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="periode_mulai" class="block text-sm font-medium text-gray-700 mb-1">
                        Periode Mulai <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="periode_mulai" name="periode_mulai"
                           value="{{ old('periode_mulai', $laporan->periode_mulai->format('Y-m-d')) }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition @error('periode_mulai') border-red-500 @enderror">
                    @error('periode_mulai')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="periode_selesai" class="block text-sm font-medium text-gray-700 mb-1">
                        Periode Selesai <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="periode_selesai" name="periode_selesai"
                           value="{{ old('periode_selesai', $laporan->periode_selesai->format('Y-m-d')) }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition @error('periode_selesai') border-red-500 @enderror">
                    @error('periode_selesai')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Deskripsi --}}
            <div>
                <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" rows="4"
                          placeholder="Tuliskan deskripsi singkat tentang laporan ini..."
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi', $laporan->deskripsi) }}</textarea>
                @error('deskripsi')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- File Upload --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">File Laporan (PDF)</label>

                @if($laporan->file_laporan)
                <div class="mb-3 flex items-center justify-between bg-red-50 border border-red-200 rounded-lg px-4 py-3">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-gray-700">File saat ini:</p>
                            <p class="text-xs text-gray-500">{{ basename($laporan->file_laporan) }}</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.laporan.download', $laporan->id) }}"
                       class="text-sm text-red-600 hover:text-red-700 font-medium flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Unduh
                    </a>
                </div>
                <p class="text-xs text-gray-500 mb-2">Upload file baru untuk mengganti file yang sudah ada:</p>
                @endif

                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition duration-150">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        <div class="flex text-sm text-gray-600 justify-center">
                            <label for="file_laporan" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 transition">
                                <span>Upload file PDF</span>
                                <input id="file_laporan" name="file_laporan" type="file" accept=".pdf" class="sr-only">
                            </label>
                            <p class="pl-1">atau drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">PDF hingga 10MB</p>
                    </div>
                </div>
                <p id="file-name" class="mt-2 text-sm text-gray-600 hidden"></p>
                @error('file_laporan')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Buttons --}}
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.laporan.show', $laporan->id) }}"
                   class="px-6 py-2.5 border border-gray-300 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50 transition duration-150">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition duration-150 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('file_laporan').addEventListener('change', function() {
        const fileNameEl = document.getElementById('file-name');
        if (this.files && this.files[0]) {
            fileNameEl.textContent = 'File dipilih: ' + this.files[0].name;
            fileNameEl.classList.remove('hidden');
        } else {
            fileNameEl.classList.add('hidden');
        }
    });
</script>
@endpush
