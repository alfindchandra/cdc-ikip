@extends('layouts.app')

@section('title', 'Buat Laporan')
@section('page-title', 'Buat Laporan Baru')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    
    <a href="{{ route('admin.laporan.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 transition-colors duration-200 font-medium group">
        <svg class="w-5 h-5 mr-2 transition-transform duration-200 group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali ke Daftar Laporan
    </a>

    {{-- Info --}}
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-5">
        <h3 class="font-semibold text-blue-900 mb-2 flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Panduan Membuat Laporan
        </h3>
        <div class="text-sm text-blue-800 space-y-1">
            <p class="font-medium">Prosesnya sebagai berikut:</p>
            <ol class="list-decimal ml-5 space-y-1">
                <li>Sistem mengumpulkan data yang telah tersimpan, seperti:
                    <ul class="list-disc ml-4 mt-1 space-y-0.5 text-blue-700">
                        <li>Data alumni</li>
                        <li>Data lowongan kerja</li>
                        <li>Data lamaran</li>
                        <li>Data pelatihan</li>
                        <li>Data kerja sama</li>
                    </ul>
                </li>
                <li>Admin memilih jenis laporan yang ingin ditampilkan.</li>
                <li>Sistem mengolah data tersebut menjadi laporan.</li>
                <li>Laporan dapat ditampilkan di layar atau dicetak/diunduh sebagai dokumentasi.</li>
            </ol>
        </div>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
            <svg class="w-6 h-6 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Form Laporan
        </h2>

        <form action="{{ route('admin.laporan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- Judul --}}
            <div>
                <label for="judul" class="block text-sm font-medium text-gray-700 mb-1">
                    Judul Laporan <span class="text-red-500">*</span>
                </label>
                <input type="text" id="judul" name="judul" value="{{ old('judul') }}"
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
                        <option value="pkl" {{ old('jenis') == 'pkl' ? 'selected' : '' }}>PKL</option>
                        <option value="pelatihan" {{ old('jenis') == 'pelatihan' ? 'selected' : '' }}>Pelatihan</option>
                        <option value="rekrutmen" {{ old('jenis') == 'rekrutmen' ? 'selected' : '' }}>Rekrutmen</option>
                        <option value="kerjasama" {{ old('jenis') == 'kerjasama' ? 'selected' : '' }}>Kerjasama</option>
                        <option value="tahunan" {{ old('jenis') == 'tahunan' ? 'selected' : '' }}>Tahunan</option>
                        <option value="lainnya" {{ old('jenis') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @error('jenis')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Contoh Laporan yang Dihasilkan</label>
                    <div class="text-sm text-gray-500 bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 space-y-0.5">
                        <p>• Laporan jumlah alumni yang terdaftar</p>
                        <p>• Laporan lowongan kerja yang dipublikasikan</p>
                        <p>• Laporan jumlah pelamar pada setiap lowongan</p>
                    </div>
                </div>
            </div>

            {{-- Periode --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="periode_mulai" class="block text-sm font-medium text-gray-700 mb-1">
                        Periode Mulai <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="periode_mulai" name="periode_mulai" value="{{ old('periode_mulai') }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition @error('periode_mulai') border-red-500 @enderror">
                    @error('periode_mulai')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="periode_selesai" class="block text-sm font-medium text-gray-700 mb-1">
                        Periode Selesai <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="periode_selesai" name="periode_selesai" value="{{ old('periode_selesai') }}"
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
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- File Upload --}}
            <div>
                <label for="file_laporan" class="block text-sm font-medium text-gray-700 mb-1">
                    File Laporan (PDF)
                </label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition duration-150">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <a href="{{ route('admin.laporan.index') }}"
                   class="px-6 py-2.5 border border-gray-300 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50 transition duration-150">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition duration-150 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Laporan
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
