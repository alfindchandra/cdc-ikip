@extends('layouts.app')

@section('title', 'Data Perusahaan')
@section('page-title', 'Daftar Perusahaan Mitra')

@section('content')
<div class="space-y-8 p-4 sm:p-6 lg:p-8">
    
    {{-- Filter & Search Card --}}
    <div class="bg-white shadow-xl rounded-2xl p-6 border-t-4 border-blue-600">
        <h3 class="text-xl font-semibold text-gray-800 mb-4 pb-2 border-b">Filter Data Perusahaan</h3>
        <form method="GET" action="{{ route('admin.perusahaan.index') }}" class="grid grid-cols-1 gap-4 md:grid-cols-5 md:gap-6 items-end">
            
            {{-- Search Input --}}
            <div class="md:col-span-2">
                <label for="search" class="sr-only">Cari...</label>
                <div class="relative">
                    <input type="text" 
                            name="search" 
                            id="search"
                            value="{{ request('search') }}"
                            placeholder="Cari nama, bidang usaha, atau kota..." 
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm transition duration-150">
                    <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>
            
            {{-- Status Filter --}}
            <div class="md:col-span-2">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status Kerjasama</label>
                <select name="status" id="status" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm transition duration-150 appearance-none bg-white">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            {{-- Action Buttons --}}
            <div class="flex space-x-3 col-span-1">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-4 rounded-lg transition duration-150 shadow-md flex items-center justify-center">
                    Filter
                </button>
                <a href="{{ route('admin.perusahaan.index') }}" class="w-full bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2.5 px-4 rounded-lg transition duration-150 shadow-md flex items-center justify-center">Reset</a>
            </div>
        </form>
    </div>

    {{-- Statistics Cards (Enhanced Aesthetics) --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        
        {{-- Aktif Count --}}
        <div class="bg-white shadow-xl rounded-2xl p-6 text-center border-l-4 border-green-500 transition duration-300 hover:shadow-2xl">
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-sm font-medium text-gray-500">Aktif</p>
            <p class="text-3xl font-extrabold text-green-600 mt-1">{{ $perusahaan->where('status_kerjasama', 'aktif')->count() }}</p>
        </div>
        
        {{-- Pending Count --}}
        <div class="bg-white shadow-xl rounded-2xl p-6 text-center border-l-4 border-yellow-500 transition duration-300 hover:shadow-2xl">
            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-sm font-medium text-gray-500">Pending</p>
            <p class="text-3xl font-extrabold text-yellow-600 mt-1">{{ $perusahaan->where('status_kerjasama', 'pending')->count() }}</p>
        </div>
        
        {{-- Nonaktif Count --}}
        <div class="bg-white shadow-xl rounded-2xl p-6 text-center border-l-4 border-gray-400 transition duration-300 hover:shadow-2xl">
            <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                </svg>
            </div>
            <p class="text-sm font-medium text-gray-500">Nonaktif</p>
            <p class="text-3xl font-extrabold text-gray-600 mt-1">{{ $perusahaan->where('status_kerjasama', 'nonaktif')->count() }}</p>
        </div>
        
        {{-- Total Count --}}
        <div class="bg-white shadow-xl rounded-2xl p-6 text-center border-l-4 border-blue-500 transition duration-300 hover:shadow-2xl">
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <p class="text-sm font-medium text-gray-500">Total Perusahaan</p>
            <p class="text-3xl font-extrabold text-blue-600 mt-1">{{ $perusahaan->total() }}</p>
        </div>
    </div>

    {{-- Actions & Header --}}
    <div class="flex justify-between items-center pt-4">
        <h3 class="text-xl font-bold text-gray-900">
            Daftar Perusahaan Mitra ({{ $perusahaan->total() }})
        </h3>
        <a href="{{ route('admin.perusahaan.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-5 rounded-lg shadow-md transition duration-150 flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Tambah Perusahaan</span>
        </a>
    </div>

    {{-- Data Table (Modernized) --}}
    <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Perusahaan</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Bidang Usaha</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Lokasi</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Kontak</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($perusahaan as $p)
                    <tr class="hover:bg-blue-50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-4">
                                @if($p->logo)
                                <img src="{{ Storage::url($p->logo) }}" alt="{{ $p->nama_perusahaan }}" class="w-10 h-10 rounded-full object-cover ring-2 ring-gray-100">
                                @else
                                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-semibold text-lg">
                                    {{ substr($p->nama_perusahaan, 0, 1) }}
                                </div>
                                @endif
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $p->nama_perusahaan }}</p>
                                    <p class="text-sm text-gray-500">{{ $p->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                            {{ $p->bidang_usaha ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <p class="text-sm text-gray-800">{{ $p->kota ?? '-' }}</p>
                            @if($p->provinsi)
                            <p class="text-xs text-gray-500">{{ $p->provinsi }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($p->no_telp)
                            <p class="text-gray-800">{{ $p->no_telp }}</p>
                            @endif
                            @if($p->website)
                            <a href="{{ $p->website }}" target="_blank" class="text-xs text-blue-600 hover:text-blue-800 flex items-center space-x-1 mt-1 transition duration-150">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                                <span>Website</span>
                            </a>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col space-y-2">
                                {{-- Status Badge --}}
                                <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-semibold 
                                    @if($p->status_kerjasama == 'aktif') bg-green-100 text-green-800
                                    @elseif($p->status_kerjasama == 'pending') bg-yellow-100 text-yellow-800
                                    @else bg-gray-200 text-gray-700
                                    @endif">
                                    {{ ucfirst($p->status_kerjasama) }}
                                </span>
                                
                                {{-- Status Update Dropdown (Requires Alpine.js) --}}
                                <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                                    <button @click="open = !open" type="button" class="text-xs text-blue-600 hover:text-blue-800 flex items-center transition duration-150">
                                        Ubah Status
                                        <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" :class="{'rotate-180': open}">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>
                                    
                                    <div x-show="open" 
                                        x-transition:enter="transition ease-out duration-100"
                                        x-transition:enter-start="transform opacity-0 scale-95"
                                        x-transition:enter-end="transform opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-75"
                                        x-transition:leave-start="transform opacity-100 scale-100"
                                        x-transition:leave-end="transform opacity-0 scale-95"
                                        class="absolute right-0 z-20 mt-2 w-32 origin-top-right bg-white rounded-lg shadow-xl ring-1 ring-black ring-opacity-5 focus:outline-none">
                                        <form action="{{ route('admin.perusahaan.status', $p->id) }}" method="POST">
                                            @csrf
                                            @foreach(['aktif', 'pending', 'nonaktif'] as $status)
                                            <button type="submit" name="status_kerjasama" value="{{ $status }}" 
                                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition duration-100">
                                                {{ ucfirst($status) }}
                                            </button>
                                            @endforeach
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2 items-center">
                                <a href="{{ route('admin.perusahaan.show', $p->id) }}" 
                                    class="text-blue-600 hover:text-blue-800 p-1 rounded-full hover:bg-blue-100 transition duration-150" 
                                    title="Detail">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('admin.perusahaan.edit', $p->id) }}" 
                                    class="text-yellow-600 hover:text-yellow-800 p-1 rounded-full hover:bg-yellow-100 transition duration-150" 
                                    title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                {{-- Tambahkan Form Hapus di sini jika diperlukan --}}
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-16 text-gray-500 bg-gray-50">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <p class="text-xl font-semibold">Belum ada data perusahaan mitra</p>
                            <p class="text-sm mt-1">Saatnya mencari dan menambahkan mitra kerjasama baru!</p>
                            <a href="{{ route('admin.perusahaan.create') }}" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 transition duration-150">
                                Tambah Perusahaan Baru
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($perusahaan->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $perusahaan->links() }}
        </div>
        @endif
    </div>
</div>
@endsection