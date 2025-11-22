<!-- resources/views/profile/index.blade.php -->
@extends('layouts.app')

@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">
    <!-- Profile Header - Enhanced with modern gradient and better mobile layout -->
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 shadow-xl">
        <!-- Decorative background patterns -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute -right-20 -top-20 w-64 h-64 bg-white rounded-full blur-3xl"></div>
            <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-white rounded-full blur-3xl"></div>
        </div>
        
        <div class="relative p-6 sm:p-8">
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
                <!-- Avatar Section -->
                <div class="relative group">
                    @if(auth()->user()->avatar)
                    <img src="{{ Storage::url(auth()->user()->avatar) }}" 
                         alt="Avatar" 
                         class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl border-4 border-white/30 shadow-2xl object-cover backdrop-blur-sm">
                    @else
                    <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center border-4 border-white/30 shadow-2xl">
                        <span class="text-white text-4xl sm:text-5xl font-bold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                    </div>
                    @endif
                    
                    
                    
                    <form id="avatarForm" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="hidden">
                        @csrf
                        @method('PUT')
                        <input type="file" id="avatarInput" name="avatar" accept="image/*" onchange="document.getElementById('avatarForm').submit()">
                    </form>
                </div>
                
                <!-- User Info -->
                <div class="flex-1 min-w-0">
                    <h1 class="text-2xl sm:text-3xl font-bold text-white mb-2 truncate">{{ auth()->user()->name }}</h1>
                    <p class="text-blue-100 mb-3 text-sm sm:text-base break-all">{{ auth()->user()->email }}</p>
                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold bg-white/20 backdrop-blur-md text-white border border-white/30">
                        <span class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></span>
                        {{ ucfirst(auth()->user()->role) }}
                    </span>
                    
                    @if(auth()->user()->isSiswa())
                    @php $siswa = auth()->user()->siswa; @endphp
                        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold bg-white/20 backdrop-blur-md text-white border border-white/30">
                            <span class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></span>
                            Mahasiswa : <p class="font-semibold text-green-300">{{ ucfirst($siswa->status) }}</p>
                        </span>
                    @endif
                </div>
                
                <!-- Edit Button -->
                <a href="{{ route('profile.edit') }}" 
                   class="w-full sm:w-auto btn bg-white text-blue-600 hover:bg-blue-50 hover:scale-105 rounded-3xl flex items-center justify-center gap-2 p-2 transition-transform shadow-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Profil
                </a>
            </div>
        </div>
    </div>

    @if(auth()->user()->isSiswa())
        <!-- Siswa Profile -->
        @php $siswa = auth()->user()->siswa; @endphp
        
        <!-- Statistics Cards - Mobile Optimized -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="card hover:shadow-lg transition-shadow duration-300 bg-gradient-to-br from-blue-50 to-white border-blue-100">
                <div class="card-body text-center">
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-lg transform hover:rotate-6 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <p class="text-3xl font-bold text-gray-900 mb-1">{{ $siswa->pkl()->count() }}</p>
                    <p class="text-sm text-gray-600 font-medium">Total PKL</p>
                </div>
            </div>
            
            <div class="card hover:shadow-lg transition-shadow duration-300 bg-gradient-to-br from-green-50 to-white border-green-100">
                <div class="card-body text-center">
                    <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-lg transform hover:rotate-6 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <p class="text-3xl font-bold text-gray-900 mb-1">{{ $siswa->lamaran()->count() }}</p>
                    <p class="text-sm text-gray-600 font-medium">Lamaran</p>
                </div>
            </div>
            
            <div class="card hover:shadow-lg transition-shadow duration-300 bg-gradient-to-br from-purple-50 to-white border-purple-100">
                <div class="card-body text-center">
                    <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-lg transform hover:rotate-6 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <p class="text-3xl font-bold text-gray-900 mb-1">{{ $siswa->pelatihan()->count() }}</p>
                    <p class="text-sm text-gray-600 font-medium">Pelatihan</p>
                </div>
            </div>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Data Pribadi & Akademik -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Data Pribadi -->
                <div class="card hover:shadow-lg transition-shadow duration-300">
                    <div class="card-header bg-gradient-to-r from-gray-50 to-white">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center">
                            <span class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </span>
                            Data Pribadi
                        </h3>
                    </div>
                    <div class="card-body space-y-4">
                        <div class="pb-3 border-b border-gray-100">
                            <p class="text-xs uppercase tracking-wide text-gray-500 mb-1 font-semibold">NIM</p>
                            <p class="font-bold text-gray-900 text-lg">{{ $siswa->nim }}</p>
                        </div>
                        
                        <div class="pb-3 border-b border-gray-100">
                            <p class="text-xs uppercase tracking-wide text-gray-500 mb-1 font-semibold">Tempat, Tanggal Lahir</p>
                            <p class="font-semibold text-gray-900">
                                {{ $siswa->tempat_lahir ?? '-' }}, 
                                {{ $siswa->tanggal_lahir ? $siswa->tanggal_lahir->format('d F Y') : '-' }}
                            </p>
                        </div>
                        
                        <div class="pb-3 border-b border-gray-100">
                            <p class="text-xs uppercase tracking-wide text-gray-500 mb-1 font-semibold">Jenis Kelamin</p>
                            <p class="font-semibold text-gray-900">{{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                        </div>
                        
                        @if($siswa->agama)
                        <div class="pb-3 border-b border-gray-100">
                            <p class="text-xs uppercase tracking-wide text-gray-500 mb-1 font-semibold">Agama</p>
                            <p class="font-semibold text-gray-900">{{ $siswa->agama }}</p>
                        </div>
                        @endif
                        
                        @if($siswa->no_telp)
                        <div class="pb-3 border-b border-gray-100">
                            <p class="text-xs uppercase tracking-wide text-gray-500 mb-1 font-semibold">No. Telepon</p>
                            <p class="font-semibold text-gray-900">{{ $siswa->no_telp }}</p>
                        </div>
                        @endif
                        
                        @if($siswa->alamat)
                        <div>
                            <p class="text-xs uppercase tracking-wide text-gray-500 mb-1 font-semibold">Alamat</p>
                            <p class="font-semibold text-gray-900 leading-relaxed">{{ $siswa->alamat }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Data Akademik -->
                <div class="card hover:shadow-lg transition-shadow duration-300">
                    <div class="card-header bg-gradient-to-r from-blue-50 to-white">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center">
                            <span class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </span>
                            Data Akademik
                        </h3>
                    </div>
                    <div class="card-body space-y-4">
                        @if($siswa->fakultas)
                        <div class="pb-3 border-b border-gray-100">
                            <p class="text-xs uppercase tracking-wide text-gray-500 mb-1 font-semibold">Fakultas</p>
                            <p class="font-semibold text-gray-900">{{ $siswa->fakultas->nama ?? '-' }}</p>
                        </div>
                        @endif
                        
                        @if($siswa->program_studi)
                        <div class="pb-3 border-b border-gray-100">
                            <p class="text-xs uppercase tracking-wide text-gray-500 mb-1 font-semibold">Program Studi</p>
                            <p class="font-semibold text-gray-900">{{ $siswa->programStudi->nama ?? '-' }}</p>
                        </div>
                        @endif
                        
                        @if($siswa->tahun_masuk)
                        <div class="pb-3 border-b border-gray-100">
                            <p class="text-xs uppercase tracking-wide text-gray-500 mb-1 font-semibold">Tahun Masuk</p>
                            <p class="font-semibold text-gray-900">{{ $siswa->tahun_masuk }}</p>
                        </div>
                        @endif
                        
                        
                    </div>
                </div>

                <!-- Data Orang Tua -->
                @if($siswa->nama_ortu || $siswa->no_telp_ortu)
                <div class="card hover:shadow-lg transition-shadow duration-300">
                    <div class="card-header bg-gradient-to-r from-purple-50 to-white">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center">
                            <span class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </span>
                            Data Orang Tua
                        </h3>
                    </div>
                    <div class="card-body space-y-4">
                        @if($siswa->nama_ortu)
                        <div class="pb-3 border-b border-gray-100">
                            <p class="text-xs uppercase tracking-wide text-gray-500 mb-1 font-semibold">Nama</p>
                            <p class="font-semibold text-gray-900">{{ $siswa->nama_ortu }}</p>
                        </div>
                        @endif
                        
                        @if($siswa->pekerjaan_ortu)
                        <div class="pb-3 border-b border-gray-100">
                            <p class="text-xs uppercase tracking-wide text-gray-500 mb-1 font-semibold">Pekerjaan</p>
                            <p class="font-semibold text-gray-900">{{ $siswa->pekerjaan_ortu }}</p>
                        </div>
                        @endif
                        
                        @if($siswa->no_telp_ortu)
                        <div>
                            <p class="text-xs uppercase tracking-wide text-gray-500 mb-1 font-semibold">No. Telepon</p>
                            <p class="font-semibold text-gray-900">{{ $siswa->no_telp_ortu }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <!-- Aktivitas Section -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Riwayat PKL -->
                <div class="card hover:shadow-lg transition-shadow duration-300">
                    <div class="card-header bg-gradient-to-r from-blue-50 to-white">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-bold text-gray-900 flex items-center">
                                <span class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </span>
                                Riwayat PKL
                            </h3>
                            <a href="{{ route('siswa.pkl.index') }}" 
                               class="text-sm text-blue-600 hover:text-blue-700 font-semibold flex items-center group">
                                Lihat Semua 
                                <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div class="card-body divide-y divide-gray-100">
                        @forelse($siswa->pkl()->latest()->take(3)->get() as $pkl)
                        <div class="py-4 first:pt-0 last:pb-0 hover:bg-gray-50 -mx-6 px-6 transition-colors">
                            <div class="flex items-start space-x-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-md">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-bold text-gray-900 mb-1">{{ $pkl->perusahaan->nama_perusahaan }}</p>
                                    <p class="text-sm text-gray-600 mb-2">{{ $pkl->posisi ?? 'Peserta PKL' }}</p>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="inline-flex items-center text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            {{ $pkl->tanggal_mulai->format('d M Y') }} - {{ $pkl->tanggal_selesai->format('d M Y') }}
                                        </span>
                                        <span class="badge badge-{{ $pkl->status == 'selesai' ? 'success' : 'info' }} text-xs">
                                            {{ ucfirst($pkl->status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <p class="text-gray-500 font-medium">Belum ada riwayat PKL</p>
                            <p class="text-sm text-gray-400 mt-1">Mulai terapkan untuk mendapatkan pengalaman kerja</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Pelatihan -->
                <div class="card hover:shadow-lg transition-shadow duration-300">
                    <div class="card-header bg-gradient-to-r from-green-50 to-white">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-bold text-gray-900 flex items-center">
                                <span class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                </span>
                                Pelatihan yang Diikuti
                            </h3>
                            <a href="{{ route('siswa.pelatihan.index') }}" 
                               class="text-sm text-green-600 hover:text-green-700 font-semibold flex items-center group">
                                Lihat Semua 
                                <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div class="card-body divide-y divide-gray-100">
                        @forelse($siswa->pelatihan()->latest()->take(3)->get() as $pelatihan)
                        <div class="py-4 first:pt-0 last:pb-0 hover:bg-gray-50 -mx-6 px-6 transition-colors">
                            <div class="flex items-start space-x-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-md">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-bold text-gray-900 mb-1">{{ $pelatihan->judul }}</p>
                                    <p class="text-sm text-gray-600 mb-2">{{ ucfirst(str_replace('_', ' ', $pelatihan->jenis)) }}</p>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="inline-flex items-center text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            {{ $pelatihan->tanggal_mulai->format('d M Y') }}
                                        </span>
                                        @if($pelatihan->pivot->nilai)
                                        <span class="inline-flex items-center text-xs font-bold text-green-700 bg-green-100 px-3 py-1 rounded-full">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Nilai: {{ $pelatihan->pivot->nilai }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                            <p class="text-gray-500 font-medium">Belum mengikuti pelatihan</p>
                            <p class="text-sm text-gray-400 mt-1">Tingkatkan skill dengan mengikuti pelatihan</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

    @elseif(auth()->user()->isPerusahaan())
        <!-- Perusahaan Profile -->
        @php $perusahaan = auth()->user()->perusahaan; @endphp
        
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="card hover:shadow-lg transition-shadow duration-300 bg-gradient-to-br from-blue-50 to-white border-blue-100">
                <div class="card-body text-center">
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-lg transform hover:rotate-6 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <p class="text-3xl font-bold text-gray-900 mb-1">{{ $perusahaan->lowonganKerja()->where('status', 'aktif')->count() }}</p>
                    <p class="text-sm text-gray-600 font-medium">Lowongan Aktif</p>
                </div>
            </div>
            
            <div class="card hover:shadow-lg transition-shadow duration-300 bg-gradient-to-br from-green-50 to-white border-green-100">
                <div class="card-body text-center">
                    <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-lg transform hover:rotate-6 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <p class="text-3xl font-bold text-gray-900 mb-1">{{ $perusahaan->pkl()->where('status', 'berlangsung')->count() }}</p>
                    <p class="text-sm text-gray-600 font-medium">Siswa PKL</p>
                </div>
            </div>
            
            <div class="card hover:shadow-lg transition-shadow duration-300 bg-gradient-to-br from-purple-50 to-white border-purple-100">
                <div class="card-body text-center">
                    <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-lg transform hover:rotate-6 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <p class="text-3xl font-bold text-gray-900 mb-1">{{ $perusahaan->kerjasamaIndustri()->count() }}</p>
                    <p class="text-sm text-gray-600 font-medium">Kerjasama</p>
                </div>
            </div>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Info Perusahaan -->
            <div class="lg:col-span-1">
                <div class="card hover:shadow-lg transition-shadow duration-300">
                    <div class="card-header bg-gradient-to-r from-gray-50 to-white">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center">
                            <span class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </span>
                            Informasi Perusahaan
                        </h3>
                    </div>
                    <div class="card-body space-y-4">
                        @if($perusahaan->bidang_usaha)
                        <div class="pb-3 border-b border-gray-100">
                            <p class="text-xs uppercase tracking-wide text-gray-500 mb-1 font-semibold">Bidang Usaha</p>
                            <p class="font-semibold text-gray-900">{{ $perusahaan->bidang_usaha }}</p>
                        </div>
                        @endif
                        
                        @if($perusahaan->alamat)
                        <div class="pb-3 border-b border-gray-100">
                            <p class="text-xs uppercase tracking-wide text-gray-500 mb-1 font-semibold">Alamat</p>
                            <p class="font-semibold text-gray-900 leading-relaxed">{{ $perusahaan->alamat }}</p>
                        </div>
                        @endif
                        
                        @if($perusahaan->kota)
                        <div class="pb-3 border-b border-gray-100">
                            <p class="text-xs uppercase tracking-wide text-gray-500 mb-1 font-semibold">Kota</p>
                            <p class="font-semibold text-gray-900">{{ $perusahaan->kota }}, {{ $perusahaan->provinsi }}</p>
                        </div>
                        @endif
                        
                        @if($perusahaan->no_telp)
                        <div class="pb-3 border-b border-gray-100">
                            <p class="text-xs uppercase tracking-wide text-gray-500 mb-1 font-semibold">No. Telepon</p>
                            <p class="font-semibold text-gray-900">{{ $perusahaan->no_telp }}</p>
                        </div>
                        @endif
                        
                        @if($perusahaan->website)
                        <div class="pb-3 border-b border-gray-100">
                            <p class="text-xs uppercase tracking-wide text-gray-500 mb-1 font-semibold">Website</p>
                            <a href="{{ $perusahaan->website }}" target="_blank" 
                               class="text-blue-600 hover:text-blue-700 font-semibold flex items-center group">
                                {{ $perusahaan->website }}
                                <svg class="w-4 h-4 ml-1 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                            </a>
                        </div>
                        @endif
                        
                        <div>
                            <p class="text-xs uppercase tracking-wide text-gray-500 mb-2 font-semibold">Status Kerjasama</p>
                            <span class="badge badge-{{ $perusahaan->status_kerjasama == 'aktif' ? 'success' : ($perusahaan->status_kerjasama == 'pending' ? 'warning' : 'gray') }} text-sm px-4 py-2">
                                {{ ucfirst($perusahaan->status_kerjasama) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Section -->
            <div class="lg:col-span-2 space-y-6">
                @if($perusahaan->deskripsi)
                <div class="card hover:shadow-lg transition-shadow duration-300">
                    <div class="card-header bg-gradient-to-r from-blue-50 to-white">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center">
                            <span class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </span>
                            Tentang Perusahaan
                        </h3>
                    </div>
                    <div class="card-body">
                        <p class="text-gray-700 whitespace-pre-line leading-relaxed">{{ $perusahaan->deskripsi }}</p>
                    </div>
                </div>
                @endif

                @if($perusahaan->nama_pic || $perusahaan->no_telp_pic)
                <div class="card hover:shadow-lg transition-shadow duration-300">
                    <div class="card-header bg-gradient-to-r from-purple-50 to-white">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center">
                            <span class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </span>
                            Person in Charge (PIC)
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            @if($perusahaan->nama_pic)
                            <div class="pb-3 border-b border-gray-100 sm:border-0">
                                <p class="text-xs uppercase tracking-wide text-gray-500 mb-1 font-semibold">Nama</p>
                                <p class="font-semibold text-gray-900">{{ $perusahaan->nama_pic }}</p>
                            </div>
                            @endif
                            
                            @if($perusahaan->jabatan_pic)
                            <div class="pb-3 border-b border-gray-100 sm:border-0">
                                <p class="text-xs uppercase tracking-wide text-gray-500 mb-1 font-semibold">Jabatan</p>
                                <p class="font-semibold text-gray-900">{{ $perusahaan->jabatan_pic }}</p>
                            </div>
                            @endif
                            
                            @if($perusahaan->no_telp_pic)
                            <div class="pb-3 border-b border-gray-100 sm:border-0">
                                <p class="text-xs uppercase tracking-wide text-gray-500 mb-1 font-semibold">No. Telepon</p>
                                <p class="font-semibold text-gray-900">{{ $perusahaan->no_telp_pic }}</p>
                            </div>
                            @endif
                            
                            @if($perusahaan->email_pic)
                            <div>
                                <p class="text-xs uppercase tracking-wide text-gray-500 mb-1 font-semibold">Email</p>
                                <p class="font-semibold text-gray-900 break-all">{{ $perusahaan->email_pic }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

    @else
        <!-- Admin Profile -->
        <div class="card hover:shadow-lg transition-shadow duration-300">
            <div class="card-header bg-gradient-to-r from-gray-50 to-white">
                <h3 class="text-lg font-bold text-gray-900 flex items-center">
                    <span class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </span>
                    Informasi Akun
                </h3>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="pb-4 border-b border-gray-100">
                        <p class="text-xs uppercase tracking-wide text-gray-500 mb-2 font-semibold">Role</p>
                        <span class="badge badge-primary text-sm px-4 py-2">Administrator</span>
                    </div>
                    
                    <div class="pb-4 border-b border-gray-100">
                        <p class="text-xs uppercase tracking-wide text-gray-500 mb-2 font-semibold">Status</p>
                        <span class="badge badge-success text-sm px-4 py-2">Aktif</span>
                    </div>
                    
                    <div class="pb-4 border-b border-gray-100 sm:border-0">
                        <p class="text-xs uppercase tracking-wide text-gray-500 mb-1 font-semibold">Member Since</p>
                        <p class="font-semibold text-gray-900">{{ auth()->user()->created_at->format('d F Y') }}</p>
                    </div>
                    
                    <div>
                        <p class="text-xs uppercase tracking-wide text-gray-500 mb-1 font-semibold">Last Login</p>
                        <p class="font-semibold text-gray-900">{{ auth()->user()->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

 

    <!-- Security Info -->
    <div class="card bg-gradient-to-br from-yellow-50 to-amber-50 border-yellow-200 hover:shadow-lg transition-shadow duration-300">
        <div class="card-body">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h4 class="font-bold text-yellow-900 mb-2 text-base">Keamanan Akun</h4>
                    <p class="text-sm text-yellow-800 leading-relaxed">
                        Jangan bagikan password Anda kepada siapapun. Gunakan password yang kuat dan unik untuk keamanan akun Anda.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scroll to top button (optional enhancement) -->
<button onclick="window.scrollTo({top: 0, behavior: 'smooth'})" 
        class="fixed bottom-6 right-6 w-12 h-12 bg-blue-600 text-white rounded-full shadow-lg hover:bg-blue-700 hover:scale-110 transition-all duration-200 flex items-center justify-center z-50 opacity-0 pointer-events-none"
        id="scrollTopBtn">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
    </svg>
</button>

<script>
// Show/hide scroll to top button
window.addEventListener('scroll', function() {
    const scrollBtn = document.getElementById('scrollTopBtn');
    if (window.pageYOffset > 300) {
        scrollBtn.classList.remove('opacity-0', 'pointer-events-none');
    } else {
        scrollBtn.classList.add('opacity-0', 'pointer-events-none');
    }
});

// Auto-hide success messages
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });
});
</script>
@endsection