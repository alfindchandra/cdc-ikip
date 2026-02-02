@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="min-h-screen bg-slate-50/50 pb-12">
    <div class="h-48 bg-gradient-to-r from-blue-700 to-indigo-800 w-full absolute top-0 left-0 z-0">
        <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 pt-12">
        
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden mb-8">
            <div class="relative p-6 sm:p-10">
                <div class="flex flex-col sm:flex-row gap-6 items-start sm:items-end">
                    
                    <div class="relative group shrink-0 mx-auto sm:mx-0">
                        <div class="w-32 h-32 sm:w-40 sm:h-40 rounded-3xl border-4 border-white shadow-lg overflow-hidden bg-slate-200 relative">
                            @if(auth()->user()->avatar)
                                <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="Avatar" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-500 to-indigo-600 text-white text-5xl font-bold">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                            @endif
                            
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center cursor-pointer"
                                 onclick="document.getElementById('avatarInput').click()">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                        </div>

                        <form id="avatarForm" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="hidden">
                            @csrf
                            @method('PUT')
                            <input type="file" id="avatarInput" name="avatar" accept="image/*" onchange="document.getElementById('avatarForm').submit()">
                        </form>
                    </div>

                    <div class="flex-1 text-center sm:text-left w-full">
                        <div class="flex flex-col sm:flex-row justify-between items-center sm:items-end gap-4">
                            <div>
                                <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-1">{{ auth()->user()->name }}</h1>
                                <p class="text-slate-500 font-medium mb-3 flex items-center justify-center sm:justify-start gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    {{ auth()->user()->email }}
                                </p>
                                
                                <div class="flex flex-wrap gap-2 justify-center sm:justify-start">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                                        {{ ucfirst(auth()->user()->role) }}
                                    </span>
                                    @if(auth()->user()->isMahasiswa())
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-100">
                                            Status: {{ ucfirst(auth()->user()->mahasiswa->status) }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <a href="{{ route('profile.edit') }}" 
                               class="px-6 py-2.5 bg-gray-900 hover:bg-gray-800 text-white rounded-xl font-medium transition-all hover:shadow-lg flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                </svg>
                                Edit Profil
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(auth()->user()->isMahasiswa())
        @php $mahasiswa = auth()->user()->mahasiswa; @endphp
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            @foreach([
                ['count' => $mahasiswa->pkl()->count(), 'label' => 'Total PKL', 'icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'color' => 'blue'],
                ['count' => $mahasiswa->lamaran()->count(), 'label' => 'Lamaran Terkirim', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'color' => 'emerald'],
                ['count' => $mahasiswa->pelatihan()->count(), 'label' => 'Pelatihan Selesai', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', 'color' => 'purple']
            ] as $stat)
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-{{ $stat['color'] }}-50 flex items-center justify-center text-{{ $stat['color'] }}-600 shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stat['icon'] }}"/></svg>
                </div>
                <div>
                    <p class="text-3xl font-bold text-slate-800">{{ $stat['count'] }}</p>
                    <p class="text-sm font-medium text-slate-500">{{ $stat['label'] }}</p>
                </div>
            </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <div class="lg:col-span-4 space-y-6">
                
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-50 bg-slate-50/50 flex items-center justify-between">
                        <h3 class="font-bold text-slate-800">Data Pribadi</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 gap-y-5">
                            <div>
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">NIM</p>
                                <p class="text-slate-800 font-semibold">{{ $mahasiswa->nim }}</p>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Tgl Lahir</p>
                                    <p class="text-slate-800">{{ $mahasiswa->tanggal_lahir ? $mahasiswa->tanggal_lahir->format('d M Y') : '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Gender</p>
                                    <p class="text-slate-800">{{ $mahasiswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                                </div>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Alamat</p>
                                <p class="text-slate-800 leading-relaxed">{{ $mahasiswa->alamat ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Kontak</p>
                                <p class="text-slate-800">{{ $mahasiswa->no_telp ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-50 bg-slate-50/50">
                        <h3 class="font-bold text-slate-800">Akademik</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Fakultas</p>
                            <p class="text-slate-800 font-medium">{{ $mahasiswa->fakultas->nama ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Program Studi</p>
                            <p class="text-slate-800 font-medium">{{ $mahasiswa->programStudi->nama ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Tahun Masuk</p>
                            <p class="text-slate-800 font-medium">{{ $mahasiswa->tahun_masuk ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                @if($mahasiswa->nama_ortu)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-50 bg-slate-50/50">
                        <h3 class="font-bold text-slate-800">Orang Tua / Wali</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Nama</p>
                            <p class="text-slate-800 font-medium">{{ $mahasiswa->nama_ortu }}</p>
                        </div>
                        @if($mahasiswa->no_telp_ortu)
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Kontak</p>
                            <p class="text-slate-800 font-medium">{{ $mahasiswa->no_telp_ortu }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <div class="lg:col-span-8 space-y-8">
                
              
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-50 bg-slate-50/50 flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center text-green-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </span>
                            <h3 class="font-bold text-lg text-slate-800">Pelatihan Terbaru</h3>
                        </div>
                        <a href="{{ route('mahasiswa.pelatihan.index') }}" class="text-sm font-medium text-green-600 hover:text-green-700 hover:underline">Lihat Semua</a>
                    </div>
                    <div class="p-6 grid gap-4">
                        @forelse($mahasiswa->pelatihan()->latest()->take(3)->get() as $pelatihan)
                        <div class="flex items-start gap-4 p-4 rounded-xl border border-slate-100 hover:border-green-200 hover:shadow-sm transition-all bg-white">
                            <div class="w-12 h-12 rounded-lg bg-green-50 flex items-center justify-center text-green-600 shrink-0 font-bold text-sm">
                                {{ $pelatihan->tanggal_mulai->format('d M') }}
                            </div>
                            <div class="flex-1">
                                <h4 class="font-bold text-slate-900">{{ $pelatihan->judul }}</h4>
                                <p class="text-sm text-slate-500 mb-2">{{ ucfirst(str_replace('_', ' ', $pelatihan->jenis)) }}</p>
                                @if($pelatihan->pivot->nilai)
                                    <span class="inline-flex items-center px-2 py-1 bg-yellow-50 text-yellow-700 text-xs font-bold rounded">
                                        Nilai: {{ $pelatihan->pivot->nilai }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        @empty
                            <div class="text-center py-10 text-slate-400">
                                <p>Belum ada data pelatihan.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>

        @elseif(auth()->user()->isPerusahaan())
        @php $perusahaan = auth()->user()->perusahaan; @endphp
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            @foreach([
                ['count' => $perusahaan->lowonganKerja()->where('status', 'aktif')->count(), 'label' => 'Loker Aktif', 'icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'color' => 'blue'],
                ['count' => $perusahaan->pkl()->where('status', 'berlangsung')->count(), 'label' => 'Mahasiswa Magang', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'color' => 'emerald'],
                ['count' => $perusahaan->kerjasamaIndustri()->count(), 'label' => 'Kerjasama', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z', 'color' => 'purple']
            ] as $stat)
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-{{ $stat['color'] }}-50 flex items-center justify-center text-{{ $stat['color'] }}-600 shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stat['icon'] }}"/></svg>
                </div>
                <div>
                    <p class="text-3xl font-bold text-slate-800">{{ $stat['count'] }}</p>
                    <p class="text-sm font-medium text-slate-500">{{ $stat['label'] }}</p>
                </div>
            </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-6">
                        <h3 class="font-bold text-slate-800 mb-4 border-b pb-2">Detail Perusahaan</h3>
                        <div class="space-y-4">
                            <div>
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Bidang</p>
                                <p class="text-slate-800 font-medium">{{ $perusahaan->bidang_usaha }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Lokasi</p>
                                <p class="text-slate-800 leading-relaxed">{{ $perusahaan->alamat }} <br> <span class="text-slate-500">{{ $perusahaan->kota }}, {{ $perusahaan->provinsi }}</span></p>
                            </div>
                            @if($perusahaan->website)
                            <div>
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Website</p>
                                <a href="{{ $perusahaan->website }}" target="_blank" class="text-blue-600 hover:underline truncate block">{{ $perusahaan->website }}</a>
                            </div>
                            @endif
                            <div>
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Status Kerjasama</p>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-{{ $perusahaan->status_kerjasama == 'aktif' ? 'green' : 'yellow' }}-100 text-{{ $perusahaan->status_kerjasama == 'aktif' ? 'green' : 'yellow' }}-800">
                                    {{ ucfirst($perusahaan->status_kerjasama) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 space-y-6">
                @if($perusahaan->deskripsi)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                    <h3 class="font-bold text-slate-800 mb-3">Tentang Perusahaan</h3>
                    <div class="prose prose-sm max-w-none text-slate-600">
                        {{ $perusahaan->deskripsi }}
                    </div>
                </div>
                @endif

                @if($perusahaan->nama_pic)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="px-6 py-4 bg-slate-50/50 border-b border-slate-100">
                        <h3 class="font-bold text-slate-800">Contact Person (PIC)</h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Nama PIC</p>
                            <p class="text-slate-800 font-medium">{{ $perusahaan->nama_pic }}</p>
                            <p class="text-sm text-slate-500">{{ $perusahaan->jabatan_pic }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Kontak</p>
                            <p class="text-slate-800 font-medium">{{ $perusahaan->no_telp_pic }}</p>
                            <p class="text-sm text-blue-600">{{ $perusahaan->email_pic }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        @else
        <div class="max-w-3xl mx-auto">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 text-center">
                <div class="w-20 h-20 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2">Administrator Access</h3>
                <p class="text-slate-500 mb-6">Akun Anda memiliki akses penuh ke sistem. Jaga kerahasiaan akun ini.</p>
                <div class="grid grid-cols-2 gap-4 text-left bg-slate-50 p-6 rounded-xl">
                    <div>
                        <p class="text-xs text-slate-400 uppercase">Bergabung Sejak</p>
                        <p class="font-medium text-slate-800">{{ auth()->user()->created_at->format('d F Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 uppercase">Status</p>
                        <span class="text-green-600 font-bold text-sm">● Aktif</span>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="mt-8 bg-yellow-50 border border-yellow-100 rounded-xl p-4 flex items-start gap-3">
            <svg class="w-6 h-6 text-yellow-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
            <div>
                <h4 class="font-bold text-yellow-800 text-sm">Keamanan Akun</h4>
                <p class="text-yellow-700 text-sm mt-1">Pastikan Anda menggunakan password yang kuat dan tidak membagikannya kepada siapapun.</p>
            </div>
        </div>

    </div>
</div>

<button onclick="window.scrollTo({top: 0, behavior: 'smooth'})" id="scrollTopBtn" 
    class="fixed bottom-8 right-8 bg-blue-600 text-white p-3 rounded-full shadow-lg translate-y-20 opacity-0 transition-all duration-300 z-50 hover:bg-blue-700 hover:-translate-y-1">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
</button>

<script>
    // Smooth reveal scroll top button
    window.addEventListener('scroll', () => {
        const btn = document.getElementById('scrollTopBtn');
        if (window.scrollY > 200) {
            btn.classList.remove('translate-y-20', 'opacity-0');
        } else {
            btn.classList.add('translate-y-20', 'opacity-0');
        }
    });
</script>
@endsection