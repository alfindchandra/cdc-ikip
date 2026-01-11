@extends('layouts.app')

@section('title', 'Kelola Lowongan Kerja')
@section('page-title', 'Kelola Lowongan Kerja')

@section('content')
<div class="space-y-8 px-4 sm:px-0">
    
   <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
    <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
        <svg class="w-6 h-6 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707v7.586l-2-2v-7.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
        Filter & Pencarian
    </h2>
    <form method="GET" action="{{ route('admin.lowongan.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
        <div class="md:col-span-2">
            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari Lowongan</label>
            <input type="text" 
                    id="search"
                    name="search" 
                    value="{{ request('search') }}"
                    placeholder="Cari judul, posisi, atau perusahaan..." 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-150">
        </div>
        <div>
            <label for="status-filter" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
            <select id="status-filter" name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                <option value="">Semua Kategori</option>
                  <option value="full_time" {{ request('status') == 'full_time' ? 'selected' : '' }}>Full Time</option>
                        <option value="part_time" {{ request('status') == 'part_time' ? 'selected' : '' }}>Part Time</option>
                        <option value="kontrak" {{ request('status') == 'kontrak' ? 'selected' : '' }}>Kontrak</option>
                        <option value="magang" {{ request('status') == 'magang' ? 'selected' : '' }}>Magang</option>

            </select>
        </div>
        <div class="flex space-x-3 mt-2 md:mt-0">
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg shadow-md flex items-center justify-center transition duration-150">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Terapkan
            </button>
            <a href="{{ route('admin.lowongan.index') }}" class="w-full bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 rounded-lg shadow-md flex items-center justify-center transition duration-150">
                Reset
            </a>
        </div>
    </form>
</div>

   

    <!-- Table Section -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-100">
        <div class="overflow-x-auto w-full">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Lowongan</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Perusahaan & Lokasi</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider whitespace-nowrap">Periode Batas</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Pelamar</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($lowongan as $l)
                    <tr class="hover:bg-gray-50 transition duration-100">
                        
                        {{-- Lowongan Title & Position --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-3">
                                @if($l->thumbnail)
                                <img src="{{ Storage::url($l->thumbnail) }}" alt="{{ $l->judul }}" class="w-12 h-12 rounded-lg object-cover flex-shrink-0">
                                @else
                                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                </div>
                                @endif
                                <div>
                                    <p class="font-medium text-gray-900 line-clamp-1">{{ $l->judul }}</p>
                                    <p class="text-sm text-gray-500">{{ $l->posisi }}</p>
                                </div>
                            </div>
                        </td>
                        
                        {{-- Perusahaan & Lokasi --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <p class="font-medium text-gray-800">{{ $l->perusahaan->nama_perusahaan }}</p>
                            <p class="text-sm text-gray-500">{{ $l->lokasi }}</p>
                        </td>
                        
                        {{-- Tipe Pekerjaan --}}
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                {{ ucfirst(str_replace('_', ' ', $l->tipe_pekerjaan)) }}
                            </span>
                        </td>
                        
                        {{-- Periode Batas --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <p class="text-sm font-medium text-gray-800">{{ $l->tanggal_mulai->format('d M Y') }}</p>
                            <p class="text-xs text-red-500 font-semibold">Batas: {{ $l->tanggal_berakhir->format('d M Y') }}</p>
                        </td>
                        
                        {{-- Jumlah Pelamar --}}
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <a href="{{ route('admin.lowongan.pelamar', $l->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-50 text-blue-600 font-bold text-sm hover:bg-blue-100 transition duration-150" title="Lihat Pelamar">
                                {{ $l->jumlah_pelamar }}
                            </a>
                        </td>
                        
                        {{-- Status Dropdown --}}
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <form action="{{ route('admin.lowongan.status', $l->id) }}" method="POST">
                                @csrf
                                @php
                                    $status_select_class = [
                                        'aktif' => 'bg-green-100 text-green-800 border-green-300',
                                        'nonaktif' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                                        'expired' => 'bg-red-100 text-red-800 border-red-300',
                                    ][$l->status] ?? 'bg-gray-100 text-gray-800 border-gray-300';
                                @endphp
                                <select name="status" onchange="this.form.submit()" class="text-xs py-1.5 px-2 rounded-lg font-medium shadow-sm cursor-pointer focus:ring-blue-500 focus:border-blue-500 {{ $status_select_class }}">
                                    <option value="aktif" {{ $l->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="nonaktif" {{ $l->status == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                    <option value="expired" {{ $l->status == 'expired' ? 'selected' : '' }}>Expired</option>
                                </select>
                            </form>
                        </td>
                        
                        {{-- Aksi --}}
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <div class="flex space-x-3 justify-center">
                                <a href="{{ route('admin.lowongan.show', $l->id) }}" 
                                   class="text-blue-600 hover:text-blue-800 p-1 rounded-full hover:bg-blue-50 transition duration-150" 
                                   title="Detail Lowongan">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                               
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-12">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-xl font-medium text-gray-500">Tidak ada lowongan yang ditemukan.</p>
                            <p class="text-sm text-gray-400 mt-1">Coba sesuaikan filter pencarian Anda.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($lowongan->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-white rounded-b-xl">
            {{ $lowongan->links() }}
        </div>
        @endif
    </div>
</div>
@endsection