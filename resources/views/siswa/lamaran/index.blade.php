@extends('layouts.app')

@section('title', 'Riwayat Lamaran')
@section('page-title', 'Riwayat Lamaran Saya')

@section('content')
<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="card">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Lamaran</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">
                            {{ auth()->user()->siswa->lamaran()->count() }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Diproses</p>
                        <p class="text-2xl font-bold text-yellow-600 mt-1">
                            {{ auth()->user()->siswa->lamaran()->whereIn('status', ['dikirim', 'dilihat', 'diproses'])->count() }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Diterima</p>
                        <p class="text-2xl font-bold text-green-600 mt-1">
                            {{ auth()->user()->siswa->lamaran()->where('status', 'diterima')->count() }}
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
                        <p class="text-sm text-gray-600">Ditolak</p>
                        <p class="text-2xl font-bold text-red-600 mt-1">
                            {{ auth()->user()->siswa->lamaran()->where('status', 'ditolak')->count() }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
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
                <div class="md:col-span-3">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="dikirim" {{ request('status') == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                        <option value="dilihat" {{ request('status') == 'dilihat' ? 'selected' : '' }}>Dilihat</option>
                        <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="diterima" {{ request('status') == 'diterima' ? 'selected' : '' }}>Diterima</option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary w-full">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Lamaran List -->
    <div class="space-y-4">
        @forelse($lamaran as $l)
        <div class="card hover:shadow-lg transition-shadow">
            <div class="card-body">
                <div class="flex items-start space-x-4">
                    <!-- Company Logo -->
                    <div class="flex-shrink-0">
                        @if($l->lowongan->perusahaan->logo)
                        <img src="{{ Storage::url($l->lowongan->perusahaan->logo) }}" 
                             alt="{{ $l->lowongan->perusahaan->nama_perusahaan }}" 
                             class="w-16 h-16 rounded-lg object-cover">
                        @else
                        <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                            <span class="text-gray-600 font-bold text-xl">
                                {{ substr($l->lowongan->perusahaan->nama_perusahaan, 0, 1) }}
                            </span>
                        </div>
                        @endif
                    </div>

                    <!-- Lamaran Info -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">{{ $l->lowongan->judul }}</h3>
                                <p class="text-sm text-gray-600">{{ $l->lowongan->perusahaan->nama_perusahaan }}</p>
                            </div>
                            <span class="badge 
                                @if($l->status == 'dikirim') badge-primary
                                @elseif($l->status == 'dilihat') badge-info
                                @elseif($l->status == 'diproses') badge-warning
                                @elseif($l->status == 'diterima') badge-success
                                @else badge-danger
                                @endif text-sm px-3 py-1 flex-shrink-0 ml-4">
                                {{ ucfirst($l->status) }}
                            </span>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-3 text-sm">
                            <div>
                                <p class="text-gray-500">Tanggal Melamar</p>
                                <p class="font-medium text-gray-900">{{ $l->tanggal_melamar->format('d M Y') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Posisi</p>
                                <p class="font-medium text-gray-900">{{ $l->lowongan->posisi }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Tipe</p>
                                <p class="font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $l->lowongan->tipe_pekerjaan)) }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Lokasi</p>
                                <p class="font-medium text-gray-900">{{ $l->lowongan->lokasi }}</p>
                            </div>
                        </div>

                        @if($l->catatan)
                        <div class="mb-3 p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-600 mb-1">Catatan Anda:</p>
                            <p class="text-sm text-gray-700 line-clamp-2">{{ $l->catatan }}</p>
                        </div>
                        @endif

                        @if($l->catatan_perusahaan)
                        <div class="mb-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                            <p class="text-xs text-blue-800 font-medium mb-1">Catatan dari Perusahaan:</p>
                            <p class="text-sm text-blue-900">{{ $l->catatan_perusahaan }}</p>
                        </div>
                        @endif

                        <!-- Actions -->
                        <div class="flex items-center justify-between pt-3 border-t border-gray-200">
                            <div class="flex items-center space-x-2 text-xs text-gray-500">
                                @if($l->cv)
                                <a href="{{ Storage::url($l->cv) }}" target="_blank" class="flex items-center text-blue-600 hover:text-blue-700">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                    CV
                                </a>
                                @endif
                                @if($l->surat_lamaran)
                                <a href="{{ Storage::url($l->surat_lamaran) }}" target="_blank" class="flex items-center text-blue-600 hover:text-blue-700">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Surat
                                </a>
                                @endif
                                @if($l->portofolio)
                                <a href="{{ Storage::url($l->portofolio) }}" target="_blank" class="flex items-center text-blue-600 hover:text-blue-700">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    Portofolio
                                </a>
                                @endif
                            </div>

                            <div class="flex items-center space-x-2">
                                <a href="{{ route('siswa.lowongan.show', $l->lowongan->id) }}" 
                                   class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                                    Lihat Lowongan →
                                </a>
                                @if(in_array($l->status, ['dikirim', 'dilihat']))
                                <form action="{{ route('siswa.lamaran.destroy', $l->id) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Yakin ingin membatalkan lamaran ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm text-red-600 hover:text-red-700 font-medium">
                                        Batalkan
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="card">
            <div class="card-body text-center py-12">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-lg font-medium text-gray-900 mb-2">Belum Ada Lamaran</p>
                <p class="text-sm text-gray-500 mb-6">Mulai cari lowongan pekerjaan yang sesuai dengan minat Anda</p>
                <a href="{{ route('siswa.lowongan.index') }}" class="btn btn-primary">
                    Cari Lowongan
                </a>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($lamaran->hasPages())
    <div class="flex justify-center">
        {{ $lamaran->links() }}
    </div>
    @endif
</div>
@endsection