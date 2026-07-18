@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="min-h-screen bg-slate-50/50 pb-12 relative">
    <!-- Header Banner -->
    <div class="h-48 bg-gradient-to-r from-indigo-700 to-indigo-900 w-full absolute top-0 left-0 z-0">
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 pt-12">
        
        <!-- Profile Header Card -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mb-6">
            <div class="p-6 sm:p-8">
                <div class="flex flex-col sm:flex-row gap-6 items-center sm:items-end justify-between">
                    <div class="flex flex-col sm:flex-row items-center sm:items-end gap-5 text-center sm:text-left">
                        <!-- Profile Image Avatar -->
                        <div class="w-28 h-28 sm:w-32 sm:h-32 rounded-2xl border-4 border-white shadow-sm overflow-hidden bg-slate-100 shrink-0">
                            @if(auth()->user()->avatar)
                                <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-indigo-500 to-indigo-600 text-white text-4xl font-extrabold">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                            @endif
                        </div>

                        <!-- Name & Badge Metadata -->
                        <div class="space-y-1.5">
                            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">{{ auth()->user()->name }}</h1>
                            <p class="text-sm font-medium text-slate-400 flex items-center justify-center sm:justify-start gap-1.5">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                {{ auth()->user()->email }}
                            </p>
                            
                            <div class="flex flex-wrap gap-2 justify-center sm:justify-start pt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200/50">
                                    Akses: {{ ucfirst(auth()->user()->role) }}
                                </span>
                                @if(auth()->user()->isMahasiswa())
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-semibold border
                                        @if(auth()->user()->mahasiswa->status == 'aktif') bg-emerald-50 text-emerald-700 border-emerald-200/60
                                        @else bg-blue-50 text-blue-700 border-blue-200/60
                                        @endif">
                                        Status: {{ ucfirst(auth()->user()->mahasiswa->status) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Edit Profile Trigger -->
                    <a href="{{ route('profile.edit') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-sm font-semibold text-white transition-all shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        Edit Profil
                    </a>
                </div>
            </div>
        </div>

        <!-- Mahasiswa View Layout -->
        @if(auth()->user()->isMahasiswa())
            @php $mahasiswa = auth()->user()->mahasiswa; @endphp
            
            <!-- Mini Cards Statistics -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-6">
                @foreach([
                    ['count' => $mahasiswa->pkl()->count(), 'label' => 'Total Program PKL', 'icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'color' => 'indigo'],
                    ['count' => $mahasiswa->lamaran()->count(), 'label' => 'Lamaran Kerja Terkirim', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'color' => 'emerald'],
                    ['count' => $mahasiswa->pelatihan()->count(), 'label' => 'Pelatihan Diikuti', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', 'color' => 'purple']
                ] as $stat)
                <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-{{ $stat['color'] }}-50 flex items-center justify-center text-{{ $stat['color'] }}-600 border border-{{ $stat['color'] }}-100/40 shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stat['icon'] }}"/></svg>
                    </div>
                    <div>
                        <p class="text-2xl font-extrabold text-slate-900 leading-none">{{ $stat['count'] }}</p>
                        <p class="text-xs font-medium text-slate-500 mt-1">{{ $stat['label'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Metadata Details Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
                
                <!-- Left Column: Personal Data -->
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden text-sm">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/40 flex items-center gap-2.5">
                        <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wider">Biodata Pribadi</h3>
                    </div>
                    <div class="p-6 divide-y divide-slate-50 space-y-3.5">
                        <div class="first:pt-0 pt-3">
                            <span class="text-xs font-semibold text-slate-400 block mb-0.5">NOMOR INDUK / NIM</span>
                            <span class="font-bold text-slate-800 font-mono">{{ $mahasiswa->nim }}</span>
                        </div>
                        <div class="pt-3.5">
                            <span class="text-xs font-semibold text-slate-400 block mb-0.5">TEMPAT, TANGGAL LAHIR</span>
                            <span class="font-medium text-slate-800">{{ $mahasiswa->tempat_lahir ?? '-' }}, {{ $mahasiswa->tanggal_lahir ? $mahasiswa->tanggal_lahir->translatedFormat('d M Y') : '-' }}</span>
                        </div>
                        <div class="pt-3.5">
                            <span class="text-xs font-semibold text-slate-400 block mb-0.5">JENIS KELAMIN</span>
                            <span class="font-medium text-slate-800">{{ $mahasiswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                        </div>
                        <div class="pt-3.5">
                            <span class="text-xs font-semibold text-slate-400 block mb-0.5">KONTAK WHATSAPP</span>
                            <span class="font-medium text-slate-800 block">{{ $mahasiswa->no_telp ?? '-' }}</span>
                        </div>
                        <div class="pt-3.5">
                            <span class="text-xs font-semibold text-slate-400 block mb-0.5">ALAMAT DOMISILI</span>
                            <span class="font-medium text-slate-800 block leading-relaxed">{{ $mahasiswa->alamat ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Academic & Parent Data -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Academic Data Box -->
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden text-sm">
                        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/40 flex items-center justify-between">
                            <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wider">Informasi Riwayat Pendidikan</h3>
                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-indigo-50 text-indigo-700 border border-indigo-100 font-bold text-[10px] uppercase tracking-wide">
                                Jenjang: {{ $mahasiswa->tingkat_pendidikan }}
                            </span>
                        </div>
                        <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <span class="text-xs font-semibold text-slate-400 block mb-0.5">INSTITUSI / ASAL SEKOLAH</span>
                                <span class="font-bold text-slate-800 leading-snug block">{{ $mahasiswa->asal_sekolah ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="text-xs font-semibold text-slate-400 block mb-0.5">TAHUN MASUK PENDIDIKAN</span>
                                <span class="font-semibold text-slate-800 block">{{ $mahasiswa->tahun_masuk ?? '-' }}</span>
                            </div>

                            @if(!in_array($mahasiswa->tingkat_pendidikan, ['SD', 'SMP']))
                            <div class="sm:col-span-2 pt-2 border-t border-slate-50 grid grid-cols-1 sm:grid-cols-2 gap-5">
                                @if(!in_array($mahasiswa->tingkat_pendidikan, ['SMA', 'SMK']))
                                    <div>
                                        <span class="text-xs font-semibold text-slate-400 block mb-0.5">FAKULTAS</span>
                                        <span class="font-medium text-slate-800 block">{{ $mahasiswa->fakultas_id ?? '-' }}</span>
                                    </div>
                                @endif
                                <div>
                                    <span class="text-xs font-semibold text-slate-400 block mb-0.5">
                                        {{ in_array($mahasiswa->tingkat_pendidikan, ['SMA', 'SMK']) ? 'JURUSAN SEKOLAH' : 'PROGRAM STUDI' }}
                                    </span>
                                    <span class="font-bold text-indigo-600 block">{{ $mahasiswa->program_studi_id ?? '-' }}</span>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Parents Info Box -->
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden text-sm">
                        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/40">
                            <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wider">Data Orang Tua / Wali</h3>
                        </div>
                        <div class="p-6 grid grid-cols-1 sm:grid-cols-3 gap-5">
                            <div>
                                <span class="text-xs font-semibold text-slate-400 block mb-0.5">NAMA WALI / ORANG TUA</span>
                                <span class="font-semibold text-slate-800 block">{{ $mahasiswa->nama_ortu ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="text-xs font-semibold text-slate-400 block mb-0.5">PEKERJAAN UTAMA</span>
                                <span class="font-medium text-slate-800 block">{{ $mahasiswa->pekerjaan_ortu ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="text-xs font-semibold text-slate-400 block mb-0.5">NO. TELEPON WALI</span>
                                <span class="font-medium text-slate-800 block">{{ $mahasiswa->no_telp_ortu ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <!-- Perusahaan View Layout -->
        @elseif(auth()->user()->isPerusahaan())
            @php $perusahaan = auth()->user()->perusahaan; @endphp
            
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-6">
                @foreach([
                    ['count' => $perusahaan->lowonganKerja()->where('status', 'aktif')->count(), 'label' => 'Lowongan Kerja Aktif', 'icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'color' => 'indigo'],
                    ['count' => $perusahaan->pkl()->where('status', 'berlangsung')->count(), 'label' => 'Siswa / Mahasiswa Magang', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'color' => 'emerald'],
                    ['count' => $perusahaan->kerjasamaIndustri()->count(), 'label' => 'Total Jalinan Kerjasama', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z', 'color' => 'purple']
                ] as $stat)
                <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-{{ $stat['color'] }}-50 flex items-center justify-center text-{{ $stat['color'] }}-600 border border-{{ $stat['color'] }}-100/40 shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stat['icon'] }}"/></svg>
                    </div>
                    <div>
                        <p class="text-2xl font-extrabold text-slate-900 leading-none">{{ $stat['count'] }}</p>
                        <p class="text-xs font-medium text-slate-500 mt-1">{{ $stat['label'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
                <!-- Left Sidebar Details -->
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 space-y-4 text-sm">
                    <h3 class="font-bold text-slate-800 border-b pb-2 text-xs uppercase tracking-wider">Detail Profil Perusahaan</h3>
                    
                    @if($perusahaan->jenis_pt)
                    <div>
                        <span class="text-xs font-semibold text-slate-400 block mb-0.5">Badan Hukum / Jenis PT</span>
                        <span class="font-medium text-slate-800">{{ $perusahaan->jenis_pt }}</span>
                    </div>
                    @endif
                    <div>
                        <span class="text-xs font-semibold text-slate-400 block mb-0.5">Sektor Bidang Usaha</span>
                        <span class="font-medium text-slate-800">{{ $perusahaan->bidang_usaha ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-xs font-semibold text-slate-400 block mb-0.5">Alamat Kantor Utama</span>
                        <span class="font-medium text-slate-800 leading-relaxed block">{{ $perusahaan->alamat }}</span>
                        <span class="text-xs text-slate-500 block mt-0.5">{{ $perusahaan->kota }}, {{ $perusahaan->provinsi }}</span>
                    </div>
                    @if($perusahaan->website)
                    <div>
                        <span class="text-xs font-semibold text-slate-400 block mb-0.5">Website Interaktif</span>
                        <a href="{{ $perusahaan->website }}" target="_blank" class="text-indigo-600 hover:underline block truncate font-medium">{{ $perusahaan->website }}</a>
                    </div>
                    @endif

                    @if($perusahaan->cv_perusahaan)
                    <div class="pt-2">
                        <a href="{{ Storage::url($perusahaan->cv_perusahaan) }}" target="_blank" class="w-full inline-flex items-center justify-center text-xs bg-emerald-50 text-emerald-700 font-bold px-3 py-2 rounded-xl border border-emerald-200/60 hover:bg-emerald-100 transition-colors">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5l5 5v11a2 2 0 01-2 2z"/></svg>
                            Unduh Profil Berkas (PDF)
                        </a>
                    </div>
                    @endif
                </div>

                <!-- Right Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    @if($perusahaan->deskripsi)
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 space-y-2">
                        <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wider">Tentang Instansi</h3>
                        <p class="text-slate-600 text-sm leading-relaxed whitespace-pre-line">{{ $perusahaan->deskripsi }}</p>
                    </div>
                    @endif

                    @if($perusahaan->nama_pimpinan)
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden text-sm">
                        <div class="px-6 py-4 bg-slate-50/50 border-b border-slate-100">
                            <h3 class="font-bold text-slate-800 text-xs uppercase tracking-wider">Manajemen Kontak & Penanggung Jawab</h3>
                        </div>
                        <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <span class="text-xs font-semibold text-slate-400 block mb-0.5">Nama Direktur / Pimpinan</span>
                                <span class="font-bold text-slate-800 block text-base">{{ $perusahaan->nama_pimpinan }}</span>
                            </div>
                            <div class="space-y-1.5">
                                <span class="text-xs font-semibold text-slate-400 block mb-0.5">Saluran Saluran Komunikasi</span>
                                <span class="font-semibold text-slate-800 block flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    {{ $perusahaan->no_telp }} <span class="text-xs text-slate-400 font-normal">(Telp Kantor)</span>
                                </span>
                                @if($perusahaan->no_hp)
                                <span class="font-semibold text-slate-800 block flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                    {{ $perusahaan->no_hp }} <span class="text-xs text-slate-400 font-normal">(WhatsApp Direct)</span>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

        <!-- Admin View Layout -->
        @else
            <div class="max-w-2xl mx-auto">
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-8 text-center space-y-4">
                    <div class="w-16 h-16 bg-indigo-50 border border-indigo-100 text-indigo-600 rounded-2xl flex items-center justify-center mx-auto shadow-xs">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900">Administrator Access</h3>
                    <p class="text-sm text-slate-500 max-w-sm mx-auto">Akun ini memiliki otoritas pengawasan tinggi ke seluruh modul sistem CDC.</p>
                    <div class="grid grid-cols-2 gap-4 text-left bg-slate-50/80 p-4 border border-slate-100 rounded-xl text-sm">
                        <div>
                            <span class="text-xs text-slate-400 block mb-0.5 font-medium">Terdaftar Sejak</span>
                            <span class="font-semibold text-slate-800">{{ auth()->user()->created_at->translatedFormat('d M Y') }}</span>
                        </div>
                        <div>
                            <span class="text-xs text-slate-400 block mb-0.5 font-medium">Status Hak Akses</span>
                            <span class="text-emerald-600 font-bold inline-flex items-center gap-1">● Aktif</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection