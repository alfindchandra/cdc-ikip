@extends('layouts.app')

@section('title', 'Riwayat Lamaran')
@section('page-title', 'Riwayat Lamaran Saya')

@section('content')
<div class="max-w-4xl mx-auto pt-12 md:pt-24 space-y-6">

    {{-- === HEADER INFO === --}}
    <div class="flex items-center justify-between border-b border-gray-100 pb-4">
        <div>
            <h2 class="text-xl font-bold text-gray-900 tracking-tight">Daftar Lamaran Kerja</h2>
            <p class="text-sm text-gray-500 mt-0.5">Pantau status berkas lamaran yang telah Anda kirimkan.</p>
        </div>
        <a href="{{ route('index.lowongan') }}"  
           class="inline-flex items-center text-sm font-semibold text-blue-600 hover:text-blue-700 transition gap-1 group">
            Cari lowongan baru 
            <svg class="w-4 h-4 transform group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>

    {{-- === LIST LAMARAN === --}}
    <div class="space-y-4">
        @forelse($lamaran as $l)
        <div class="bg-white border border-gray-100 rounded-2xl p-5 hover:border-gray-200 hover:shadow-sm transition duration-200">
            <div class="flex items-start gap-4">

                {{-- Logo Perusahaan (Modern Round-Box) --}}
                <div class="flex-shrink-0">
                    @if($l->lowongan->perusahaan->logo)
                        <img src="{{ Storage::url($l->lowongan->perusahaan->logo) }}"
                             class="w-14 h-14 rounded-xl object-cover ring-1 ring-gray-100">
                    @else
                        <div class="w-14 h-14 bg-gradient-to-br from-gray-50 to-gray-100 border border-gray-200 rounded-xl flex items-center justify-center text-lg font-bold text-gray-600">
                            {{ substr( $l->lowongan->perusahaan->user->name ?? 'P', 0, 1) }}
                        </div>
                    @endif
                </div>

                {{-- Konten Utama --}}
                <div class="flex-1 min-w-0">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2">
                        <div>
                            <h3 class="font-bold text-gray-900 hover:text-blue-600 transition text-base leading-snug">
                                <a href="{{ route('mahasiswa.lowongan.show', $l->lowongan->id) }}">
                                    {{ $l->lowongan->judul }}
                                </a>
                            </h3>
                            <p class="text-sm font-medium text-gray-600 mt-0.5">{{ $l->lowongan->perusahaan->user->name }}</p>
                        </div>
                        
                        {{-- Status Badge (Clean & Soft Pill Style) --}}
                        <span @class([
                            'inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-md self-start sm:self-center uppercase tracking-wider',
                            'bg-blue-50 text-blue-700 ring-1 ring-blue-600/10' => $l->status === 'dikirim',
                            'bg-purple-50 text-purple-700 ring-1 ring-purple-600/10' => $l->status === 'dilihat',
                            'bg-amber-50 text-amber-700 ring-1 ring-amber-600/10' => $l->status === 'diproses',
                            'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-600/10' => $l->status === 'diterima',
                            'bg-rose-50 text-rose-700 ring-1 ring-rose-600/10' => $l->status === 'ditolak',
                        ])>
                            {{ $l->status }}
                        </span>
                    </div>

                    {{-- Metadata Lowongan --}}
                    <div class="flex flex-wrap items-center gap-y-1 gap-x-4 mt-3 text-xs text-gray-500">
                        <div class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <span>{{ $l->lowongan->posisi }}</span>
                        </div>
                        <span class="text-gray-300">•</span>
                        <div class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>{{ $l->lowongan->lokasi }}</span>
                        </div>
                        <span class="text-gray-300">•</span>
                        <div>
                            <span>Tipe: <strong class="text-gray-700 font-medium">{{ ucfirst(str_replace('_',' ',$l->lowongan->tipe_pekerjaan)) }}</strong></span>
                        </div>
                        <span class="text-gray-300">•</span>
                        <div>
                            <span>Dikirim: <strong class="text-gray-700 font-medium">{{ $l->tanggal_melamar->translatedFormat('d M Y') }}</strong></span>
                        </div>
                    </div>

                    {{-- Section Berkas & Aksi Kompak --}}
                    <div class="flex items-center justify-between pt-4 mt-4 border-t border-gray-50 text-xs">
                        {{-- Lampiran Berkas --}}
                        <div class="flex items-center gap-3">
                            <span class="text-gray-400">Berkas:</span>
                            @foreach(['cv'=>'CV','surat_lamaran'=>'Surat','portofolio'=>'Portofolio'] as $f=>$lbl)
                                @if($l->$f)
                                <a href="{{ Storage::url($l->$f) }}" target="_blank"
                                   class="inline-flex items-center text-gray-600 hover:text-blue-600 font-medium gap-0.5 bg-gray-50 hover:bg-blue-50 px-2 py-1 rounded transition">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    {{ $lbl }}
                                </a>
                                @endif
                            @endforeach
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="flex items-center gap-4">
                            <a href="{{ route('mahasiswa.lowongan.show', $l->lowongan->id) }}"
                               class="text-gray-600 font-semibold hover:text-gray-950 transition">
                                Detail Lowongan
                            </a>

                            @if(in_array($l->status, ['dikirim', 'dilihat']))
                            <form method="POST" action="{{ route('mahasiswa.lamaran.destroy', $l->id) }}"
                                  onsubmit="return confirm('Apakah Anda yakin ingin membatalkan lamaran ini?')">
                                @csrf @method('DELETE')
                                <button class="text-red-500 font-semibold hover:text-red-600 transition">
                                    Batalkan
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
        @empty
        {{-- State Kosong Modern --}}
        <div class="bg-white border border-dashed border-gray-200 rounded-2xl p-12 text-center">
            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 ring-8 ring-gray-50/50">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
            </div>
            <h3 class="text-base font-bold text-gray-900">Belum ada lamaran yang terkirim</h3>
            <p class="text-sm text-gray-500 max-w-sm mx-auto mt-1 mb-6">Jelajahi berbagai peluang karir yang sesuai dengan keahlian dan minat potensial Anda sekarang.</p>
            <a href="{{ route('index.lowongan') }}"
               class="inline-flex items-center bg-blue-600 text-white px-5 py-2.5 rounded-xl font-medium text-sm hover:bg-blue-700 shadow-sm shadow-blue-600/10 transition">
                Cari Lowongan Kerja
            </a>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="pt-4">
        {{ $lamaran->links() }}
    </div>
</div>
@endsection