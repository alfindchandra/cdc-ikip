@extends('layouts.app')

@section('title', 'Kerjasama Industri')
@section('page-title', 'Daftar Kerjasama Industri')

@section('content')
<div class="space-y-10">
    {{-- Filter Section --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Filter Kerjasama</h2>
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div class="md:col-span-2">
                <label for="jenis" class="text-sm font-medium text-gray-700">Jenis Kerjasama</label>
                <select name="jenis" id="jenis" class="mt-1 w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Semua Jenis</option>
                    @foreach(['pkl', 'rekrutmen', 'pelatihan', 'penelitian', 'sponsorship', 'lainnya'] as $jenis)
                        <option value="{{ $jenis }}" {{ request('jenis') == $jenis ? 'selected' : '' }}>
                            {{ ucfirst($jenis) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-2">
                <label for="status" class="text-sm font-medium text-gray-700">Status</label>
                <select name="status" id="status" class="mt-1 w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Semua Status</option>
                    @foreach(['draft', 'proposal', 'negosiasi', 'aktif', 'selesai', 'batal'] as $status)
                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-600 text-white font-medium rounded-xl py-2 hover:bg-blue-700 transition duration-200">
                    <i class="fa-solid fa-filter mr-1"></i> Filter
                </button>
            </div>
        </form>
    </div>

    {{-- Statistik Section --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5 text-center">
            <p class="text-sm text-gray-500">Draft</p>
            <p class="text-3xl font-bold text-gray-800">{{ $kerjasama->where('status', 'draft')->count() }}</p>
        </div>
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5 text-center">
            <p class="text-sm text-gray-500">Proses</p>
            <p class="text-3xl font-bold text-yellow-600">{{ $kerjasama->whereIn('status', ['proposal', 'negosiasi'])->count() }}</p>
        </div>
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5 text-center">
            <p class="text-sm text-gray-500">Aktif</p>
            <p class="text-3xl font-bold text-green-600">{{ $kerjasama->where('status', 'aktif')->count() }}</p>
        </div>
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5 text-center">
            <p class="text-sm text-gray-500">Selesai / Batal</p>
            <p class="text-3xl font-bold text-red-600">{{ $kerjasama->whereIn('status', ['selesai', 'batal'])->count() }}</p>
        </div>
    </div>

    {{-- Tabel Daftar Kerjasama --}}
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3 text-left font-medium">Judul & Jenis</th>
                        <th class="px-6 py-3 text-left font-medium">Periode</th>
                        <th class="px-6 py-3 text-left font-medium">Status</th>
                        <th class="px-6 py-3 text-center font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($kerjasama as $k)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-900">{{ $k->judul }}</p>
                                <p class="text-xs text-gray-500">{{ ucfirst($k->jenis_kerjasama) }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-gray-800">{{ $k->tanggal_mulai->format('d M Y') }}</p>
                                <p class="text-xs text-gray-500">s/d {{ $k->tanggal_berakhir ? $k->tanggal_berakhir->format('d M Y') : 'Tidak Berlaku' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $badgeClasses = [
                                        'aktif' => 'bg-green-100 text-green-700',
                                        'negosiasi' => 'bg-blue-100 text-blue-700',
                                        'proposal' => 'bg-blue-100 text-blue-700',
                                        'draft' => 'bg-yellow-100 text-yellow-700',
                                        'selesai' => 'bg-gray-100 text-gray-700',
                                        'batal' => 'bg-red-100 text-red-700'
                                    ];
                                @endphp
                                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $badgeClasses[$k->status] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ ucfirst($k->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('perusahaan.kerjasama.show', $k->id) }}" class="text-blue-600 hover:text-blue-700 font-medium transition">
                                    Lihat Detail →
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-12 text-gray-500">
                                <svg class="w-16 h-16 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <p class="text-sm font-medium">Belum ada data kerjasama industri</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($kerjasama->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $kerjasama->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
