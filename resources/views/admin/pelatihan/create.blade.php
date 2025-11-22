@extends('layouts.app')

@section('title', 'Buat Pelatihan')
@section('page-title', 'Buat Pelatihan Baru')

@section('content')
<div class="max-w-6xl mx-auto p-4 sm:p-6 lg:p-8">
    <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
        
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-xl font-bold text-gray-800 flex items-center">
                <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Formulir Pelatihan Baru
            </h3>
            <p class="text-sm text-gray-500 mt-1">Isi detail lengkap untuk membuat program pelatihan baru.</p>
        </div>

        <form action="{{ route('admin.pelatihan.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-8">
            @csrf

            <!-- Bagian 1: Informasi Dasar 📝 -->
            <fieldset class="space-y-6">
                <legend class="text-lg font-bold text-indigo-600 border-b pb-2 mb-4">Informasi Dasar</legend>
                
                <!-- Judul -->
                <div>
                    <label for="judul" class="block text-sm font-medium text-gray-700 mb-1">Judul Pelatihan <span class="text-red-500">*</span></label>
                    <input type="text" id="judul" name="judul" value="{{ old('judul') }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2"
                           placeholder="Contoh: Pelatihan Web Development untuk Pemula" required>
                    @error('judul')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <!-- Deskripsi -->
                <div>
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi <span class="text-red-500">*</span></label>
                    <textarea id="deskripsi" name="deskripsi" rows="5"
                              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2"
                              placeholder="Jelaskan tentang pelatihan ini..." required>{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <!-- Jenis & Thumbnail -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="jenis" class="block text-sm font-medium text-gray-700 mb-1">Jenis Pelatihan <span class="text-red-500">*</span></label>
                        <select id="jenis" name="jenis" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2" required>
                            <option value="">Pilih Jenis</option>
                            <option value="soft_skill" {{ old('jenis') == 'soft_skill' ? 'selected' : '' }}>Soft Skill</option>
                            <option value="hard_skill" {{ old('jenis') == 'hard_skill' ? 'selected' : '' }}>Hard Skill</option>
                            <option value="sertifikasi" {{ old('jenis') == 'sertifikasi' ? 'selected' : '' }}>Sertifikasi</option>
                            <option value="pembekalan" {{ old('jenis') == 'pembekalan' ? 'selected' : '' }}>Pembekalan</option>
                        </select>
                        @error('jenis')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="thumbnail" class="block text-sm font-medium text-gray-700 mb-1">Upload Thumbnail</label>
                        <input type="file" id="thumbnail" name="thumbnail" accept="image/*"
                               class="w-full file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 block text-sm text-gray-500 border border-gray-300 rounded-lg shadow-sm cursor-pointer focus:outline-none">
                        <p class="text-xs text-gray-500 mt-1">Format JPG/PNG, maksimal 2MB</p>
                        @error('thumbnail')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
            </fieldset>

            <hr class="border-gray-100">

            <!-- Bagian 2: Instruktur & Lokasi 📍 -->
            <fieldset class="space-y-6">
                <legend class="text-lg font-bold text-indigo-600 border-b pb-2 mb-4">Instruktur & Lokasi</legend>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="instruktur" class="block text-sm font-medium text-gray-700 mb-1">Nama Instruktur</label>
                        <input type="text" id="instruktur" name="instruktur" value="{{ old('instruktur') }}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2"
                               placeholder="Nama instruktur">
                        @error('instruktur')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="tempat" class="block text-sm font-medium text-gray-700 mb-1">Tempat</label>
                        <input type="text" id="tempat" name="tempat" value="{{ old('tempat') }}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2"
                               placeholder="Contoh: Lab Komputer / Online via Zoom">
                        @error('tempat')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
            </fieldset>

            <hr class="border-gray-100">

            <!-- Bagian 3: Jadwal, Kuota & Biaya 💰 -->
            <fieldset class="space-y-6">
                <legend class="text-lg font-bold text-indigo-600 border-b pb-2 mb-4">Jadwal, Kuota & Biaya</legend>

                <!-- Jadwal -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-1">Tanggal & Waktu Mulai <span class="text-red-500">*</span></label>
                        <input type="datetime-local" id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2" required>
                        @error('tanggal_mulai')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 mb-1">Tanggal & Waktu Selesai <span class="text-red-500">*</span></label>
                        <input type="datetime-local" id="tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2" required>
                        @error('tanggal_selesai')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <!-- Kuota & Biaya -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="kuota" class="block text-sm font-medium text-gray-700 mb-1">Kuota Peserta</label>
                        <input type="number" id="kuota" name="kuota" value="{{ old('kuota') }}" min="1"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2"
                               placeholder="Contoh: 30">
                        <p class="text-xs text-gray-500 mt-1">Biarkan kosong jika tidak ada batasan jumlah peserta.</p>
                        @error('kuota')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="biaya" class="block text-sm font-medium text-gray-700 mb-1">Biaya (Rp)</label>
                        <input type="number" id="biaya" name="biaya" value="{{ old('biaya', 0) }}" min="0"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2"
                               placeholder="0 untuk gratis">
                        @error('biaya')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
            </fieldset>

            <hr class="border-gray-100">

            <!-- Bagian 4: Materi & Sertifikat 📄 -->
            <fieldset class="space-y-4">
                <legend class="text-lg font-bold text-indigo-600 border-b pb-2 mb-4">Dokumen</legend>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="materi" class="block text-sm font-medium text-gray-700 mb-1">Upload Materi (PDF)</label>
                        <input type="file" id="materi" name="materi" accept=".pdf"
                               class="w-full file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 block text-sm text-gray-500 border border-gray-300 rounded-lg shadow-sm cursor-pointer focus:outline-none">
                        <p class="text-xs text-gray-500 mt-1">Format PDF, maksimal 10MB.</p>
                        @error('materi')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="sertifikat_template" class="block text-sm font-medium text-gray-700 mb-1">Template Sertifikat (PDF)</label>
                        <input type="file" id="sertifikat_template" name="sertifikat_template" accept=".pdf"
                               class="w-full file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 block text-sm text-gray-500 border border-gray-300 rounded-lg shadow-sm cursor-pointer focus:outline-none">
                        <p class="text-xs text-gray-500 mt-1">Format PDF, maksimal 5MB. Digunakan untuk mencetak sertifikat.</p>
                        @error('sertifikat_template')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
            </fieldset>

            <!-- Info Box Penting -->
            <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4">
                <div class="flex items-start space-x-3">
                    <svg class="w-5 h-5 text-indigo-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div class="text-sm text-indigo-800">
                        <p class="font-bold mb-1">Penyimpanan Otomatis sebagai Draft</p>
                        <p>Pelatihan yang baru dibuat akan disimpan sebagai **Draft** secara otomatis. Pelatihan **tidak akan terlihat** oleh siswa sebelum Anda mengubah statusnya menjadi **Published** di halaman detail.</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-100">
                <a href="{{ route('admin.pelatihan.index') }}" class="btn btn-outline border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium py-2 px-4 rounded-lg transition duration-150">Batal</a>
                <button type="submit" class="btn btn-primary bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-150 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Pelatihan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection