@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Mahamahasiswa')

@section('content')
<div class="space-y-6">
    <!-- Welcome Card -->
    <div class="card bg-gradient-to-r from-blue-600 to-blue-800 text-white rounded-3xl">
        <div class="card-body m-3 ">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold mb-2">Selamat Datang, {{ auth()->user()->name }}! 👋</h2>
                    <p class="text-blue-100">
                        @php
                            $hour = date('H');
                            if($hour < 12) echo 'Selamat pagi';
                            elseif($hour < 15) echo 'Selamat siang';
                            elseif($hour < 18) echo 'Selamat sore';
                            else echo 'Selamat malam';
                        @endphp
                        , jangan lupa untuk tetap tersenyum dan cek lowongan terbaru!
                    </p>
                </div>
                <div class="hidden md:block">
                    <svg class="w-24 h-24 text-blue-400 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- PKL Status -->
        

        <!-- Lamaran Pending -->
        <div class="card hover:shadow-lg transition-shadow p-3 rounded-2xl">
            <div class="card-body">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    @if($lamaran_pending > 0)
                    <span class="badge badge-warning">{{ $lamaran_pending }}</span>
                    @endif
                </div>
                <h3 class="text-sm font-medium text-gray-600 mb-1">Lamaran Pending</h3>
                <p class="text-2xl font-bold text-gray-900">{{ $lamaran_pending }}</p>
                <p class="text-xs text-gray-500 mt-1">Menunggu respon</p>
                <a href="{{ route('mahasiswa.lamaran.index') }}" class="text-sm text-blue-600 hover:text-blue-700 mt-2 inline-block">
                    Lihat Lamaran →
                </a>
            </div>
        </div>

        <!-- Pelatihan -->
        <div class="card hover:shadow-lg transition-shadow p-3 rounded-2xl">
            <div class="card-body">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                </div>
                <h3 class="text-sm font-medium text-gray-600 mb-1">Pelatihan Diikuti</h3>
                <p class="text-2xl font-bold text-gray-900">{{ $pelatihan_terdaftar }}</p>
                <p class="text-xs text-gray-500 mt-1">Program terdaftar</p>
                <a href="{{ route('mahasiswa.pelatihan.index') }}" class="text-sm text-blue-600 hover:text-blue-700 mt-2 inline-block">
                    Lihat Pelatihan →
                </a>
            </div>
        </div>

        <!-- Profil Completion -->
        <div class="card hover:shadow-lg transition-shadow p-2 rounded-2xl">
            <div class="card-body">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                </div>
                <h3 class="text-sm font-medium text-gray-600 mb-1">Kelengkapan Profil</h3>
                @php
                    $mahasiswa = auth()->user()->mahasiswa;
                    $fields = ['tempat_lahir', 'tanggal_lahir', 'alamat', 'no_telp', 'nama_ortu'];
                    $filled = 0;
                    foreach($fields as $field) {
                        if(!empty($mahasiswa->$field)) $filled++;
                    }
                    $percentage = round(($filled / count($fields)) * 100);
                @endphp
                <p class="text-2xl font-bold text-gray-900">{{ $percentage }}%</p>
                <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                    <div class="bg-purple-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                </div>
                <a href="{{ route('profile') }}" class="text-sm text-blue-600 hover:text-blue-700 mt-2 inline-block">
                    Lengkapi Profil →
                </a>
            </div>
        </div>
    </div>

   

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Lowongan Terbaru -->
        <div class="card">
            <div class="card-header">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Lowongan Terbaru</h3>
                    <a href="{{ route('mahasiswa.lowongan.index') }}" class="text-sm text-blue-600 hover:text-blue-700">
                        Lihat Semua →
                    </a>
                </div>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($lowongan_terbaru as $lowongan)
                <div class="px-6 py-4 hover:bg-gray-50 transition">
                    <div class="flex items-start space-x-3">
                        @if($lowongan->perusahaan->logo)
                        <img src="{{ Storage::url($lowongan->perusahaan->logo) }}" alt="{{ $lowongan->perusahaan->nama_perusahaan }}" class="w-12 h-12 rounded-lg object-cover flex-shrink-0">
                        @else
                        <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center flex-shrink-0">
                            <span class="text-gray-600 font-semibold">{{ substr($lowongan->perusahaan->nama_perusahaan, 0, 1) }}</span>
                        </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <h4 class="font-semibold text-gray-900 truncate">{{ $lowongan->judul }}</h4>
                            <p class="text-sm text-gray-600 truncate">{{ $lowongan->perusahaan->nama_perusahaan }}</p>
                            <div class="flex items-center space-x-3 mt-2">
                                <span class="badge badge-primary text-xs">
                                    {{ ucfirst(str_replace('_', ' ', $lowongan->tipe_pekerjaan)) }}
                                </span>
                                <span class="text-xs text-gray-500">{{ $lowongan->lokasi }}</span>
                                <span class="text-xs text-gray-500">{{ $lowongan->jumlah_pelamar }} pelamar</span>
                            </div>
                        </div>
                        <a href="{{ route('mahasiswa.lowongan.show', $lowongan->id) }}" class="text-blue-600 hover:text-blue-700 flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>
                @empty
                <div class="px-6 py-12 text-center">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                    <p class="text-sm text-gray-500">Belum ada lowongan tersedia</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Pelatihan Tersedia -->
        <div class="card">
            <div class="card-header">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Pelatihan Tersedia</h3>
                    <a href="{{ route('mahasiswa.pelatihan.index') }}" class="text-sm text-blue-600 hover:text-blue-700">
                        Lihat Semua →
                    </a>
                </div>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($pelatihan_tersedia as $pelatihan)
                <div class="px-6 py-4 hover:bg-gray-50 transition">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-2">
                                <span class="badge badge-primary text-xs">
                                    {{ ucfirst(str_replace('_', ' ', $pelatihan->jenis)) }}
                                </span>
                                @if($pelatihan->biaya == 0)
                                <span class="badge badge-success text-xs">GRATIS</span>
                                @endif
                            </div>
                            <h4 class="font-semibold text-gray-900">{{ $pelatihan->judul }}</h4>
                            <div class="flex items-center space-x-4 mt-2 text-xs text-gray-500">
                                @if($pelatihan->instruktur)
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    {{ $pelatihan->instruktur }}
                                </span>
                                @endif
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    {{ $pelatihan->tanggal_mulai->format('d M Y') }}
                                </span>
                            </div>
                            <div class="mt-2">
                                <div class="flex items-center text-xs text-gray-600">
                                    <span class="font-medium">{{ $pelatihan->jumlah_peserta }}</span>
                                    <span class="mx-1">/</span>
                                    <span>{{ $pelatihan->kuota ?? '∞' }} peserta</span>
                                </div>
                                @if($pelatihan->kuota)
                                <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                                    <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ min(100, ($pelatihan->jumlah_peserta / $pelatihan->kuota) * 100) }}%"></div>
                                </div>
                                @endif
                            </div>
                        </div>
                        <a href="{{ route('mahasiswa.pelatihan.show', $pelatihan->id) }}" class="text-blue-600 hover:text-blue-700 ml-4">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>
                @empty
                <div class="px-6 py-12 text-center">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <p class="text-sm text-gray-500">Belum ada pelatihan tersedia</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="card">
        <div class="card-header">
            <h3 class="text-lg font-semibold text-gray-900">Aksi Cepat</h3>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                <a href="{{ route('mahasiswa.lowongan.index') }}" class="flex flex-col items-center justify-center p-6 border-2 border-dashed border-gray-300 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition group">
                    <svg class="w-10 h-10 text-gray-400 group-hover:text-blue-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-blue-700">Cari Lowongan</span>
                </a>

                

                <a href="{{ route('mahasiswa.pelatihan.index') }}" class="flex flex-col items-center justify-center p-6 border-2 border-dashed border-gray-300 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition group">
                    <svg class="w-10 h-10 text-gray-400 group-hover:text-blue-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-blue-700">Ikuti Pelatihan</span>
                </a>

                <a href="{{ route('profile') }}" class="flex flex-col items-center justify-center p-6 border-2 border-dashed border-gray-300 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition group">
                    <svg class="w-10 h-10 text-gray-400 group-hover:text-blue-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-blue-700">Edit Profil</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Tips & Informasi -->
    <div class="card bg-gradient-to-r from-purple-50 to-pink-50 border-purple-200">
        <div class="card-body">
            <div class="flex items-start space-x-4">
                <div class="w-12 h-12 bg-purple-600 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-gray-900 mb-2">💡 Tips Hari Ini</h3>
                    
                    <div class="flex flex-wrap gap-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-white text-purple-700 border border-purple-200">
                            #KonsistenJurnal
                        </span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-white text-purple-700 border border-purple-200">
                            #PersiapanKarir
                        </span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-white text-purple-700 border border-purple-200">
                            #TerusBelajar
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection