@extends('layouts.app')

@section('title', $lowongan->judul)
@section('page-title', 'Detail Lowongan')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="space-y-6">
        <!-- Back Button -->
        <a href="{{ route('siswa.lowongan.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Daftar Lowongan
        </a>

        <!-- Header Image -->
        @if($lowongan->thumbnail)
        <div class="card overflow-hidden">
            <img src="{{ Storage::url($lowongan->thumbnail) }}" alt="{{ $lowongan->judul }}" class="w-full h-64 object-cover">
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <div class="card">
                    <div class="card-body">
                        <!-- Job Title -->
                        <div class="mb-6">
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $lowongan->judul }}</h1>
                            <p class="text-lg text-gray-600">{{ $lowongan->posisi }}</p>
                        </div>

                        <!-- Job Info -->
                        <div class="flex flex-wrap gap-3 mb-6">
                            <span class="badge badge-primary text-sm px-3 py-1">
                                {{ ucfirst(str_replace('_', ' ', $lowongan->tipe_pekerjaan)) }}
                            </span>
                            <span class="badge badge-info text-sm px-3 py-1">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                {{ $lowongan->lokasi }}
                            </span>
                            @if($lowongan->gaji_min && $lowongan->gaji_max)
                            <span class="badge badge-success text-sm px-3 py-1">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Rp {{ number_format($lowongan->gaji_min/1000000, 1) }}jt - {{ number_format($lowongan->gaji_max/1000000, 1) }}jt
                            </span>
                            @endif
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-6">
                            <h2 class="text-xl font-bold text-gray-900 mb-3">Deskripsi Pekerjaan</h2>
                            <div class="prose max-w-none text-gray-700">
                                {!! nl2br(e($lowongan->deskripsi)) !!}
                            </div>
                        </div>

                        <!-- Kualifikasi -->
                        <div class="mb-6">
                            <h2 class="text-xl font-bold text-gray-900 mb-3">Kualifikasi</h2>
                            <div class="prose max-w-none text-gray-700">
                                {!! nl2br(e($lowongan->kualifikasi)) !!}
                            </div>
                        </div>

                        <!-- Benefit -->
                        @if($lowongan->benefit)
                        <div>
                            <h2 class="text-xl font-bold text-gray-900 mb-3">Benefit</h2>
                            <div class="prose max-w-none text-gray-700">
                                {!! nl2br(e($lowongan->benefit)) !!}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Company Card -->
                <div class="card">
                    <div class="card-body">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Tentang Perusahaan</h3>
                        <div class="flex items-center space-x-3 mb-4">
                            @if($lowongan->perusahaan->logo)
                            <img src="{{ Storage::url($lowongan->perusahaan->logo) }}" alt="{{ $lowongan->perusahaan->nama_perusahaan }}" class="w-16 h-16 rounded-lg object-cover">
                            @else
                            <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                <span class="text-gray-600 font-bold text-xl">{{ substr($lowongan->perusahaan->nama_perusahaan, 0, 1) }}</span>
                            </div>
                            @endif
                            <div>
                                <p class="font-semibold text-gray-900">{{ $lowongan->perusahaan->nama_perusahaan }}</p>
                                <p class="text-sm text-gray-600">{{ $lowongan->perusahaan->bidang_usaha }}</p>
                            </div>
                        </div>
                        
                        @if($lowongan->perusahaan->deskripsi)
                        <p class="text-sm text-gray-700 mb-4">{{ Str::limit($lowongan->perusahaan->deskripsi, 150) }}</p>
                        @endif

                        <div class="space-y-2 text-sm">
                            @if($lowongan->perusahaan->website)
                            <a href="{{ $lowongan->perusahaan->website }}" target="_blank" class="flex items-center text-blue-600 hover:text-blue-700">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                </svg>
                                Website
                            </a>
                            @endif
                            @if($lowongan->perusahaan->email)
                            <div class="flex items-center text-gray-600">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                {{ $lowongan->perusahaan->email }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Job Details -->
                <div class="card">
                    <div class="card-body">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Informasi Lowongan</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-600">Tanggal Posting</p>
                                <p class="font-medium text-gray-900">{{ $lowongan->created_at->format('d F Y') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Batas Lamaran</p>
                                <p class="font-medium text-gray-900">{{ $lowongan->tanggal_berakhir->format('d F Y') }}</p>
                            </div>
                            @if($lowongan->kuota)
                            <div>
                                <p class="text-sm text-gray-600">Kuota</p>
                                <p class="font-medium text-gray-900">{{ $lowongan->kuota }} orang</p>
                            </div>
                            @endif
                            <div>
                                <p class="text-sm text-gray-600">Total Pelamar</p>
                                <p class="font-medium text-gray-900">{{ $lowongan->jumlah_pelamar }} orang</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Apply Button -->
                <div class="card bg-blue-50 border-blue-200">
                    <div class="card-body">
                        @php
                            $sudahMelamar = auth()->user()->siswa->lamaran()->where('lowongan_id', $lowongan->id)->exists();
                        @endphp

                        @if($sudahMelamar)
                        <div class="text-center">
                            <svg class="w-12 h-12 mx-auto mb-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm font-medium text-gray-900 mb-2">Anda sudah melamar</p>
                            <a href="{{ route('siswa.lamaran.index') }}" class="text-sm text-blue-600 hover:text-blue-700">
                                Lihat status lamaran →
                            </a>
                        </div>
                        @elseif($lowongan->tanggal_berakhir < now())
                        <div class="text-center">
                            <svg class="w-12 h-12 mx-auto mb-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm font-medium text-gray-900">Lowongan sudah ditutup</p>
                        </div>
                        @else
                        <button onclick="document.getElementById('modalLamar').classList.remove('hidden')" class="btn btn-primary w-full py-3">
                            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Lamar Sekarang
                        </button>
                        <p class="text-xs text-center text-gray-600 mt-2">
                            Pastikan data profil Anda sudah lengkap
                        </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Lamar -->
@if(!$sudahMelamar && $lowongan->tanggal_berakhir >= now())
<div id="modalLamar" class="modal-overlay hidden" onclick="if(event.target === this) this.classList.add('hidden')">
    <div class="modal-content" onclick="event.stopPropagation()">
        <form action="{{ route('siswa.lamaran.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="lowongan_id" value="{{ $lowongan->id }}">

            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-gray-900">Lamar Pekerjaan</h3>
                    <button type="button" onclick="document.getElementById('modalLamar').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="px-6 py-4 space-y-4">
                <div>
                    <label class="form-label">Upload CV (PDF) *</label>
                    <input type="file" name="cv" accept=".pdf" class="form-input" required>
                    <p class="text-xs text-gray-500 mt-1">Maksimal 5MB</p>
                </div>

                <div>
                    <label class="form-label">Upload Surat Lamaran (PDF)</label>
                    <input type="file" name="surat_lamaran" accept=".pdf" class="form-input">
                    <p class="text-xs text-gray-500 mt-1">Opsional, maksimal 5MB</p>
                </div>

                <div>
                    <label class="form-label">Upload Portofolio (PDF/ZIP)</label>
                    <input type="file" name="portofolio" accept=".pdf,.zip" class="form-input">
                    <p class="text-xs text-gray-500 mt-1">Opsional, maksimal 10MB</p>
                </div>

                <div>
                    <label class="form-label">Catatan Tambahan</label>
                    <textarea name="catatan" rows="3" class="form-textarea" placeholder="Ceritakan mengapa Anda tertarik dengan posisi ini..."></textarea>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                <button type="button" onclick="document.getElementById('modalLamar').classList.add('hidden')" class="btn btn-outline">
                    Batal
                </button>
                <button type="submit" class="btn btn-primary">
                    Kirim Lamaran
                </button>
            </div>
        </form>
    </div>
</div>
@endif
@endsection