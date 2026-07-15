@extends('layouts.app')

@section('title', 'Detail Perusahaan - Admin')

@section('content')
<div class="px-6 py-8 mx-auto max-w-7xl">

    <div class="flex items-center justify-between mb-8">
        <div>
            <nav class="flex items-center space-x-2 text-sm font-medium text-gray-500">
                <a href="{{ route('admin.perusahaan.index') }}" class="hover:text-blue-600 transition">Perusahaan</a>
                <span class="text-gray-400">/</span>
                <span class="text-gray-800 font-semibold">{{ $perusahaan->user->name }}</span>
            </nav>
            <h1 class="text-3xl font-bold text-gray-900 mt-1">Profil Perusahaan</h1>
        </div>
        <div>
            <a href="{{ route('admin.perusahaan.index') }}"
               class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl shadow-sm hover:bg-gray-50 transition">
                <i class="fas fa-arrow-left mr-2 text-gray-400"></i> Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="h-24 bg-gradient-to-r from-blue-500 to-indigo-600"></div>
                
                <div class="px-6 pb-6 relative">
                    <div class="-mt-12 mb-4 flex justify-center">
                        @if($perusahaan->logo)
                            <div class="h-24 w-24 bg-white p-2 rounded-2xl shadow-md border border-gray-100 flex items-center justify-center overflow-hidden">
                                <img src="{{ asset('storage/'.$perusahaan->logo) }}" class="max-h-full max-w-full object-contain">
                            </div>
                        @else
                            <div class="h-24 w-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl shadow-md border border-gray-50 flex items-center justify-center">
                                <i class="fas fa-building text-3xl text-gray-400"></i>
                            </div>
                        @endif
                    </div>

                    <div class="text-center border-b border-gray-100 pb-5">
                        <h3 class="text-xl font-bold text-gray-900">{{ $perusahaan->user->name }}</h3>
                        <p class="text-sm text-gray-500 font-medium mt-0.5">{{ $perusahaan->bidang_usaha ?? 'Bidang Usaha tidak diatur' }}</p>
                        
                        <div class="mt-3 flex items-center justify-center">
                            <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full
                                {{ $perusahaan->status_kerjasama === 'aktif' ? 'bg-green-50 text-green-700 border border-green-200' :
                                   ($perusahaan->status_kerjasama === 'pending' ? 'bg-amber-50 text-amber-700 border border-amber-200' :
                                   'bg-rose-50 text-rose-700 border border-rose-200') }}">
                                <span class="h-1.5 w-1.5 rounded-full mr-1.5 
                                    {{ $perusahaan->status_kerjasama === 'aktif' ? 'bg-green-500' : 
                                       ($perusahaan->status_kerjasama === 'pending' ? 'bg-amber-500' : 'bg-rose-500') }}"></span>
                                {{ ucfirst($perusahaan->status_kerjasama) }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-5 pt-1">
                        <form action="{{ route('admin.perusahaan.update', $perusahaan) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            
                            @if($perusahaan->status_kerjasama !== 'aktif')
                                <input type="hidden" name="status_kerjasama" value="aktif">
                                <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold bg-green-600 text-white rounded-xl hover:bg-green-700 transition shadow-sm hover:shadow active:scale-[0.98]">
                                    <i class="fas fa-check-circle mr-2"></i> Aktifkan Kemitraan
                                </button>
                            @else
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Ubah Status Kemitraan</label>
                                <div class="relative">
                                    <select name="status_kerjasama" onchange="this.form.submit()" class="block w-full text-sm bg-white border border-gray-300 rounded-xl px-3 py-2 text-gray-700 appearance-none focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition cursor-pointer">
                                        <option value="aktif" selected>🟢 Aktif</option>
                                        <option value="nonaktif">🔴 Nonaktif</option>
                                        <option value="pending">🟡 Pending</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                                        <i class="fas fa-chevron-down text-xs"></i>
                                    </div>
                                </div>
                            @endif
                        </form>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-100 space-y-4 text-sm">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-8 text-gray-400 mt-0.5"><i class="fas fa-envelope"></i></div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Email Perusahaan</p>
                                <p class="text-gray-800 break-words mt-0.5 font-medium">{{ $perusahaan->user->email }}</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-8 text-gray-400 mt-0.5"><i class="fas fa-phone"></i></div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Nomor Telepon</p>
                                <p class="text-gray-800 mt-0.5 font-medium">{{ $perusahaan->no_telp ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-8 text-gray-400 mt-0.5"><i class="fas fa-globe"></i></div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Situs Web</p>
                                @if($perusahaan->website)
                                    <a href="{{ $perusahaan->website }}" class="inline-flex items-center text-blue-600 hover:underline mt-0.5 font-medium" target="_blank">
                                        {{ Str::limit($perusahaan->website, 24) }} <i class="fas fa-external-link-alt text-xs ml-1 text-blue-400"></i>
                                    </a>
                                @else
                                    <p class="text-gray-400 mt-0.5">-</p>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-8 text-gray-400 mt-0.5"><i class="fas fa-map-marker-alt"></i></div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Kota Operasional</p>
                                <p class="text-gray-800 mt-0.5 font-medium">{{ $perusahaan->kota ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-6">

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                @php
                    $stats = [
                        ['Lowongan Aktif', $perusahaan->lowonganKerja()->where('status','aktif')->count(), 'briefcase', 'from-blue-50 to-indigo-50 text-blue-600'],
                        ['Mahasiswa PKL', $perusahaan->pkl()->whereIn('status',['berlangsung','diterima'])->count(), 'user-graduate', 'from-emerald-50 to-teal-50 text-emerald-600'],
                        ['Total Kerjasama', $perusahaan->kerjasamaIndustri()->whereIn('status',['aktif','negosiasi'])->count(), 'handshake', 'from-purple-50 to-violet-50 text-purple-600'],
                    ];
                @endphp

                @foreach($stats as $s)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex justify-between items-center group hover:shadow transition-shadow">
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ $s[0] }}</p>
                        <h3 class="text-3xl font-bold text-gray-900 mt-1">{{ $s[1] }}</h3>
                    </div>
                    <div class="h-12 w-12 rounded-xl bg-gradient-to-br {{ $s[3] }} flex items-center justify-center text-xl transition-transform group-hover:scale-110">
                        <i class="fas fa-{{ $s[2] }}"></i>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-5">
                    <h2 class="text-lg font-bold text-gray-900 flex items-center">
                        <i class="fas fa-file-alt text-blue-500 mr-2.5"></i> Informasi Lengkap
                    </h2>
                    <a href="{{ route('admin.perusahaan.edit', $perusahaan) }}"
                       class="inline-flex items-center text-sm font-semibold text-blue-600 hover:text-blue-700 transition">
                        <i class="fas fa-pen mr-1.5 text-xs"></i> Edit Data
                    </a>
                </div>

                <div class="space-y-6">
                    <div>
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Alamat Kantor Utama</h4>
                        <p class="text-gray-800 leading-relaxed font-medium">{{ $perusahaan->alamat ?? 'Alamat lengkap belum dicantumkan.' }}</p>
                        @if($perusahaan->kota || $perusahaan->provinsi)
                            <p class="text-sm font-semibold text-gray-500 mt-1">
                                {{ $perusahaan->kota }}{{ $perusahaan->provinsi ? ', '.$perusahaan->provinsi : '' }}
                            </p>
                        @endif
                    </div>

                    <div class="pt-5 border-t border-gray-100">
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Tentang Perusahaan</h4>
                        <div class="text-gray-700 leading-relaxed space-y-2 whitespace-pre-line">
                            {{ $perusahaan->deskripsi ?? 'Tidak ada deskripsi profil untuk perusahaan ini.' }}
                        </div>
                    </div>
                </div>
            </div>

            

        </div>
    </div>
</div>
@endsection