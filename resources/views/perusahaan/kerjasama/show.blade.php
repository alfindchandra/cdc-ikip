@extends('layouts.app')

@section('title', 'Detail Kerjasama')
@section('page-title', 'Detail Kerjasama Industri')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Back Button -->
    <a href="{{ route('perusahaan.kerjasama.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali
    </a>

    <!-- Header -->
    <div class="card">
        <div class="card-body">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <div class="flex items-center space-x-3 mb-2">
                        <span class="badge badge-primary">
                            {{ ucfirst($kerjasama->jenis_kerjasama) }}
                        </span>
                        <span class="badge 
                            @if($kerjasama->status == 'aktif') badge-success
                            @elseif($kerjasama->status == 'proposal' || $kerjasama->status == 'negosiasi') badge-warning
                            @elseif($kerjasama->status == 'selesai') badge-info
                            @else badge-gray
                            @endif">
                            {{ ucfirst($kerjasama->status) }}
                        </span>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $kerjasama->judul }}</h1>
                    <p class="text-gray-600">{{ $kerjasama->perusahaan->nama_perusahaan }}</p>
                </div>
            </div>

            @if($kerjasama->deskripsi)
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Deskripsi</h3>
                <p class="text-gray-700 whitespace-pre-line">{{ $kerjasama->deskripsi }}</p>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Informasi Kerjasama</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-600">Tanggal Mulai</p>
                            <p class="font-semibold text-gray-900">{{ $kerjasama->tanggal_mulai->format('d F Y') }}</p>
                        </div>
                        @if($kerjasama->tanggal_berakhir)
                        <div>
                            <p class="text-sm text-gray-600">Tanggal Berakhir</p>
                            <p class="font-semibold text-gray-900">{{ $kerjasama->tanggal_berakhir->format('d F Y') }}</p>
                        </div>
                        @endif
                        @if($kerjasama->nilai_kontrak)
                        <div>
                            <p class="text-sm text-gray-600">Nilai Kontrak</p>
                            <p class="font-semibold text-gray-900">Rp {{ number_format($kerjasama->nilai_kontrak, 0, ',', '.') }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Penanggung Jawab</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-600">PIC Sekolah</p>
                            <p class="font-semibold text-gray-900">{{ $kerjasama->pic_sekolah ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">PIC Industri</p>
                            <p class="font-semibold text-gray-900">{{ $kerjasama->pic_industri ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            @if($kerjasama->catatan)
            <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <p class="text-sm font-medium text-yellow-900 mb-2">Catatan:</p>
                <p class="text-yellow-800">{{ $kerjasama->catatan }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Dokumen MoU -->
    @if($kerjasama->dokumen_mou)
    <div class="card">
        <div class="card-header">
            <h3 class="text-lg font-semibold text-gray-900">Dokumen MoU</h3>
        </div>
        <div class="card-body">
            <a href="{{ Storage::url($kerjasama->dokumen_mou) }}" target="_blank" class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">Memorandum of Understanding</p>
                        <p class="text-sm text-gray-500">PDF Document</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-blue-600 font-medium">Download</span>
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                </div>
            </a>
        </div>
    </div>
    @endif


</div>
@endsection>
