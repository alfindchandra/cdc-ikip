@extends('layouts.app')

@section('title', $pelatihan->judul)
@section('page-title', 'Detail Pelatihan')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Back Button -->
    <a href="{{ route('mahasiswa.pelatihan.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali ke Daftar Pelatihan
    </a>

    <!-- Header Image -->
    @if($pelatihan->thumbnail)
    <div class="card overflow-hidden">
        <img src="{{ Storage::url($pelatihan->thumbnail) }}" alt="{{ $pelatihan->judul }}" class="w-full h-64 object-cover">
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <div class="card">
                <div class="card-body">
                    <!-- Title & Badges -->
                    <div class="mb-6">
                        <div class="flex items-center space-x-3 mb-3">
                            <span class="badge badge-primary text-sm px-3 py-1">
                                {{ ucfirst(str_replace('_', ' ', $pelatihan->jenis)) }}
                            </span>
                            @if($pelatihan->biaya == 0)
                            <span class="badge badge-success text-sm px-3 py-1">GRATIS</span>
                            @endif
                            @if($pelatihan->sertifikat)
                            <span class="badge badge-warning text-sm px-3 py-1">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                </svg>
                                Bersertifikat
                            </span>
                            @endif
                        </div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $pelatihan->judul }}</h1>
                    </div>

                    <!-- Deskripsi -->
                    <div class="mb-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-3">Tentang Pelatihan</h2>
                        <div class="prose max-w-none text-gray-700">
                            {!! nl2br(e($pelatihan->deskripsi)) !!}
                        </div>
                    </div>

                    <!-- Materi -->
                    @if($pelatihan->materi)
                    <div class="mb-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-3">Materi Pelatihan</h2>
                        <div class="prose max-w-none text-gray-700">
                            {!! nl2br(e($pelatihan->materi)) !!}
                        </div>
                    </div>
                    @endif

                    <!-- Persyaratan -->
                    @if($pelatihan->persyaratan)
                    <div class="mb-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-3">Persyaratan</h2>
                        <div class="prose max-w-none text-gray-700">
                            {!! nl2br(e($pelatihan->persyaratan)) !!}
                        </div>
                    </div>
                    @endif

                    <!-- Fasilitas -->
                    @if($pelatihan->fasilitas)
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 mb-3">Fasilitas</h2>
                        <div class="prose max-w-none text-gray-700">
                            {!! nl2br(e($pelatihan->fasilitas)) !!}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Registration Card -->
            <div class="card bg-blue-50 border-blue-200">
                <div class="card-body">
                    @php
                        $pendaftaran = auth()->user()->mahasiswa->pelatihan()->where('pelatihan_id', $pelatihan->id)->first();
                    @endphp

                    @if($pendaftaran)
                        @php
                            $statusPendaftaran = $pendaftaran->pivot->status_pendaftaran;
                        @endphp
                        
                        <div class="text-center mb-4">
                            @if($statusPendaftaran == 'diterima')
                            <svg class="w-12 h-12 mx-auto mb-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm font-medium text-green-900">Pendaftaran Diterima</p>
                            @elseif($statusPendaftaran == 'pending')
                            <svg class="w-12 h-12 mx-auto mb-3 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm font-medium text-yellow-900">Menunggu Konfirmasi</p>
                            @else
                            <svg class="w-12 h-12 mx-auto mb-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm font-medium text-red-900">Pendaftaran Ditolak</p>
                            @endif
                        </div>

                        @if($pendaftaran->pivot->nilai)
                        <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg text-center">
                            <p class="text-sm text-green-800 mb-1">Nilai Anda</p>
                            <p class="text-3xl font-bold text-green-600">{{ $pendaftaran->pivot->nilai }}</p>
                        </div>
                        @endif

                        @if($pendaftaran->pivot->sertifikat)
                        <a href="{{ Storage::url($pendaftaran->pivot->sertifikat) }}" 
                           target="_blank"
                           class="btn btn-success w-full mb-3">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Download Sertifikat
                        </a>
                        @endif

                        @if($statusPendaftaran == 'pending' || $statusPendaftaran == 'ditolak')
                        <form action="{{ route('mahasiswa.pelatihan.batal', $pelatihan->id) }}" 
                              method="POST"
                              onsubmit="return confirm('Yakin ingin membatalkan pendaftaran?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline w-full text-red-600 border-red-300 hover:bg-red-50">
                                Batalkan Pendaftaran
                            </button>
                        </form>
                        @endif
                    @elseif($pelatihan->tanggal_mulai < now())
                        <div class="text-center">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm font-medium text-gray-900">Pelatihan Sudah Dimulai</p>
                            <p class="text-xs text-gray-500 mt-1">Pendaftaran telah ditutup</p>
                        </div>
                    @elseif($pelatihan->kuota && $pelatihan->jumlah_peserta >= $pelatihan->kuota)
                        <div class="text-center">
                            <svg class="w-12 h-12 mx-auto mb-3 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <p class="text-sm font-medium text-gray-900">Kuota Penuh</p>
                            <p class="text-xs text-gray-500 mt-1">Semua slot telah terisi</p>
                        </div>
                    @else
                        <form action="{{ route('mahasiswa.pelatihan.daftar', $pelatihan->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary w-full py-3 mb-3">
                                <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                Daftar Sekarang
                            </button>
                        </form>
                        
                        @if($pelatihan->biaya > 0)
                        <p class="text-center text-xl font-bold text-gray-900">
                            Rp {{ number_format($pelatihan->biaya, 0, ',', '.') }}
                        </p>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Info Card -->
            <div class="card">
                <div class="card-body">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Informasi Pelatihan</h3>
                    <div class="space-y-4">
                        @if($pelatihan->instruktur)
                        <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <div>
                                <p class="text-sm text-gray-600">Instruktur</p>
                                <p class="font-medium text-gray-900">{{ $pelatihan->instruktur }}</p>
                            </div>
                        </div>
                        @endif

                        <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <div>
                                <p class="text-sm text-gray-600">Tanggal Pelaksanaan</p>
                                <p class="font-medium text-gray-900">{{ $pelatihan->tanggal_mulai->format('d F Y, H:i') }}</p>
                                @if($pelatihan->tanggal_selesai)
                                <p class="text-sm text-gray-500">s/d {{ $pelatihan->tanggal_selesai->format('d F Y, H:i') }}</p>
                                @endif
                            </div>
                        </div>

                        @if($pelatihan->tempat)
                        <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            </svg>
                            <div>
                                <p class="text-sm text-gray-600">Tempat</p>
                                <p class="font-medium text-gray-900">{{ $pelatihan->tempat }}</p>
                            </div>
                        </div>
                        @endif

                        @if($pelatihan->durasi)
                        <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <p class="text-sm text-gray-600">Durasi</p>
                                <p class="font-medium text-gray-900">{{ $pelatihan->durasi }}</p>
                            </div>
                        </div>
                        @endif

                        <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <div>
                                <p class="text-sm text-gray-600">Peserta Terdaftar</p>
                                <p class="font-medium text-gray-900">
                                    {{ $pelatihan->jumlah_peserta }} / {{ $pelatihan->kuota ?? '∞' }} peserta
                                </p>
                                @if($pelatihan->kuota)
                                <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                                    <div class="bg-blue-600 h-2 rounded-full" 
                                         style="width: {{ min(100, ($pelatihan->jumlah_peserta / $pelatihan->kuota) * 100) }}%">
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection