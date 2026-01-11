@extends('layouts.app')

@section('title', 'Dashboard Perusahaan')
@section('page-title', 'Dashboard Perusahaan')

@section('content')
<div class="space-y-6">
    <!-- Welcome Card -->
    <div class="card bg-gradient-to-r from-purple-600 to-purple-800 text-white p-4 md:p-6 lg:p-8 rounded-3xl">
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold mb-2">{{ auth()->user()->perusahaan->nama_perusahaan }}</h2>
                    <p class="text-purple-100 mb-3">{{ auth()->user()->perusahaan->bidang_usaha ?? 'Perusahaan Mitra' }}</p>
                    <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                            @if(auth()->user()->perusahaan->status_kerjasama == 'aktif') bg-green-500 text-white
                            @elseif(auth()->user()->perusahaan->status_kerjasama == 'pending') bg-yellow-500 text-white
                            @else bg-gray-500 text-white
                            @endif">
                            {{ ucfirst(auth()->user()->perusahaan->status_kerjasama) }}
                        </span>
                    </div>
                </div>
                <div class="hidden md:block">
                    @if(auth()->user()->avatar)
                    <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="Logo" class="w-24 h-24 rounded-lg object-cover border-4 border-white shadow-lg">
                    @else
                    <div class="w-24 h-24 bg-white rounded-lg flex items-center justify-center">
                        <span class="text-purple-600 text-4xl font-bold">{{ substr(auth()->user()->perusahaan->nama_perusahaan, 0, 1) }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Lowongan Aktif -->
        <div class="card hover:shadow-lg transition-shadow p-3 rounded-2xl">
            <div class="card-body">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
                <h3 class="text-sm font-medium text-gray-600 mb-1">Lowongan Aktif</h3>
                <p class="text-2xl font-bold text-gray-900">{{ $lowongan_aktif }}</p>
                <p class="text-xs text-gray-500 mt-1">Sedang dibuka</p>
                <a href="{{ route('perusahaan.lowongan.index') }}" class="text-sm text-blue-600 hover:text-blue-700 mt-2 inline-block">
                    Kelola Lowongan →
                </a>
            </div>
        </div>

        <!-- Total Pelamar -->
        <div class="card hover:shadow-lg transition-shadow p-3 rounded-2xl">
            <div class="card-body">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                </div>
                <h3 class="text-sm font-medium text-gray-600 mb-1">Total Pelamar</h3>
                <p class="text-2xl font-bold text-gray-900">{{ $total_pelamar }}</p>
                <p class="text-xs text-gray-500 mt-1">Semua lowongan</p>
                <a href="{{ route('perusahaan.lamaran.index') }}" class="text-sm text-blue-600 hover:text-blue-700 mt-2 inline-block">
                    Lihat Lamaran →
                </a>
            </div>
        </div>

        <!-- Siswa PKL -->
       
        <!-- Lamaran Baru -->
        <div class="card hover:shadow-lg transition-shadow p-3 rounded-2xl">
            <div class="card-body">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    @if($lowongan_list->sum(function($l) { return $l->lamaran->where('status', 'dikirim')->count(); }) > 0)
                    <span class="badge badge-danger">Baru</span>
                    @endif
                </div>
                <h3 class="text-sm font-medium text-gray-600 mb-1">Lamaran Baru</h3>
                <p class="text-2xl font-bold text-gray-900">
                    {{ $lowongan_list->sum(function($l) { return $l->lamaran->where('status', 'dikirim')->count(); }) }}
                </p>
                <p class="text-xs text-gray-500 mt-1">Belum ditinjau</p>
                <a href="{{ route('perusahaan.lamaran.index', ['status' => 'dikirim']) }}" class="text-sm text-blue-600 hover:text-blue-700 mt-2 inline-block">
                    Tinjau Sekarang →
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Lowongan Kerja -->
        <div class="card">
            <div class="card-header">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Lowongan Kerja Anda</h3>
                    <a href="{{ route('perusahaan.lowongan.create') }}" class="btn btn-primary btn-sm">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Tambah
                    </a>
                </div>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($lowongan_list as $lowongan)
                <div class="px-6 py-4 hover:bg-gray-50 transition">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-2">
                                <h4 class="font-semibold text-gray-900">{{ $lowongan->judul }}</h4>
                                <span class="badge badge-{{ $lowongan->status == 'aktif' ? 'success' : 'gray' }} text-xs">
                                    {{ ucfirst($lowongan->status) }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mb-2">{{ $lowongan->posisi }}</p>
                            <div class="flex items-center space-x-4 text-xs text-gray-500">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                    {{ $lowongan->jumlah_pelamar }} pelamar
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    {{ $lowongan->tanggal_berakhir->format('d M Y') }}
                                </span>
                            </div>
                        </div>
                        <a href="{{ route('perusahaan.lowongan.show', $lowongan->id) }}" class="text-blue-600 hover:text-blue-700 ml-4">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>
                @empty
                <div class="px-6 py-12 text-center">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="text-sm text-gray-500 mb-3">Belum ada lowongan dibuat</p>
                    <a href="{{ route('perusahaan.lowongan.create') }}" class="btn btn-primary btn-sm">
                        Buat Lowongan Pertama
                    </a>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Siswa PKL -->
        
    </div>

    <!-- Quick Actions -->
    <div class="card">
        <div class="card-header">
            <h3 class="text-lg font-semibold text-gray-900">Aksi Cepat</h3>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{ route('perusahaan.lowongan.create') }}" class="flex flex-col items-center justify-center p-6 border-2 border-dashed border-gray-300 rounded-lg hover:border-purple-500 hover:bg-purple-50 transition group">
                    <svg class="w-10 h-10 text-gray-400 group-hover:text-purple-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-purple-700">Buat Lowongan</span>
                </a>

                <a href="{{ route('perusahaan.lamaran.index') }}" class="flex flex-col items-center justify-center p-6 border-2 border-dashed border-gray-300 rounded-lg hover:border-purple-500 hover:bg-purple-50 transition group">
                    <svg class="w-10 h-10 text-gray-400 group-hover:text-purple-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-purple-700">Tinjau Lamaran</span>
                </a>

               

                <a href="{{ route('perusahaan.profile.edit') }}" class="flex flex-col items-center justify-center p-6 border-2 border-dashed border-gray-300 rounded-lg hover:border-purple-500 hover:bg-purple-50 transition group">
                    <svg class="w-10 h-10 text-gray-400 group-hover:text-purple-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-purple-700">Edit Profil</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Informasi Penting -->
    <div class="card bg-gradient-to-r from-blue-50 to-indigo-50 border-blue-200">
        <div class="card-body">
            <div class="flex items-start space-x-4">
                <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-gray-900 mb-2">💼 Tips untuk Perusahaan</h3>
                    <p class="text-gray-700 mb-3">
                        Pastikan deskripsi lowongan kerja Anda jelas dan detail. Semakin lengkap informasi yang Anda berikan, semakin mudah bagi Mahasiswa untuk memahami posisi yang ditawarkan.
                    </p>
                    <ul class="space-y-2 text-sm text-gray-700">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-600 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Tinjau lamaran masuk secara berkala untuk mendapatkan kandidat terbaik</span>
                        </li>
                       
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-green-600 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Update profil perusahaan agar lebih menarik bagi calon pelamar</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection