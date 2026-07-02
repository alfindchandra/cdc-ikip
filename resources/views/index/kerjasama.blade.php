@extends('layouts.index')
@section('title', 'Bentuk Kerjasama')
@section('content')

<!-- Hero Section -->
<section class="py-12 bg-gradient-to-r from-blue-600 to-indigo-600">
    <div class="container mx-auto px-4">
        <div class="text-center">
            <h1 class="text-4xl font-bold text-white mb-3">Bentuk Kerjasama</h1>
            <p class="text-blue-100 text-lg">Pelajari berbagai bentuk kerjasama strategis dengan industri</p>
        </div>
    </div>
</section>

<!-- Search & Filter Section -->
<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <form action="{{ route('index.kerjasama') }}" method="GET" class="bg-white rounded-xl shadow-md p-8">
            <div class="grid md:grid-cols-3 gap-6 mb-6">
                <!-- Kata Kunci -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-search mr-2 text-blue-600"></i>Kata Kunci
                    </label>
                    <input 
                        type="text" 
                        name="keyword" 
                        value="{{ request('keyword') }}"
                        placeholder="Cari judul, deskripsi..." 
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                    >
                </div>

                <!-- Jenis Kerjasama -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-handshake mr-2 text-blue-600"></i>Jenis Kerjasama
                    </label>
                    <select 
                        name="jenis" 
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all appearance-none bg-white cursor-pointer"
                    >
                        <option value="">Semua Jenis</option>
                        <option value="magang" {{ request('jenis') == 'magang' ? 'selected' : '' }}>Magang</option>
                        <option value="pkl" {{ request('jenis') == 'pkl' ? 'selected' : '' }}>PKL</option>
                        <option value="rekrutmen" {{ request('jenis') == 'rekrutmen' ? 'selected' : '' }}>Rekrutmen</option>
                        <option value="pelatihan" {{ request('jenis') == 'pelatihan' ? 'selected' : '' }}>Pelatihan</option>
                        <option value="penelitian" {{ request('jenis') == 'penelitian' ? 'selected' : '' }}>Penelitian</option>
                        <option value="lainnya" {{ request('jenis') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>

                <!-- Tombol -->
                <div class="flex items-end gap-3">
                    <button 
                        type="submit" 
                        class="flex-1 bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-3 px-6 rounded-lg font-semibold hover:shadow-lg hover:scale-105 transition-all flex items-center justify-center space-x-2"
                    >
                        <i class="fas fa-filter"></i>
                        <span>Terapkan Filter</span>
                    </button>
                    
                    @if(request()->hasAny(['keyword', 'jenis']))
                    <a 
                        href="{{ route('index.kerjasama') }}" 
                        class="bg-gray-200 text-gray-700 py-3 px-6 rounded-lg font-semibold hover:bg-gray-300 transition-all flex items-center space-x-2"
                    >
                        <i class="fas fa-redo"></i>
                        <span>Reset</span>
                    </a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</section>

<!-- Main Content -->
<section class="py-12 bg-white">
    <div class="container mx-auto px-4">
        <!-- Result Count -->
        <div class="mb-8">
            <p class="text-gray-600">
                Menampilkan <span class="font-semibold text-blue-600">{{ $kerjasamaTerbaru->count() }}</span> 
                dari <span class="font-semibold text-blue-600">{{ $kerjasamaTerbaru->total() }}</span> bentuk kerjasama
            </p>
        </div>

        @if($kerjasamaTerbaru->count() > 0)
            <!-- Grid Kerjasama -->
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
                @foreach($kerjasamaTerbaru as $kerjasama)
                <div class="bg-gradient-to-br from-gray-50 to-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 hover:-translate-y-1 flex flex-col">
                    <!-- Header Color Bar -->
                    <div class="h-2 bg-gradient-to-r from-blue-500 to-indigo-500"></div>
                    
                    <div class="p-6 flex flex-col flex-grow">
                        <!-- Badges -->
                        <div class="flex items-center justify-between mb-4 gap-2 flex-wrap">
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold whitespace-nowrap">
                                {{ ucfirst(str_replace('_', ' ', $kerjasama->jenis_kerjasama)) }}
                            </span>
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold whitespace-nowrap">
                                {{ ucfirst($kerjasama->status) }}
                            </span>
                        </div>

                        <!-- Judul -->
                        <h3 class="text-lg font-bold text-gray-800 mb-2 line-clamp-2 hover:text-blue-600 transition-colors">
                            {{ $kerjasama->judul }}
                        </h3>

                        <!-- Perusahaan -->
                        @if($kerjasama->perusahaan)
                        <div class="flex items-center mb-3 text-sm text-gray-600">
                            <i class="fas fa-building text-blue-500 mr-2 w-4"></i>
                            <span class="font-semibold line-clamp-1">{{ $kerjasama->perusahaan->nama_perusahaan }}</span>
                        </div>
                        @endif

                        <!-- Deskripsi -->
                        <p class="text-sm text-gray-600 mb-4 line-clamp-3 flex-grow">
                            {{ $kerjasama->deskripsi }}
                        </p>

                        <!-- Divider -->
                        <div class="border-t border-gray-200 my-4"></div>

                        <!-- Info Grid -->
                        <div class="space-y-2 mb-4">
                            <!-- Tanggal Mulai -->
                            <div class="flex items-start text-sm text-gray-600">
                                <i class="fas fa-calendar-start text-blue-500 w-4 mr-2 mt-0.5 flex-shrink-0"></i>
                                <div>
                                    <p class="font-semibold text-gray-700">Mulai</p>
                                    <p>{{ \Carbon\Carbon::parse($kerjasama->tanggal_mulai)->format('d M Y') }}</p>
                                </div>
                            </div>

                            <!-- Tanggal Berakhir -->
                            <div class="flex items-start text-sm text-gray-600">
                                <i class="fas fa-calendar-end text-blue-500 w-4 mr-2 mt-0.5 flex-shrink-0"></i>
                                <div>
                                    <p class="font-semibold text-gray-700">Berakhir</p>
                                    <p>{{ \Carbon\Carbon::parse($kerjasama->tanggal_berakhir)->format('d M Y') }}</p>
                                </div>
                            </div>

                            <!-- Nilai Kontrak -->
                            @if($kerjasama->nilai_kontrak)
                            <div class="flex items-start text-sm text-gray-600">
                                <i class="fas fa-money-bill text-green-500 w-4 mr-2 mt-0.5 flex-shrink-0"></i>
                                <div>
                                    <p class="font-semibold text-gray-700">Nilai Kontrak</p>
                                    <p>Rp {{ number_format($kerjasama->nilai_kontrak, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            @endif

                            <!-- PIC Sekolah -->
                            @if($kerjasama->pic_sekolah)
                            <div class="flex items-start text-sm text-gray-600">
                                <i class="fas fa-user-tie text-purple-500 w-4 mr-2 mt-0.5 flex-shrink-0"></i>
                                <div>
                                    <p class="font-semibold text-gray-700">PIC Sekolah</p>
                                    <p>{{ $kerjasama->pic_sekolah }}</p>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Button -->
                        <a href="{{ route('show.kerjasama', $kerjasama->id) }}"
                            class="w-full text-center bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-2.5 rounded-lg font-semibold hover:shadow-lg hover:scale-105 transition-all duration-300 mt-auto">
                            Lihat Detail Lengkap
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="flex justify-center">
                {{ $kerjasamaTerbaru->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <i class="fas fa-handshake text-gray-300 text-6xl mb-4"></i>
                <h3 class="text-2xl font-bold text-gray-800 mb-2">Tidak Ada Bentuk Kerjasama</h3>
                <p class="text-gray-600 mb-6">Belum ada bentuk kerjasama yang tersedia saat ini</p>
                <a href="{{ route('index.kerjasama') }}" class="inline-flex items-center space-x-2 bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-all">
                    <i class="fas fa-redo"></i>
                    <span>Kembali</span>
                </a>
            </div>
        @endif
    </div>
</section>

@endsection

@push('styles')
<style>
    .pagination {
        @apply flex justify-center gap-2 flex-wrap;
    }

    .pagination a, .pagination span {
        @apply px-4 py-2 rounded-lg transition-all;
    }

    .pagination a {
        @apply bg-gray-100 text-gray-700 hover:bg-blue-600 hover:text-white;
    }

    .pagination .active span {
        @apply bg-blue-600 text-white;
    }

    .pagination .disabled span {
        @apply bg-gray-100 text-gray-500 cursor-not-allowed;
    }
</style>
@endpush
