@extends('layouts.app')

@section('title', 'Kelola Pelatihan')
@section('page-title', 'Kelola Pelatihan')

@section('content')
<div class="space-y-8 p-4 sm:p-6 lg:p-8">
    
    {{-- Filter & Search Card --}}
    <div class="bg-white shadow-xl rounded-xl p-6 border-t-4 border-blue-600">
        <h3 class="text-xl font-semibold text-gray-800 mb-4 pb-2 border-b">Filter Pelatihan</h3>
        <form method="GET" class="grid grid-cols-1 gap-4 md:grid-cols-5 md:gap-6 items-end">
            
            {{-- Search Input --}}
            <div class="md:col-span-2">
                <label for="search" class="sr-only">Cari...</label>
                <input type="text" 
                        name="search" 
                        id="search"
                        value="{{ request('search') }}"
                        placeholder="Cari judul pelatihan..." 
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm transition duration-150">
            </div>
            
            {{-- Jenis Filter --}}
            <div>
                <label for="jenis" class="sr-only">Jenis Pelatihan</label>
                <select name="jenis" id="jenis" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm transition duration-150 appearance-none bg-white">
                    <option value="">Semua Jenis</option>
                    @foreach(['soft_skill', 'hard_skill', 'sertifikasi', 'pembekalan'] as $jenis)
                        <option value="{{ $jenis }}" {{ request('jenis') == $jenis ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $jenis)) }}</option>
                    @endforeach
                </select>
            </div>
            
            {{-- Status Filter --}}
            <div>
                <label for="status" class="sr-only">Status Pelatihan</label>
                <select name="status" id="status" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm transition duration-150 appearance-none bg-white">
                    <option value="">Semua Status</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                </select>
            </div>
            
            {{-- Filter Button --}}
            <div>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-4 rounded-lg transition duration-150 shadow-md transform hover:scale-[1.01] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Terapkan Filter
                </button>
            </div>
        </form>
    </div>

    {{-- Actions & Header --}}
    <div class="flex justify-between items-center pt-4">
        <h3 class="text-xl font-bold text-gray-900">
            Total: {{ $pelatihan->total() }} Pelatihan
        </h3>
        <a href="{{ route('admin.pelatihan.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-5 rounded-lg shadow-md transition duration-150 flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Buat Pelatihan</span>
        </a>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        
        {{-- Draft Count --}}
        <div class="bg-white shadow-xl rounded-2xl p-6 text-center border-l-4 border-yellow-500 transition duration-300 hover:shadow-2xl">
            <p class="text-sm font-medium text-gray-500 mb-1">Draft</p>
            <p class="text-3xl font-extrabold text-yellow-600 mt-1">{{ $pelatihan->where('status', 'draft')->count() }}</p>
        </div>
        
        {{-- Published Count --}}
        <div class="bg-white shadow-xl rounded-2xl p-6 text-center border-l-4 border-green-500 transition duration-300 hover:shadow-2xl">
            <p class="text-sm font-medium text-gray-500 mb-1">Published</p>
            <p class="text-3xl font-extrabold text-green-600 mt-1">{{ $pelatihan->where('status', 'published')->count() }}</p>
        </div>
        
        {{-- Total Peserta Sum --}}
        <div class="bg-white shadow-xl rounded-2xl p-6 text-center border-l-4 border-blue-500 transition duration-300 hover:shadow-2xl">
            <p class="text-sm font-medium text-gray-500 mb-1">Total Peserta</p>
            <p class="text-3xl font-extrabold text-blue-600 mt-1">{{ $pelatihan->sum('jumlah_peserta') }}</p>
        </div>
        
        {{-- Akan Datang Count (Contoh logika sederhana, asumsikan published dan tanggal mulai di masa depan) --}}
        <div class="bg-white shadow-xl rounded-2xl p-6 text-center border-l-4 border-purple-500 transition duration-300 hover:shadow-2xl">
            <p class="text-sm font-medium text-gray-500 mb-1">Akan Datang</p>
            {{-- Catatan: Mengakses now() di blade membutuhkan passing data dari controller. 
                Jika $pelatihan adalah Collection/Pagination, pastikan data yang diakses sudah difilter di Controller. 
                Untuk tujuan demonstrasi front-end, ini hanyalah contoh. --}}
            <p class="text-3xl font-extrabold text-purple-600 mt-1">
                {{-- Logika ini hanya berfungsi jika $pelatihan adalah koleksi penuh yang belum difilter oleh status/tanggal di query utama --}}
                {{ $pelatihan->where('status', 'published')->where('tanggal_mulai', '>', now())->count() }}
            </p>
        </div>
    </div>

    {{-- Pelatihan Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($pelatihan as $p)
        @php
            $status_class = $p->status == 'published' ? 'bg-green-500' : 'bg-yellow-500';
            $status_badge_class = $p->status == 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800';
            $jenis_badge_class = 'bg-blue-100 text-blue-800'; // Default untuk jenis
        @endphp
        <div class="bg-white rounded-xl shadow-lg overflow-hidden flex flex-col transition-transform duration-300 hover:scale-[1.02] hover:shadow-2xl">
            
            {{-- Thumbnail / Placeholder --}}
            <div class="w-full h-48 bg-gray-200 relative">
                @if($p->thumbnail)
                <img src="{{ Storage::url($p->thumbnail) }}" alt="{{ $p->judul }}" class="w-full h-full object-cover">
                @else
                <div class="w-full h-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center">
                    <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                @endif
                {{-- Status Overlay --}}
                <span class="absolute top-3 right-3 inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $status_badge_class }}">
                    {{ ucfirst($p->status) }}
                </span>
            </div>
            
            {{-- Content --}}
            <div class="p-6 flex-grow space-y-4">
                
                <div class="flex items-start justify-between">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $jenis_badge_class }}">
                        {{ ucfirst(str_replace('_', ' ', $p->jenis)) }}
                    </span>
                    @if($p->biaya > 0)
                    <span class="text-lg font-extrabold text-gray-900">Rp {{ number_format($p->biaya, 0, ',', '.') }}</span>
                    @else
                    <span class="text-sm font-bold text-green-600 bg-green-50 px-2 py-0.5 rounded-full">GRATIS</span>
                    @endif
                </div>

                <h3 class="text-xl font-bold text-gray-900 line-clamp-2 min-h-[56px]">{{ $p->judul }}</h3>
                
                {{-- Detail Grid --}}
                <div class="grid grid-cols-2 gap-4 text-sm pt-2 border-t border-gray-100">
                    <div>
                        <p class="text-gray-500">Instruktur</p>
                        <p class="font-semibold text-gray-800 truncate">{{ $p->instruktur ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Tempat</p>
                        <p class="font-semibold text-gray-800 truncate">{{ $p->tempat ?? 'Online' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Tanggal Mulai</p>
                        <p class="font-semibold text-gray-800">{{ $p->tanggal_mulai->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Peserta (Kuota)</p>
                        <p class="font-semibold text-gray-800">{{ $p->jumlah_peserta }} / {{ $p->kuota ?? '∞' }}</p>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="p-6 pt-0 border-t border-gray-200">
                <div class="grid grid-cols-2 gap-3 mt-4">
                    <a href="{{ route('admin.pelatihan.show', $p->id) }}" 
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 rounded-lg transition duration-150 flex items-center justify-center space-x-1 text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <span>Detail</span>
                    </a>
                    <a href="{{ route('admin.pelatihan.peserta', $p->id) }}" 
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 rounded-lg transition duration-150 flex items-center justify-center space-x-1 text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        <span>Peserta ({{ $p->jumlah_peserta }})</span>
                    </a>
                </div>
                
                <div class="flex flex-wrap gap-3 mt-3">
                    <a href="{{ route('admin.pelatihan.edit', $p->id) }}" 
                        class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 rounded-lg transition duration-150 text-center text-sm">
                        Edit
                    </a>
                    @if($p->status == 'draft')
                    <form action="{{ route('admin.pelatihan.publish', $p->id) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 rounded-lg transition duration-150 text-center text-sm">
                            Publish
                        </button>
                    </form>
                    @endif
                    <form action="{{ route('admin.pelatihan.destroy', $p->id) }}" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        {{-- Catatan: Mengganti confirm() dengan modal UI kustom di lingkungan produksi --}}
                        <button type="submit" onclick="return confirm('Yakin ingin menghapus pelatihan ini?')" class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2 rounded-lg transition duration-150 text-center text-sm">
                            Hapus
                        </button>
                    </form>
                </div>

            </div>
        </div>
        @empty
        <div class="col-span-full bg-white shadow-xl rounded-2xl p-6 text-center py-12 border border-gray-200">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            <p class="text-xl font-semibold text-gray-900">Belum ada pelatihan yang dibuat</p>
            <p class="text-sm text-gray-500 mt-1">Mulai buat pelatihan untuk siswa dan kembangkan kemampuan mereka.</p>
            <a href="{{ route('admin.pelatihan.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-5 rounded-lg shadow-md transition duration-150 mt-4 inline-flex items-center">
                Buat Pelatihan Pertama
            </a>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($pelatihan->hasPages())
    <div class="flex justify-center pt-4">
        {{ $pelatihan->links() }}
    </div>
    @endif
</div>
@endsection