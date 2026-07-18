@extends('layouts.app')

@section('title', 'Detail Mahasiswa')
@section('page-title', 'Detail Data Mahasiswa')

@section('content')
<div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8 space-y-6">

    <!-- Header Card Profile -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 sm:p-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5">
                <!-- Avatar Initial -->
                <div class="w-16 h-16 sm:w-20 sm:h-20 flex-shrink-0 rounded-2xl bg-gradient-to-br from-indigo-50 to-indigo-100/60 text-indigo-600 flex items-center justify-center text-3xl font-extrabold border border-indigo-100">
                    {{ substr($mahasiswa->user->name, 0, 1) }}
                </div>
                
                <!-- Profile Identity -->
                <div class="space-y-1.5">
                    <div class="flex flex-wrap items-center gap-2.5">
                        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">{{ $mahasiswa->user->name }}</h2>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-semibold tracking-wide
                            @if($mahasiswa->status == 'aktif') bg-emerald-50 text-emerald-700 border border-emerald-200/60
                            @elseif($mahasiswa->status == 'lulus') bg-blue-50 text-blue-700 border border-blue-200/60
                            @else bg-slate-50 text-slate-600 border border-slate-200
                            @endif">
                            {{ $mahasiswa->status_text }}
                        </span>
                    </div>
                    
                    <p class="text-sm font-medium text-slate-500 font-mono">NIM {{ $mahasiswa->nim }}</p>
                    
                    <div class="flex flex-wrap items-center gap-2 pt-1">
                        @if(in_array($mahasiswa->tingkat_pendidikan, ['SD', 'SMP']))
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-slate-100 text-slate-700">
                                {{ $mahasiswa->tingkat_pendidikan_label }}
                            </span>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-indigo-50 text-indigo-700">
                                {{ $mahasiswa->asal_sekolah ?? '-' }}
                            </span>
                        @elseif(in_array($mahasiswa->tingkat_pendidikan, ['SMA', 'SMK']))
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-slate-100 text-slate-700">
                                {{ $mahasiswa->tingkat_pendidikan_label }}
                            </span>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-indigo-50 text-indigo-700">
                                {{ $mahasiswa->asal_sekolah ?? '-' }}
                            </span>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200">
                                Jurusan: {{ $mahasiswa->program_studi_id ?? '-' }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-slate-100 text-slate-700">
                                {{ $mahasiswa->tingkat_pendidikan_label }}
                            </span>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-indigo-50 text-indigo-700">
                                Asal: {{ $mahasiswa->asal_sekolah ?? '-' }}
                            </span>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-slate-100 text-slate-700">
                                {{ $mahasiswa->fakultas_id ?? '-' }}
                            </span>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-indigo-50 text-indigo-700">
                                Prodi: {{ $mahasiswa->program_studi_id ?? '-' }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-3 w-full md:w-auto border-t md:border-t-0 pt-4 md:pt-0 border-slate-100">
                <a href="{{ route('admin.mahasiswa.index') }}" class="flex-1 md:flex-none text-center px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition-colors duration-200">
                    Kembali
                </a>
                <a href="{{ route('admin.mahasiswa.edit', $mahasiswa->id) }}" class="flex-1 md:flex-none inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-sm font-semibold text-white shadow-sm shadow-amber-500/10 transition-colors duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Edit Profil
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        
        <!-- Left Column: Personal & Parents Info -->
        <div class="space-y-6 lg:col-span-1">
            
            <!-- Data Pribadi -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2.5">
                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <h3 class="font-bold text-slate-800 text-sm tracking-wide uppercase">Data Pribadi</h3>
                </div>
                <div class="p-6 divide-y divide-slate-100 text-sm">
                    <div class="pb-3">
                        <span class="text-xs font-medium text-slate-400 block mb-0.5">Tempat, Tanggal Lahir</span>
                        <span class="font-medium text-slate-800">{{ $mahasiswa->tempat_lahir ?? '-' }}, {{ $mahasiswa->tanggal_lahir ? $mahasiswa->tanggal_lahir->translatedFormat('d F Y') : '-' }}</span>
                        @if($mahasiswa->umur)
                            <span class="text-xs text-slate-500 font-normal">({{ $mahasiswa->umur }} Tahun)</span>
                        @endif
                    </div>
                    <div class="py-3">
                        <span class="text-xs font-medium text-slate-400 block mb-0.5">Jenis Kelamin</span>
                        <span class="font-medium text-slate-800">{{ $mahasiswa->jenis_kelamin == 'L' ? 'Laki-laki' : ($mahasiswa->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}</span>
                    </div>
                    <div class="py-3">
                        <span class="text-xs font-medium text-slate-400 block mb-0.5">Agama</span>
                        <span class="font-medium text-slate-800">{{ $mahasiswa->agama ?? '-' }}</span>
                    </div>
                    <div class="py-3">
                        <span class="text-xs font-medium text-slate-400 block mb-0.5">Kontak</span>
                        <span class="font-medium text-slate-800 block">{{ $mahasiswa->no_telp ?? '-' }}</span>
                        <span class="text-xs text-slate-500 block mt-0.5">{{ $mahasiswa->user->email }}</span>
                    </div>
                    <div class="pt-3">
                        <span class="text-xs font-medium text-slate-400 block mb-0.5">Alamat</span>
                        <span class="font-medium text-slate-800 leading-relaxed block">{{ $mahasiswa->alamat ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- Data Orang Tua -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2.5">
                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <h3 class="font-bold text-slate-800 text-sm tracking-wide uppercase">Data Orang Tua</h3>
                </div>
                <div class="p-6 divide-y divide-slate-100 text-sm">
                    <div class="pb-3">
                        <span class="text-xs font-medium text-slate-400 block mb-0.5">Nama Wali / Orang Tua</span>
                        <span class="font-medium text-slate-800">{{ $mahasiswa->nama_ortu ?? '-' }}</span>
                    </div>
                    <div class="py-3">
                        <span class="text-xs font-medium text-slate-400 block mb-0.5">Pekerjaan</span>
                        <span class="font-medium text-slate-800">{{ $mahasiswa->pekerjaan_ortu ?? '-' }}</span>
                    </div>
                    <div class="pt-3">
                        <span class="text-xs font-medium text-slate-400 block mb-0.5">No. Telepon</span>
                        <span class="font-medium text-slate-800">{{ $mahasiswa->no_telp_ortu ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Activities, Applications & Training -->
        <div class="space-y-6 lg:col-span-2">
            
            <!-- Pelatihan Terbaru (Highlight Section) -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        <h3 class="font-bold text-slate-800 text-sm tracking-wide uppercase">Pelatihan Terbaru</h3>
                    </div>
                    <a href="{{ route('admin.pelatihan.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 transition-colors">Lihat Semua</a>
                </div>
                <div class="p-6">
                    <div class="grid gap-4">
                        @forelse($mahasiswa->pelatihan()->latest()->take(3)->get() as $pelatihan)
                        <div class="flex items-start gap-4 p-4 rounded-xl border border-slate-100 bg-slate-50/40 hover:bg-slate-50 transition-colors">
                            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-100 flex flex-col items-center justify-center font-bold text-xs p-1 shrink-0">
                                <span class="text-[10px] uppercase tracking-wider text-emerald-500 -mb-0.5">{{ $pelatihan->tanggal_mulai->translatedFormat('M') }}</span>
                                <span class="text-base leading-none">{{ $pelatihan->tanggal_mulai->format('d') }}</span>
                            </div>
                            <div class="flex-1 space-y-1">
                                <h4 class="font-semibold text-slate-800 text-sm leading-snug">{{ $pelatihan->judul }}</h4>
                                <div class="flex flex-wrap items-center gap-2 text-xs">
                                    <span class="text-slate-500 font-medium">{{ ucfirst(str_replace('_', ' ', $pelatihan->jenis)) }}</span>
                                    @if($pelatihan->pivot->nilai)
                                        <span class="inline-flex items-center px-2 py-0.5 bg-amber-50 text-amber-800 border border-amber-200/60 font-semibold rounded text-[11px]">
                                            Nilai: {{ $pelatihan->pivot->nilai }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-6 border border-dashed border-slate-200 rounded-xl text-slate-400 text-sm">
                            Belum ada data pelatihan terdaftar.
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Riwayat Lamaran Pekerjaan -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2.5">
                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <h3 class="font-bold text-slate-800 text-sm tracking-wide uppercase">Riwayat Lamaran Kerja</h3>
                </div>
                <div class="p-6 divide-y divide-slate-100">
                    @forelse($mahasiswa->lamaran as $lamaran)
                    <div class="py-4 first:pt-0 last:pb-0 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="space-y-0.5">
                            <h4 class="font-semibold text-slate-800 text-sm">{{ $lamaran->lowongan->judul }}</h4>
                            <p class="text-xs font-medium text-indigo-600">{{ $lamaran->lowongan->perusahaan->nama_perusahaan }}</p>
                            <p class="text-[11px] text-slate-400">
                                Melamar pada: {{ $lamaran->tanggal_melamar->translatedFormat('d M Y, H:i') }}
                            </p>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold self-start sm:self-center border
                            @if($lamaran->status == 'diterima') bg-emerald-50 text-emerald-700 border-emerald-200/60
                            @elseif($lamaran->status == 'ditolak') bg-rose-50 text-rose-700 border-rose-200/60
                            @elseif($lamaran->status == 'diproses') bg-blue-50 text-blue-700 border-blue-200/60
                            @else bg-amber-50 text-amber-700 border-amber-200/60
                            @endif">
                            {{ ucfirst($lamaran->status) }}
                        </span>
                    </div>
                    @empty
                    <div class="text-center py-8 text-slate-400 text-sm italic">
                        Belum ada riwayat lamaran pekerjaan.
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Seluruh Pelatihan yang Diikuti -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2.5">
                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.206 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.794 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.794 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.206 18 16.5 18s-3.332.477-4.5 1.253"/></svg>
                    <h3 class="font-bold text-slate-800 text-sm tracking-wide uppercase">Daftar Partisipasi Pelatihan</h3>
                </div>
                <div class="p-6 divide-y divide-slate-100">
                    @forelse($mahasiswa->pelatihan as $pelatihan)
                    <div class="py-4 first:pt-0 last:pb-0 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="space-y-0.5">
                            <h4 class="font-semibold text-slate-800 text-sm">{{ $pelatihan->judul }}</h4>
                            <div class="flex items-center gap-2 text-xs text-slate-400">
                                <span>{{ ucfirst(str_replace('_', ' ', $pelatihan->jenis)) }}</span>
                                <span>•</span>
                                <span>Mulai: {{ $pelatihan->tanggal_mulai->translatedFormat('d M Y') }}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 sm:text-right self-start sm:self-center">
                            @if($pelatihan->pivot->nilai)
                            <div class="text-xs text-slate-500">
                                Nilai: <span class="font-bold text-indigo-600">{{ $pelatihan->pivot->nilai }}</span>
                            </div>
                            @endif
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold border
                                @if($pelatihan->pivot->status_pendaftaran == 'diterima') bg-emerald-50 text-emerald-700 border-emerald-200/60
                                @else bg-amber-50 text-amber-700 border-amber-200/60
                                @endif">
                                {{ ucfirst($pelatihan->pivot->status_pendaftaran) }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-slate-400 text-sm italic">
                        Belum ada data pelatihan yang diikuti.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
        
    </div>
</div>
@endsection