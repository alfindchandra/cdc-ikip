@extends('layouts.index')
@section('title', 'Lowongan Pekerjaan')
@section('content')

<!-- Hero Section -->
<section class="py-12 bg-gradient-to-r from-purple-600 to-blue-600">
    <div class="container mx-auto px-4">
        <div class="text-center">
            <h1 class="text-4xl font-bold text-white mb-3">Lowongan Pekerjaan</h1>
            <p class="text-purple-100 text-lg">Temukan peluang karir impianmu dari perusahaan terkemuka</p>
        </div>
    </div>
</section>

<!-- Search & Filter Section -->
<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <form action="{{ route('index.lowongan') }}" method="GET" class="bg-white rounded-xl shadow-md p-8">
            <div class="grid md:grid-cols-4 gap-6 mb-6">
                <!-- Kata Kunci -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-search mr-2 text-purple-600"></i>Kata Kunci
                    </label>
                    <input type="text" name="keyword" value="{{ request('keyword') }}"
                        placeholder="Posisi, perusahaan..."
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all">
                </div>

                <!-- Lokasi -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-map-marker-alt mr-2 text-purple-600"></i>Lokasi
                    </label>
                    <select name="lokasi"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all appearance-none bg-white cursor-pointer">
                        <option value="">Semua Lokasi</option>
                        <option value="Jakarta" {{ request('lokasi') == 'Jakarta' ? 'selected' : '' }}>Jakarta</option>
                        <option value="Bandung" {{ request('lokasi') == 'Bandung' ? 'selected' : '' }}>Bandung</option>
                        <option value="Surabaya" {{ request('lokasi') == 'Surabaya' ? 'selected' : '' }}>Surabaya
                        </option>
                        <option value="Medan" {{ request('lokasi') == 'Medan' ? 'selected' : '' }}>Medan</option>
                        <option value="Semarang" {{ request('lokasi') == 'Semarang' ? 'selected' : '' }}>Semarang
                        </option>
                        <option value="Yogyakarta" {{ request('lokasi') == 'Yogyakarta' ? 'selected' : '' }}>Yogyakarta
                        </option>
                    </select>
                </div>

                <!-- Tipe Pekerjaan -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-briefcase mr-2 text-purple-600"></i>Tipe Pekerjaan
                    </label>
                    <select name="tipe"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all appearance-none bg-white cursor-pointer">
                        <option value="">Semua Tipe</option>
                        <option value="full_time" {{ request('tipe') == 'full_time' ? 'selected' : '' }}>Full Time
                        </option>
                        <option value="part_time" {{ request('tipe') == 'part_time' ? 'selected' : '' }}>Part Time
                        </option>
                        <option value="kontrak" {{ request('tipe') == 'kontrak' ? 'selected' : '' }}>Kontrak</option>
                        <option value="magang" {{ request('tipe') == 'magang' ? 'selected' : '' }}>Magang</option>
                    </select>
                </div>

                <!-- Tombol -->
                <div class="flex items-end gap-3">
                    <button type="submit"
                        class="flex-1 bg-gradient-to-r from-purple-600 to-blue-600 text-white py-3 px-6 rounded-lg font-semibold hover:shadow-lg hover:scale-105 transition-all flex items-center justify-center space-x-2">
                        <i class="fas fa-filter"></i>
                        <span>Cari</span>
                    </button>

                    @if(request()->hasAny(['keyword', 'lokasi', 'tipe']))
                    <a href="{{ route('index.lowongan') }}"
                        class="bg-gray-200 text-gray-700 py-3 px-6 rounded-lg font-semibold hover:bg-gray-300 transition-all flex items-center space-x-2">
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


        @if($lowonganTerbaru->count() > 0)
        <!-- Grid Lowongan -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
            @foreach($lowonganTerbaru as $lowongan)
            <a href="{{ route('lowongan.show', $lowongan->id) }}"
                class="group bg-gradient-to-br from-gray-50 to-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 hover:-translate-y-1 flex flex-col h-full">
                <!-- Header Color Bar -->
                <div class="h-2 bg-gradient-to-r from-purple-500 to-blue-500"></div>

                <div class="p-6 flex flex-col flex-grow">
                    <!-- Badges -->
                    <div class="flex items-center justify-between mb-4 gap-2 flex-wrap">
                        <span
                            class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-semibold whitespace-nowrap">
                            {{ ucfirst(str_replace('_', ' ', $lowongan->tipe_pekerjaan)) }}
                        </span>
                        <span
                            class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold whitespace-nowrap">
                            {{ ucfirst($lowongan->status) }}
                        </span>
                    </div>

                    <!-- Posisi -->
                    <h3
                        class="text-lg font-bold text-gray-800 mb-1 line-clamp-2 group-hover:text-purple-600 transition-colors">
                        {{ $lowongan->posisi }}
                    </h3>

                    <!-- Perusahaan -->
                    @if($lowongan->perusahaan)
                    <div class="flex items-center mb-3 text-sm text-gray-600">
                        <i class="fas fa-building text-purple-500 mr-2 w-4"></i>
                        <span class="font-semibold line-clamp-1">{{ $lowongan->perusahaan->nama_perusahaan }}</span>
                    </div>
                    @endif

                    <!-- Gaji -->
                    <p class="text-sm font-semibold text-green-600 mb-4">
                        @if($lowongan->gaji_min && $lowongan->gaji_max)
                        Rp {{ number_format($lowongan->gaji_min, 0, ',', '.') }} - Rp
                        {{ number_format($lowongan->gaji_max, 0, ',', '.') }}
                        @elseif($lowongan->gaji_max)
                        Rp {{ number_format($lowongan->gaji_max, 0, ',', '.') }}
                        @else
                        Negosiasi
                        @endif
                    </p>

                    <!-- Deskripsi -->
                    <p class="text-sm text-gray-600 mb-4 line-clamp-3 flex-grow">
                        {{ $lowongan->deskripsi }}
                    </p>

                    <!-- Divider -->
                    <div class="border-t border-gray-200 my-4"></div>

                    <!-- Info Footer -->
                    <div class="space-y-2 text-xs text-gray-600">
                        <div class="flex items-center">
                            <i class="fas fa-map-marker-alt text-purple-500 mr-2 w-3"></i>
                            <span>{{ $lowongan->lokasi ?? 'Tidak Diketahui' }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-calendar text-purple-500 mr-2 w-3"></i>
                            <span>{{ \Carbon\Carbon::parse($lowongan->tanggal_berakhir)->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>


        @else
        <!-- Empty State -->
        <div class="text-center py-16">
            <i class="fas fa-briefcase text-gray-300 text-6xl mb-4"></i>
            <h3 class="text-2xl font-bold text-gray-800 mb-2">Tidak Ada Lowongan</h3>
            <p class="text-gray-600 mb-6">Belum ada lowongan pekerjaan yang sesuai dengan filter Anda</p>
            <a href="{{ route('index.lowongan') }}"
                class="inline-flex items-center space-x-2 bg-purple-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-purple-700 transition-all">
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

.pagination a,
.pagination span {
    @apply px-4 py-2 rounded-lg transition-all;
}

.pagination a {
    @apply bg-gray-100 text-gray-700 hover: bg-purple-600 hover:text-white;
}

.pagination .active span {
    @apply bg-purple-600 text-white;
}

.pagination .disabled span {
    @apply bg-gray-100 text-gray-500 cursor-not-allowed;
}
</style>
@endpush