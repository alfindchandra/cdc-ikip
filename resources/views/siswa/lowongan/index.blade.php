@extends('layouts.app')

@section('title', 'Lowongan Kerja')
@section('page-title', '')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">

    {{-- SEARCH & FILTER --}}
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-6 gap-4">
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Cari posisi atau perusahaan..."
                   class="md:col-span-3 w-full rounded-xl border-gray-300 focus:ring focus:ring-blue-200 px-4 py-2.5 ring-1">

            <select name="tipe_pekerjaan"
                    class="md:col-span-2 w-full rounded-xl border-gray-300 focus:ring focus:ring-blue-200 ring-1 px-4 py-2.5">
                <option value="">Kategori Pekerjaan</option>
                @foreach(['full_time'=>'Full Time','part_time'=>'Part Time','kontrak'=>'Kontrak','magang'=>'Magang'] as $k=>$v)
                    <option value="{{ $k }}" {{ request('tipe_pekerjaan') == $k ? 'selected' : '' }}>
                        {{ $v }}
                    </option>
                @endforeach
            </select>


            <button class="w-full bg-blue-600 text-white rounded-xl py-2.5 hover:bg-blue-700 transition">
                Cari
            </button>
        </form>
    </div>

    {{-- GRID LOWONGAN --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($lowongan as $l)
        <div class="bg-white rounded-2xl shadow-sm hover:shadow-lg transition p-5 flex flex-col">
            <h3 class="text-xl font-bold text-gray-900 mb-2 line-clamp-2"> {{ $l->posisi }} </h3>
            {{-- COMPANY --}}
            <div class="flex items-center gap-3 mb-4">
                @if($l->perusahaan->logo)
                    <img src="{{ Storage::url($l->perusahaan->logo) }}"
                         class="w-11 h-11 rounded-xl object-cover">
                @else
                    <div class="w-11 h-11 bg-gray-200 rounded-xl
                                flex items-center justify-center font-semibold">
                        {{ substr($l->perusahaan->nama_perusahaan,0,1) }}
                    </div>
                @endif
                <div class="min-w-0">
                    <p class="font-semibold text-sm truncate text-slate-700">
                        {{ $l->perusahaan->nama_perusahaan }}
                    </p>
                    <p class="text-xs text-gray-500 truncate">
                        📍 {{ $l->lokasi }}
                    </p>
                </div>
            </div>

            {{-- TITLE --}}
            <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2">
                {{ $l->judul }}
            </h3>

            {{-- META --}}
            <div class="flex flex-wrap items-center gap-3 mb-4">
                <span class="px-3 py-1 text-xs rounded-full bg-blue-100 text-blue-700 font-medium">
                    {{ ucfirst(str_replace('_',' ',$l->tipe_pekerjaan)) }}
                </span>

                @if($l->gaji_min || $l->gaji_max)
                    <span class="badge badge-success text-sm px-3 py-1">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2
                                                m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1
                                                m0-1c-1.11 0-2.08-.402-2.599-1
                                                M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>

                                    @if($l->gaji_min && $l->gaji_max)
                                        Rp {{ number_format($l->gaji_min / 1000000, 0) }}jt -
                                        {{ number_format($l->gaji_max / 1000000, 0) }}jt
                                    @elseif($l->gaji_min)
                                        Min Rp {{ number_format($l->gaji_min / 1000000, 0) }}jt
                                    @elseif($l->gaji_max)
                                        Max Rp {{ number_format($l->gaji_max / 1000000, 0) }}jt
                                    @endif
                                </span>
                @endif
            </div>

           

            {{-- FOOTER --}}
            <div class="mt-auto pt-4 border-t flex items-center justify-between text-xs text-gray-500">
                <div class="flex items-center gap-4">
                    <span class="flex items-center gap-1">
                        👥 {{ $l->jumlah_pelamar }}
                    </span>
                    <span class="flex items-center gap-1">
                        ⏳ {{ $l->tanggal_berakhir->diffForHumans() }}
                    </span>
                </div>

                <a href="{{ route('siswa.lowongan.show',$l->id) }}"
                   class="text-blue-600 font-semibold hover:underline">
                    Detail →
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white rounded-2xl shadow-sm p-12 text-center">
            <p class="text-xl font-semibold">Belum ada lowongan tersedia</p>
            <p class="text-gray-500 mt-2">
                Silakan ubah filter atau coba kembali nanti
            </p>
        </div>
        @endforelse
    </div>

    {{-- PAGINATION --}}
    <div class="flex justify-center">
        {{ $lowongan->links() }}
    </div>
</div>
@endsection
