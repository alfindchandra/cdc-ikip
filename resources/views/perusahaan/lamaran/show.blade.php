@extends('layouts.app')

@section('title', 'Detail Lamaran')
@section('page-title', 'Detail Lamaran')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Back Button -->
    <a href="{{ route('perusahaan.lamaran.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Profil Pelamar -->
            <div class="card">
                <div class="card-body">
                    <div class="flex items-start space-x-4 mb-6">
                        <div class="w-20 h-20 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 text-3xl font-bold">
                            {{ substr($lamaran->siswa->user->name, 0, 1) }}
                        </div>
                        <div class="flex-1">
                            <h2 class="text-2xl font-bold text-gray-900 mb-1">{{ $lamaran->siswa->user->name }}</h2>
                            <p class="text-gray-600 mb-2">NIM: {{ $lamaran->siswa->nim }}</p>
                            <div class="flex flex-wrap gap-2">
                                <span class="badge badge-primary">{{ $lamaran->siswa->fakultas }}</span>
                                <span class="badge badge-info">{{ $lamaran->siswa->program_studi }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Email</p>
                            <p class="font-medium text-gray-900">{{ $lamaran->siswa->user->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">No. Telepon</p>
                            <p class="font-medium text-gray-900">{{ $lamaran->siswa->no_telp ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Tanggal Lahir</p>
                            <p class="font-medium text-gray-900">
                                {{ $lamaran->siswa->tanggal_lahir ? $lamaran->siswa->tanggal_lahir->format('d F Y') : '-' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Alamat</p>
                            <p class="font-medium text-gray-900">{{ $lamaran->siswa->alamat ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dokumen Lamaran -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-gray-900">Dokumen Lamaran</h3>
                </div>
                <div class="card-body space-y-3">
                    @if($lamaran->cv)
                    <a href="{{ Storage::url($lamaran->cv) }}" target="_blank" class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Curriculum Vitae</p>
                                <p class="text-sm text-gray-500">PDF</p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                    </a>
                    @endif

                    @if($lamaran->surat_lamaran)
                    <a href="{{ Storage::url($lamaran->surat_lamaran) }}" target="_blank" class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Surat Lamaran</p>
                                <p class="text-sm text-gray-500">PDF</p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                    </a>
                    @endif

                    @if($lamaran->portofolio)
                    <a href="{{ Storage::url($lamaran->portofolio) }}" target="_blank" class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Portofolio</p>
                                <p class="text-sm text-gray-500">ZIP/PDF</p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                    </a>
                    @endif

                    @if(!$lamaran->cv && !$lamaran->surat_lamaran && !$lamaran->portofolio)
                    <p class="text-center text-gray-500 py-8">Tidak ada dokumen dilampirkan</p>
                    @endif
                </div>
            </div>

            <!-- Catatan -->
            @if($lamaran->catatan)
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-gray-900">Catatan Pelamar</h3>
                </div>
                <div class="card-body">
                    <p class="text-gray-700 whitespace-pre-line">{{ $lamaran->catatan }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Info Lowongan -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-gray-900">Info Lowongan</h3>
                </div>
                <div class="card-body space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Posisi</p>
                        <p class="font-semibold text-gray-900">{{ $lamaran->lowongan->judul }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Tanggal Melamar</p>
                        <p class="font-medium text-gray-900">{{ $lamaran->tanggal_melamar->format('d F Y, H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Status Saat Ini</p>
                        <span class="badge 
                            @if($lamaran->status == 'dikirim') badge-warning
                            @elseif($lamaran->status == 'dilihat' || $lamaran->status == 'diproses') badge-info
                            @elseif($lamaran->status == 'diterima') badge-success
                            @else badge-danger
                            @endif">
                            {{ ucfirst($lamaran->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-gray-900">Aksi</h3>
                </div>
                <div class="card-body space-y-3">
                    <form action="{{ route('perusahaan.lamaran.status', $lamaran->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Ubah Status</label>
                            <select name="status" class="form-select">
                                <option value="dilihat" {{ $lamaran->status == 'dilihat' ? 'selected' : '' }}>Dilihat</option>
                                <option value="diproses" {{ $lamaran->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                <option value="diterima" {{ $lamaran->status == 'diterima' ? 'selected' : '' }}>Diterima</option>
                                <option value="ditolak" {{ $lamaran->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Catatan</label>
                            <textarea name="catatan" rows="3" class="form-textarea" placeholder="Berikan catatan...">{{ $lamaran->catatan }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-full">
                            Update Status
                        </button>
                    </form>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="card bg-blue-50 border-blue-200">
                <div class="card-body">
                    <h4 class="font-semibold text-gray-900 mb-3">Hubungi Pelamar</h4>
                    <div class="space-y-2 text-sm">
                        <a href="mailto:{{ $lamaran->siswa->user->email }}" class="flex items-center text-blue-600 hover:text-blue-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            {{ $lamaran->siswa->user->email }}
                        </a>
                        @if($lamaran->siswa->no_telp)
                        <a href="tel:{{ $lamaran->siswa->no_telp }}" class="flex items-center text-blue-600 hover:text-blue-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            {{ $lamaran->siswa->no_telp }}
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
