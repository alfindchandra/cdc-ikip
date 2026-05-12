@extends('layouts.index')
@section('title', $lowongan->posisi)
@section('content')

<!-- Breadcrumb -->
<section class="py-6 bg-gray-50 border-b border-gray-200">
    <div class="container mx-auto px-4">
        <div class="flex items-center space-x-2 text-sm">
            <a href="{{ route('welcome') }}" class="text-purple-600 hover:text-purple-700 font-semibold">
                <i class="fas fa-home mr-1"></i>Beranda
            </a>
            <i class="fas fa-chevron-right text-gray-400"></i>
            <a href="{{ route('index.lowongan') }}" class="text-purple-600 hover:text-purple-700 font-semibold">
                <i class="fas fa-briefcase mr-1"></i>Lowongan
            </a>
            <i class="fas fa-chevron-right text-gray-400"></i>
            <span class="text-gray-600">{{ $lowongan->posisi }}</span>
        </div>
    </div>
</section>

<!-- Hero with Title -->
<section class="py-12 bg-gradient-to-r from-purple-600 to-blue-600">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl">
            <!-- Badge -->
            <div class="flex items-center gap-2 mb-4">
                <span class="px-4 py-2 bg-purple-500 text-white rounded-full text-sm font-semibold">
                    {{ ucfirst(str_replace('_', ' ', $lowongan->tipe_pekerjaan)) }}
                </span>
                <span class="px-4 py-2 bg-green-500 text-white rounded-full text-sm font-semibold">
                    {{ ucfirst($lowongan->status) }}
                </span>
            </div>

            <!-- Posisi -->
            <h1 class="text-4xl font-bold text-white mb-4">{{ $lowongan->posisi }}</h1>

            <!-- Meta Info -->
            <div class="flex flex-col sm:flex-row gap-6 text-purple-100">
                @if($lowongan->perusahaan)
                <div class="flex items-center space-x-2">
                    <i class="fas fa-building w-5"></i>
                    <span>{{ $lowongan->perusahaan->nama_perusahaan }}</span>
                </div>
                @endif
                <div class="flex items-center space-x-2">
                    <i class="fas fa-map-marker-alt w-5"></i>
                    <span>{{ $lowongan->lokasi ?? 'Tidak Diketahui' }}</span>
                </div>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-calendar w-5"></i>
                    <span>Dibuka {{ $lowongan->created_at->diffForHumans() }}</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Left Content (Main) -->
            <div class="lg:col-span-2">
                <!-- Deskripsi Card -->
                <div class="bg-white rounded-xl shadow-md p-8 mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-file-alt text-purple-600 mr-3"></i>Deskripsi Pekerjaan
                    </h2>
                    <div class="prose prose-sm max-w-none text-gray-700 leading-relaxed">
                        {{ nl2br(e($lowongan->deskripsi)) }}
                    </div>
                </div>

                <!-- Persyaratan -->
                @if($lowongan->persyaratan)
                <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
                    <div class="bg-gradient-to-r from-purple-600 to-blue-600 px-8 py-6">
                        <h2 class="text-xl font-bold text-white flex items-center">
                            <i class="fas fa-list-check mr-3"></i>Persyaratan
                        </h2>
                    </div>
                    <div class="p-8">
                        <div class="text-gray-700 leading-relaxed">
                            {{ nl2br(e($lowongan->persyaratan)) }}
                        </div>
                    </div>
                </div>
                @endif

                <!-- Kualifikasi -->
                @if($lowongan->kualifikasi)
                <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-6">
                        <h2 class="text-xl font-bold text-white flex items-center">
                            <i class="fas fa-graduation-cap mr-3"></i>Kualifikasi
                        </h2>
                    </div>
                    <div class="p-8">
                        <div class="text-gray-700 leading-relaxed">
                            {{ nl2br(e($lowongan->kualifikasi)) }}
                        </div>
                    </div>
                </div>
                @endif

                <!-- Detail Pekerjaan -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-8 py-6">
                        <h2 class="text-xl font-bold text-white flex items-center">
                            <i class="fas fa-info-circle mr-3"></i>Detail Pekerjaan
                        </h2>
                    </div>
                    <div class="p-8">
                        <div class="grid md:grid-cols-2 gap-8">
                            <!-- Gaji -->
                            <div>
                                <p class="text-sm font-semibold text-gray-600 mb-2">KISARAN GAJI</p>
                                <p class="text-2xl font-bold text-green-600">
                                    @if($lowongan->gaji_min && $lowongan->gaji_max)
                                        Rp {{ number_format($lowongan->gaji_min, 0, ',', '.') }} - Rp {{ number_format($lowongan->gaji_max, 0, ',', '.') }}
                                    @elseif($lowongan->gaji_max)
                                        Rp {{ number_format($lowongan->gaji_max, 0, ',', '.') }}
                                    @else
                                        Negosiasi
                                    @endif
                                </p>
                            </div>

                            <!-- Lokasi -->
                            <div>
                                <p class="text-sm font-semibold text-gray-600 mb-2">LOKASI KERJA</p>
                                <p class="text-lg font-bold text-gray-800">{{ $lowongan->lokasi ?? 'Tidak Diketahui' }}</p>
                            </div>

                            <!-- Tipe Pekerjaan -->
                            <div>
                                <p class="text-sm font-semibold text-gray-600 mb-2">TIPE PEKERJAAN</p>
                                <p class="text-lg font-bold text-gray-800">{{ ucfirst(str_replace('_', ' ', $lowongan->tipe_pekerjaan)) }}</p>
                            </div>

                            <!-- Tanggal Berakhir -->
                            <div>
                                <p class="text-sm font-semibold text-gray-600 mb-2">BATAS LAMARAN</p>
                                <p class="text-lg font-bold text-gray-800">{{ \Carbon\Carbon::parse($lowongan->tanggal_berakhir)->format('d F Y') }}</p>
                            </div>

                            <!-- Jumlah Posisi -->
                            @if($lowongan->jumlah_posisi)
                            <div>
                                <p class="text-sm font-semibold text-gray-600 mb-2">JUMLAH POSISI</p>
                                <p class="text-lg font-bold text-gray-800">{{ $lowongan->jumlah_posisi }} Orang</p>
                            </div>
                            @endif

                            <!-- Status -->
                            <div>
                                <p class="text-sm font-semibold text-gray-600 mb-2">STATUS LOWONGAN</p>
                                <p class="text-lg font-bold">
                                    @if($lowongan->status == 'aktif')
                                        <span class="text-green-600">Dibuka</span>
                                    @else
                                        <span class="text-red-600">Ditutup</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Sidebar -->
            <div class="lg:col-span-1">
                <!-- Informasi Perusahaan -->
                @if($lowongan->perusahaan)
                <div class="bg-white rounded-xl shadow-md overflow-hidden sticky top-6 mb-8">
                    <div class="h-2 bg-gradient-to-r from-purple-500 to-blue-500"></div>

                    <div class="p-8 text-center">
                        <!-- Logo/Avatar -->
                        <div class="mb-6">
                            @if($lowongan->perusahaan->user && $lowongan->perusahaan->user->avatar)
                                <div class="w-24 h-24 mx-auto rounded-full overflow-hidden border-4 border-purple-100">
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($lowongan->perusahaan->user->avatar) }}" 
                                         alt="{{ $lowongan->perusahaan->nama_perusahaan }}" 
                                         class="w-full h-full object-cover">
                                </div>
                            @else
                                <div class="w-24 h-24 mx-auto rounded-full bg-gradient-to-r from-purple-500 to-blue-500 flex items-center justify-center">
                                    <i class="fas fa-building text-white text-3xl"></i>
                                </div>
                            @endif
                        </div>

                        <!-- Nama Perusahaan -->
                        <h3 class="text-xl font-bold text-gray-800 mb-3">
                            {{ $lowongan->perusahaan->nama_perusahaan }}
                        </h3>

                        <!-- Deskripsi Perusahaan -->
                        @if($lowongan->perusahaan->deskripsi)
                        <p class="text-sm text-gray-600 mb-4 line-clamp-3">
                            {{ $lowongan->perusahaan->deskripsi }}
                        </p>
                        @endif

                        <!-- Info Perusahaan -->
                        <div class="space-y-3 py-4 border-y border-gray-200">
                            @if($lowongan->perusahaan->alamat)
                            <div class="flex items-start text-sm text-gray-600">
                                <i class="fas fa-map-marker-alt text-purple-500 mr-2 mt-1 flex-shrink-0"></i>
                                <span>{{ $lowongan->perusahaan->alamat }}</span>
                            </div>
                            @endif

                            @if($lowongan->perusahaan->no_telepon)
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-phone text-purple-500 mr-2"></i>
                                <a href="tel:{{ $lowongan->perusahaan->no_telepon }}" class="hover:text-purple-600">
                                    {{ $lowongan->perusahaan->no_telepon }}
                                </a>
                            </div>
                            @endif

                            @if($lowongan->perusahaan->email)
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-envelope text-purple-500 mr-2"></i>
                                <a href="mailto:{{ $lowongan->perusahaan->email }}" class="hover:text-purple-600 truncate">
                                    {{ $lowongan->perusahaan->email }}
                                </a>
                            </div>
                            @endif
                        </div>

                        <!-- Tombol CTA -->
                        <div class="mt-6 space-y-3">
                            @if($lowongan->status == 'aktif')
                            <a href="{{ route('mahasiswa.lowongan.show', $lowongan->id) }}?action=apply" 
                               class="block w-full bg-gradient-to-r from-purple-600 to-blue-600 text-white py-3 rounded-lg font-semibold hover:shadow-lg hover:scale-105 transition-all">
                                <i class="fas fa-paper-plane mr-2"></i>Lamar Sekarang
                            </a>
                            @endif

                            <a href="{{ route('index.lowongan') }}" 
                               class="block w-full bg-gray-200 text-gray-700 py-3 rounded-lg font-semibold hover:bg-gray-300 transition-all">
                                Kembali ke Daftar
                            </a>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
    .prose p {
        @apply mb-4;
    }

    .prose strong {
        @apply font-bold text-gray-900;
    }

    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush
