@extends('layouts.app')

@section('title', $lowongan->judul)
@section('page-title', '')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="space-y-6">
        <!-- Back Button -->
        <a href="{{ route('mahasiswa.lowongan.index') }}" 
           class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 transition group">
            <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span class="font-medium">Kembali ke Daftar Lowongan</span>
        </a>

        <!-- Header Banner -->
        @if($lowongan->thumbnail)
        <div class="relative rounded-3xl overflow-hidden shadow-lg h-72">
            <img src="{{ Storage::url($lowongan->thumbnail) }}" 
                 alt="{{ $lowongan->judul }}" 
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Job Header Card -->
                <div class="bg-white rounded-3xl shadow-sm p-8 border border-gray-100">
                    <!-- Company Info -->
                    <div class="flex items-center gap-4 mb-2 pb-6">
                        
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">{{ $lowongan->posisi }}</h3>
                            <p class="text-sm text-gray-500">{{ $lowongan->perusahaan->nama_perusahaan }}</p>
                        </div>
                    </div>

                   

                    <!-- Job Meta -->
                    <div class="flex flex-wrap gap-3">
                        <span class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-700 rounded-xl font-medium text-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            {{ ucfirst(str_replace('_', ' ', $lowongan->tipe_pekerjaan)) }}
                        </span>

                        <span class="inline-flex items-center gap-2 px-4 py-2 bg-purple-50 text-purple-700 rounded-xl font-medium text-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $lowongan->lokasi }}
                        </span>

                        @if($lowongan->gaji_min || $lowongan->gaji_max)
                        <span class="inline-flex items-center gap-2 px-4 py-2 bg-green-50 text-green-700 rounded-xl font-medium text-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                             @if($lowongan->gaji_min && $lowongan->gaji_max)
                                    Rp {{ number_format($lowongan->gaji_min / 1000000, 0) }}jt -
                                    {{ number_format($lowongan->gaji_max / 1000000, 0) }}jt
                                @elseif($lowongan->gaji_min)
                                    Min Rp {{ number_format($lowongan->gaji_min / 1000000, 0) }}jt
                                @elseif($lowongan->gaji_max)
                                    Max Rp {{ number_format($lowongan->gaji_max / 1000000, 0) }}jt
                                @endif
                        </span>
                        @endif

                        <span class="inline-flex items-center gap-2 px-4 py-2 bg-orange-50 text-orange-700 rounded-xl font-medium text-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            {{ $lowongan->jumlah_pelamar }} Pelamar
                        </span>
                    </div>
                </div>

                <!-- Job Description -->
                <div class="bg-white rounded-3xl shadow-sm p-8 border border-gray-100">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900">Deskripsi Pekerjaan</h2>
                    </div>
                    <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                        {!! nl2br(e($lowongan->deskripsi)) !!}
                    </div>
                </div>

                <!-- Qualifications -->
                <div class="bg-white rounded-3xl shadow-sm p-8 border border-gray-100">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900">Kualifikasi</h2>
                    </div>
                    <div class="">
                        {!! nl2br(e($lowongan->kualifikasi)) !!}
                    </div>
                </div>

                <!-- Benefits -->
                @if($lowongan->benefit)
                <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-3xl shadow-sm p-8 border border-green-100">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900">Benefit & Fasilitas</h2>
                    </div>
                    <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                        {!! nl2br(e($lowongan->benefit)) !!}
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Apply Button Card -->
                @php
                    $sudahMelamar = auth()->user()->mahasiswa->lamaran()->where('lowongan_id', $lowongan->id)->exists();
                @endphp

                <div class="bg-white rounded-3xl shadow-lg p-6 border-2 border-gray-100 sticky top-20">
                    @if($sudahMelamar)
                        <div class="text-center space-y-4">
                            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto">
                                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-lg font-bold text-gray-900 mb-1">Lamaran Terkirim</p>
                                <p class="text-sm text-gray-600">Anda sudah melamar pekerjaan ini</p>
                            </div>
                            <a href="{{ route('mahasiswa.lamaran.index') }}" 
                               class="block w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-4 px-6 rounded-xl transition shadow-lg shadow-green-600/20">
                                Lihat Status Lamaran →
                            </a>
                        </div>
                    @elseif($lowongan->tanggal_berakhir < now())
                        <div class="text-center space-y-4">
                            <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto">
                                <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-lg font-bold text-gray-900 mb-1">Lowongan Ditutup</p>
                                <p class="text-sm text-gray-600">Pendaftaran sudah berakhir</p>
                            </div>
                        </div>
                    @else
                        <div class="space-y-4">
                            <a href="{{ route('mahasiswa.lowongan.apply', $lowongan->id) }}" 
                               class="block w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold py-5 px-6 rounded-xl transition transform hover:scale-105 shadow-xl shadow-blue-600/30 text-center">
                                <span class="flex items-center justify-center gap-2">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Lamar Sekarang
                                </span>
                            </a>
                            <p class="text-xs text-center text-gray-500">
                                Pastikan data profil Anda sudah lengkap sebelum melamar
                            </p>
                        </div>
                    @endif
                </div>

                <!-- Job Info Card -->
                <div class="bg-white rounded-3xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-5 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Informasi Lowongan
                    </h3>
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 mb-1">Tanggal Posting</p>
                                <p class="font-semibold text-gray-900">{{ $lowongan->created_at->format('d F Y') }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-red-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 mb-1">Batas Lamaran</p>
                                <p class="font-semibold text-gray-900">{{ $lowongan->tanggal_berakhir->format('d F Y') }}</p>
                                <p class="text-xs text-red-600 mt-1">{{ $lowongan->tanggal_berakhir->diffForHumans() }}</p>
                            </div>
                        </div>

                        @if($lowongan->kuota)
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-purple-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 mb-1">Kuota</p>
                                <p class="font-semibold text-gray-900">{{ $lowongan->kuota }} orang</p>
                            </div>
                        </div>
                        @endif

                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-orange-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 mb-1">Total Pelamar</p>
                                <p class="font-semibold text-gray-900">{{ $lowongan->jumlah_pelamar }} orang</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Company Card -->
                <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-3xl shadow-sm p-6 border border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900 mb-5">Tentang Perusahaan</h3>
                    
                    <div class="flex items-center gap-3 mb-4">
                        @if($lowongan->perusahaan->logo)
                            <img src="{{ Storage::url($lowongan->perusahaan->logo) }}" 
                                 alt="{{ $lowongan->perusahaan->nama_perusahaan }}" 
                                 class="w-14 h-14 rounded-xl object-cover ring-2 ring-white">
                        @else
                            <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center ring-2 ring-white">
                                <span class="text-white font-bold text-lg">{{ substr($lowongan->perusahaan->nama_perusahaan, 0, 1) }}</span>
                            </div>
                        @endif
                        <div>
                            <p class="font-bold text-gray-900">{{ $lowongan->perusahaan->nama_perusahaan }}</p>
                            <p class="text-sm text-gray-600">{{ $lowongan->perusahaan->bidang_usaha }}</p>
                        </div>
                    </div>
                    
                    @if($lowongan->perusahaan->deskripsi)
                    <p class="text-sm text-gray-700 mb-4 leading-relaxed">
                        {{ Str::limit($lowongan->perusahaan->deskripsi, 150) }}
                    </p>
                    @endif

                    <div class="space-y-2">
                        @if($lowongan->perusahaan->website)
                        <a href="{{ $lowongan->perusahaan->website }}" 
                           target="_blank" 
                           class="flex items-center gap-2 text-blue-600 hover:text-blue-700 text-sm font-medium group">
                            <svg class="w-4 h-4 transform group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                            </svg>
                            Kunjungi Website
                        </a>
                        @endif

                        @if($lowongan->perusahaan->email)
                        <div class="flex items-center gap-2 text-gray-600 text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            {{ $lowongan->perusahaan->email }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection