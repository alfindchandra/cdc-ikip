@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Mahasiswa')

@section('content')
<div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8 space-y-6">

    <!-- Welcome Card -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden relative">
        <div class="absolute inset-0 bg-gradient-to-r from-indigo-600/5 via-transparent to-transparent pointer-events-none"></div>
        <div class="p-6 sm:p-8 relative flex items-center justify-between gap-6">
            <div class="space-y-2 max-w-2xl">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                    Selamat Datang, {{ auth()->user()->name }}! 👋
                </h2>
                <p class="text-slate-600 text-sm sm:text-base leading-relaxed font-medium">
                    @php
                        $hour = date('H');
                        if($hour < 12) echo 'Selamat pagi';
                        elseif($hour < 15) echo 'Selamat siang';
                        elseif($hour < 18) echo 'Selamat sore';
                        else echo 'Selamat malam';
                    @endphp, 
                    jangan lupa untuk tetap tersenyum dan pantau perkembangan lowongan serta pelatihan terbaru hari ini.
                </p>
            </div>
            <div class="hidden lg:block shrink-0">
                <div class="w-20 h-20 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600 border border-indigo-100">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.794 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        <!-- Lamaran Pending -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 flex flex-col justify-between group hover:border-amber-200 transition-colors duration-200">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600 border border-amber-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    @if($lamaran_pending > 0)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                            {{ $lamaran_pending }} Review
                        </span>
                    @endif
                </div>
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Lamaran Kerja Pending</h3>
                <p class="text-3xl font-extrabold text-slate-900 mt-1">{{ $lamaran_pending }}</p>
                <p class="text-xs text-slate-500 font-medium mt-1">Berkas sedang ditinjau perusahaan.</p>
            </div>
            <div class="mt-5 pt-4 border-t border-slate-50">
                <a href="{{ route('mahasiswa.lamaran.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-700 inline-flex items-center gap-1 group-hover:underline">
                    Lihat Riwayat Lamaran
                    <svg class="w-4 h-4 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>

        <!-- Pelatihan -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 flex flex-col justify-between group hover:border-emerald-200 transition-colors duration-200">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 border border-emerald-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                </div>
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pelatihan Diikuti</h3>
                <p class="text-3xl font-extrabold text-slate-900 mt-1">{{ $pelatihan_terdaftar }}</p>
                <p class="text-xs text-slate-500 font-medium mt-1">Total kelas kompetensi terdaftar.</p>
            </div>
            <div class="mt-5 pt-4 border-t border-slate-50">
                <a href="{{ route('mahasiswa.pelatihan.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-700 inline-flex items-center gap-1 group-hover:underline">
                    Lihat Kelas Saya
                    <svg class="w-4 h-4 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>

        <!-- Profil Completion -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 flex flex-col justify-between group hover:border-purple-200 transition-colors duration-200">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center text-purple-600 border border-purple-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                </div>
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Kelengkapan Profil CV</h3>
                @php
                    $mahasiswa = auth()->user()->mahasiswa;
                    $fields = ['tempat_lahir', 'tanggal_lahir', 'alamat', 'no_telp', 'nama_ortu'];
                    $filled = 0;
                    if($mahasiswa) {
                        foreach($fields as $field) {
                            if(!empty($mahasiswa->$field)) $filled++;
                        }
                        $percentage = round(($filled / count($fields)) * 100);
                    } else {
                        $percentage = 0;
                    }
                @endphp
                <p class="text-3xl font-extrabold text-slate-900 mt-1">{{ $percentage }}%</p>
                <div class="w-full bg-slate-100 rounded-full h-2 mt-2 border border-slate-200/40 overflow-hidden">
                    <div class="bg-purple-600 h-2 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                </div>
            </div>
            <div class="mt-5 pt-4 border-t border-slate-50">
                <a href="{{ route('profile') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-700 inline-flex items-center gap-1 group-hover:underline">
                    Perbarui Berkas Biodata
                    <svg class="w-4 h-4 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Main Lists Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Lowongan Terbaru -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden flex flex-col justify-between">
            <div>
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/40">
                    <h3 class="font-bold text-slate-800 text-sm tracking-wide uppercase">Lowongan Terbaru</h3>
                    <a href="{{ route('mahasiswa.lowongan.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 transition-colors">Lihat Semua</a>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse($lowongan_terbaru as $lowongan)
                    <div class="p-6 hover:bg-slate-50/50 transition-colors flex items-start gap-4">
                        @if($lowongan->perusahaan->logo)
                            <img src="{{ Storage::url($lowongan->perusahaan->logo) }}" alt="{{ $lowongan->perusahaan->nama_perusahaan }}" class="w-12 h-12 rounded-xl object-cover border border-slate-100 shrink-0">
                        @else
                            <div class="w-12 h-12 bg-gradient-to-br from-slate-50 to-slate-100 border border-slate-200 rounded-xl flex items-center justify-center shrink-0">
                                <span class="text-slate-600 font-bold text-sm uppercase">{{ substr($lowongan->perusahaan->nama_perusahaan, 0, 1) }}</span>
                            </div>
                        @endif
                        <div class="flex-1 min-w-0 space-y-1">
                            <h4 class="font-semibold text-slate-800 text-sm truncate leading-snug">{{ $lowongan->judul }}</h4>
                            <p class="text-xs font-medium text-indigo-600 truncate">{{ $lowongan->perusahaan->nama_perusahaan }}</p>
                            <div class="flex flex-wrap items-center gap-x-3 gap-y-1 pt-1">
                                <span class="inline-flex items-center px-2 py-0.5 rounded bg-slate-100 text-slate-600 font-medium text-[10px] tracking-wide uppercase border border-slate-200/40">
                                    {{ ucfirst(str_replace('_', ' ', $lowongan->tipe_pekerjaan)) }}
                                </span>
                                <span class="text-xs text-slate-400 font-medium">{{ $lowongan->lokasi }}</span>
                                <span class="text-xs text-slate-400 font-medium">•</span>
                                <span class="text-xs text-slate-400 font-medium">{{ $lowongan->jumlah_pelamar }} Pelamar</span>
                            </div>
                        </div>
                        <a href="{{ route('mahasiswa.lowongan.show', $lowongan->id) }}" class="text-slate-400 hover:text-indigo-600 transition-colors self-center p-1.5 hover:bg-indigo-50 rounded-lg shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                    @empty
                    <div class="p-12 text-center text-slate-400 text-sm">
                        <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                        Belum ada lowongan pekerjaan aktif saat ini.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Pelatihan Tersedia -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden flex flex-col justify-between">
            <div>
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/40">
                    <h3 class="font-bold text-slate-800 text-sm tracking-wide uppercase">Program Pelatihan Tersedia</h3>
                    <a href="{{ route('mahasiswa.pelatihan.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 transition-colors">Lihat Semua</a>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse($pelatihan_tersedia as $pelatihan)
                    <div class="p-6 hover:bg-slate-50/50 transition-colors flex items-start gap-4">
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-100 flex flex-col items-center justify-center font-bold text-xs p-1 shrink-0">
                            <span class="text-[9px] uppercase tracking-wider text-emerald-500 -mb-0.5">{{ $pelatihan->tanggal_mulai->translatedFormat('M') }}</span>
                            <span class="text-base leading-none tracking-tight">{{ $pelatihan->tanggal_mulai->format('d') }}</span>
                        </div>
                        <div class="flex-1 min-w-0 space-y-1.5">
                            <div class="flex flex-wrap items-center gap-1.5">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-600 border border-slate-200/40 uppercase">
                                    {{ ucfirst(str_replace('_', ' ', $pelatihan->jenis)) }}
                                </span>
                                @if($pelatihan->biaya == 0)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/50 uppercase">GRATIS</span>
                                @endif
                            </div>
                            <h4 class="font-semibold text-slate-800 text-sm leading-snug">{{ $pelatihan->judul }}</h4>
                            <div class="flex flex-wrap items-center gap-x-3 gap-y-1 pt-0.5 text-xs text-slate-400 font-medium">
                                @if($pelatihan->instruktur)
                                    <span>Instruktur: <strong class="text-slate-600 font-semibold">{{ $pelatihan->instruktur }}</strong></span>
                                @endif
                                <span>Mulai {{ $pelatihan->tanggal_mulai->translatedFormat('d M Y') }}</span>
                            </div>
                            
                            @if($pelatihan->kuota)
                            <div class="pt-1 max-w-xs">
                                <div class="flex items-center justify-between text-[11px] text-slate-400 font-medium mb-1">
                                    <span>Kuota: <strong class="text-slate-700">{{ $pelatihan->jumlah_peserta }}</strong>/{{ $pelatihan->kuota }} Peserta</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-1 border border-slate-200/30 overflow-hidden">
                                    <div class="bg-indigo-600 h-1 rounded-full transition-all duration-300" style="width: {{ min(100, ($pelatihan->jumlah_peserta / $pelatihan->kuota) * 100) }}%"></div>
                                </div>
                            </div>
                            @endif
                        </div>
                        <a href="{{ route('mahasiswa.pelatihan.show', $pelatihan->id) }}" class="text-slate-400 hover:text-indigo-600 transition-colors self-center p-1.5 hover:bg-indigo-50 rounded-lg shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                    @empty
                    <div class="p-12 text-center text-slate-400 text-sm">
                        <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        Belum ada program pelatihan yang dibuka saat ini.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/40">
            <h3 class="font-bold text-slate-800 text-sm tracking-wide uppercase">Aksi Cepat</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <a href="{{ route('mahasiswa.lowongan.index') }}" class="flex flex-col items-center justify-center p-6 border border-slate-200 border-dashed rounded-xl hover:border-indigo-500 hover:bg-indigo-50/40 transition-all duration-200 group text-center">
                    <div class="w-10 h-10 rounded-lg bg-slate-50 text-slate-400 flex items-center justify-center group-hover:bg-indigo-100 group-hover:text-indigo-600 transition-colors mb-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <span class="text-sm font-semibold text-slate-700 group-hover:text-indigo-900">Cari Lowongan Kerja</span>
                </a>

                <a href="{{ route('mahasiswa.pelatihan.index') }}" class="flex flex-col items-center justify-center p-6 border border-slate-200 border-dashed rounded-xl hover:border-indigo-500 hover:bg-indigo-50/40 transition-all duration-200 group text-center">
                    <div class="w-10 h-10 rounded-lg bg-slate-50 text-slate-400 flex items-center justify-center group-hover:bg-indigo-100 group-hover:text-indigo-600 transition-colors mb-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <span class="text-sm font-semibold text-slate-700 group-hover:text-indigo-900">Ikuti Program Pelatihan</span>
                </a>

                <a href="{{ route('profile') }}" class="flex flex-col items-center justify-center p-6 border border-slate-200 border-dashed rounded-xl hover:border-indigo-500 hover:bg-indigo-50/40 transition-all duration-200 group text-center">
                    <div class="w-10 h-10 rounded-lg bg-slate-50 text-slate-400 flex items-center justify-center group-hover:bg-indigo-100 group-hover:text-indigo-600 transition-colors mb-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <span class="text-sm font-semibold text-slate-700 group-hover:text-indigo-900">Kelola & Edit Profil</span>
                </a>
            </div>
        </div>
    </div>

    
</div>
@endsection