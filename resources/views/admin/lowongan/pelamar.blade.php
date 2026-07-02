@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-7xl">
    <!-- Header Section -->
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Manajemen Pelamar Kerja
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Pantau dan cari seluruh data berkas lamaran masuk dari mahasiswa serta alumni.
            </p>
        </div>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-md shadow-sm flex items-center">
            <svg class="h-5 w-5 text-green-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-sm font-medium text-green-800">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Search Form (Controller-based Backend Search) -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-6">
        <form action="{{ route('admin.lowongan.pelamar', $lowonganId) }}" method="GET" class="md:flex md:items-center md:space-x-4">
            <div class="relative flex-1 max-w-md">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7.5 7.5 0 11-15 0 7.5 7.5 0 0115 0z" />
                    </svg>
                </div>
                <input type="text" name="search" value="{{ $search ?? '' }}"
                    placeholder="Cari nama pelamar, NIM, atau posisi..." 
                    class="block w-full pl-10 pr-3 py-2.5 border border-gray-200 rounded-lg text-sm placeholder-gray-400 bg-gray-50 focus:outline-none focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all">
            </div>
            <div class="mt-3 md:mt-0 flex space-x-2">
                <button type="submit" class="inline-flex items-center justify-center px-4 py-2.5 border border-transparent rounded-lg text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-100 transition-all shadow-sm">
                    Cari Data
                </button>
                @if($search)
                    <a href="{{ route('admin.lowongan.pelamar', request()->route('lowongan')) }}" class="inline-flex items-center justify-center px-4 py-2.5 border border-gray-200 rounded-lg text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 focus:outline-none transition-all shadow-sm">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table Card (Aksi Removed) -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <th class="py-4 px-6 text-center w-16">No</th>
                        <th class="py-4 px-6">Pelamar</th>
                        <th class="py-4 px-6 text-center">Status Akademik</th>
                        <th class="py-4 px-6">Posisi Lowongan</th>
                        <th class="py-4 px-6 text-center">Tanggal Masuk</th>
                        <th class="py-4 px-6 text-center">Status Lamaran</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($pelamar as $key => $item)
                        <tr class="hover:bg-gray-50/70 transition-colors">
                            <!-- Nomor -->
                            <td class="py-4 px-6 text-center text-gray-400 font-medium">{{ $key + 1 }}</td>
                            
                            <!-- Nama Pelamar -->
                            <td class="py-4 px-6 font-semibold text-gray-900">
                                {{ $item->mahasiswa->user->name ?? $item->mahasiswa->nama ?? 'Nama Tidak Tersedia' }}
                                <span class="block text-xs font-normal text-gray-400 mt-0.5">NIM: {{ $item->mahasiswa->nim ?? '-' }}</span>
                            </td>
                            
                            <!-- Status Mahasiswa -->
                            <td class="py-4 px-6 text-center whitespace-nowrap">
                                @if(isset($item->mahasiswa->status) && $item->mahasiswa->status === 'lulus')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                        <span class="w-1.5 h-1.5 bg-blue-500 rounded-full mr-1.5"></span>
                                        {{ $item->mahasiswa->status_text }}
                                    </span>
                                @elseif(isset($item->mahasiswa->status) && $item->mahasiswa->status === 'aktif')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-purple-50 text-purple-700 border border-purple-100">
                                        <span class="w-1.5 h-1.5 bg-purple-500 rounded-full mr-1.5"></span>
                                        {{ $item->mahasiswa->status_text }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-50 text-gray-600 border border-gray-200">
                                        {{ $item->mahasiswa->status_text ?? 'N/A' }}
                                    </span>
                                @endif
                            </td>

                            <!-- Posisi Kerja -->
                            <td class="py-4 px-6 text-gray-700 font-medium">
                                {{ $item->lowongan->posisi ?? $item->lowongan->judul ?? 'Posisi Tidak Tersedia' }}
                            </td>

                            <!-- Tanggal Melamar -->
                            <td class="py-4 px-6 text-center text-gray-500 whitespace-nowrap">
                                {{ $item->tanggal_melamar ? $item->tanggal_melamar->format('d M Y') : $item->created_at->format('d M Y') }}
                            </td>

                            <!-- Status Lamaran -->
                            <td class="py-4 px-6 text-center whitespace-nowrap">
                                @if($item->status == 'Diterima')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-green-50 text-green-700 border border-green-200">
                                        {{ $item->status }}
                                    </span>
                                @elseif($item->status == 'Ditolak')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-red-50 text-red-700 border border-red-200">
                                        {{ $item->status }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-amber-50 text-yellow-700 border border-amber-200">
                                        {{ $item->status ?? 'Pending' }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="h-10 w-10 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-sm font-medium text-gray-400">
                                        @if($search)
                                            Pencarian untuk "{{ $search }}" tidak ditemukan.
                                        @else
                                            Belum ada pelamar kerja saat ini.
                                        @endif
                                    </span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection