@extends('layouts.app')

@section('title', 'Lowongan Kerja')
@section('page-title', 'Lowongan Kerja')

@section('content')
<div class="space-y-6">
    <!-- Search & Filter -->
    <div class="card">
        <div class="card-body">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-2">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Cari posisi atau perusahaan..." 
                           class="form-input">
                </div>
                <div>
                    <select name="tipe" class="form-select">
                        <option value="">Semua Tipe</option>
                        <option value="full_time" {{ request('tipe') == 'full_time' ? 'selected' : '' }}>Full Time</option>
                        <option value="part_time" {{ request('tipe') == 'part_time' ? 'selected' : '' }}>Part Time</option>
                        <option value="kontrak" {{ request('tipe') == 'kontrak' ? 'selected' : '' }}>Kontrak</option>
                        <option value="magang" {{ request('tipe') == 'magang' ? 'selected' : '' }}>Magang</option>
                    </select>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary w-full">Cari</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Lowongan Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($lowongan as $l)
        <div class="card hover:shadow-lg transition-shadow">
            @if($l->thumbnail)
            <img src="{{ Storage::url($l->thumbnail) }}" alt="{{ $l->judul }}" class="w-full h-48 object-cover rounded-t-lg">
            @else
            <div class="w-full h-48 bg-gradient-to-br from-blue-500 to-purple-600 rounded-t-lg flex items-center justify-center">
                <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            @endif
            
            <div class="p-6">
                <!-- Company Info -->
                <div class="flex items-center space-x-3 mb-4">
                    @if($l->perusahaan->logo)
                    <img src="{{ Storage::url($l->perusahaan->logo) }}" alt="{{ $l->perusahaan->nama_perusahaan }}" class="w-10 h-10 rounded-lg object-cover">
                    @else
                    <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center">
                        <span class="text-gray-600 font-semibold text-sm">{{ substr($l->perusahaan->nama_perusahaan, 0, 1) }}</span>
                    </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ $l->perusahaan->nama_perusahaan }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ $l->lokasi }}</p>
                    </div>
                </div>

                <!-- Job Title -->
                <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2">{{ $l->judul }}</h3>
                
                <!-- Job Type & Salary -->
                <div class="flex items-center justify-between mb-4">
                    <span class="badge badge-primary">
                        {{ ucfirst(str_replace('_', ' ', $l->tipe_pekerjaan)) }}
                    </span>
                    @if($l->gaji_min && $l->gaji_max)
                    <span class="text-sm text-gray-600">
                        Rp {{ number_format($l->gaji_min/1000000, 1) }}jt - {{ number_format($l->gaji_max/1000000, 1) }}jt
                    </span>
                    @endif
                </div>

                <!-- Description -->
                <p class="text-sm text-gray-600 mb-4 line-clamp-3">{{ $l->deskripsi }}</p>

                <!-- Footer -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    <div class="flex items-center space-x-4 text-xs text-gray-500">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            {{ $l->jumlah_pelamar }} pelamar
                        </span>
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ $l->tanggal_berakhir->diffForHumans() }}
                        </span>
                    </div>
                    <a href="{{ route('siswa.lowongan.show', $l->id) }}" class="text-blue-600 hover:text-blue-700 font-medium text-sm">
                        Lihat Detail →
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="card">
                <div class="card-body text-center py-12">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-lg font-medium text-gray-900">Belum ada lowongan tersedia</p>
                    <p class="text-sm text-gray-500 mt-1">Coba lagi nanti atau ubah filter pencarian</p>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($lowongan->hasPages())
    <div class="flex justify-center">
        {{ $lowongan->links() }}
    </div>
    @endif
</div>
@endsection
