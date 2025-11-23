@extends('layouts.app')

@section('title', 'Edit Lowongan')
@section('page-title', 'Edit Lowongan Kerja')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="bg-white shadow-lg rounded-2xl overflow-hidden border border-gray-100">
        <!-- Header -->
        <div class="bg-gradient-to-r from-indigo-500 to-blue-600 px-6 py-4">
            <h3 class="text-lg md:text-xl font-semibold text-white flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 md:h-6 md:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit Lowongan Kerja
            </h3>
        </div>

        <form 
            action="{{ route('perusahaan.lowongan.update', $lowongan->id) }}" 
            method="POST" 
            enctype="multipart/form-data" 
            class="p-6 space-y-6"
        >
            @csrf
            @method('PUT')

            <!-- Current Thumbnail Preview -->
            @if($lowongan->thumbnail)
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Thumbnail Saat Ini</label>
                <div class="relative inline-block">
                    <img src="{{ Storage::url($lowongan->thumbnail) }}" alt="Current thumbnail" class="w-48 h-32 object-cover rounded-lg border border-gray-300">
                    <span class="absolute top-2 right-2 bg-black bg-opacity-50 text-white text-xs px-2 py-1 rounded">Current</span>
                </div>
            </div>
            @endif

            <!-- Judul dan Posisi -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Judul Lowongan *</label>
                    <input type="text" name="judul" value="{{ old('judul', $lowongan->judul) }}" class="w-full rounded-lg p-3 border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm" placeholder="Contoh: Web Developer" required>
                    @error('judul')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Posisi *</label>
                    <input type="text" name="posisi" value="{{ old('posisi', $lowongan->posisi) }}" class="w-full rounded-lg p-3 border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm" placeholder="Contoh: Full Stack Developer" required>
                    @error('posisi')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <!-- Deskripsi dan Kualifikasi -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Pekerjaan *</label>
                    <textarea name="deskripsi" rows="5" class="w-full p-2 rounded-lg border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm" placeholder="Jelaskan tugas dan tanggung jawab..." required>{{ old('deskripsi', $lowongan->deskripsi) }}</textarea>
                    @error('deskripsi')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kualifikasi *</label>
                    <textarea name="kualifikasi" rows="5" class="w-full p-2 rounded-lg border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm" placeholder="Tuliskan kualifikasi yang dibutuhkan..." required>{{ old('kualifikasi', $lowongan->kualifikasi) }}</textarea>
                    @error('kualifikasi')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <!-- Benefit -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Benefit</label>
                <textarea name="benefit" rows="3" class="w-full rounded-lg p-2 border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm" placeholder="Benefit yang didapatkan...">{{ old('benefit', $lowongan->benefit) }}</textarea>
                @error('benefit')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Info pekerjaan -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Pekerjaan *</label>
                    <select name="tipe_pekerjaan" class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm" required>
                        <option value="">Pilih Tipe</option>
                        <option value="full_time" {{ old('tipe_pekerjaan', $lowongan->tipe_pekerjaan) == 'full_time' ? 'selected' : '' }}>Full Time</option>
                        <option value="part_time" {{ old('tipe_pekerjaan', $lowongan->tipe_pekerjaan) == 'part_time' ? 'selected' : '' }}>Part Time</option>
                        <option value="kontrak" {{ old('tipe_pekerjaan', $lowongan->tipe_pekerjaan) == 'kontrak' ? 'selected' : '' }}>Kontrak</option>
                        <option value="magang" {{ old('tipe_pekerjaan', $lowongan->tipe_pekerjaan) == 'magang' ? 'selected' : '' }}>Magang</option>
                    </select>
                    @error('tipe_pekerjaan')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi *</label>
                    <input type="text" name="lokasi" value="{{ old('lokasi', $lowongan->lokasi) }}" class="p-3 w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm" placeholder="Contoh: Surabaya" required>
                    @error('lokasi')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <!-- Gaji -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Gaji Minimum (Rp)</label>
                    <input type="number" name="gaji_min" value="{{ old('gaji_min', $lowongan->gaji_min) }}" class="w-full p-3 rounded-lg border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm" placeholder="3000000">
                    @error('gaji_min')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Gaji Maksimum (Rp)</label>
                    <input type="number" name="gaji_max" value="{{ old('gaji_max', $lowongan->gaji_max) }}" class="w-full p-3 rounded-lg border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm" placeholder="5000000">
                    @error('gaji_max')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <!-- Kuota & Thumbnail -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kuota</label>
                    <input type="number" name="kuota" value="{{ old('kuota', $lowongan->kuota) }}" class="w-full p-3 rounded-lg border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm" placeholder="5">
                    <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ada batasan</p>
                    @error('kuota')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Thumbnail Baru (Opsional)</label>
                    <input type="file" name="thumbnail" accept="image/*" class="w-full text-sm p-3 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    <p class="text-xs text-gray-500 mt-1">Maksimal 2MB. Kosongkan jika tidak ingin mengubah</p>
                    @error('thumbnail')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <!-- Tanggal -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai *</label>
                    <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', $lowongan->tanggal_mulai->format('Y-m-d')) }}" class="w-full p-3 rounded-lg border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm" required>
                    @error('tanggal_mulai')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Berakhir *</label>
                    <input type="date" name="tanggal_berakhir" value="{{ old('tanggal_berakhir', $lowongan->tanggal_berakhir->format('Y-m-d')) }}" class="w-full p-3 rounded-lg border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm" required>
                    @error('tanggal_berakhir')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <!-- Info Box -->
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            <strong>Perhatian:</strong> Pastikan semua informasi yang dimasukkan sudah benar. Perubahan akan langsung terlihat oleh siswa/mahasiswa yang mengakses lowongan ini.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-3 pt-6 border-t border-gray-200">
                <a href="{{ route('perusahaan.lowongan.show', $lowongan->id) }}" class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 text-sm font-medium transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection