@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')

@section('content')
<div class="space-y-6">
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
                        , jangan lupa untuk cek lowongan terbaru!
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
        <!-- Total Siswa -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Mahaiswa Aktif</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $total_siswa }}</p>
                    <p class="text-xs text-gray-500 mt-1">Mahasiswa terdaftar</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Perusahaan -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Perusahaan Mitra</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $total_perusahaan }}</p>
                    <p class="text-xs text-gray-500 mt-1">Aktif bekerjasama</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
            </div>
        </div>

      
        <!-- Lowongan Aktif -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Lowongan Aktif</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $lowongan_aktif }}</p>
                    <p class="text-xs text-gray-500 mt-1">Tersedia untuk Mahasiswa</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">


        <!-- Lowongan Terbaru -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Lowongan Terbaru</h3>
                    <a href="{{ route('admin.lowongan.index') }}" class="text-sm text-blue-600 hover:text-blue-700">Lihat Semua</a>
                </div>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($lowongan_terbaru as $lowongan)
                <div class="px-6 py-4 hover:bg-gray-50 transition">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">{{ $lowongan->judul }}</p>
                            <p class="text-sm text-gray-600 mt-1">{{ $lowongan->perusahaan->nama_perusahaan }}</p>
                            <div class="flex items-center space-x-4 mt-2">
                                <span class="text-xs text-gray-500">{{ $lowongan->lokasi }}</span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ ucfirst(str_replace('_', ' ', $lowongan->tipe_pekerjaan)) }}
                                </span>
                                <span class="text-xs text-gray-500">
                                    {{ $lowongan->jumlah_pelamar }} pelamar
                                </span>
                            </div>
                        </div>
                        <a href="{{ route('admin.lowongan.show', $lowongan->id) }}" class="ml-4 text-blue-600 hover:text-blue-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>
                @empty
                <div class="px-6 py-12 text-center text-gray-500">
                    <p class="text-sm">Belum ada lowongan tersedia</p>
                </div>
                @endforelse
            </div>
        </div>
        <!-- Pelatihan Terbaru -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Pelatihan Terbaru</h3>
            <a href="{{ route('admin.pelatihan.index') }}" class="text-sm text-blue-600 hover:text-blue-700">Lihat Semua</a>
        </div>
    </div>        
    <div class="divide-y divide-gray-200">
        @forelse($pelatihan_terbaru as $pelatihan)
        <div class="px-6 py-4 hover:bg-gray-50 transition">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <p class="font-medium text-gray-900">{{ $pelatihan->judul }}</p>
                    {{-- Menggunakan 'instruktur' sebagai ganti 'penyelenggara' --}}
                    <p class="text-sm text-gray-600 mt-1">Instruktur: {{ $pelatihan->instruktur }}</p>
                    <div class="flex items-center space-x-4 mt-2">
                        {{-- Menggunakan 'tanggal_mulai' sebagai ganti 'tanggal' --}}
                        <span class="text-xs text-gray-500">Mulai: {{ \Carbon\Carbon::parse($pelatihan->tanggal_mulai)->format('d M Y') }}</span>
                        {{-- Menggunakan 'jenis' sebagai ganti 'tipe_pelatihan' --}}
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            {{ ucfirst(str_replace('_', ' ', $pelatihan->jenis)) }}
                        </span>
                        <span class="text-xs text-gray-500">
                            {{ $pelatihan->jumlah_peserta }} peserta
                        </span>
                    </div>
                </div>
                <a href="{{ route('admin.pelatihan.show', $pelatihan->id) }}" class="ml-4 text-blue-600 hover:text-blue-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
        @empty
        <div class="px-6 py-12 text-center text-gray-500">
            <p class="text-sm">Belum ada pelatihan tersedia</p>
        </div>
        @endforelse
    </div>
</div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Aksi Cepat</h3>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            <a href="{{ route('admin.siswa.create') }}" class="flex flex-col items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition">
                <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span class="text-sm font-medium text-gray-700">Tambah Mahasiswa</span>
            </a>

            <a href="{{ route('admin.perusahaan.create') }}" class="flex flex-col items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition">
                <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span class="text-sm font-medium text-gray-700">Tambah Perusahaan</span>
            </a>

            <a href="{{ route('admin.pelatihan.create') }}" class="flex flex-col items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition">
                <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span class="text-sm font-medium text-gray-700">Buat Pelatihan</span>
            </a>

            
        </div>
    </div>
</div>
@endsection