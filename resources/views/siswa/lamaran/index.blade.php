@extends('layouts.app')

@section('title', 'Riwayat Lamaran')
@section('page-title', 'Riwayat Lamaran Saya')

@section('content')
<div class="space-y-8">

    {{-- === STATISTICS === --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @php
            $stats = [
                ['label'=>'Total Lamaran','count'=>auth()->user()->siswa->lamaran()->count(),'color'=>'blue','icon'=>'M9 12h6'],
                ['label'=>'Diproses','count'=>auth()->user()->siswa->lamaran()->whereIn('status',['dikirim','dilihat','diproses'])->count(),'color'=>'yellow','icon'=>'M12 8v4l3 3'],
                ['label'=>'Diterima','count'=>auth()->user()->siswa->lamaran()->where('status','diterima')->count(),'color'=>'green','icon'=>'M9 12l2 2 4-4'],
                ['label'=>'Ditolak','count'=>auth()->user()->siswa->lamaran()->where('status','ditolak')->count(),'color'=>'red','icon'=>'M10 14l2-2'],
            ];
        @endphp

        @foreach($stats as $s)
        <div class="bg-white rounded-xl shadow-sm p-4 hover:shadow-md transition">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500">{{ $s['label'] }}</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $s['count'] }}</p>
                </div>
                <div class="w-12 h-12 bg-{{ $s['color'] }}-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-{{ $s['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $s['icon'] }}"/>
                    </svg>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- === FILTER === --}}
    <div class="bg-white rounded-xl shadow-sm p-5">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <select name="status" class="w-full rounded-lg border-gray-300 focus:ring focus:ring-blue-200">
                <option value="">Semua Status</option>
                @foreach(['dikirim','dilihat','diproses','diterima','ditolak'] as $st)
                    <option value="{{ $st }}" {{ request('status')==$st?'selected':'' }}>
                        {{ ucfirst($st) }}
                    </option>
                @endforeach
            </select>
            <button class="md:col-span-1 w-full bg-blue-600 text-white rounded-lg py-2 hover:bg-blue-700 transition">
                Filter
            </button>
        </form>
    </div>

    {{-- === LIST LAMARAN === --}}
    <div class="space-y-5">
        @forelse($lamaran as $l)
        <div class="bg-white rounded-xl shadow-sm p-5 hover:shadow-md transition">
            <div class="flex flex-col md:flex-row gap-4">

                {{-- Logo --}}
                <div class="flex-shrink-0">
                    @if($l->lowongan->perusahaan->logo)
                        <img src="{{ Storage::url($l->lowongan->perusahaan->logo) }}"
                             class="w-16 h-16 rounded-lg object-cover">
                    @else
                        <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center text-xl font-bold">
                            {{ substr($l->lowongan->perusahaan->nama_perusahaan,0,1) }}
                        </div>
                    @endif
                </div>

                {{-- Content --}}
                <div class="flex-1 space-y-3">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
                        <div>
                            <h3 class="text-lg font-semibold">{{ $l->lowongan->judul }}</h3>
                            <p class="text-sm text-gray-500">{{ $l->lowongan->perusahaan->nama_perusahaan }}</p>
                        </div>
                       
                       <span
                        @class([
                            'px-3 py-3 text-xs rounded-full font-medium',

                            'bg-blue-100 text-blue-700' => $l->status === 'dikirim',
                            'bg-cyan-100 text-cyan-700' => $l->status === 'dilihat',
                            'bg-yellow-100 text-yellow-700' => $l->status === 'diproses',
                            'bg-green-100 text-green-700' => $l->status === 'diterima',
                            'bg-red-100 text-red-700' => $l->status === 'ditolak',
                        ])
                    >
                        {{ ucfirst($l->status) }}
                    </span>


                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
                        <div><p class="text-gray-400">Tanggal</p><p class="font-medium">{{ $l->tanggal_melamar->format('d M Y') }}</p></div>
                        <div><p class="text-gray-400">Posisi</p><p class="font-medium">{{ $l->lowongan->posisi }}</p></div>
                        <div><p class="text-gray-400">Tipe</p><p class="font-medium">{{ ucfirst(str_replace('_',' ',$l->lowongan->tipe_pekerjaan)) }}</p></div>
                        <div><p class="text-gray-400">Lokasi</p><p class="font-medium">{{ $l->lowongan->lokasi }}</p></div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pt-3 border-t gap-3">
                        <div class="flex gap-3 text-sm">
                            @foreach(['cv'=>'CV','surat_lamaran'=>'Surat','portofolio'=>'Portofolio'] as $f=>$lbl)
                                @if($l->$f)
                                <a href="{{ Storage::url($l->$f) }}" target="_blank"
                                   class="text-blue-600 hover:underline">{{ $lbl }}</a>
                                @endif
                            @endforeach
                        </div>

                        <div class="flex gap-3">
                            <a href="{{ route('siswa.lowongan.show',$l->lowongan->id) }}"
                               class="text-blue-600 font-medium hover:underline">Detail</a>

                            @if(in_array($l->status,['dikirim','dilihat']))
                            <form method="POST" action="{{ route('siswa.lamaran.destroy',$l->id) }}"
                                  onsubmit="return confirm('Batalkan lamaran ini?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 font-medium hover:underline">Batalkan</button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl shadow-sm p-10 text-center">
            <p class="text-lg font-semibold">Belum Ada Lamaran</p>
            <p class="text-gray-500 mt-1 mb-4">Mulai cari lowongan yang sesuai minat Anda</p>
            <a href="{{ route('siswa.lowongan.index') }}"
               class="inline-block bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700">
                Cari Lowongan
            </a>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="flex justify-center">
        {{ $lamaran->links() }}
    </div>
</div>
@endsection
