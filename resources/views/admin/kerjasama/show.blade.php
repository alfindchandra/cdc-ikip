@extends('layouts.app')

@section('title', 'Detail Kerjasama')
@section('page-title', 'Detail Kerjasama Industri')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
    
    <!-- Back Button -->
    <a href="{{ route('admin.kerjasama.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 transition-colors duration-200 font-medium group">
        <svg class="w-5 h-5 mr-2 transition-transform duration-200 group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali ke Daftar Kerjasama
    </a>

    <!-- Main Card -->
    <div class="bg-white shadow-2xl rounded-2xl overflow-hidden">
        
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-indigo-600 to-blue-600 px-8 py-6 text-white">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="flex-1">
                    <div class="flex items-center space-x-3 mb-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-white/20 backdrop-blur-sm">
                            {{ ucfirst(str_replace('_', ' ', $kerjasama->jenis_kerjasama)) }}
                        </span>
                        @php
                            $status_color_map = [
                                'aktif' => 'bg-green-500',
                                'selesai' => 'bg-purple-500',
                                'proposal' => 'bg-yellow-500',
                                'negosiasi' => 'bg-orange-500',
                                'batal' => 'bg-red-500',
                                'draft' => 'bg-gray-500',
                            ];
                            $current_status_color = $status_color_map[$kerjasama->status] ?? 'bg-gray-500';
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $current_status_color }}">
                            {{ ucfirst($kerjasama->status) }}
                        </span>
                    </div>
                    <h1 class="text-3xl font-extrabold mb-2">{{ $kerjasama->judul }}</h1>
                    <p class="text-white/90 text-lg">{{ $kerjasama->perusahaan->nama_perusahaan }}</p>
                </div>
                <div class="flex space-x-3 mt-4 md:mt-0">
                    <a href="{{ route('admin.kerjasama.edit', $kerjasama->id) }}" 
                       class="inline-flex items-center px-4 py-2 bg-white text-indigo-600 font-semibold rounded-lg hover:bg-gray-50 transition duration-150">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit
                    </a>
                    <form action="{{ route('admin.kerjasama.destroy', $kerjasama->id) }}" method="POST" 
                          onsubmit="return confirm('Yakin ingin menghapus kerjasama ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition duration-150">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 p-8">
            
            <!-- Left Column - Main Info -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Deskripsi -->
                @if($kerjasama->deskripsi)
                <section>
                    <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                        <span class="w-8 h-8 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </span>
                        Deskripsi Kerjasama
                    </h2>
                    <div class="prose max-w-none text-gray-700 p-6 rounded-xl bg-gray-50 border border-gray-200">
                        {!! nl2br(e($kerjasama->deskripsi)) !!}
                    </div>
                </section>
                @endif

                <!-- Periode Kerjasama -->
                <section>
                    <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                        <span class="w-8 h-8 bg-green-50 text-green-600 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </span>
                        Periode Kerjasama
                    </h2>
                    <div class="grid grid-cols-2 gap-6 p-6 rounded-xl bg-gray-50 border border-gray-200">
                        <div>
                            <p class="text-sm text-gray-600 font-medium mb-1">Tanggal Mulai</p>
                            <p class="text-lg font-bold text-gray-900">{{ $kerjasama->tanggal_mulai->format('d F Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 font-medium mb-1">Tanggal Berakhir</p>
                            <p class="text-lg font-bold text-gray-900">
                                {{ $kerjasama->tanggal_berakhir ? $kerjasama->tanggal_berakhir->format('d F Y') : 'Tidak Terbatas' }}
                            </p>
                        </div>
                    </div>
                </section>

                <!-- PIC -->
                <section>
                    <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                        <span class="w-8 h-8 bg-purple-50 text-purple-600 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </span>
                        Penanggung Jawab (PIC)
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="p-6 rounded-xl bg-blue-50 border border-blue-200">
                            <p class="text-xs text-blue-600 font-semibold uppercase mb-2">PIC Sekolah</p>
                            <p class="text-lg font-bold text-gray-900">{{ $kerjasama->pic_sekolah ?? '-' }}</p>
                        </div>
                        <div class="p-6 rounded-xl bg-indigo-50 border border-indigo-200">
                            <p class="text-xs text-indigo-600 font-semibold uppercase mb-2">PIC Industri</p>
                            <p class="text-lg font-bold text-gray-900">{{ $kerjasama->pic_industri ?? '-' }}</p>
                        </div>
                    </div>
                </section>

                <!-- Catatan -->
                @if($kerjasama->catatan)
                <section>
                    <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                        <span class="w-8 h-8 bg-yellow-50 text-yellow-600 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                            </svg>
                        </span>
                        Catatan Tambahan
                    </h2>
                    <div class="prose max-w-none text-gray-700 p-6 rounded-xl bg-yellow-50 border border-yellow-200">
                        {!! nl2br(e($kerjasama->catatan)) !!}
                    </div>
                </section>
                @endif

            </div>

            <!-- Right Column - Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                
                <!-- Status Update Card -->
                <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
                    <div class="bg-indigo-50 px-6 py-4 border-b border-indigo-100">
                        <h3 class="text-lg font-bold text-indigo-900">Ubah Status</h3>
                    </div>
                    <div class="p-6">
                        <form action="{{ route('admin.kerjasama.status', $kerjasama->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status Kerjasama</label>
                            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 mb-4">
                                <option value="draft" {{ $kerjasama->status == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="proposal" {{ $kerjasama->status == 'proposal' ? 'selected' : '' }}>Proposal</option>
                                <option value="negosiasi" {{ $kerjasama->status == 'negosiasi' ? 'selected' : '' }}>Negosiasi</option>
                                <option value="aktif" {{ $kerjasama->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="selesai" {{ $kerjasama->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="batal" {{ $kerjasama->status == 'batal' ? 'selected' : '' }}>Batal</option>
                            </select>
                            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 rounded-lg transition duration-150">
                                Perbarui Status
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Detail Info Card -->
                <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-100">
                        <h3 class="text-lg font-bold text-gray-900">Informasi Detail</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        @if($kerjasama->nilai_kontrak)
                        <div class="pb-4 border-b border-gray-100">
                            <p class="text-sm text-gray-600 font-medium mb-1">Nilai Kontrak</p>
                            <p class="text-xl font-extrabold text-green-600">Rp {{ number_format($kerjasama->nilai_kontrak, 0, ',', '.') }}</p>
                        </div>
                        @endif
                        
                        <div class="pb-4 border-b border-gray-100">
                            <p class="text-sm text-gray-600 font-medium mb-1">Tanggal Dibuat</p>
                            <p class="text-md font-semibold text-gray-900">{{ $kerjasama->created_at->format('d F Y H:i') }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-600 font-medium mb-1">Terakhir Diperbarui</p>
                            <p class="text-md font-semibold text-gray-900">{{ $kerjasama->updated_at->format('d F Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Dokumen MoU -->
                @if($kerjasama->dokumen_mou)
                <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
                    <div class="bg-green-50 px-6 py-4 border-b border-green-100">
                        <h3 class="text-lg font-bold text-green-900">Dokumen</h3>
                    </div>
                    <div class="p-6">
                        <a href="{{ Storage::url($kerjasama->dokumen_mou) }}" 
                           target="_blank"
                           class="flex items-center justify-center space-x-2 w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded-lg transition duration-150">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span>Download MoU</span>
                        </a>
                    </div>
                </div>
                @endif

                <!-- Perusahaan Info -->
                <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
                    <div class="bg-blue-50 px-6 py-4 border-b border-blue-100">
                        <h3 class="text-lg font-bold text-blue-900">Perusahaan Mitra</h3>
                    </div>
                    <div class="p-6">
                        <div class="flex items-start space-x-4 mb-4">
                            @if($kerjasama->perusahaan->logo)
                            <img src="{{ Storage::url($kerjasama->perusahaan->logo) }}" 
                                 alt="{{ $kerjasama->perusahaan->nama_perusahaan }}" 
                                 class="w-16 h-16 rounded-lg object-cover border border-gray-200 flex-shrink-0">
                            @else
                            <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center flex-shrink-0">
                                <span class="text-gray-600 font-bold text-2xl">{{ substr($kerjasama->perusahaan->nama_perusahaan, 0, 1) }}</span>
                            </div>
                            @endif
                            <div class="flex-grow">
                                <p class="font-bold text-gray-900 text-lg">{{ $kerjasama->perusahaan->nama_perusahaan }}</p>
                                <p class="text-sm text-gray-600">{{ $kerjasama->perusahaan->bidang_usaha }}</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.perusahaan.show', $kerjasama->perusahaan->id) }}" 
                           class="block text-center w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 rounded-lg transition duration-150">
                            Lihat Detail Perusahaan
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection