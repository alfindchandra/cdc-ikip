@extends('layouts.app')

@section('title', 'Kerjasama Industri')
@section('page-title', 'Kerjasama Industri')

@section('content')
<div class="space-y-8 p-4 sm:p-6 lg:p-8">

    <!-- Header dengan Statistik -->
    <div class="bg-gradient-to-r from-indigo-600 to-blue-600 rounded-2xl shadow-xl p-8 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-3xl font-extrabold mb-2">Kerjasama Industri</h2>
                <p class="text-white/90">Program kerjasama antara {{ auth()->user()->perusahaan->nama_perusahaan }} dengan sekolah</p>
            </div>
            <div class="mt-4 md:mt-0 grid grid-cols-2 gap-4">
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 text-center">
                    <p class="text-sm font-medium text-white/80">Total Kerjasama</p>
                    <p class="text-3xl font-extrabold mt-1">{{ $kerjasama->total() }}</p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 text-center">
                    <p class="text-sm font-medium text-white/80">Aktif</p>
                    <p class="text-3xl font-extrabold mt-1">{{ $kerjasama->where('status', 'aktif')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-6">
        @php
            $stats = [
                ['status' => 'draft', 'label' => 'Draft', 'color' => 'gray', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                ['status' => 'proposal', 'label' => 'Proposal', 'color' => 'blue', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                ['status' => 'negosiasi', 'label' => 'Negosiasi', 'color' => 'yellow', 'icon' => 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4'],
                ['status' => 'aktif', 'label' => 'Aktif', 'color' => 'green', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['status' => 'selesai', 'label' => 'Selesai', 'color' => 'purple', 'icon' => 'M5 13l4 4L19 7'],
            ];
        @endphp

        @foreach($stats as $stat)
        <div class="card bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition duration-300 border border-{{ $stat['color'] }}-100">
            <div class="text-center">
                <div class="w-12 h-12 bg-{{ $stat['color'] }}-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-{{ $stat['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stat['icon'] }}"/>
                    </svg>
                </div>
                <p class="text-xs font-medium text-gray-500 mb-1 uppercase">{{ $stat['label'] }}</p>
                <p class="text-3xl font-extrabold text-{{ $stat['color'] }}-600">{{ $kerjasama->where('status', $stat['status'])->count() }}</p>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Kerjasama Table -->
    <div class="card bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Judul & Jenis</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Periode</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider hidden lg:table-cell">PIC</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($kerjasama as $k)
                    <tr class="hover:bg-indigo-50/30 transition duration-100">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col">
                                <p class="font-medium text-gray-900 truncate max-w-xs">{{ $k->judul }}</p>
                                <span class="badge inline-flex items-center px-2 py-0.5 mt-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 w-fit">
                                    {{ ucfirst($k->jenis_kerjasama) }}
                                </span>
                                @if($k->nilai_kontrak)
                                <p class="text-xs text-green-600 font-semibold mt-1">
                                    Nilai: Rp {{ number_format($k->nilai_kontrak, 0, ',', '.') }}
                                </p>
                                @endif
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            <p class="font-medium">{{ $k->tanggal_mulai->format('d M Y') }}</p>
                            @if($k->tanggal_berakhir)
                            <p class="text-xs text-gray-500">s/d {{ $k->tanggal_berakhir->format('d M Y') }}</p>
                            @else
                            <p class="text-xs text-gray-500 italic">Tidak terbatas</p>
                            @endif
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 hidden lg:table-cell">
                            <div class="space-y-1">
                                @if($k->pic_sekolah)
                                <p><span class="text-xs text-gray-500">Sekolah:</span> <strong>{{ $k->pic_sekolah }}</strong></p>
                                @endif
                                @if($k->pic_industri)
                                <p><span class="text-xs text-gray-500">Industri:</span> <strong>{{ $k->pic_industri }}</strong></p>
                                @endif
                                @if(!$k->pic_sekolah && !$k->pic_industri)
                                <p class="text-gray-500 italic">-</p>
                                @endif
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $status_map = ['aktif' => 'success', 'proposal' => 'warning', 'negosiasi' => 'warning', 'selesai' => 'info', 'batal' => 'danger', 'draft' => 'gray'];
                                $status_color = $status_map[$k->status] ?? 'gray';
                            @endphp

                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                @if($status_color == 'success') bg-green-100 text-green-800
                                @elseif($status_color == 'warning') bg-yellow-100 text-yellow-800
                                @elseif($status_color == 'info') bg-blue-100 text-blue-800
                                @elseif($status_color == 'danger') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($k->status) }}
                            </span>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center space-x-3">
                                <a href="{{ route('perusahaan.kerjasama.show', $k->id) }}" class="text-blue-600 hover:text-blue-800 p-1 rounded-full hover:bg-blue-50 transition duration-150" title="Detail">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                @if($k->dokumen_mou)
                                <a href="{{ Storage::url($k->dokumen_mou) }}" target="_blank" class="text-green-600 hover:text-green-800 p-1 rounded-full hover:bg-green-50 transition duration-150" title="Lihat Dokumen MoU">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-12 text-gray-500">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10m0-10l4-2m-4 2l-4-2m0 0v10l4 2m4-2v10l-4 2"/>
                            </svg>
                            <p class="text-lg font-semibold">Belum ada data kerjasama</p>
                            <p class="text-sm mt-1">Data kerjasama akan muncul di sini setelah admin menambahkan kerjasama untuk perusahaan Anda.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($kerjasama->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
            {{ $kerjasama->links() }}
        </div>
        @endif
    </div>
</div>
@endsection