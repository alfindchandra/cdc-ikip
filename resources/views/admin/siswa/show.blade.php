
@extends('layouts.app')

@section('title', 'Detail Siswa')
@section('page-title', 'Detail Data Siswa')

@section('content')
<div class="space-y-6">
    <!-- Profile Header -->
    <div class="card">
        <div class="card-body">
            <div class="flex items-start justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-20 h-20 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 text-3xl font-semibold">
                        {{ substr($siswa->user->name, 0, 1) }}
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $siswa->user->name }}</h2>
                        <p class="text-gray-600">NIS: {{ $siswa->nis }}</p>
                        <div class="flex items-center space-x-3 mt-2">
                            <span class="badge badge-primary">{{ $siswa->fakultas }}</span>
                            <span class="badge badge-info">{{ $siswa->program_studi }}</span>
                            <span class="badge badge-{{ $siswa->status == 'aktif' ? 'success' : 'gray' }}">
                                {{ ucfirst($siswa->status) }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.siswa.edit', $siswa->id) }}" class="btn btn-secondary">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit
                    </a>
                    <a href="{{ route('admin.siswa.index') }}" class="btn btn-outline">Kembali</a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Data Pribadi -->
        <div class="lg:col-span-1 space-y-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-gray-900">Data Pribadi</h3>
                </div>
                <div class="card-body space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">NISN</p>
                        <p class="font-medium text-gray-900">{{ $siswa->nisn ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Tempat, Tanggal Lahir</p>
                        <p class="font-medium text-gray-900">
                            {{ $siswa->tempat_lahir ?? '-' }}, 
                            {{ $siswa->tanggal_lahir ? $siswa->tanggal_lahir->format('d F Y') : '-' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Jenis Kelamin</p>
                        <p class="font-medium text-gray-900">{{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Agama</p>
                        <p class="font-medium text-gray-900">{{ $siswa->agama ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">No. Telepon</p>
                        <p class="font-medium text-gray-900">{{ $siswa->no_telp ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="font-medium text-gray-900">{{ $siswa->user->email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Alamat</p>
                        <p class="font-medium text-gray-900">{{ $siswa->alamat ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-gray-900">Data Orang Tua</h3>
                </div>
                <div class="card-body space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Nama</p>
                        <p class="font-medium text-gray-900">{{ $siswa->nama_ortu ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Pekerjaan</p>
                        <p class="font-medium text-gray-900">{{ $siswa->pekerjaan_ortu ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">No. Telepon</p>
                        <p class="font-medium text-gray-900">{{ $siswa->no_telp_ortu ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Aktivitas -->
        <div class="lg:col-span-2 space-y-6">
            <!-- PKL -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-gray-900">Riwayat PKL</h3>
                </div>
                <div class="card-body">
                    @forelse($siswa->pkl as $pkl)
                    <div class="border-l-4 border-blue-500 pl-4 py-3 mb-4 last:mb-0">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $pkl->perusahaan->nama_perusahaan }}</p>
                                <p class="text-sm text-gray-600 mt-1">{{ $pkl->posisi ?? 'Peserta PKL' }}</p>
                                <p class="text-sm text-gray-500 mt-1">
                                    {{ $pkl->tanggal_mulai->format('d M Y') }} - {{ $pkl->tanggal_selesai->format('d M Y') }}
                                </p>
                            </div>
                            <span class="badge 
                                @if($pkl->status == 'selesai') badge-success
                                @elseif($pkl->status == 'berlangsung') badge-info
                                @elseif($pkl->status == 'diterima') badge-primary
                                @else badge-warning
                                @endif">
                                {{ ucfirst($pkl->status) }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-gray-500 py-8">Belum ada riwayat PKL</p>
                    @endforelse
                </div>
            </div>

            <!-- Lamaran -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-gray-900">Riwayat Lamaran</h3>
                </div>
                <div class="card-body">
                    @forelse($siswa->lamaran as $lamaran)
                    <div class="border-l-4 border-purple-500 pl-4 py-3 mb-4 last:mb-0">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $lamaran->lowongan->judul }}</p>
                                <p class="text-sm text-gray-600 mt-1">{{ $lamaran->lowongan->perusahaan->nama_perusahaan }}</p>
                                <p class="text-sm text-gray-500 mt-1">{{ $lamaran->tanggal_melamar->format('d M Y H:i') }}</p>
                            </div>
                            <span class="badge 
                                @if($lamaran->status == 'diterima') badge-success
                                @elseif($lamaran->status == 'ditolak') badge-danger
                                @elseif($lamaran->status == 'diproses') badge-info
                                @else badge-warning
                                @endif">
                                {{ ucfirst($lamaran->status) }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-gray-500 py-8">Belum ada riwayat lamaran</p>
                    @endforelse
                </div>
            </div>

            <!-- Pelatihan -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-gray-900">Pelatihan yang Diikuti</h3>
                </div>
                <div class="card-body">
                    @forelse($siswa->pelatihan as $pelatihan)
                    <div class="border-l-4 border-green-500 pl-4 py-3 mb-4 last:mb-0">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $pelatihan->judul }}</p>
                                <p class="text-sm text-gray-600 mt-1">{{ ucfirst(str_replace('_', ' ', $pelatihan->jenis)) }}</p>
                                <p class="text-sm text-gray-500 mt-1">
                                    {{ $pelatihan->tanggal_mulai->format('d M Y') }}
                                </p>
                            </div>
                            <div class="text-right">
                                <span class="badge badge-{{ $pelatihan->pivot->status_pendaftaran == 'diterima' ? 'success' : 'warning' }}">
                                    {{ ucfirst($pelatihan->pivot->status_pendaftaran) }}
                                </span>
                                @if($pelatihan->pivot->nilai)
                                <p class="text-sm text-gray-600 mt-2">Nilai: <span class="font-semibold">{{ $pelatihan->pivot->nilai }}</span></p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-gray-500 py-8">Belum mengikuti pelatihan</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection