@extends('layouts.app')

@section('title', 'Detail Pelatihan')
@section('page-title', 'Detail Pelatihan')

@section('content')
<div class="max-w-6xl mx-auto p-4 sm:p-6 lg:p-8 space-y-8">
    
    <a href="{{ route('admin.pelatihan.index') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 font-medium transition duration-150">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Kembali ke Daftar Pelatihan
    </a>

    <div class="bg-white shadow-2xl rounded-xl overflow-hidden">
        @if($pelatihan->thumbnail)
        <img src="{{ Storage::url($pelatihan->thumbnail) }}" alt="{{ $pelatihan->judul }}" class="w-full h-64 object-cover">
        @else
        <div class="w-full h-64 bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
            <svg class="w-16 h-16 text-white opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.206 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.794 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.794 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.206 18 16.5 18s-3.332.477-4.5 1.253"/></svg>
        </div>
        @endif

        <div class="p-6 sm:p-8">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between space-y-4 md:space-y-0">
                <div class="flex-1">
                    <div class="flex flex-wrap items-center gap-3 mb-3">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800">
                            {{ ucfirst(str_replace('_', ' ', $pelatihan->jenis)) }}
                        </span>
                        @php
                            $status_color = match($pelatihan->status) {
                                'published' => 'green',
                                'ongoing' => 'blue',
                                'completed' => 'purple',
                                'cancelled' => 'red',
                                default => 'yellow',
                            };
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-{{ $status_color }}-100 text-{{ $status_color }}-800">
                            {{ ucfirst($pelatihan->status) }}
                        </span>
                    </div>
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-2">{{ $pelatihan->judul }}</h1>
                </div>
                
                <div class="flex space-x-3 flex-shrink-0">
                    <a href="{{ route('admin.pelatihan.edit', $pelatihan->id) }}" class="btn bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-4 rounded-lg shadow-md flex items-center transition duration-150">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit
                    </a>
                    @if($pelatihan->status == 'draft' || $pelatihan->status == 'cancelled')
                    <form action="{{ route('admin.pelatihan.publish', $pelatihan->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg shadow-md transition duration-150">
                            Publikasikan
                        </button>
                    </form>
                    @endif
                </div>
            </div>

            <div class="mt-6 prose max-w-none text-gray-700 leading-relaxed border-t pt-6 border-gray-100">
                <p>{{ $pelatihan->deskripsi }}</p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 mt-6 pt-6 border-t border-gray-100">
                <div class="border-l-4 border-indigo-200 pl-3">
                    <p class="text-xs text-gray-500 uppercase font-semibold">Instruktur</p>
                    <p class="font-bold text-gray-900 mt-1">{{ $pelatihan->instruktur ?? 'Belum Ditentukan' }}</p>
                </div>
                <div class="border-l-4 border-indigo-200 pl-3">
                    <p class="text-xs text-gray-500 uppercase font-semibold">Lokasi</p>
                    <p class="font-bold text-gray-900 mt-1">{{ $pelatihan->tempat ?? 'Daring/Offline' }}</p>
                </div>
                <div class="border-l-4 border-indigo-200 pl-3">
                    <p class="text-xs text-gray-500 uppercase font-semibold">Mulai Pelatihan</p>
                    <p class="font-bold text-gray-900 mt-1">{{ $pelatihan->tanggal_mulai->format('d M Y') }}</p>
                    <p class="text-sm text-gray-500">{{ $pelatihan->tanggal_mulai->format('H:i') }} WIB</p>
                </div>
                <div class="border-l-4 border-indigo-200 pl-3">
                    <p class="text-xs text-gray-500 uppercase font-semibold">Durasi</p>
                   @php
                        $diffInDays = $pelatihan->tanggal_mulai->diffInDays($pelatihan->tanggal_selesai);
                    @endphp
                    <p class="font-bold text-gray-900 mt-1">{{ $diffInDays }} hari</p>

                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-1 space-y-8">
            
            <div class="bg-white shadow-lg rounded-xl p-6 space-y-6 border border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c1.657 0 3 1.343 3 3v2a3 3 0 01-3 3m0 0a3 3 0 00-3 3m3-3m-3 3v2m-3-2v-2a3 3 0 013-3m3 3a3 3 0 01-3-3v-2c0-1.657 1.343-3 3-3"/></svg>
                    Informasi Kunci
                </h3>
                
                <div class="pt-4 border-t border-gray-100">
                    <div class="flex justify-between items-center mb-1">
                        <p class="text-sm text-gray-600 font-medium">Kuota Peserta</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $pelatihan->jumlah_peserta }} / {{ $pelatihan->kuota ?? 'Tidak Terbatas' }}</p>
                    </div>
                    @if($pelatihan->kuota)
                    @php
                        $percentage = ($pelatihan->jumlah_peserta / $pelatihan->kuota) * 100;
                        $progress_color = $percentage < 80 ? 'bg-indigo-600' : 'bg-red-500';
                    @endphp
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="{{ $progress_color }} h-2 rounded-full transition-all duration-500" style="width: {{ min(100, $percentage) }}%"></div>
                    </div>
                    @endif
                </div>

                <div class="pt-4 border-t border-gray-100">
                    <p class="text-sm text-gray-600 font-medium mb-1">Biaya Pelatihan</p>
                    @if($pelatihan->biaya > 0)
                    <p class="text-2xl font-bold text-green-600">Rp {{ number_format($pelatihan->biaya, 0, ',', '.') }}</p>
                    @else
                    <span class="inline-flex items-center px-4 py-1 rounded-full text-lg font-extrabold bg-green-100 text-green-700">GRATIS</span>
                    @endif
                </div>
            </div>

            <div class="bg-white shadow-lg rounded-xl p-6 space-y-4 border border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Dokumen Pelatihan
                </h3>
                
                @if($pelatihan->materi)
                <div class="pt-2">
                    <a href="{{ Storage::url($pelatihan->materi) }}" target="_blank" class="flex items-center text-blue-600 hover:text-blue-800 font-medium transition duration-150">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Download Materi Pelatihan (PDF)
                    </a>
                </div>
                @else
                <p class="text-sm text-gray-500 italic">Materi belum diunggah.</p>
                @endif

                @if($pelatihan->sertifikat_template)
                <div class="pt-2">
                    <a href="{{ Storage::url($pelatihan->sertifikat_template) }}" target="_blank" class="flex items-center text-purple-600 hover:text-purple-800 font-medium transition duration-150">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Lihat Template Sertifikat (PDF)
                    </a>
                </div>
                @else
                <p class="text-sm text-gray-500 italic">Template sertifikat belum diunggah.</p>
                @endif
            </div>

        </div>

        <div class="lg:col-span-2">
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-100">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-800 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            Peserta Terdaftar (Total: {{ $pelatihan->jumlah_peserta }})
                        </h3>
                        <a href="{{ route('admin.pelatihan.peserta', $pelatihan->id) }}" class="btn bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-1.5 px-4 rounded-lg text-sm transition duration-150">
                            Kelola Semua Peserta
                        </a>
                    </div>
                </div>
                
                <div class="divide-y divide-gray-100">
                    @forelse($pelatihan->peserta()->latest()->take(10)->get() as $mahasiswa)
                    <div class="px-6 py-4 hover:bg-gray-50 transition duration-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 flex-shrink-0 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-lg">
                                    {{ substr($mahasiswa->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $mahasiswa->user->name }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $mahasiswa->kelas }} - {{ $mahasiswa->jurusan }}</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                @if($mahasiswa->pivot->status_pendaftaran == 'diterima') bg-green-100 text-green-800
                                @elseif($mahasiswa->pivot->status_pendaftaran == 'menunggu') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($mahasiswa->pivot->status_pendaftaran) }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="px-6 py-12 text-center text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <p class="font-medium">Belum ada peserta terdaftar.</p>
                        <p class="text-sm mt-1">Mahasiswa dapat mulai mendaftar ketika pelatihan berstatus **Published**.</p>
                    </div>
                    @endforelse
                </div>
                
                @if($pelatihan->jumlah_peserta > 10)
                <div class="p-4 text-center border-t border-gray-100 bg-gray-50">
                    <a href="{{ route('admin.pelatihan.peserta', $pelatihan->id) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                        Lihat Semua Peserta ({{ $pelatihan->jumlah_peserta }}) →
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection