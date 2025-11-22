@extends('layouts.app')

@section('title', 'Detail Siswa')
@section('page-title', 'Detail Data Siswa')

@section('content')
<div class="space-y-8 p-4 sm:p-6 lg:p-8">

    <div class="bg-white shadow-xl rounded-2xl p-6 border-t-4 border-indigo-600">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between space-y-4 sm:space-y-0">
            <div class="flex items-center space-x-6">
                <div class="w-16 h-16 sm:w-20 sm:h-20 flex-shrink-0 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 text-3xl font-bold ring-4 ring-indigo-50/50">
                    {{ substr($siswa->user->name, 0, 1) }}
                </div>
                <div>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900">{{ $siswa->user->name }}</h2>
                    <p class="text-md text-gray-600 font-mono mt-1">NIM:{{ $siswa->nim }}</p>
                    <div class="flex flex-wrap items-center gap-3 mt-3">
                        <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                            
                        </span>
                        <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            {{ $siswa->ProgramStudi->nama ?? '-' }}
                        </span>
                        <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-semibold
                            @if($siswa->status == 'aktif') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-600
                            @endif">
                            Status: {{ ucfirst($siswa->status) }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="flex space-x-3 w-full sm:w-auto mr-5">
                <a href="{{ route('admin.siswa.edit', $siswa->id) }}" class="btn btn-secondary flex-1 sm:flex-none bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-150 flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Edit Data
                </a>
                <a href="{{ route('admin.siswa.index') }}" class="btn btn-outline flex-1 sm:flex-none border border-gray-300 text-gray-700 hover:bg-gray-100 font-medium py-2 px-4 rounded-lg transition duration-150 flex items-center justify-center">Kembali</a>
            </div>
        </div>
    </div>

    ---

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-1 space-y-6">
            
            <div class="card bg-white shadow-lg rounded-xl overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Data Pribadi
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    @php
                        $data_pribadi = [
                            ['label' => 'NIM', 'value' => $siswa->nim ?? '-'],
                            ['label' => 'TTL', 'value' => ($siswa->tempat_lahir ?? '-') . ', ' . ($siswa->tanggal_lahir ? $siswa->tanggal_lahir->format('d F Y') : '-')],
                            ['label' => 'Jenis Kelamin', 'value' => $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : ($siswa->jenis_kelamin == 'P' ? 'Perempuan' : '-')],
                            ['label' => 'Agama', 'value' => $siswa->agama ?? '-'],
                            ['label' => 'No. Telepon', 'value' => $siswa->no_telp ?? '-'],
                            ['label' => 'Email', 'value' => $siswa->user->email],
                            ['label' => 'Alamat', 'value' => $siswa->alamat ?? '-'],
                        ];
                    @endphp
                    @foreach($data_pribadi as $data)
                    <div class="pb-1 border-b border-gray-100">
                        <p class="text-sm font-medium text-gray-500">{{ $data['label'] }}</p>
                        <p class="text-md font-semibold text-gray-900 mt-0.5">{{ $data['value'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="card bg-white shadow-lg rounded-xl overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        Data Orang Tua
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    @php
                        $data_ortu = [
                            ['label' => 'Nama', 'value' => $siswa->nama_ortu ?? '-'],
                            ['label' => 'Pekerjaan', 'value' => $siswa->pekerjaan_ortu ?? '-'],
                            ['label' => 'No. Telepon', 'value' => $siswa->no_telp_ortu ?? '-'],
                        ];
                    @endphp
                    @foreach($data_ortu as $data)
                    <div class="pb-1 border-b border-gray-100">
                        <p class="text-sm font-medium text-gray-500">{{ $data['label'] }}</p>
                        <p class="text-md font-semibold text-gray-900 mt-0.5">{{ $data['value'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-6">
            
            <div class="card bg-white shadow-lg rounded-xl overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H2m3 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-2a7 7 0 00-7-7h14a7 7 0 00-7 7v2"/></svg>
                        Riwayat PKL (Praktik Kerja Lapangan)
                    </h3>
                </div>
                <div class="p-6 divide-y divide-gray-100">
                    @forelse($siswa->pkl as $pkl)
                    <div class="py-4 flex items-start justify-between space-x-4">
                        <div class="border-l-4 border-blue-500 pl-4">
                            <p class="font-bold text-lg text-gray-900">{{ $pkl->perusahaan->nama_perusahaan }}</p>
                            <p class="text-sm text-gray-700 mt-1 font-semibold">{{ $pkl->posisi ?? 'Peserta PKL' }}</p>
                            <p class="text-xs text-gray-500 mt-1">
                                <span class="text-blue-600">Periode:</span> {{ $pkl->tanggal_mulai->format('d M Y') }} - {{ $pkl->tanggal_selesai->format('d M Y') }}
                            </p>
                        </div>
                        <span class="flex-shrink-0 inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold h-6
                            @if($pkl->status == 'selesai') bg-green-100 text-green-800
                            @elseif($pkl->status == 'berlangsung') bg-indigo-100 text-indigo-800
                            @elseif($pkl->status == 'diterima') bg-blue-100 text-blue-800
                            @else bg-yellow-100 text-yellow-800
                            @endif">
                            {{ ucfirst($pkl->status) }}
                        </span>
                    </div>
                    @empty
                    <p class="text-center text-gray-500 py-8 italic">Belum ada riwayat Praktik Kerja Lapangan.</p>
                    @endforelse
                </div>
            </div>

            <div class="card bg-white shadow-lg rounded-xl overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v12"/></svg>
                        Riwayat Lamaran Pekerjaan
                    </h3>
                </div>
                <div class="p-6 divide-y divide-gray-100">
                    @forelse($siswa->lamaran as $lamaran)
                    <div class="py-4 flex items-start justify-between space-x-4">
                        <div class="border-l-4 border-purple-500 pl-4">
                            <p class="font-bold text-lg text-gray-900">{{ $lamaran->lowongan->judul }}</p>
                            <p class="text-sm text-purple-600 mt-1 font-semibold">{{ $lamaran->lowongan->perusahaan->nama_perusahaan }}</p>
                            <p class="text-xs text-gray-500 mt-1">
                                <span class="text-purple-600">Tanggal Melamar:</span> {{ $lamaran->tanggal_melamar->format('d M Y H:i') }}
                            </p>
                        </div>
                        <span class="flex-shrink-0 inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold h-6
                            @if($lamaran->status == 'diterima') bg-green-100 text-green-800
                            @elseif($lamaran->status == 'ditolak') bg-red-100 text-red-800
                            @elseif($lamaran->status == 'diproses') bg-blue-100 text-blue-800
                            @else bg-yellow-100 text-yellow-800
                            @endif">
                            {{ ucfirst($lamaran->status) }}
                        </span>
                    </div>
                    @empty
                    <p class="text-center text-gray-500 py-8 italic">Belum ada riwayat lamaran pekerjaan.</p>
                    @endforelse
                </div>
            </div>

            <div class="card bg-white shadow-lg rounded-xl overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.206 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.794 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.794 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.206 18 16.5 18s-3.332.477-4.5 1.253"/></svg>
                        Pelatihan yang Diikuti
                    </h3>
                </div>
                <div class="p-6 divide-y divide-gray-100">
                    @forelse($siswa->pelatihan as $pelatihan)
                    <div class="py-4 flex items-start justify-between space-x-4">
                        <div class="border-l-4 border-green-500 pl-4">
                            <p class="font-bold text-lg text-gray-900">{{ $pelatihan->judul }}</p>
                            <p class="text-sm text-gray-700 mt-1">{{ ucfirst(str_replace('_', ' ', $pelatihan->jenis)) }}</p>
                            <p class="text-xs text-gray-500 mt-1">
                                <span class="text-green-600">Tanggal Mulai:</span> {{ $pelatihan->tanggal_mulai->format('d M Y') }}
                            </p>
                        </div>
                        <div class="flex-shrink-0 text-right space-y-1">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold h-6
                                @if($pelatihan->pivot->status_pendaftaran == 'diterima') bg-green-100 text-green-800
                                @else bg-yellow-100 text-yellow-800
                                @endif">
                                {{ ucfirst($pelatihan->pivot->status_pendaftaran) }}
                            </span>
                            @if($pelatihan->pivot->nilai)
                            <p class="text-sm text-gray-600 pt-1">Nilai: <span class="font-bold text-indigo-600">{{ $pelatihan->pivot->nilai }}</span></p>
                            @endif
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-gray-500 py-8 italic">Belum ada data pelatihan yang diikuti.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection