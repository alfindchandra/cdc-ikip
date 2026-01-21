@extends('layouts.app')

@section('title', 'Buat Lowongan')
@section('page-title', 'Buat Lowongan Kerja Baru')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="bg-white shadow-lg rounded-2xl overflow-hidden border border-gray-100">
        <!-- Header -->
        <div class="bg-gradient-to-r from-indigo-500 to-blue-600 px-6 py-4">
            <h3 class="text-lg md:text-xl font-semibold text-white flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 md:h-6 md:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Form Lowongan Kerja
            </h3>
        </div>

        <form 
            action="{{ route('perusahaan.lowongan.store') }}" 
            method="POST" 
            enctype="multipart/form-data" 
            class="p-6 space-y-6"
        >
            @csrf

            <!-- Judul dan Posisi -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
               
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Posisi *</label>
                    <input type="text" name="posisi" value="{{ old('posisi') }}" class="w-full border-2 rounded-lg p-3 border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm" placeholder="Contoh: Full Stack Developer" required>
                    @error('posisi')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <!-- Deskripsi dan Kualifikasi -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Pekerjaan *</label>
                    <textarea name="deskripsi" rows="5" class="w-full border-2 p-2 rounded-lg border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm" placeholder="Jelaskan tugas dan tanggung jawab..." required>{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kualifikasi *</label>
                    <textarea name="kualifikasi" rows="5" class="w-full p-2 rounded-lg border-gray-300 border-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm" placeholder="Tuliskan kualifikasi yang dibutuhkan..." required>{{ old('kualifikasi') }}</textarea>
                    @error('kualifikasi')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <!-- Benefit -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Benefit</label>
                <textarea name="benefit" rows="3" class="w-full rounded-lg p-2 border-2 border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm" placeholder="Benefit yang didapatkan...">{{ old('benefit') }}</textarea>
                @error('benefit')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pendidikan</label>
                    <select name="pendidikan" class="w-full rounded-lg border-2 border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm p-2">
                        <option value="">Pilih Tipe</option>
                        <option value="SMA/SMK" {{ old('pendidikan') == 'smk/sma' ? 'selected' : '' }}>SMA/SMK</option>
                        <option value="D1" {{ old('pendidikan') == 'D1' ? 'selected' : '' }}>D1</option>
                        <option value="D3" {{ old('pendidikan') == 'D3' ? 'selected' : '' }}>D3</option>
                        <option value="S1" {{ old('pendidikan') == 'S1' ? 'selected' : '' }}>S1</option>
                        <option value="S2" {{ old('pendidikan') == 'S2' ? 'selected' : '' }}>S2</option>
                        <option value="S3" {{ old('pendidikan') == 'S3' ? 'selected' : '' }}>S3</option>
                    </select>
                    @error('pendidikan')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

            <!-- Info pekerjaan -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Pekerjaan *</label>
                    <select name="tipe_pekerjaan" class="w-full rounded-lg border-gray-300 border-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm p-2" required>
                        <option value="">Pilih Tipe</option>
                        <option value="full_time" {{ old('tipe_pekerjaan') == 'full_time' ? 'selected' : '' }}>Full Time</option>
                        <option value="part_time" {{ old('tipe_pekerjaan') == 'part_time' ? 'selected' : '' }}>Part Time</option>
                        <option value="kontrak" {{ old('tipe_pekerjaan') == 'kontrak' ? 'selected' : '' }}>Kontrak</option>
                        <option value="magang" {{ old('tipe_pekerjaan') == 'magang' ? 'selected' : '' }}>Magang</option>
                    </select>
                    @error('tipe_pekerjaan')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi *</label>
                    <input type="text" name="lokasi" value="{{ old('lokasi') }}" class=" p-3 border-2 w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm" placeholder="Contoh: Surabaya" required>
                    @error('lokasi')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <!-- Gaji -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Gaji Minimum (Rp)</label>
                    <input type="number" name="gaji_min" value="{{ old('gaji_min') }}" class="w-full border-2 p-3 rounded-lg border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm" placeholder="3000000">
                    @error('gaji_min')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Gaji Maksimum (Rp)</label>
                    <input type="number" name="gaji_max" value="{{ old('gaji_max') }}" class="w-full border-2 p-3 rounded-lg border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm" placeholder="5000000">
                    @error('gaji_max')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <!-- Kuota & Thumbnail -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kuota</label>
                    <input type="number" name="kuota" value="{{ old('kuota') }}" class="w-full p-3 rounded-lg border-gray-300 border-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm" placeholder="5">
                    <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ada batasan</p>
                    @error('kuota')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
              
            </div>

            <!-- Tanggal -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai *</label>
                    <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', date('Y-m-d')) }}" class="w-full border-2 p-3 rounded-lg border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm" required>
                    @error('tanggal_mulai')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Berakhir *</label>
                    <input type="date" name="tanggal_berakhir" value="{{ old('tanggal_berakhir') }}" class="w-full border-2 p-3 rounded-lg border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm" required>
                    @error('tanggal_berakhir')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-3 pt-6 border-t border-gray-200">
                <a href="{{ route('perusahaan.lowongan.index') }}" class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 text-sm font-medium transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm transition">
                    Simpan Lowongan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
