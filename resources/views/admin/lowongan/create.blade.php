@extends('layouts.app')

@section('title', 'Buat Lowongan Kerja')
@section('page-title', 'Buat Lowongan Kerja Baru')

@section('content')
<div class="max-w-5xl mx-auto pb-12">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Buat Lowongan Kerja</h2>
            <p class="text-sm text-gray-500">Admin membuat lowongan kerja yang dipublikasikan secara mandiri tanpa terikat perusahaan tertentu.</p>
        </div>
        <a href="{{ route('admin.lowongan.index') }}" class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-indigo-600 transition-colors">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-indigo-500 to-blue-600 px-8 py-5">
            <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Form Lowongan Kerja (Admin)
            </h3>
        </div>

        <form action="{{ route('admin.lowongan.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-7">
            @csrf

            {{-- Judul & Posisi --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Posisi / Jabatan <span class="text-red-500">*</span></label>
                    <input type="text" name="posisi" value="{{ old('posisi') }}" class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition-all" placeholder="Contoh: Full Stack Developer" required>
                    @error('posisi')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                    <select name="category" class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition-all" required>
                        <option value="">Pilih Kategori</option>
                        @foreach(['Teknologi & IT', 'Pendidikan', 'Marketing & Sales', 'Keuangan & Akuntansi', 'Administrasi & SDM', 'Kesehatan', 'Teknik & Engineering', 'Media & Kreatif', 'Lainnya'] as $cat)
                            <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                    @error('category')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Deskripsi & Kualifikasi --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi Pekerjaan <span class="text-red-500">*</span></label>
                    <textarea name="deskripsi" rows="5" class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition-all" placeholder="Jelaskan tugas dan tanggung jawab..." required>{{ old('deskripsi') }}</textarea>
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

            {{-- Benefit --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Benefit</label>
                <textarea name="benefit" rows="3" class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition-all" placeholder="Benefit yang didapatkan...">{{ old('benefit') }}</textarea>
                @error('benefit')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Pendidikan, Tipe, Lokasi --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Pendidikan <span class="text-red-500">*</span></label>
                    <select name="pendidikan" class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition-all" required>
                        <option value="">Pilih Pendidikan</option>
                        @foreach(['SMA/SMK', 'D1', 'D3', 'S1', 'S2', 'S3'] as $pend)
                            <option value="{{ $pend }}" {{ old('pendidikan') == $pend ? 'selected' : '' }}>{{ $pend }}</option>
                        @endforeach
                    </select>
                    @error('pendidikan')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tipe Pekerjaan <span class="text-red-500">*</span></label>
                    <select name="tipe_pekerjaan" class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition-all" required>
                        <option value="">Pilih Tipe</option>
                        <option value="full_time" {{ old('tipe_pekerjaan') == 'full_time' ? 'selected' : '' }}>Full Time</option>
                        <option value="part_time" {{ old('tipe_pekerjaan') == 'part_time' ? 'selected' : '' }}>Part Time</option>
                        <option value="kontrak" {{ old('tipe_pekerjaan') == 'kontrak' ? 'selected' : '' }}>Kontrak</option>
                        <option value="magang" {{ old('tipe_pekerjaan') == 'magang' ? 'selected' : '' }}>Magang</option>
                    </select>
                    @error('tipe_pekerjaan')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Lokasi <span class="text-red-500">*</span></label>
                    <input type="text" name="lokasi" value="{{ old('lokasi') }}" class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition-all" placeholder="Contoh: Surabaya" required>
                    @error('lokasi')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Gaji --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Gaji Minimum (Rp)</label>
                    <input type="number" name="gaji_min" value="{{ old('gaji_min') }}" class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition-all" placeholder="3000000">
                    @error('gaji_min')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Gaji Maksimum (Rp)</label>
                    <input type="number" name="gaji_max" value="{{ old('gaji_max') }}" class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition-all" placeholder="5000000">
                    @error('gaji_max')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Kuota & Thumbnail --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kuota</label>
                    <input type="number" name="kuota" value="{{ old('kuota') }}" class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition-all" placeholder="Kosongkan jika tidak terbatas">
                    @error('kuota')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Thumbnail</label>
                    <input type="file" name="thumbnail" accept="image/*" class="w-full text-sm p-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 transition-all">
                    <p class="text-xs text-gray-500 mt-1">JPG/PNG/WEBP, maks. 2MB</p>
                    @error('thumbnail')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Tanggal --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Mulai <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', date('Y-m-d')) }}" class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition-all" required>
                    @error('tanggal_mulai')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Berakhir <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_berakhir" value="{{ old('tanggal_berakhir') }}" class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition-all" required>
                    @error('tanggal_berakhir')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex justify-end gap-4 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.lowongan.index') }}" class="px-6 py-3 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-100 text-sm font-semibold transition-all">
                    Batal
                </a>
                <button type="submit" class="px-8 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm transition-all shadow-lg shadow-indigo-200 hover:-translate-y-0.5">
                    💾 Simpan & Publikasikan Lowongan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
