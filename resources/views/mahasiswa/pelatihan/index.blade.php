
@extends('layouts.app')

@section('title', 'Pelatihan')
@section('page-title', 'Pelatihan & Pembekalan')

@section('content')
<div class="space-y-6">
    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="card">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Pelatihan Tersedia</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">
                            {{ \App\Models\Pelatihan::published()->where('tanggal_mulai', '>', now())->count() }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Pelatihan Diikuti</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">
                            {{ auth()->user()->mahasiswa->pelatihan()->wherePivot('status_pendaftaran', 'diterima')->count() }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Sertifikat Didapat</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">
                            {{ auth()->user()->mahasiswa->pelatihan()->wherePivotNotNull('sertifikat')->count() }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="card">
        <div class="card-body">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari pelatihan..." class="form-input p-2 border rounded-lg w-full">
                </div>
                <div>
                    <select name="jenis" class="form-select border rounded-lg w-full p-2">
                        <option value="">Semua Jenis</option>
                         @foreach(['webinar','seminar','bimbingan_karier','workshop', 'lainnya'] as $jenis)
                        <option value="{{ $jenis }}" {{ request('jenis') == $jenis ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $jenis)) }}</option>
                    @endforeach
                 </select>
                </div>
                <div>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded w-full">Cari</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Pelatihan List -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @forelse($pelatihan as $p)
        <div class="card hover:shadow-lg transition-shadow">
            @if($p->thumbnail)
            <img src="{{ Storage::url($p->thumbnail) }}" alt="{{ $p->judul }}" class="w-full h-48 object-cover rounded-t-lg">
            @else
            <div class="w-full h-48 bg-gradient-to-br from-purple-500 to-pink-600 rounded-t-lg flex items-center justify-center">
                <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            @endif

            <div class="p-6">
                <div class="flex items-center justify-between mb-3">
                    <span class="badge badge-primary">
                        {{ ucfirst(str_replace('_', ' ', $p->jenis)) }}
                    </span>
                    @if($p->biaya > 0)
                    <span class="text-sm font-semibold text-gray-900">Rp {{ number_format($p->biaya, 0, ',', '.') }}</span>
                    @else
                    <span class="badge badge-success">GRATIS</span>
                    @endif
                </div>

                <h3 class="text-xl font-bold text-gray-900 mb-2 line-clamp-2">{{ $p->judul }}</h3>
                
                <div class="space-y-2 mb-4">
                    @if($p->instruktur)
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        {{ $p->instruktur }}
                    </div>
                    @endif
                    
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ $p->tanggal_mulai->format('d M Y, H:i') }}
                    </div>

                    @if($p->tempat)
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        </svg>
                        {{ $p->tempat }}
                    </div>
                    @endif
                </div>

                <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $p->deskripsi }}</p>

                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    <div class="text-sm text-gray-600">
                        <span class="font-semibold">{{ $p->jumlah_peserta }}</span> / {{ $p->kuota ?? '∞' }} peserta
                    </div>
                    
                    @php
                        $sudahDaftar = auth()->user()->mahasiswa->pelatihan()->where('pelatihan_id', $p->id)->exists();
                    @endphp

                    @if($sudahDaftar)
                    <span class="badge badge-info">Terdaftar</span>
                    @else
                    <a href="{{ route('mahasiswa.pelatihan.show', $p->id) }}" class="text-blue-600 hover:text-blue-700 font-medium text-sm">
                        Lihat Detail →
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full card">
            <div class="card-body text-center py-12">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <p class="text-lg font-medium text-gray-900">Belum ada pelatihan tersedia</p>
                <p class="text-sm text-gray-500 mt-1">Pelatihan akan segera hadir</p>
            </div>
        </div>
        @endforelse
    </div>

    @if($pelatihan->hasPages())
    <div class="flex justify-center">
        {{ $pelatihan->links() }}
    </div>
    @endif
</div>
@endsection