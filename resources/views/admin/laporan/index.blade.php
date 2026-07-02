@extends('layouts.app')

@section('title', 'Kelola Laporan')
@section('page-title', 'Kelola Laporan')

@section('content')
<div class="space-y-6">

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        @php
            $cards = [
                ['jenis' => 'alumni',    'label' => 'Alumni',       'count' => $stats['alumni'],    'color' => 'blue',   'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
                ['jenis' => 'lowongan',  'label' => 'Lowongan',     'count' => $stats['lowongan'],  'color' => 'purple', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                ['jenis' => 'lamaran',   'label' => 'Lamaran',      'count' => $stats['lamaran'],   'color' => 'yellow', 'icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                ['jenis' => 'pelatihan', 'label' => 'Pelatihan',    'count' => $stats['pelatihan'], 'color' => 'green',  'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                ['jenis' => 'kerjasama', 'label' => 'Kerja Sama',   'count' => $stats['kerjasama'], 'color' => 'red',    'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
            ];
        @endphp
        @foreach($cards as $card)
        <a href="{{ route('admin.laporan.index', ['jenis' => $card['jenis']]) }}"
           class="bg-white rounded-xl shadow-sm border-2 p-4 flex flex-col items-center text-center transition hover:shadow-md
                  {{ $jenis == $card['jenis'] ? 'border-'.$card['color'].'-500 bg-'.$card['color'].'-50' : 'border-gray-100 hover:border-'.$card['color'].'-300' }}">
            <div class="w-11 h-11 rounded-full bg-{{ $card['color'] }}-100 flex items-center justify-center mb-2">
                <svg class="w-6 h-6 text-{{ $card['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/>
                </svg>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($card['count']) }}</p>
            <p class="text-xs font-medium text-gray-500 mt-0.5">{{ $card['label'] }}</p>
        </a>
        @endforeach
    </div>

    {{-- Filter & Download Panel --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <form method="GET" action="{{ route('admin.laporan.index') }}" class="space-y-4">
            <input type="hidden" name="jenis" value="{{ $jenis }}">

            <div class="flex flex-wrap items-center gap-3">
                <span class="text-sm font-semibold text-gray-700">Filter:</span>

                {{-- Filter: Alumni --}}
                @if($jenis == 'alumni')
                <select name="tahun_lulus" class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Tahun Lulus</option>
                    @foreach(range(date('Y'), 2015) as $yr)
                    <option value="{{ $yr }}" {{ request('tahun_lulus') == $yr ? 'selected' : '' }}>{{ $yr }}</option>
                    @endforeach
                </select>
                <select name="fakultas_id" class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Fakultas</option>
                    @foreach($fakultas as $fak)
                    <option value="{{ $fak->id }}" {{ request('fakultas_id') == $fak->id ? 'selected' : '' }}>{{ $fak->nama }}</option>
                    @endforeach
                </select>
                @endif

                {{-- Filter: Lowongan --}}
                @if($jenis == 'lowongan')
                <select name="status" class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                </select>
                <select name="tipe_pekerjaan" class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Tipe</option>
                    <option value="full_time" {{ request('tipe_pekerjaan') == 'full_time' ? 'selected' : '' }}>Full Time</option>
                    <option value="part_time" {{ request('tipe_pekerjaan') == 'part_time' ? 'selected' : '' }}>Part Time</option>
                    <option value="kontrak" {{ request('tipe_pekerjaan') == 'kontrak' ? 'selected' : '' }}>Kontrak</option>
                    <option value="magang" {{ request('tipe_pekerjaan') == 'magang' ? 'selected' : '' }}>Magang</option>
                </select>
                @endif

                {{-- Filter: Lamaran --}}
                @if($jenis == 'lamaran')
                <select name="status" class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="diterima" {{ request('status') == 'diterima' ? 'selected' : '' }}>Diterima</option>
                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    <option value="wawancara" {{ request('status') == 'wawancara' ? 'selected' : '' }}>Wawancara</option>
                </select>
                @endif

                {{-- Filter: Pelatihan --}}
                @if($jenis == 'pelatihan')
                <select name="jenis_pelatihan" class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Jenis</option>
                    <option value="online" {{ request('jenis_pelatihan') == 'online' ? 'selected' : '' }}>Online</option>
                    <option value="offline" {{ request('jenis_pelatihan') == 'offline' ? 'selected' : '' }}>Offline</option>
                    <option value="hybrid" {{ request('jenis_pelatihan') == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                </select>
                <select name="status" class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Status</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
                @endif

                {{-- Filter: Kerjasama --}}
                @if($jenis == 'kerjasama')
                <select name="status" class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="berakhir" {{ request('status') == 'berakhir' ? 'selected' : '' }}>Berakhir</option>
                </select>
                <select name="jenis_kerjasama" class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Jenis</option>
                    <option value="magang" {{ request('jenis_kerjasama') == 'magang' ? 'selected' : '' }}>Magang</option>
                    <option value="rekrutmen" {{ request('jenis_kerjasama') == 'rekrutmen' ? 'selected' : '' }}>Rekrutmen</option>
                    <option value="pelatihan" {{ request('jenis_kerjasama') == 'pelatihan' ? 'selected' : '' }}>Pelatihan</option>
                    <option value="penelitian" {{ request('jenis_kerjasama') == 'penelitian' ? 'selected' : '' }}>Penelitian</option>
                </select>
                @endif

                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition">
                    Terapkan Filter
                </button>
                <a href="{{ route('admin.laporan.index', ['jenis' => $jenis]) }}"
                   class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg transition">
                    Reset
                </a>
            </div>
        </form>

        {{-- Download Button --}}
        <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between flex-wrap gap-3">
            <p class="text-sm text-gray-500">
                Menampilkan data <span class="font-semibold text-gray-800">{{ ucfirst($jenis) }}</span>.
                Download akan mengikuti filter yang aktif.
            </p>
            <a href="{{ route('admin.laporan.download', array_merge(['jenis' => $jenis], request()->except(['page']))) }}"
               class="inline-flex items-center px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-semibold text-sm rounded-lg shadow transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Download PDF
            </a>
        </div>
    </div>

    {{-- Data Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-bold text-gray-900 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Laporan {{ ucfirst($jenis) }}
            </h2>
            <span class="text-sm text-gray-500">Total: <strong>{{ $data->total() }}</strong> data</span>
        </div>

        <div class="overflow-x-auto">

            {{-- ===== ALUMNI ===== --}}
            @if($jenis == 'alumni')
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">No</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">NIM</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nama</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Email</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Fakultas</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Program Studi</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Th. Masuk</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Th. Lulus</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($data as $i => $m)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $data->firstItem() + $i }}</td>
                        <td class="px-4 py-3 text-sm font-mono text-gray-700">{{ $m->nim }}</td>
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $m->user->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $m->user->email ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $m->fakultas->nama ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $m->programStudi->nama_prodi ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm text-center text-gray-700">{{ $m->tahun_masuk }}</td>
                        <td class="px-4 py-3 text-sm text-center text-gray-700">{{ $m->tahun_lulus ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-12 text-gray-400">Tidak ada data alumni ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
            @endif

            {{-- ===== LOWONGAN ===== --}}
            @if($jenis == 'lowongan')
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">No</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Judul / Posisi</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Perusahaan</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Tipe</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Pelamar</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Batas Lamaran</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($data as $i => $l)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $data->firstItem() + $i }}</td>
                        <td class="px-4 py-3">
                            <p class="text-sm font-medium text-gray-900">{{ $l->judul }}</p>
                            <p class="text-xs text-gray-500">{{ $l->posisi }}</p>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ $l->perusahaan->nama_perusahaan ?? '-' }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-indigo-100 text-indigo-700 font-medium">
                                {{ ucfirst(str_replace('_',' ',$l->tipe_pekerjaan)) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-center font-semibold text-blue-700">{{ $l->jumlah_pelamar }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $l->tanggal_berakhir ? $l->tanggal_berakhir->format('d M Y') : '-' }}</td>
                        <td class="px-4 py-3 text-center">
                            @php $sc = ['aktif'=>'green','nonaktif'=>'yellow','expired'=>'red'][$l->status] ?? 'gray'; @endphp
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-{{ $sc }}-100 text-{{ $sc }}-700 font-medium">{{ ucfirst($l->status) }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-12 text-gray-400">Tidak ada data lowongan ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
            @endif

            {{-- ===== LAMARAN ===== --}}
            @if($jenis == 'lamaran')
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">No</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nama Pelamar</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Posisi / Perusahaan</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal Melamar</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($data as $i => $lm)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $data->firstItem() + $i }}</td>
                        <td class="px-4 py-3">
                            <p class="text-sm font-medium text-gray-900">{{ $lm->mahasiswa->user->name ?? '-' }}</p>
                            <p class="text-xs text-gray-500">{{ $lm->mahasiswa->nim ?? '-' }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <p class="text-sm text-gray-900">{{ $lm->lowongan->posisi ?? '-' }}</p>
                            <p class="text-xs text-gray-500">{{ $lm->lowongan->perusahaan->nama_perusahaan ?? '-' }}</p>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $lm->tanggal_melamar ? $lm->tanggal_melamar->format('d M Y') : '-' }}</td>
                        <td class="px-4 py-3 text-center">
                            @php $sc = ['pending'=>'yellow','diterima'=>'green','ditolak'=>'red','wawancara'=>'blue'][$lm->status] ?? 'gray'; @endphp
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-{{ $sc }}-100 text-{{ $sc }}-700 font-medium">{{ ucfirst($lm->status) }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-12 text-gray-400">Tidak ada data lamaran ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
            @endif

            {{-- ===== PELATIHAN ===== --}}
            @if($jenis == 'pelatihan')
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">No</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Judul Pelatihan</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Instruktur</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Jenis</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Peserta</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($data as $i => $p)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $data->firstItem() + $i }}</td>
                        <td class="px-4 py-3">
                            <p class="text-sm font-medium text-gray-900">{{ $p->judul }}</p>
                            <p class="text-xs text-gray-500">{{ $p->tempat ?? '-' }}</p>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ $p->instruktur ?? '-' }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-700 font-medium">{{ ucfirst($p->jenis) }}</span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            {{ $p->tanggal_mulai ? $p->tanggal_mulai->format('d M Y') : '-' }}
                        </td>
                        <td class="px-4 py-3 text-center text-sm font-semibold text-blue-700">{{ $p->jumlah_peserta }}</td>
                        <td class="px-4 py-3 text-center">
                            @php $sc = ['draft'=>'yellow','published'=>'green','selesai'=>'gray'][$p->status] ?? 'gray'; @endphp
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-{{ $sc }}-100 text-{{ $sc }}-700 font-medium">{{ ucfirst($p->status) }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-12 text-gray-400">Tidak ada data pelatihan ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
            @endif

            {{-- ===== KERJASAMA ===== --}}
            @if($jenis == 'kerjasama')
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">No</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Judul</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Perusahaan</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Jenis</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Periode</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($data as $i => $k)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $data->firstItem() + $i }}</td>
                        <td class="px-4 py-3">
                            <p class="text-sm font-medium text-gray-900">{{ $k->judul }}</p>
                            <p class="text-xs text-gray-500">{{ $k->pic_sekolah ?? '' }}</p>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ $k->perusahaan->nama_perusahaan ?? '-' }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-yellow-100 text-yellow-700 font-medium">
                                {{ ucfirst(str_replace('_',' ',$k->jenis_kerjasama)) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            {{ $k->tanggal_mulai ? $k->tanggal_mulai->format('d M Y') : '-' }}
                            @if($k->tanggal_berakhir)
                            <span class="text-gray-400"> s/d </span>{{ $k->tanggal_berakhir->format('d M Y') }}
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            @php $sc = ['aktif'=>'green','pending'=>'yellow','berakhir'=>'red'][$k->status] ?? 'gray'; @endphp
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-{{ $sc }}-100 text-{{ $sc }}-700 font-medium">{{ ucfirst($k->status) }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-12 text-gray-400">Tidak ada data kerjasama ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
            @endif

        </div>

        {{-- Pagination --}}
        @if($data->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $data->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
