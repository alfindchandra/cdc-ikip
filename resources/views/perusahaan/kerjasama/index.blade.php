@extends('layouts.app')

@section('title', 'Kerjasama Industri')
@section('page-title', 'Daftar Kerjasama Industri')

@section('content')
<div class="space-y-6">
    <div class="card">
        <div class="card-body">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div class="md:col-span-2">
                    <label for="jenis" class="sr-only">Jenis Kerjasama</label>
                    <select name="jenis" id="jenis" class="form-select">
                        <option value="">Semua Jenis</option>
                        {{-- Jenis-jenis kerjasama diambil dari KerjasamaIndustriController.php dan Model --}}
                        @foreach(['pkl', 'rekrutmen', 'pelatihan', 'penelitian', 'sponsorship', 'lainnya'] as $jenis)
                            <option value="{{ $jenis }}" {{ request('jenis') == $jenis ? 'selected' : '' }}>{{ ucfirst($jenis) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label for="status" class="sr-only">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">Semua Status</option>
                        {{-- Statuses diambil dari KerjasamaIndustriController.php dan Model --}}
                        @foreach(['draft', 'proposal', 'negosiasi', 'aktif', 'selesai', 'batal'] as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary w-full">Filter</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Statistik menggunakan status dari KerjasamaIndustriController.php --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="card">
            <div class="card-body text-center">
                <p class="text-sm text-gray-600">Draft</p>
                <p class="text-2xl font-bold text-gray-900">{{ $kerjasama->where('status', 'draft')->count() }}</p>
            </div>
        </div>
        <div class="card">
            <div class="card-body text-center">
                <p class="text-sm text-gray-600">Proses (Proposal/Negosiasi)</p>
                <p class="text-2xl font-bold text-yellow-600">{{ $kerjasama->whereIn('status', ['proposal', 'negosiasi'])->count() }}</p>
            </div>
        </div>
        <div class="card">
            <div class="card-body text-center">
                <p class="text-sm text-gray-600">Aktif</p>
                <p class="text-2xl font-bold text-green-600">{{ $kerjasama->where('status', 'aktif')->count() }}</p>
            </div>
        </div>
        <div class="card">
            <div class="card-body text-center">
                <p class="text-sm text-gray-600">Selesai/Batal</p>
                <p class="text-2xl font-bold text-red-600">{{ $kerjasama->whereIn('status', ['selesai', 'batal'])->count() }}</p>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Judul & Jenis</th>
                        <th>Periode</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kerjasama as $k)
                    <tr class="hover:bg-gray-50">
                        <td>
                            <p class="font-medium text-gray-900">{{ $k->judul }}</p>
                            <p class="text-sm text-gray-500">{{ ucfirst($k->jenis_kerjasama) }}</p>
                        </td>
                        <td>
                            {{-- Menggunakan casting 'date' dari model KerjasamaIndustri.php --}}
                            <p class="text-sm font-medium text-gray-900">{{ $k->tanggal_mulai->format('d M Y') }}</p>
                            <p class="text-sm text-gray-500">s/d {{ $k->tanggal_berakhir ? $k->tanggal_berakhir->format('d M Y') : 'Tidak Berlaku' }}</p>
                        </td>
                        <td>
                            {{-- Logika badge disesuaikan dengan status yang mungkin --}}
                            <span class="badge 
                                @if($k->status == 'aktif') badge-success
                                @elseif($k->status == 'negosiasi' || $k->status == 'proposal') badge-info
                                @elseif($k->status == 'draft') badge-warning
                                @elseif($k->status == 'selesai') badge-gray
                                @else badge-danger
                                @endif">
                                {{ ucfirst($k->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('perusahaan.kerjasama.show', $k->id) }}" class="text-blue-600 hover:text-blue-700">
                                Lihat Detail →
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-12 text-gray-500">
                            {{-- Menggunakan ikon dari sidebar untuk Kerjasama Industri --}}
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <p class="text-lg font-medium">Belum ada data kerjasama industri</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($kerjasama->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{-- Menggunakan links() untuk menampilkan pagination, sesuai dengan controller --}}
            {{ $kerjasama->links() }}
        </div>
        @endif
    </div>
</div>
@endsection