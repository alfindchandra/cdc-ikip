@extends('layouts.app')

@section('title', 'Detail Laporan')
@section('page-title', 'Detail Laporan')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <a href="{{ route('admin.laporan.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 transition-colors duration-200 font-medium group">
        <svg class="w-5 h-5 mr-2 transition-transform duration-200 group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali ke Daftar Laporan
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Header Card --}}
            <div class="bg-white rounded-xl shadow-lg border border-gray-100">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                        <div class="flex items-start space-x-4">
                            <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">{{ $laporan->judul }}</h1>
                                <div class="flex items-center flex-wrap gap-2 mt-2">
                                    @php
                                        $jenisColors = [
                                            'pkl'       => 'bg-purple-100 text-purple-800',
                                            'pelatihan' => 'bg-green-100 text-green-800',
                                            'rekrutmen' => 'bg-blue-100 text-blue-800',
                                            'kerjasama' => 'bg-yellow-100 text-yellow-800',
                                            'tahunan'   => 'bg-indigo-100 text-indigo-800',
                                            'lainnya'   => 'bg-gray-100 text-gray-800',
                                        ];
                                        $color = $jenisColors[$laporan->jenis] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $color }}">
                                        {{ ucfirst($laporan->jenis) }}
                                    </span>
                                    @if($laporan->status == 'published')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><circle cx="10" cy="10" r="5"/></svg>
                                            Dipublikasikan
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><circle cx="10" cy="10" r="5"/></svg>
                                            Draft
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-6 space-y-6">
                    {{-- Periode --}}
                    <div class="flex items-center space-x-3 text-sm text-gray-600">
                        <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="font-medium text-gray-700">Periode:</span>
                        <span>{{ $laporan->periode_mulai->format('d F Y') }} &mdash; {{ $laporan->periode_selesai->format('d F Y') }}</span>
                    </div>

                    {{-- Deskripsi --}}
                    @if($laporan->deskripsi)
                    <div>
                        <h2 class="text-base font-semibold text-gray-900 mb-2 flex items-center">
                            <span class="w-7 h-7 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mr-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h12"/>
                                </svg>
                            </span>
                            Deskripsi
                        </h2>
                        <div class="prose max-w-none text-gray-700 bg-gray-50 rounded-lg p-4 border border-gray-200 shadow-inner text-sm leading-relaxed">
                            {!! nl2br(e($laporan->deskripsi)) !!}
                        </div>
                    </div>
                    @endif

                    {{-- Informasi Proses --}}
                    <div>
                        <h2 class="text-base font-semibold text-gray-900 mb-3 flex items-center">
                            <span class="w-7 h-7 bg-green-50 text-green-600 rounded-full flex items-center justify-center mr-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </span>
                            Prosesnya sebagai berikut:
                        </h2>
                        <div class="bg-gray-50 rounded-lg border border-gray-200 p-4 text-sm text-gray-700">
                            <ol class="list-decimal ml-5 space-y-2">
                                <li>Sistem mengumpulkan data yang telah tersimpan, seperti:
                                    <ul class="list-disc ml-4 mt-1 space-y-0.5 text-gray-600">
                                        <li>Data alumni</li>
                                        <li>Data lowongan kerja</li>
                                        <li>Data lamaran</li>
                                        <li>Data pelatihan</li>
                                        <li>Data kerja sama</li>
                                    </ul>
                                </li>
                                <li>Admin memilih jenis laporan yang ingin ditampilkan.</li>
                                <li>Sistem mengolah data tersebut menjadi laporan.</li>
                                <li>Laporan dapat ditampilkan di layar atau dicetak/diunduh sebagai dokumentasi.</li>
                            </ol>
                        </div>
                    </div>

                    {{-- Contoh Laporan --}}
                    <div>
                        <h2 class="text-base font-semibold text-gray-900 mb-3">Contoh laporan yang dihasilkan:</h2>
                        <ul class="space-y-2 text-sm text-gray-700">
                            @foreach([
                                'Laporan jumlah alumni yang terdaftar.',
                                'Laporan lowongan kerja yang dipublikasikan.',
                                'Laporan jumlah pelamar pada setiap lowongan.',
                                'Laporan pelatihan yang telah dilaksanakan.',
                                'Laporan kerja sama dengan perusahaan.',
                            ] as $contoh)
                            <li class="flex items-start space-x-2">
                                <svg class="w-4 h-4 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/>
                                </svg>
                                <span>{{ $contoh }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            {{-- File Laporan --}}
            @if($laporan->file_laporan)
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
                <h2 class="text-base font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    File Laporan
                </h2>
                <div class="flex items-center justify-between bg-red-50 border border-red-200 rounded-lg px-4 py-3">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800 text-sm">{{ basename($laporan->file_laporan) }}</p>
                            <p class="text-xs text-gray-500">Dokumen PDF</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.laporan.download', $laporan->id) }}"
                       class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-lg transition duration-150">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Unduh
                    </a>
                </div>
            </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="lg:col-span-1 space-y-6">

            {{-- Aksi Cepat --}}
            <div class="bg-white rounded-xl shadow-lg border border-gray-100">
                <div class="p-4 bg-blue-50 rounded-t-xl border-b border-blue-100">
                    <h3 class="font-bold text-blue-900">Aksi Cepat</h3>
                </div>
                <div class="p-4 space-y-3">
                    <a href="{{ route('admin.laporan.edit', $laporan->id) }}"
                       class="w-full flex items-center justify-center px-4 py-2.5 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg transition duration-150">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Laporan
                    </a>

                    @if($laporan->status == 'draft')
                    <form action="{{ route('admin.laporan.publish', $laporan->id) }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="w-full flex items-center justify-center px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition duration-150">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Publikasikan
                        </button>
                    </form>
                    @endif

                    @if($laporan->file_laporan)
                    <a href="{{ route('admin.laporan.download', $laporan->id) }}"
                       class="w-full flex items-center justify-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition duration-150">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Unduh PDF
                    </a>
                    @endif

                    <form action="{{ route('admin.laporan.destroy', $laporan->id) }}" method="POST"
                          onsubmit="return confirm('Yakin ingin menghapus laporan ini? Aksi tidak dapat dibatalkan.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full flex items-center justify-center px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition duration-150">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Hapus Laporan
                        </button>
                    </form>
                </div>
            </div>

            {{-- Info Detail --}}
            <div class="bg-white rounded-xl shadow-lg border border-gray-100">
                <div class="p-4 bg-gray-50 rounded-t-xl border-b border-gray-100">
                    <h3 class="font-bold text-gray-900">Informasi Laporan</h3>
                </div>
                <div class="p-4 space-y-3">
                    @foreach([
                        'Jenis'          => ucfirst($laporan->jenis),
                        'Periode Mulai'  => $laporan->periode_mulai->format('d F Y'),
                        'Periode Selesai'=> $laporan->periode_selesai->format('d F Y'),
                        'Status'         => ucfirst($laporan->status),
                        'Dibuat Oleh'    => $laporan->creator?->name ?? '-',
                        'Tanggal Dibuat' => $laporan->created_at->format('d F Y, H:i'),
                        'Terakhir Diperbarui' => $laporan->updated_at->format('d F Y, H:i'),
                    ] as $label => $value)
                    <div class="flex justify-between items-start pb-2 border-b border-gray-100 last:border-b-0">
                        <p class="text-xs uppercase tracking-wider text-gray-500 font-medium">{{ $label }}</p>
                        <p class="font-semibold text-gray-800 text-sm text-right max-w-[55%]">{{ $value }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
