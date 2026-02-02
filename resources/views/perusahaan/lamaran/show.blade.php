@extends('layouts.app')

@section('title', 'Detail Lamaran')
@section('page-title', 'Detail Lamaran')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">

    <!-- Back Button -->
    <a href="{{ route('perusahaan.lamaran.index') }}" 
       class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- MAIN CONTENT -->
        <div class="lg:col-span-2 space-y-8">

            <!-- Profil Pelamar -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-start space-x-4 mb-6">
                    <div class="w-20 h-20 rounded-full bg-blue-100 flex items-center justify-center 
                                text-blue-600 text-3xl font-bold">
                        {{ substr($lamaran->mahasiswa->user->name, 0, 1) }}
                    </div>
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-gray-900">{{ $lamaran->mahasiswa->user->name }}</h2>
                        <p class="text-gray-600 mt-1">NIM: {{ $lamaran->mahasiswa->nim }}</p>

                        <div class="flex flex-wrap gap-2 mt-3">
                            <span class="px-3 py-1 text-sm bg-blue-100 text-blue-700 rounded-full">
                                {{ $lamaran->mahasiswa->fakultas->nama }}
                            </span>
                            <span class="px-3 py-1 text-sm bg-indigo-100 text-indigo-700 rounded-full">
                                {{ $lamaran->mahasiswa->programStudi->nama }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="font-medium text-gray-900">{{ $lamaran->mahasiswa->user->email }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">No. Telepon</p>
                        <p class="font-medium text-gray-900">{{ $lamaran->mahasiswa->no_telp ?? '-' }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Tanggal Lahir</p>
                        <p class="font-medium text-gray-900">
                            {{ $lamaran->mahasiswa->tanggal_lahir ? $lamaran->mahasiswa->tanggal_lahir->format('d F Y') : '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Alamat</p>
                        <p class="font-medium text-gray-900">{{ $lamaran->mahasiswa->alamat ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Dokumen Lamaran -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Dokumen Lamaran</h3>

                @php 
                    $documents = [
                        'cv' => ['name' => 'Curriculum Vitae', 'color' => 'red'],
                        'surat_lamaran' => ['name' => 'Surat Lamaran', 'color' => 'blue'],
                        'portofolio' => ['name' => 'Portofolio', 'color' => 'purple']
                    ];
                @endphp

                <div class="space-y-4">
                    @foreach($documents as $key => $doc)
                        @if($lamaran->$key)
                        <a href="{{ Storage::url($lamaran->$key) }}" 
                           target="_blank"
                           class="flex items-center justify-between p-4 border border-gray-200 rounded-lg 
                                  hover:bg-gray-50 transition">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 rounded-lg flex items-center justify-center bg-{{ $doc['color'] }}-100">
                                    <svg class="w-6 h-6 text-{{ $doc['color'] }}-600" fill="none" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M7 21h10a2 2 0 002-2V9.4a1 1 0 00-.3-.7l-5.4-5.4A1 1 0 0012.6 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $doc['name'] }}</p>
                                    <p class="text-sm text-gray-500">Klik untuk melihat</p>
                                </div>
                            </div>

                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                        </a>
                        @endif
                    @endforeach

                    @if(!$lamaran->cv && !$lamaran->surat_lamaran && !$lamaran->portofolio)
                        <p class="text-center text-gray-500 py-6">Tidak ada dokumen dilampirkan</p>
                    @endif
                </div>
            </div>

            <!-- Catatan -->
            @if($lamaran->catatan)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Catatan Pelamar</h3>
                <p class="text-gray-700 whitespace-pre-line">{{ $lamaran->catatan }}</p>
            </div>
            @endif
        </div>

        <!-- SIDEBAR -->
        <div class="space-y-8">

            <!-- Info Lowongan -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Info Lowongan</h3>

                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-500">Posisi</p>
                        <p class="font-semibold text-gray-900">{{ $lamaran->lowongan->judul }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Tanggal Melamar</p>
                        <p class="font-medium text-gray-900">{{ $lamaran->tanggal_melamar->format('d F Y, H:i') }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500 mb-2">Status Saat Ini</p>
                        <span class="
                            px-3 py-1 text-sm rounded-full font-medium
                            @if($lamaran->status == 'dikirim') bg-yellow-100 text-yellow-700
                            @elseif($lamaran->status == 'dilihat' || $lamaran->status == 'diproses') bg-blue-100 text-blue-700
                            @elseif($lamaran->status == 'diterima') bg-green-100 text-green-700
                            @else bg-red-100 text-red-700
                            @endif
                        ">
                            {{ ucfirst($lamaran->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Ubah Status -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Ubah Status</h3>

                <form action="{{ route('perusahaan.lamaran.status', $lamaran->id) }}" method="POST">
                    @csrf

                    <div class="space-y-3">
                        <div>
                            
                            <select name="status" 
                                class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2">
                                <option value="dilihat" {{ $lamaran->status == 'dilihat' ? 'selected' : '' }}>Dilihat</option>
                                <option value="diproses" {{ $lamaran->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                <option value="diterima" {{ $lamaran->status == 'diterima' ? 'selected' : '' }}>Diterima</option>
                                <option value="ditolak" {{ $lamaran->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>

                        

                        <button type="submit"
                            class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition font-semibold">
                            Update Status
                        </button>
                    </div>
                </form>
            </div>

            <!-- Contact Info -->
            <div class="bg-blue-50 rounded-xl border border-blue-200 p-6">
                <h4 class="font-semibold text-gray-900 mb-4">Hubungi Pelamar</h4>

                <div class="space-y-3 text-sm">
                    <a href="mailto:{{ $lamaran->mahasiswa->user->email }}"
                       class="flex items-center text-blue-700 hover:underline">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        {{ $lamaran->mahasiswa->user->email }}
                    </a>

                    @if($lamaran->mahasiswa->no_telp)
                    <a href="tel:{{ $lamaran->mahasiswa->no_telp }}"
                       class="flex items-center text-blue-700 hover:underline">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M3 5a2 2 0 012-2h3.3a1 1 0 01.9.7l1.5 4.5a1 1 0 01-.5 1.2l-2.3 1.1a11 11 0 005.5 5.5l1.1-2.3a1 1 0 011.2-.5l4.5 1.5a1 1 0 01.7.9V19a2 2 0 01-2 2h-1C9.7 21 3 14.3 3 6V5z"/>
                        </svg>
                        {{ $lamaran->mahasiswa->no_telp }}
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
