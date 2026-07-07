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
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori *</label>
                    <select name="category" class="w-full rounded-lg border-2 border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm p-2" required>
                        <option value="">Pilih Kategori</option>
                        @foreach(['Teknologi & IT', 'Pendidikan', 'Marketing & Sales', 'Keuangan & Akuntansi', 'Administrasi & SDM', 'Kesehatan', 'Teknik & Engineering', 'Media & Kreatif', 'Lainnya'] as $cat)
                            <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                    @error('category')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
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
    <label class="block text-sm font-semibold text-gray-700 mb-2">Kualifikasi <span class="text-red-500">*</span></label>
    
    <!-- Wrapper Alpine.js -->
    <div x-data="{ 
        open: false, 
        search: '', 
        selected: {{ json_encode(old('kualifikasi', [])) }},
        options: [
            'S1 Pendidikan Matematika', 'D3 Teknik Informatika', 'S2 Pendidikan Matematika',
            'S1 Ilmu Komputer', 'S1 Sistem Informasi', 'D3 Manajemen Informatika',
            'S1 Pendidikan Fisika', 'S1 Pendidikan Biologi', 'S1 Pendidikan Kimia',
            'S1 Teknik Elektro', 'S1 Teknik Sipil', 'S1 Teknik Mesin',
            'S1 Akuntansi', 'S1 Manajemen', 'S1 Ilmu Ekonomi',
            'S1 Hukum', 'S1 Psikologi', 'S1 Hubungan Internasional',
            'S1 Sastra Inggris', 'S1 Sastra Indonesia', 'S1 Komunikasi',
            'S2 Ilmu Komputer', 'S2 Sistem Informasi', 'S2 Manajemen',
            'D4 Teknik Elektronika', 'D3 Keperawatan', 'D3 Kebidanan',
            'S1 Farmasi', 'S1 Kedokteran', 'S1 Kesehatan Masyarakat', 'Lainnya'
        ],
        toggle(option) {
            if (this.selected.includes(option)) {
                this.selected = this.selected.filter(item => item !== option);
            } else {
                this.selected.push(option);
            }
        },
        get filteredOptions() {
            return this.options.filter(i => i.toLowerCase().includes(this.search.toLowerCase()));
        }
    }" class="relative">
        
        <!-- Input Utama / Tempat Chips -->
        <div @click="open = !open" class="w-full min-h-[46px] border border-gray-300 rounded-xl p-2 focus-within:ring-2 focus-within:ring-indigo-500 focus-within:border-indigo-500 text-sm transition-all bg-white flex flex-wrap gap-1.5 items-center cursor-pointer">
            
            <!-- Jika belum ada yang dipilih -->
            <template x-if="selected.length === 0">
                <span class="text-gray-400 pl-1">Pilih Kualifikasi (Bisa lebih dari satu)</span>
            </template>

            <!-- Tampilan Chips yang dipilih -->
            <template x-for="item in selected" :key="item">
                <span class="inline-flex items-center gap-1 bg-indigo-50 text-indigo-700 text-xs font-medium px-2.5 py-1 rounded-lg border border-indigo-100">
                    <span x-text="item"></span>
                    <button type="button" @click.stop="toggle(item)" class="text-indigo-400 hover:text-indigo-600 font-bold">&times;</button>
                </span>
            </template>

            <!-- Hidden inputs untuk dikirim ke Backend Laravel -->
            <template x-for="item in selected" :key="'input-'+item">
                <input type="hidden" name="kualifikasi" :value="item">
            </template>
        </div>

        <!-- Dropdown Menu + Search Bar -->
        <div x-show="open" @click.away="open = false" class="absolute z-10 mt-2 w-full bg-white border border-gray-200 rounded-xl shadow-lg max-h-60 overflow-y-auto p-2" x-cloak>
            <input type="text" x-model="search" placeholder="Cari kualifikasi..." class="w-full p-2 mb-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
            
            <div class="flex flex-col gap-0.5">
                <template x-for="option in filteredOptions" :key="option">
                    <div @click="toggle(option)" 
                         :class="selected.includes(option) ? 'bg-indigo-600 text-white' : 'text-gray-700 hover:bg-gray-100'"
                         class="p-2 text-sm rounded-lg cursor-pointer transition-colors flex justify-between items-center">
                        <span x-text="option"></span>
                        <span x-show="selected.includes(option)" class="text-xs font-bold">✓</span>
                    </div>
                </template>
                <template x-if="filteredOptions.length === 0">
                    <span class="p-2 text-sm text-gray-500 italic text-center">Kualifikasi tidak ditemukan</span>
                </template>
            </div>
        </div>
    </div>
    
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
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Thumbnail</label>
                    <input type="file" name="thumbnail" accept="image/*" class="w-full text-sm p-2.5 border-2 rounded-lg border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <p class="text-xs text-gray-500 mt-1">JPG/PNG/WEBP, maks. 2MB</p>
                    @error('thumbnail')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
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
