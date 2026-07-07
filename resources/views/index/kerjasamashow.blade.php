@extends('layouts.index')
@section('title', $kerjasama->judul)
@section('content')

<!-- Breadcrumb -->
<section class="py-6 bg-gray-50 border-b border-gray-200">
    <div class="container mx-auto px-4">
        <div class="flex items-center space-x-2 text-sm">
            <a href="{{ route('welcome') }}" class="text-blue-600 hover:text-blue-700 font-semibold">
                <i class="fas fa-home mr-1"></i>Beranda
            </a>
            <i class="fas fa-chevron-right text-gray-400"></i>
            <a href="{{ route('index.kerjasama') }}" class="text-blue-600 hover:text-blue-700 font-semibold">
                <i class="fas fa-handshake mr-1"></i>Bentuk Kerjasama
            </a>
            <i class="fas fa-chevron-right text-gray-400"></i>
            <span class="text-gray-600">{{ $kerjasama->judul }}</span>
        </div>
    </div>
</section>

<!-- Hero with Judul -->
<section class="py-12 bg-gradient-to-r from-blue-600 to-indigo-600">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl">
            <!-- Badge -->
            <div class="flex items-center gap-2 mb-4">
                <span class="px-4 py-2 bg-blue-500 text-white rounded-full text-sm font-semibold">
                    {{ ucfirst(str_replace('_', ' ', $kerjasama->jenis_kerjasama)) }}
                </span>
                <span class="px-4 py-2 bg-green-500 text-white rounded-full text-sm font-semibold">
                    {{ ucfirst($kerjasama->status) }}
                </span>
            </div>

            <!-- Judul -->
            <h1 class="text-4xl font-bold text-white mb-3">{{ $kerjasama->judul }}</h1>

            <!-- Meta Info -->
            <div class="flex flex-col sm:flex-row gap-6 text-blue-100">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-calendar w-5"></i>
                    <span>Dibuat {{ $kerjasama->created_at->diffForHumans() }}</span>
                </div>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-building w-5"></i>
                    @if($kerjasama->perusahaan)
                    <span>{{ $kerjasama->perusahaan->nama_perusahaan }}</span>
                    @endif
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
                        <i class="fas fa-file-alt text-blue-600 mr-3"></i>Deskripsi
                    </h2>
                    <div class="prose prose-sm max-w-none text-gray-700 leading-relaxed">
                        {{ nl2br(e($kerjasama->deskripsi)) }}
                    </div>
                </div>

                <!-- Detail Jadwal -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-6">
                        <h2 class="text-xl font-bold text-white flex items-center">
                            <i class="fas fa-calendar-alt mr-3"></i>Jadwal Kerjasama
                        </h2>
                    </div>
                    <div class="p-8">
                        <div class="grid md:grid-cols-2 gap-8">
                            <!-- Tanggal Mulai -->
                            <div>
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-calendar-check text-green-500 text-lg mr-3"></i>
                                    <p class="text-sm font-semibold text-gray-600">TANGGAL MULAI</p>
                                </div>
                                <p class="text-lg font-bold text-gray-800 ml-8">
                                    {{ \Carbon\Carbon::parse($kerjasama->tanggal_mulai)->format('d F Y') }}
                                </p>
                            </div>

                            <!-- Tanggal Berakhir -->
                            <div>
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-calendar-times text-red-500 text-lg mr-3"></i>
                                    <p class="text-sm font-semibold text-gray-600">TANGGAL BERAKHIR</p>
                                </div>
                                <p class="text-lg font-bold text-gray-800 ml-8">
                                    {{ \Carbon\Carbon::parse($kerjasama->tanggal_berakhir)->format('d F Y') }}
                                </p>
                            </div>

                            <!-- Durasi -->
                            <div>
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-hourglass-end text-blue-500 text-lg mr-3"></i>
                                    <p class="text-sm font-semibold text-gray-600">DURASI</p>
                                </div>
                                <p class="text-lg font-bold text-gray-800 ml-8">
                                    {{ \Carbon\Carbon::parse($kerjasama->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($kerjasama->tanggal_berakhir)) }} Hari
                                </p>
                            </div>

                            <!-- Status Berlaku -->
                            <div>
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-check-circle text-blue-500 text-lg mr-3"></i>
                                    <p class="text-sm font-semibold text-gray-600">STATUS SAAT INI</p>
                                </div>
                                <p class="text-lg font-bold text-gray-800 ml-8">
                                    @if(\Carbon\Carbon::parse($kerjasama->tanggal_berakhir)->isFuture())
                                        <span class="text-green-600">Berlaku</span>
                                    @else
                                        <span class="text-red-600">Berakhir</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detail Finansial -->
                @if($kerjasama->nilai_kontrak || $kerjasama->catatan)
                <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
                    <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-8 py-6">
                        <h2 class="text-xl font-bold text-white flex items-center">
                            <i class="fas fa-money-bill-wave mr-3"></i>Informasi Finansial
                        </h2>
                    </div>
                    <div class="p-8">
                        @if($kerjasama->nilai_kontrak)
                        <div class="mb-6 pb-6 border-b border-gray-200 last:border-b-0 last:mb-0 last:pb-0">
                            <p class="text-sm font-semibold text-gray-600 mb-2">NILAI KONTRAK</p>
                            <p class="text-2xl font-bold text-green-600">
                                Rp {{ number_format($kerjasama->nilai_kontrak, 0, ',', '.') }}
                            </p>
                        </div>
                        @endif

                        @if($kerjasama->catatan)
                        <div>
                            <p class="text-sm font-semibold text-gray-600 mb-2">CATATAN</p>
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-gray-700">
                                {{ nl2br(e($kerjasama->catatan)) }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- PIC & Dokumen -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-8 py-6">
                        <h2 class="text-xl font-bold text-white flex items-center">
                            <i class="fas fa-users mr-3"></i>Penanggung Jawab & Dokumen
                        </h2>
                    </div>
                    <div class="p-8">
                        <div class="grid md:grid-cols-2 gap-8">
                            <!-- Pic lembaga/bidang -->
                            @if($kerjasama->pic_sekolah)
                            <div>
                                <p class="text-sm font-semibold text-gray-600 mb-2">PIC LEMBAGA/BIDANG</p>
                                <p class="text-lg font-bold text-gray-800">{{ $kerjasama->pic_sekolah }}</p>
                            </div>
                            @endif

                            <!-- PIC Industri -->
                            @if($kerjasama->pic_industri)
                            <div>
                                <p class="text-sm font-semibold text-gray-600 mb-2">PIC INDUSTRI</p>
                                <p class="text-lg font-bold text-gray-800">{{ $kerjasama->pic_industri }}</p>
                            </div>
                            @endif
                        </div>

                        <!-- Dokumen MOU -->
                        @if($kerjasama->dokumen_mou)
                        <div class="mt-8 pt-8 border-t border-gray-200">
                            <p class="text-sm font-semibold text-gray-600 mb-3">DOKUMEN MOU</p>
                            <a href="{{ asset('storage/' . $kerjasama->dokumen_mou) }}" 
                               target="_blank" 
                               class="inline-flex items-center space-x-2 bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-all">
                                <i class="fas fa-download"></i>
                                <span>Download Dokumen MOU</span>
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Sidebar -->
            <div class="lg:col-span-1">
                <!-- Informasi Perusahaan -->
                @if($kerjasama->perusahaan)
                <div class="bg-white rounded-xl shadow-md overflow-hidden sticky top-6 mb-8">
                    <div class="h-2 bg-gradient-to-r from-blue-500 to-indigo-500"></div>

                    <div class="p-8 text-center">
                        <!-- Logo/Avatar -->
                        <div class="mb-6">
                            @if($kerjasama->perusahaan->user && $kerjasama->perusahaan->user->avatar)
                                <div class="w-24 h-24 mx-auto rounded-full overflow-hidden border-4 border-blue-100">
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($kerjasama->perusahaan->user->avatar) }}" 
                                         alt="{{ $kerjasama->perusahaan->nama_perusahaan }}" 
                                         class="w-full h-full object-cover">
                                </div>
                            @else
                                <div class="w-24 h-24 mx-auto rounded-full bg-gradient-to-r from-blue-500 to-indigo-500 flex items-center justify-center">
                                    <i class="fas fa-building text-white text-3xl"></i>
                                </div>
                            @endif
                        </div>

                        <!-- Nama Perusahaan -->
                        <h3 class="text-xl font-bold text-gray-800 mb-3">
                            {{ $kerjasama->perusahaan->nama_perusahaan }}
                        </h3>

                        <!-- Deskripsi Perusahaan -->
                        @if($kerjasama->perusahaan->deskripsi)
                        <p class="text-sm text-gray-600 mb-4 line-clamp-3">
                            {{ $kerjasama->perusahaan->deskripsi }}
                        </p>
                        @endif

                        <!-- Info Perusahaan -->
                        <div class="space-y-3 py-4 border-y border-gray-200">
                            @if($kerjasama->perusahaan->alamat)
                            <div class="flex items-start text-sm text-gray-600">
                                <i class="fas fa-map-marker-alt text-blue-500 mr-2 mt-1 flex-shrink-0"></i>
                                <span>{{ $kerjasama->perusahaan->alamat }}</span>
                            </div>
                            @endif

                            @if($kerjasama->perusahaan->no_telepon)
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-phone text-blue-500 mr-2"></i>
                                <a href="tel:{{ $kerjasama->perusahaan->no_telepon }}" class="hover:text-blue-600">
                                    {{ $kerjasama->perusahaan->no_telepon }}
                                </a>
                            </div>
                            @endif

                            @if($kerjasama->perusahaan->email)
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-envelope text-blue-500 mr-2"></i>
                                <a href="mailto:{{ $kerjasama->perusahaan->email }}" class="hover:text-blue-600 truncate">
                                    {{ $kerjasama->perusahaan->email }}
                                </a>
                            </div>
                            @endif
                        </div>

                        <!-- Tombol -->
                        <div class="mt-6 space-y-3">
                            <a href="{{ route('index.kerjasama') }}" 
                               class="block w-full bg-blue-600 text-white py-2.5 rounded-lg font-semibold hover:bg-blue-700 transition-all">
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
