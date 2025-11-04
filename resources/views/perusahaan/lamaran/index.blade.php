@extends('layouts.app')

@section('title', 'Lamaran Masuk')
@section('page-title', 'Lamaran Masuk')

@section('content')
<div class="space-y-6">
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

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="card">
            <div class="card-body text-center">
                <p class="text-sm text-gray-600">Dikirim</p>
                <p class="text-2xl font-bold text-gray-900">{{ $lamaran->where('status', 'dikirim')->count() }}</p>
            </div>
        </div>
        <div class="card">
            <div class="card-body text-center">
                <p class="text-sm text-gray-600">Diproses</p>
                <p class="text-2xl font-bold text-blue-600">{{ $lamaran->where('status', 'diproses')->count() }}</p>
            </div>
        </div>
        <div class="card">
            <div class="card-body text-center">
                <p class="text-sm text-gray-600">Diterima</p>
                <p class="text-2xl font-bold text-green-600">{{ $lamaran->where('status', 'diterima')->count() }}</p>
            </div>
        </div>
        <div class="card">
            <div class="card-body text-center">
                <p class="text-sm text-gray-600">Ditolak</p>
                <p class="text-2xl font-bold text-red-600">{{ $lamaran->where('status', 'ditolak')->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Lamaran List -->
    <div class="card">
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Pelamar</th>
                        <th>Lowongan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lamaran as $l)
                    <tr class="hover:bg-gray-50">
                        <td>{{ $l->tanggal_melamar->format('d M Y') }}</td>
                        <td>
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-semibold">
                                    {{ substr($l->siswa->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $l->siswa->user->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $l->siswa->jurusan }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <p class="font-medium text-gray-900">{{ $l->lowongan->judul }}</p>
                            <p class="text-sm text-gray-500">{{ $l->lowongan->posisi }}</p>
                        </td>
                        <td>
                            <span class="badge 
                                @if($l->status == 'dikirim') badge-warning
                                @elseif($l->status == 'dilihat' || $l->status == 'diproses') badge-info
                                @elseif($l->status == 'diterima') badge-success
                                @else badge-danger
                                @endif">
                                {{ ucfirst($l->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('perusahaan.lamaran.show', $l->id) }}" class="text-blue-600 hover:text-blue-700">
                                Lihat Detail →
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-12 text-gray-500">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-lg font-medium">Belum ada lamaran masuk</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($lamaran->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $lamaran->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
