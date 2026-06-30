@extends('layouts.app')

@section('title', 'Detail Kerjasama')
@section('page-title', 'Detail Kerjasama Industri')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
    
    <!-- Back Button -->
    <a href="{{ route('perusahaan.kerjasama.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 transition-colors duration-200 font-medium group">
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
                    <p class="text-white/90 text-lg">Program kerjasama dengan {{ config('app.name', 'Sekolah') }}</p>
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
                            <p class="text-xs text-indigo-600 font-semibold uppercase mb-2">PIC Perusahaan</p>
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
                
                <!-- Status Pengajuan Card -->
                <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
                    <div class="bg-indigo-50 px-6 py-4 border-b border-indigo-100">
                        <h3 class="text-lg font-bold text-indigo-900 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Status Pengajuan
                        </h3>
                    </div>
                    <div class="p-6">
                        @if(in_array($kerjasama->status, ['draft', 'proposal', 'negosiasi']))
                            <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <p class="text-sm text-yellow-800">
                                    <strong>Menunggu Persetujuan (ACC) Admin.</strong> Pengajuan kerjasama Anda sedang ditinjau. Anda akan diberi tahu setelah admin menyetujui atau menolak.
                                </p>
                            </div>
                            <form action="{{ route('perusahaan.kerjasama.status', $kerjasama->id) }}" method="POST"
                                  onsubmit="return confirm('Yakin ingin membatalkan pengajuan kerjasama ini?')">
                                @csrf
                                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2.5 rounded-lg transition duration-150 flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Batalkan Pengajuan
                                </button>
                            </form>
                        @elseif($kerjasama->status === 'aktif')
                            <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                                <p class="text-sm text-green-800"><strong>Disetujui (ACC) oleh Admin.</strong> Kerjasama ini sedang aktif berjalan.</p>
                            </div>
                        @elseif($kerjasama->status === 'selesai')
                            <div class="p-4 bg-purple-50 border border-purple-200 rounded-lg">
                                <p class="text-sm text-purple-800"><strong>Kerjasama telah selesai.</strong></p>
                            </div>
                        @elseif($kerjasama->status === 'batal')
                            <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                                <p class="text-sm text-red-800"><strong>Pengajuan dibatalkan/ditolak.</strong></p>
                            </div>
                        @endif
                        <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                            <p class="text-xs text-blue-800">
                                <strong>Catatan:</strong> Persetujuan (ACC) status kerjasama dilakukan oleh admin sekolah, bukan oleh perusahaan.
                            </p>
                        </div>
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
                        <h3 class="text-lg font-bold text-green-900">Dokumen MoU</h3>
                    </div>
                    <div class="p-6">
                        <a href="{{ Storage::url($kerjasama->dokumen_mou) }}" 
                           target="_blank"
                           class="flex items-center justify-center space-x-2 w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded-lg transition duration-150">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span>Download MoU/Dokumen</span>
                        </a>
                    </div>
                </div>
                @endif

                <!-- Dokumen MoA -->
                @if($kerjasama->dokumen_moa)
                <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
                    <div class="bg-emerald-50 px-6 py-4 border-b border-emerald-100">
                        <h3 class="text-lg font-bold text-emerald-900">Dokumen MoA</h3>
                    </div>
                    <div class="p-6">
                        <a href="{{ Storage::url($kerjasama->dokumen_moa) }}" 
                           target="_blank"
                           class="flex items-center justify-center space-x-2 w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3 rounded-lg transition duration-150">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span>Download MoA</span>
                        </a>
                    </div>
                </div>
                @endif

                <!-- Dokumen Kontrak -->
                @if($kerjasama->dokumen_kontrak)
                <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
                    <div class="bg-amber-50 px-6 py-4 border-b border-amber-100">
                        <h3 class="text-lg font-bold text-amber-900">Dokumen Kontrak</h3>
                    </div>
                    <div class="p-6">
                        <a href="{{ Storage::url($kerjasama->dokumen_kontrak) }}" 
                           target="_blank"
                           class="flex items-center justify-center space-x-2 w-full bg-amber-600 hover:bg-amber-700 text-white font-semibold py-3 rounded-lg transition duration-150">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span>Download Kontrak</span>
                        </a>
                    </div>
                </div>
                @endif

                <!-- Info Box -->
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                    <div class="flex items-start space-x-3">
                        <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="text-sm text-blue-800">
                            <p class="font-bold mb-1">Panduan Status</p>
                            <ul class="list-disc list-inside space-y-1 mt-2">
                                <li><strong>Draft:</strong> Rancangan awal</li>
                                <li><strong>Proposal:</strong> Diajukan ke sekolah</li>
                                <li><strong>Negosiasi:</strong> Dalam pembahasan</li>
                                <li><strong>Aktif:</strong> Sedang berjalan</li>
                                <li><strong>Selesai:</strong> Sudah selesai</li>
                                <li><strong>Batal:</strong> Dibatalkan</li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection