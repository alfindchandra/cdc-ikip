@extends('layouts.app')

@section('title', 'Detail Lowongan')
@section('page-title', 'Detail Lowongan Kerja')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
    <a href="{{ route('admin.lowongan.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 transition-colors duration-200 font-medium group">
        <svg class="w-5 h-5 mr-2 transition-transform duration-200 group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali ke Daftar Lowongan
    </a>

    <div class="space-y-6">
        @if($lowongan->thumbnail)
        <div class="card overflow-hidden shadow-lg border border-gray-100">
            <img src="{{ Storage::url($lowongan->thumbnail) }}" alt="{{ $lowongan->judul }}" class="w-full h-64 object-cover">
        </div>
        @endif
    
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2 space-y-8">
                
                <div class="card shadow-xl border border-gray-100">
                    <div class="card-header bg-white rounded-t-xl p-6 border-b border-gray-100">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                            <h1 class="text-3xl font-extrabold text-gray-900 mb-2 sm:mb-0">{{ $lowongan->judul }}</h1>
                            <span class="badge badge-{{ $lowongan->status == 'aktif' ? 'success' : ($lowongan->status == 'expired' ? 'danger' : 'gray') }} text-sm px-3 py-1.5 font-semibold">
                                {{ ucfirst($lowongan->status) }}
                            </span>
                        </div>
                        <p class="text-xl text-gray-600 mt-1">{{ $lowongan->posisi }}</p>
                    </div>

                    <div class="card-body p-6 space-y-8">
                        
                        <div class="flex flex-wrap gap-3 border-b pb-4">
                            <span class="badge badge-primary flex items-center text-sm px-3 py-1.5 font-medium bg-blue-100 text-blue-800 rounded-full">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                {{ ucfirst(str_replace('_', ' ', $lowongan->tipe_pekerjaan)) }}
                            </span>
                            <span class="badge badge-info flex items-center text-sm px-3 py-1.5 font-medium bg-indigo-100 text-indigo-800 rounded-full">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                {{ $lowongan->lokasi }}
                            </span>
                            @if($lowongan->gaji_min && $lowongan->gaji_max)
                            <span class="badge badge-success flex items-center text-sm px-3 py-1.5 font-medium bg-green-100 text-green-800 rounded-full">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Rp {{ number_format($lowongan->gaji_min/1000000, 1) }}jt - {{ number_format($lowongan->gaji_max/1000000, 1) }}jt
                            </span>
                            @endif
                        </div>

                        <section class="space-y-4">
                            <h2 class="text-xl font-bold text-gray-900 flex items-center">
                                <span class="w-8 h-8 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </span>
                                Deskripsi Pekerjaan
                            </h2>
                            <div class="prose max-w-none text-gray-700 p-4 rounded-lg bg-gray-50 border border-gray-200 shadow-inner">
                                {!! nl2br(e($lowongan->deskripsi)) !!}
                            </div>
                        </section>

                        <section class="space-y-4">
                            <h2 class="text-xl font-bold text-gray-900 flex items-center">
                                <span class="w-8 h-8 bg-green-50 text-green-600 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </span>
                                Kualifikasi
                            </h2>
                            <div class="prose max-w-none text-gray-700 p-4 rounded-lg bg-gray-50 border border-gray-200 shadow-inner">
                                {!! nl2br(e($lowongan->kualifikasi)) !!}
                            </div>
                        </section>

                        @if($lowongan->benefit)
                        <section class="space-y-4">
                            <h2 class="text-xl font-bold text-gray-900 flex items-center">
                                <span class="w-8 h-8 bg-yellow-50 text-yellow-600 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg>
                                </span>
                                Benefit
                            </h2>
                            <div class="prose max-w-none text-gray-700 p-4 rounded-lg bg-gray-50 border border-gray-200 shadow-inner">
                                {!! nl2br(e($lowongan->benefit)) !!}
                            </div>
                        </section>
                        @endif
                    </div>
                </div>

                <div class="card shadow-md border border-gray-100">
                    <div class="card-header bg-white rounded-t-xl p-6 border-b border-gray-100">
                        <h3 class="text-xl font-bold text-gray-900">Tentang Perusahaan</h3>
                    </div>
                    <div class="card-body p-6">
                        <div class="flex items-start space-x-4 mb-4">
                            @if($lowongan->perusahaan->logo)
                            <img src="{{ Storage::url($lowongan->perusahaan->logo) }}" alt="{{ $lowongan->perusahaan->nama_perusahaan }}" class="w-16 h-16 rounded-xl object-cover border border-gray-200 flex-shrink-0 shadow-sm">
                            @else
                            <div class="w-16 h-16 bg-gray-200 rounded-xl flex items-center justify-center flex-shrink-0 border border-gray-200 shadow-sm">
                                <span class="text-gray-600 font-bold text-2xl">{{ substr($lowongan->perusahaan->nama_perusahaan, 0, 1) }}</span>
                            </div>
                            @endif
                            <div class="flex-grow">
                                <p class="font-bold text-gray-900 text-xl">{{ $lowongan->perusahaan->nama_perusahaan }}</p>
                                <p class="text-sm text-gray-600">{{ $lowongan->perusahaan->bidang_usaha }}</p>
                            </div>
                        </div>
                        
                        @if($lowongan->perusahaan->deskripsi)
                        <p class="text-gray-700 mb-6 leading-relaxed text-sm">{{ Str::limit($lowongan->perusahaan->deskripsi, 200) }}</p>
                        @endif

                        <div class="space-y-3 text-sm">
                            @if($lowongan->perusahaan->alamat)
                            <div class="flex items-start">
                                <svg class="w-4 h-4 mr-3 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                <span class="text-gray-600">{{ $lowongan->perusahaan->alamat }}</span>
                            </div>
                            @endif
                            @if($lowongan->perusahaan->website)
                            <div class="flex items-start">
                                <svg class="w-4 h-4 mr-3 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                                <a href="{{ $lowongan->perusahaan->website }}" target="_blank" class="text-blue-600 hover:text-blue-700 transition-colors duration-200 break-all">
                                    {{ $lowongan->perusahaan->website }}
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1 space-y-8">
                
                <div class="card shadow-md border border-gray-100">
                    <div class="card-header bg-blue-50 rounded-t-xl p-4 border-b border-blue-100">
                        <h3 class="text-lg font-bold text-blue-900">Aksi Cepat</h3>
                    </div>
                    <div class="card-body p-4 space-y-3">
                        

                        <form action="{{ route('admin.lowongan.status', $lowongan->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="{{ $lowongan->status == 'aktif' ? 'nonaktif' : 'aktif' }}">
                            <button type="submit" class="btn btn-{{ $lowongan->status == 'aktif' ? 'warning' : 'success' }} w-full justify-center font-semibold py-2 rounded-lg transition duration-150 ease-in-out flex items-center {{ $lowongan->status == 'aktif' ? 'bg-yellow-500 hover:bg-yellow-600 text-white' : 'bg-green-500 hover:bg-green-600 text-white' }}">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                {{ $lowongan->status == 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                        </form>

                        <form action="{{ route('admin.lowongan.destroy', $lowongan->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus lowongan {{ $lowongan->judul }}? Aksi ini tidak dapat dibatalkan.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-full justify-center bg-red-600 hover:bg-red-700 text-white font-semibold py-2 rounded-lg transition duration-150 ease-in-out flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                Hapus Lowongan
                            </button>
                        </form>
                    </div>
                </div>

                <div class="card shadow-md border border-gray-100">
                    <div class="card-header bg-gray-50 rounded-t-xl p-4 border-b border-gray-100">
                        <h3 class="text-lg font-bold text-gray-900">Detail Lowongan</h3>
                    </div>
                    <div class="card-body p-4 space-y-4">
                        @foreach([
                            'Diposting' => $lowongan->created_at->format('d F Y'),
                            'Tanggal Mulai' => $lowongan->tanggal_mulai->format('d F Y'),
                            'Batas Lamaran' => $lowongan->tanggal_berakhir->format('d F Y'),
                            'Kuota' => $lowongan->kuota ? $lowongan->kuota . ' orang' : 'Tidak terbatas',
                            'Total Pelamar' => $lowongan->jumlah_pelamar . ' orang',
                        ] as $label => $value)
                            <div class="flex justify-between items-center pb-2 border-b border-gray-100 last:border-b-0">
                                <p class="text-sm uppercase tracking-wider text-gray-500 font-medium">{{ $label }}</p>
                                <p class="font-semibold text-gray-800 text-sm">{{ $value }}</p>
                            </div>
                        @endforeach
                        
                        <div class="pt-2">
                             <p class="text-sm uppercase tracking-wider text-gray-500 font-medium mb-1">Status Lowongan</p>
                             <span class="badge badge-{{ $lowongan->status == 'aktif' ? 'success' : ($lowongan->status == 'expired' ? 'danger' : 'gray') }} text-sm px-3 py-1.5 font-semibold bg-{{ $lowongan->status == 'aktif' ? 'green' : ($lowongan->status == 'expired' ? 'red' : 'gray') }}-100 text-{{ $lowongan->status == 'aktif' ? 'green' : ($lowongan->status == 'expired' ? 'red' : 'gray') }}-800 rounded-full">
                                {{ ucfirst($lowongan->status) }}
                             </span>
                        </div>
                    </div>
                </div>

                <div class="card bg-gradient-to-br from-blue-50 to-indigo-50 border-blue-200 shadow-lg">
                    <div class="card-body p-6">
                        <h4 class="font-bold text-xl text-blue-900 mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                            Statistik Lowongan
                        </h4>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-2 bg-white/70 rounded-lg shadow-sm">
                                <span class="text-sm text-gray-600 font-medium">Views</span>
                                <span class="font-extrabold text-lg text-gray-900">-</span>
                            </div>
                            <div class="flex items-center justify-between p-2 bg-white/70 rounded-lg shadow-sm">
                                <span class="text-sm text-gray-600 font-medium">Lamaran</span>
                                <span class="font-extrabold text-lg text-blue-600">{{ $lowongan->jumlah_pelamar }}</span>
                            </div>
                            <div class="flex items-center justify-between p-2 bg-white/70 rounded-lg shadow-sm">
                                <span class="text-sm text-gray-600 font-medium">Conversion Rate</span>
                                <span class="font-extrabold text-lg text-gray-900">-</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection