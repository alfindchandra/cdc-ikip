@extends('layouts.app')

@section('title', 'Edit Kerjasama')
@section('page-title', 'Edit Kerjasama Industri')

@section('content')
<div class="max-w-5xl mx-auto pb-12">
    <!-- Breadcrumb / Back Navigation -->
    <div class="mb-6">
        <a href="{{ route('admin.kerjasama.show', $kerjasama->id) }}" class="flex items-center text-sm font-medium text-gray-500 hover:text-indigo-600 transition-colors">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Detail
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <!-- Header with Gradient Background -->
        <div class="px-8 py-6 bg-gradient-to-r from-slate-900 to-slate-800">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="p-3 bg-white/10 rounded-xl backdrop-blur-md">
                        <svg class="w-7 h-7 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white">Edit Kerjasama Industri</h3>
                        <p class="text-indigo-200 text-sm">Perbarui detail kontrak dan informasi mitra</p>
                    </div>
                </div>
                <div class="hidden md:block">
                    <span class="px-3 py-1 bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 rounded-full text-xs font-semibold uppercase tracking-wider">
                        ID: #{{ $kerjasama->id }}
                    </span>
                </div>
            </div>
        </div>
        
        <form action="{{ route('admin.kerjasama.update', $kerjasama->id) }}" method="POST" enctype="multipart/form-data" class="p-8">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                
                <!-- Left Column: Primary Info -->
                <div class="lg:col-span-2 space-y-8">
                    
                    <!-- Section: Core Data -->
                    <section>
                        <div class="flex items-center mb-5">
                            <span class="w-1.5 h-6 bg-indigo-600 rounded-full mr-3"></span>
                            <h4 class="text-lg font-bold text-gray-800">Informasi Utama</h4>
                        </div>
                        
                        <div class="space-y-5">
                            <div>
                                <label for="judul" class="block text-sm font-semibold text-gray-700 mb-2">Judul Kerjasama <span class="text-red-500">*</span></label>
                                <input type="text" id="judul" name="judul" value="{{ old('judul', $kerjasama->judul) }}" 
                                    class="w-full px-4 py-3 rounded-xl border-gray-200 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-sm" required>
                                @error('judul')<p class="mt-2 text-xs text-red-500">{{ $message }}</p>@enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label for="perusahaan_id" class="block text-sm font-semibold text-gray-700 mb-2">Perusahaan Mitra</label>
                                    <select id="perusahaan_id" name="perusahaan_id" class="w-full px-4 py-3 rounded-xl border-gray-200 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-sm appearance-none bg-no-repeat bg-[right_1rem_center]" required>
                                        <option value="">-- Pilih Perusahaan --</option>
                                        @foreach($perusahaan as $p)
                                        <option value="{{ $p->id }}" {{ old('perusahaan_id', $kerjasama->perusahaan_id) == $p->id ? 'selected' : '' }}>
                                            {{ $p->nama_perusahaan }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="jenis_kerjasama" class="block text-sm font-semibold text-gray-700 mb-2">Jenis Kerjasama</label>
                                    <select id="jenis_kerjasama" name="jenis_kerjasama" class="w-full px-4 py-3 rounded-xl border-gray-200 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-sm appearance-none" required>
                                        @php $types = ['pkl' => 'PKL', 'rekrutmen' => 'Rekrutmen', 'pelatihan' => 'Pelatihan', 'penelitian' => 'Penelitian', 'sponsorship' => 'Sponsorship', 'lainnya' => 'Lainnya']; @endphp
                                        @foreach($types as $value => $label)
                                            <option value="{{ $value }}" {{ old('jenis_kerjasama', $kerjasama->jenis_kerjasama) == $value ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label for="deskripsi" class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi Kerjasama</label>
                                <textarea id="deskripsi" name="deskripsi" rows="4" 
                                    class="w-full px-4 py-3 rounded-xl border-gray-200 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-sm placeholder:text-gray-400"
                                    placeholder="Jelaskan detail lingkup kerjasama...">{{ old('deskripsi', $kerjasama->deskripsi) }}</textarea>
                            </div>
                        </div>
                    </section>

                    <!-- Section: Dates & Value -->
                    <section>
                        <div class="flex items-center mb-5">
                            <span class="w-1.5 h-6 bg-emerald-500 rounded-full mr-3"></span>
                            <h4 class="text-lg font-bold text-gray-800">Periode & Komitmen</h4>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50 p-6 rounded-2xl border border-gray-100">
                            <div>
                                <label for="tanggal_mulai" class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Mulai</label>
                                <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai', $kerjasama->tanggal_mulai->format('Y-m-d')) }}" 
                                    class="w-full px-4 py-3 rounded-xl border-gray-200 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all shadow-sm">
                            </div>
                            <div>
                                <label for="tanggal_berakhir" class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Berakhir</label>
                                <input type="date" id="tanggal_berakhir" name="tanggal_berakhir" value="{{ old('tanggal_berakhir', $kerjasama->tanggal_berakhir?->format('Y-m-d')) }}" 
                                    class="w-full px-4 py-3 rounded-xl border-gray-200 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all shadow-sm">
                            </div>
                            <div class="md:col-span-2">
                                <label for="nilai_kontrak" class="block text-sm font-semibold text-gray-700 mb-2">Nilai Kontrak (IDR)</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-500 font-medium">Rp</div>
                                    <input type="number" id="nilai_kontrak" name="nilai_kontrak" value="{{ old('nilai_kontrak', $kerjasama->nilai_kontrak) }}" 
                                        class="w-full pl-12 pr-4 py-3 rounded-xl border-gray-200 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-sm font-mono text-lg" 
                                        placeholder="0">
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                <!-- Right Column: Sidebar (Upload & PIC) -->
                <div class="space-y-8">
                    <!-- File Upload Card -->
                    <div class="p-6 bg-indigo-50 rounded-2xl border border-indigo-100">
                        <h4 class="text-sm font-bold text-indigo-900 uppercase tracking-wider mb-4">Dokumen MoU</h4>
                        
                        @if($kerjasama->dokumen_mou)
                        <div class="mb-4 flex items-center p-3 bg-white rounded-xl shadow-sm border border-indigo-200">
                            <div class="p-2 bg-red-100 text-red-600 rounded-lg mr-3">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 012-2h4.586A1 1 0 0111.293 2.707l4 4a1 1 0 01.293.707V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/></svg>
                            </div>
                            <div class="overflow-hidden">
                                <p class="text-xs font-bold text-gray-900 truncate">Dokumen_Lama.pdf</p>
                                <a href="{{ Storage::url($kerjasama->dokumen_mou) }}" target="_blank" class="text-indigo-600 hover:underline text-[10px] font-semibold">LIHAT DOKUMEN</a>
                            </div>
                        </div>
                        @endif

                        <div class="relative group">
                            <input type="file" id="dokumen_mou" name="dokumen_mou" accept=".pdf" class="hidden">
                            <label for="dokumen_mou" class="flex flex-col items-center justify-center w-full h-32 px-4 transition bg-white border-2 border-indigo-300 border-dashed rounded-xl appearance-none cursor-pointer hover:border-indigo-500 hover:bg-indigo-50 focus:outline-none">
                                <span class="flex items-center space-x-2">
                                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                    <span class="font-medium text-gray-600">Ganti Dokumen</span>
                                </span>
                                <span class="mt-1 text-xs text-gray-500">PDF max. 10MB</span>
                            </label>
                        </div>
                        @error('dokumen_mou')<p class="mt-2 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>

                    <!-- Dokumen MoA Card -->
                    <div class="p-6 bg-emerald-50 rounded-2xl border border-emerald-100">
                        <h4 class="text-sm font-bold text-emerald-900 uppercase tracking-wider mb-4">Dokumen MoA</h4>

                        @if($kerjasama->dokumen_moa)
                        <div class="mb-4 flex items-center p-3 bg-white rounded-xl shadow-sm border border-emerald-200">
                            <div class="p-2 bg-red-100 text-red-600 rounded-lg mr-3">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 012-2h4.586A1 1 0 0111.293 2.707l4 4a1 1 0 01.293.707V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/></svg>
                            </div>
                            <div class="overflow-hidden">
                                <p class="text-xs font-bold text-gray-900 truncate">Dokumen_MoA.pdf</p>
                                <a href="{{ Storage::url($kerjasama->dokumen_moa) }}" target="_blank" class="text-emerald-600 hover:underline text-[10px] font-semibold">LIHAT DOKUMEN</a>
                            </div>
                        </div>
                        @endif

                        <div class="relative group">
                            <input type="file" id="dokumen_moa" name="dokumen_moa" accept=".pdf" class="hidden">
                            <label for="dokumen_moa" class="flex flex-col items-center justify-center w-full h-32 px-4 transition bg-white border-2 border-emerald-300 border-dashed rounded-xl appearance-none cursor-pointer hover:border-emerald-500 hover:bg-emerald-50 focus:outline-none">
                                <span class="flex items-center space-x-2">
                                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                    <span class="font-medium text-gray-600">{{ $kerjasama->dokumen_moa ? 'Ganti Dokumen' : 'Upload Dokumen' }}</span>
                                </span>
                                <span class="mt-1 text-xs text-gray-500">PDF max. 10MB</span>
                            </label>
                        </div>
                        @error('dokumen_moa')<p class="mt-2 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>

                    <!-- Dokumen Kontrak Card -->
                    <div class="p-6 bg-amber-50 rounded-2xl border border-amber-100">
                        <h4 class="text-sm font-bold text-amber-900 uppercase tracking-wider mb-4">Dokumen Kontrak</h4>

                        @if($kerjasama->dokumen_kontrak)
                        <div class="mb-4 flex items-center p-3 bg-white rounded-xl shadow-sm border border-amber-200">
                            <div class="p-2 bg-red-100 text-red-600 rounded-lg mr-3">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 012-2h4.586A1 1 0 0111.293 2.707l4 4a1 1 0 01.293.707V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/></svg>
                            </div>
                            <div class="overflow-hidden">
                                <p class="text-xs font-bold text-gray-900 truncate">Dokumen_Kontrak.pdf</p>
                                <a href="{{ Storage::url($kerjasama->dokumen_kontrak) }}" target="_blank" class="text-amber-600 hover:underline text-[10px] font-semibold">LIHAT DOKUMEN</a>
                            </div>
                        </div>
                        @endif

                        <div class="relative group">
                            <input type="file" id="dokumen_kontrak" name="dokumen_kontrak" accept=".pdf" class="hidden">
                            <label for="dokumen_kontrak" class="flex flex-col items-center justify-center w-full h-32 px-4 transition bg-white border-2 border-amber-300 border-dashed rounded-xl appearance-none cursor-pointer hover:border-amber-500 hover:bg-amber-50 focus:outline-none">
                                <span class="flex items-center space-x-2">
                                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                    <span class="font-medium text-gray-600">{{ $kerjasama->dokumen_kontrak ? 'Ganti Dokumen' : 'Upload Dokumen' }}</span>
                                </span>
                                <span class="mt-1 text-xs text-gray-500">PDF max. 10MB</span>
                            </label>
                        </div>
                        @error('dokumen_kontrak')<p class="mt-2 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>

                    <!-- PIC Info -->
                    <div class="p-6 bg-white rounded-2xl border border-gray-200 shadow-sm space-y-5">
                        <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-2">Person In Charge (PIC)</h4>
                        
                        <div>
                            <label for="pic_sekolah" class="block text-xs font-bold text-gray-500 mb-1">PIC SEKOLAH</label>
                            <input type="text" id="pic_sekolah" name="pic_sekolah" value="{{ old('pic_sekolah', $kerjasama->pic_sekolah) }}" 
                                class="w-full px-4 py-2 bg-gray-50 border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500/20 text-sm" placeholder="Nama lengkap">
                        </div>

                        <div>
                            <label for="pic_industri" class="block text-xs font-bold text-gray-500 mb-1">PIC INDUSTRI</label>
                            <input type="text" id="pic_industri" name="pic_industri" value="{{ old('pic_industri', $kerjasama->pic_industri) }}" 
                                class="w-full px-4 py-2 bg-gray-50 border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500/20 text-sm" placeholder="Nama lengkap">
                        </div>
                    </div>

                    <!-- Note Section -->
                    <div class="space-y-2">
                        <label for="catatan" class="block text-sm font-semibold text-gray-700">Catatan Internal</label>
                        <textarea id="catatan" name="catatan" rows="3" class="w-full px-4 py-3 rounded-xl border-gray-200 bg-amber-50/30 text-sm" placeholder="Tambahkan catatan khusus...">{{ old('catatan', $kerjasama->catatan) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Bottom Actions -->
            <div class="mt-12 pt-8 border-t border-gray-100 flex items-center justify-between">
                <p class="text-xs text-gray-400 italic">Terakhir diperbarui: {{ $kerjasama->updated_at->diffForHumans() }}</p>
                <div class="flex space-x-4">
                    <a href="{{ route('admin.kerjasama.show', $kerjasama->id) }}" 
                        class="px-6 py-3 rounded-xl text-sm font-bold text-gray-500 hover:bg-gray-100 transition-all">
                        Batal
                    </a>
                    <button type="submit" 
                        class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-bold shadow-lg shadow-indigo-200 transition-all transform hover:-translate-y-0.5 active:scale-95">
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    /* Custom style untuk select arrow agar lebih modern */
    select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        background-size: 1.5em 1.5em;
        background-position: right 0.5rem center;
        background-repeat: no-repeat;
    }
</style>
@endsection