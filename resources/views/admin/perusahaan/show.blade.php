@extends('layouts.app')

@section('title', 'Detail Perusahaan')

@section('content')
<div class="px-4 py-6 mx-auto max-w-7xl">

    <!-- HEADER -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Detail Perusahaan</h1>
            <nav class="text-sm text-gray-500 mt-1">
                <a href="{{ route('admin.perusahaan.index') }}" class="hover:text-blue-600">Perusahaan</a>
                <span class="mx-1">/</span>
                <span class="text-gray-700">{{ $perusahaan->nama_perusahaan }}</span>
            </nav>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.perusahaan.edit', $perusahaan) }}"
               class="px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                ✏️ Edit
            </a>
            <a href="{{ route('admin.perusahaan.index') }}"
               class="px-4 py-2 text-sm border rounded-lg hover:bg-gray-100 transition">
                ⬅️ Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- KIRI -->
        <div class="space-y-6">

            <!-- INFO PERUSAHAAN -->
            <div class="bg-white rounded-2xl shadow p-6">
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between mb-4">
        <h2 class="font-semibold text-blue-600">Informasi Perusahaan</h2>
        
        <span class="self-start px-3 py-1 text-xs rounded-full font-medium
            {{ $perusahaan->status_kerjasama === 'aktif' ? 'bg-green-100 text-green-700' :
               ($perusahaan->status_kerjasama === 'pending' ? 'bg-yellow-100 text-yellow-700' :
               'bg-red-100 text-red-700') }}">
            {{ ucfirst($perusahaan->status_kerjasama) }}
        </span>
    </div>

    <div class="mb-5 p-3 bg-gray-50 rounded-xl border border-dashed border-gray-200">
        <p class="text-xs text-gray-500 mb-2 font-medium">Aksi Status Kerjasama:</p>
        <form action="{{ route('admin.perusahaan.update', $perusahaan) }}" method="POST">
            @csrf
            @method('PATCH')
            
            @if($perusahaan->status_kerjasama !== 'aktif')
                <input type="hidden" name="status_kerjasama" value="aktif">
                <button type="submit" class="w-full text-center px-4 py-2 text-sm bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition shadow-sm flex items-center justify-center gap-1">
                    ✅ Aktifkan Perusahaan
                </button>
            @else
                <div class="flex gap-2">
                    <select name="status_kerjasama" onchange="this.form.submit()" class="w-full text-sm bg-white border rounded-lg px-2 py-1.5 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="aktif" selected>🟢 Aktif</option>
                        <option value="nonaktif">🔴 Nonaktif</option>
                        <option value="pending">🟡 Pending</option>
                    </select>
                </div>
            @endif
        </form>
    </div>

    <div class="text-center">
        @if($perusahaan->logo)
            <img src="{{ asset('storage/'.$perusahaan->logo) }}"
                 class="mx-auto h-36 object-contain mb-4 rounded-xl">
        @else
            <div class="h-36 flex items-center justify-center bg-gray-100 rounded-xl mb-4">
                <i class="fas fa-building text-4xl text-gray-400"></i>
            </div>
        @endif

        <h3 class="text-lg font-bold">{{ $perusahaan->nama_perusahaan }}</h3>
        <p class="text-sm text-gray-500">{{ $perusahaan->bidang_usaha }}</p>
    </div>

    <div class="mt-5 text-sm text-gray-600 space-y-2">
        <p><strong>Email:</strong> {{ $perusahaan->email }}</p>
        <p><strong>Telepon:</strong> {{ $perusahaan->no_telp ?? '-' }}</p>
        <p>
            <strong>Website:</strong>
            @if($perusahaan->website)
                <a href="{{ $perusahaan->website }}" class="text-blue-600 hover:underline" target="_blank">
                    {{ Str::limit($perusahaan->website, 30) }}
                </a>
            @else -
            @endif
        </p>
        <p><strong>Kota:</strong> {{ $perusahaan->kota ?? '-' }}</p>
    </div>
</div>

            <!-- PIC -->
            <div class="bg-white rounded-2xl shadow p-6">
                <h2 class="font-semibold text-blue-600 mb-3">Person in Charge</h2>
                <div class="text-sm text-gray-700 space-y-2">
                    <p><strong>Nama:</strong> {{ $perusahaan->nama_pic ?? '-' }}</p>
                    <p><strong>Jabatan:</strong> {{ $perusahaan->jabatan_pic ?? '-' }}</p>
                    <p><strong>Email:</strong> {{ $perusahaan->email_pic ?? '-' }}</p>
                    <p><strong>Telepon:</strong> {{ $perusahaan->no_telp_pic ?? '-' }}</p>
                </div>
            </div>
        </div>

        <!-- KANAN -->
        <div class="lg:col-span-2 space-y-6">

            <!-- ALAMAT -->
            <div class="bg-white rounded-2xl shadow p-6">
                <h2 class="font-semibold text-blue-600 mb-2">Alamat & Deskripsi</h2>
                <p class="text-gray-700">{{ $perusahaan->alamat ?? '-' }}</p>
                <p class="text-sm text-gray-500 mt-1">
                    {{ $perusahaan->kota }} {{ $perusahaan->provinsi ? ', '.$perusahaan->provinsi : '' }}
                </p>
                <hr class="my-4">
                <p class="text-gray-600">
                    {{ $perusahaan->deskripsi ?? 'Tidak ada deskripsi perusahaan.' }}
                </p>
            </div>

            <!-- STAT -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                @php
                    $stats = [
                        ['Lowongan Aktif', $perusahaan->lowonganKerja()->where('status','aktif')->count(), 'briefcase'],
                        ['Mahasiswa PKL', $perusahaan->pkl()->whereIn('status',['berlangsung','diterima'])->count(), 'user-graduate'],
                        ['Kerjasama', $perusahaan->kerjasamaIndustri()->whereIn('status',['aktif','negosiasi'])->count(), 'handshake'],
                    ];
                @endphp

                @foreach($stats as $s)
                <div class="bg-white rounded-2xl shadow p-5 flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-500">{{ $s[0] }}</p>
                        <h3 class="text-2xl font-bold text-gray-800">{{ $s[1] }}</h3>
                    </div>
                    <i class="fas fa-{{ $s[2] }} text-3xl text-blue-500"></i>
                </div>
                @endforeach
            </div>

            <!-- TABS (SIMPLE) -->
            <div class="bg-white rounded-2xl shadow p-6">
                <h2 class="font-semibold text-blue-600 mb-4">Aktivitas Perusahaan</h2>
                <p class="text-sm text-gray-500">
                    Lowongan Kerja, PKL, dan Kerjasama tetap menggunakan logic yang sama seperti sebelumnya.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
