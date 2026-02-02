@extends('layouts.app')

@section('title', 'Detail PKL')
@section('page-title', 'Detail PKL Mahasiswa')

@section('content')
<div class="space-y-8">
    <!-- Back Button -->
    <a href="{{ route('perusahaan.pkl.index') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 font-medium transition duration-150 ease-in-out group">
        <svg class="w-5 h-5 mr-2 transform group-hover:-translate-x-1 transition duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali ke Daftar PKL
    </a>

    <!-- PKL Info Card -->
    <div class="bg-white p-6 md:p-8 rounded-2xl shadow-xl border-t-4 border-indigo-500">
        <div class="flex flex-col md:flex-row items-start justify-between mb-6 border-b pb-4">
            <div class="flex items-center space-x-4">
                <!-- Modern Avatar -->
                <div class="w-14 h-14 md:w-16 md:h-16 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 text-2xl font-bold flex-shrink-0">
                    {{ substr($pkl->mahasiswa->user->name, 0, 1) }}
                </div>
                <div>
                    <h2 class="text-xl md:text-2xl font-bold text-gray-900 mb-1">{{ $pkl->mahasiswa->user->name }}</h2>
                    <p class="text-sm md:text-base text-gray-600 mb-1">{{ $pkl->mahasiswa->nim }} - {{ $pkl->mahasiswa->programStudi->nama }}</p>
                    <p class="text-xs text-gray-500 font-medium">{{ $pkl->posisi ?? 'Peserta PKL' }}</p>
                </div>
            </div>
            <!-- Status Badge -->
            @php
                $status = strtolower($pkl->status);
                $badge_class = '';
                switch ($status) {
                    case 'pengajuan': $badge_class = 'bg-yellow-100 text-yellow-800 border-yellow-300'; break;
                    case 'diterima': $badge_class = 'bg-indigo-100 text-indigo-800 border-indigo-300'; break;
                    case 'berlangsung': $badge_class = 'bg-blue-100 text-blue-800 border-blue-300'; break;
                    case 'selesai': $badge_class = 'bg-green-100 text-green-800 border-green-300'; break;
                    default: $badge_class = 'bg-red-100 text-red-800 border-red-300'; break;
                }
            @endphp
            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold border mt-4 md:mt-0 {{ $badge_class }}">
                {{ ucfirst($status) }}
            </span>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="border-l-4 border-gray-100 pl-4">
                <p class="text-sm text-gray-500 mb-1 uppercase tracking-wider">Mulai PKL</p>
                <p class="font-bold text-gray-900">{{ $pkl->tanggal_mulai->format('d M Y') }}</p>
            </div>
            <div class="border-l-4 border-gray-100 pl-4">
                <p class="text-sm text-gray-500 mb-1 uppercase tracking-wider">Selesai PKL</p>
                <p class="font-bold text-gray-900">{{ $pkl->tanggal_selesai->format('d M Y') }}</p>
            </div>
            <div class="border-l-4 border-gray-100 pl-4">
                <p class="text-sm text-gray-500 mb-1 uppercase tracking-wider">Pembimbing Sekolah</p>
                <p class="font-semibold text-gray-900 truncate">{{ $pkl->pembimbing_sekolah ?? '-' }}</p>
            </div>
            <div class="border-l-4 border-gray-100 pl-4">
                <p class="text-sm text-gray-500 mb-1 uppercase tracking-wider">Pembimbing Industri</p>
                <p class="font-semibold text-gray-900 truncate">{{ $pkl->pembimbing_industri ?? '-' }}</p>
            </div>
        </div>

        @if($pkl->catatan)
        <div class="mt-8 p-4 bg-gray-50 border border-gray-200 rounded-xl">
            <p class="text-sm font-bold text-gray-700 mb-2 flex items-center">
                <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8h-6m14-8a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Catatan (dari Mahasiswa/Admin):
            </p>
            <p class="text-gray-600 whitespace-pre-line text-sm">{{ $pkl->catatan }}</p>
        </div>
        @endif
    </div>

    <!-- Actions for Pengajuan -->
    @if($pkl->status == 'pengajuan')
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Terima Pengajuan -->
        <div class="bg-white p-6 rounded-2xl shadow-xl border-t-4 border-green-500">
            <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center text-green-600">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Terima Pengajuan PKL
            </h3>
            <form action="{{ route('perusahaan.pkl.terima', $pkl->id) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="pembimbing_industri" class="block text-sm font-medium text-gray-700 mb-1">Pembimbing Industri <span class="text-red-500">*</span></label>
                    <input type="text" name="pembimbing_industri" id="pembimbing_industri" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 transition py-2 px-3" placeholder="Nama pembimbing dari perusahaan" required>
                </div>
                <div>
                    <label for="divisi" class="block text-sm font-medium text-gray-700 mb-1">Divisi/Bagian</label>
                    <input type="text" name="divisi" id="divisi" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 transition py-2 px-3" placeholder="Contoh: Departemen IT">
                </div>
                <button type="submit" class="w-full flex items-center justify-center bg-green-600 hover:bg-green-700 text-white font-semibold py-2.5 px-4 rounded-lg shadow-md transition duration-200 ease-in-out transform hover:scale-[1.01]">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Konfirmasi Penerimaan
                </button>
            </form>
        </div>

        <!-- Tolak Pengajuan -->
        <div class="bg-white p-6 rounded-2xl shadow-xl border-t-4 border-red-500">
            <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center text-red-600">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Tolak Pengajuan PKL
            </h3>
            <form action="{{ route('perusahaan.pkl.tolak', $pkl->id) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="catatan_tolak" class="block text-sm font-medium text-gray-700 mb-1">Alasan Penolakan <span class="text-red-500">*</span></label>
                    <textarea name="catatan" id="catatan_tolak" rows="4" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 transition py-2 px-3" placeholder="Berikan alasan penolakan..." required></textarea>
                </div>
                <button type="submit" class="w-full flex items-center justify-center bg-red-600 hover:bg-red-700 text-white font-semibold py-2.5 px-4 rounded-lg shadow-md transition duration-200 ease-in-out transform hover:scale-[1.01]">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Tolak Pengajuan
                </button>
            </form>
        </div>
    </div>
    @endif

    <!-- Jurnal Harian (Timeline Style) -->
    @if(in_array($pkl->status, ['berlangsung', 'selesai']))
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-gray-50">
            <h3 class="text-xl font-bold text-gray-900">Jurnal Harian PKL</h3>
        </div>
        <div class="p-6 md:p-8 space-y-6">
            @forelse($pkl->jurnalPkl()->latest('tanggal')->get() as $jurnal)
            
            @php
                $jurnal_status = strtolower($jurnal->status_validasi);
                $jurnal_color = 'gray';
                $jurnal_ring = 'ring-gray-300';
                $jurnal_icon = '<svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>';
                
                if ($jurnal_status == 'disetujui') {
                    $jurnal_color = 'green';
                    $jurnal_ring = 'ring-green-300';
                    $jurnal_icon = '<svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
                } elseif ($jurnal_status == 'ditolak') {
                    $jurnal_color = 'red';
                    $jurnal_ring = 'ring-red-300';
                    $jurnal_icon = '<svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
                } elseif ($jurnal_status == 'pending') {
                    $jurnal_color = 'yellow';
                    $jurnal_ring = 'ring-yellow-300';
                    $jurnal_icon = '<svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>';
                }
            @endphp
            
            <div class="relative pl-8 sm:pl-12 pb-6 border-l border-gray-200">
                <!-- Timeline Dot -->
                <div class="absolute -left-3 sm:-left-4 top-0 w-6 h-6 sm:w-8 sm:h-8 rounded-full bg-white ring-8 {{ $jurnal_ring }} flex items-center justify-center">
                    {!! $jurnal_icon !!}
                </div>
                
                <div class="bg-white p-5 rounded-xl shadow-md border-2 border-{{ $jurnal_color }}-100">
                    
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $jurnal->tanggal->format('l, d F Y') }}</p>
                            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-medium border bg-{{ $jurnal_color }}-100 text-{{ $jurnal_color }}-800 border-{{ $jurnal_color }}-300 mt-1">
                                {{ ucfirst($jurnal_status) }}
                            </span>
                        </div>
                        @if($jurnal_status == 'pending')
                        <button onclick="document.getElementById('modalValidasi{{ $jurnal->id }}').classList.remove('hidden')" 
                                class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold py-1.5 px-3 rounded-lg transition duration-150 shadow-md">
                            Validasi
                        </button>
                        @endif
                    </div>

                    <p class="text-sm font-semibold text-gray-800 mb-3">Kegiatan Harian:</p>
                    <div class="text-gray-700 whitespace-pre-line text-base mb-4 border-l-4 border-gray-100 pl-3">
                        {{ $jurnal->kegiatan }}
                    </div>

                    @if($jurnal->foto)
                    <p class="text-sm font-semibold text-gray-800 mb-2">Dokumentasi:</p>
                    <img src="{{ Storage::url($jurnal->foto) }}" alt="Dokumentasi" class="rounded-lg max-h-52 w-full object-cover shadow-sm cursor-pointer" onclick="window.open(this.src)">
                    @endif

                    @if($jurnal->catatan_pembimbing)
                    <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-sm font-bold text-blue-900 mb-1">Catatan Pembimbing:</p>
                        <p class="text-sm text-blue-800 whitespace-pre-line">{{ $jurnal->catatan_pembimbing }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Modal Validasi Jurnal (using standard Tailwind/JS) -->
            <div id="modalValidasi{{ $jurnal->id }}" class="fixed inset-0 z-50 overflow-y-auto bg-gray-900 bg-opacity-50 hidden" onclick="if(event.target === this) this.classList.add('hidden')">
                <div class="min-h-screen flex items-center justify-center p-4">
                    <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg" onclick="event.stopPropagation()">
                        <form action="{{ route('perusahaan.pkl.jurnal.validasi', $jurnal->id) }}" method="POST">
                            @csrf
                            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                                <h3 class="text-xl font-bold text-gray-900">Validasi Jurnal - {{ $jurnal->tanggal->format('d M') }}</h3>
                                <button type="button" onclick="document.getElementById('modalValidasi{{ $jurnal->id }}').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                            <div class="p-6 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Status Validasi <span class="text-red-500">*</span></label>
                                    <select name="status_validasi" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition py-2 px-3" required>
                                        <option value="disetujui">Disetujui</option>
                                        <option value="ditolak">Ditolak</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Pembimbing</label>
                                    <textarea name="catatan_pembimbing" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition py-2 px-3" placeholder="Berikan feedback untuk mahasiswa..."></textarea>
                                </div>
                            </div>
                            <div class="px-6 py-4 border-t border-gray-100 flex justify-end space-x-3">
                                <button type="button" onclick="document.getElementById('modalValidasi{{ $jurnal->id }}').classList.add('hidden')" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2 px-4 rounded-lg transition duration-150">
                                    Batal
                                </button>
                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-150">
                                    Simpan Validasi
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-12 bg-gray-50 rounded-xl border border-gray-200">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-lg font-medium text-gray-700">Belum ada jurnal harian</p>
                <p class="text-sm text-gray-500">Mahasiswa belum mengirimkan catatan kegiatan.</p>
            </div>
            @endforelse
        </div>
    </div>
    @endif

    <!-- Contact Info Card -->
    <div class="bg-white p-6 rounded-2xl shadow-xl border-t-4 border-blue-500">
        <h3 class="text-xl font-bold text-gray-900 mb-5 flex items-center text-blue-600">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 00.948-.684L10.74 2.164a1 1 0 01.442-.442l1.248-.705A1 1 0 0113 1h4a2 2 0 012 2v18a2 2 0 01-2 2H5a2 2 0 01-2-2V5z" />
            </svg>
            Informasi Kontak Mahasiswa
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-y-6 gap-x-8">
            <div class="space-y-1">
                <p class="text-sm text-gray-500">Email</p>
                <a href="mailto:{{ $pkl->mahasiswa->user->email }}" class="text-blue-600 hover:text-blue-700 font-semibold text-base transition-colors truncate block">
                    {{ $pkl->mahasiswa->user->email }}
                </a>
            </div>
            <div class="space-y-1">
                <p class="text-sm text-gray-500">No. Telepon Mahasiswa</p>
                <a href="tel:{{ $pkl->mahasiswa->no_telp }}" class="text-blue-600 hover:text-blue-700 font-semibold text-base transition-colors truncate block">
                    {{ $pkl->mahasiswa->no_telp ?? '-' }}
                </a>
            </div>
            <div class="space-y-1">
                <p class="text-sm text-gray-500">Alamat</p>
                <p class="font-semibold text-gray-900 text-base break-words">{{ $pkl->mahasiswa->alamat ?? '-' }}</p>
            </div>
            <div class="space-y-1">
                <p class="text-sm text-gray-500">Orang Tua/Wali</p>
                <p class="font-semibold text-gray-900 text-base">{{ $pkl->mahasiswa->nama_ortu ?? '-' }}</p>
                @if($pkl->mahasiswa->no_telp_ortu)
                <p class="text-xs text-gray-500">{{ $pkl->mahasiswa->no_telp_ortu }}</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Kerjasama List (Jika ada) -->
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-gray-50">
            <h3 class="text-xl font-bold text-gray-900">Riwayat Kerjasama dengan Sekolah</h3>
        </div>
        <div class="p-6 space-y-6">
            @forelse($kerjasama as $k)
            <div class="border border-gray-200 rounded-xl p-5 hover:border-indigo-300 transition-all duration-300">
                <div class="flex items-start justify-between flex-col sm:flex-row mb-4">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center space-x-3 mb-2">
                            <h3 class="text-lg font-bold text-gray-900 truncate">{{ $k->judul }}</h3>
                            @php
                                $k_status = strtolower($k->status);
                                $k_badge_class = '';
                                switch ($k_status) {
                                    case 'aktif': $k_badge_class = 'bg-green-100 text-green-800 border-green-300'; break;
                                    case 'proposal':
                                    case 'negosiasi': $k_badge_class = 'bg-yellow-100 text-yellow-800 border-yellow-300'; break;
                                    case 'selesai': $k_badge_class = 'bg-blue-100 text-blue-800 border-blue-300'; break;
                                    default: $k_badge_class = 'bg-gray-100 text-gray-800 border-gray-300'; break;
                                }
                            @endphp
                            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-medium border {{ $k_badge_class }} flex-shrink-0">
                                {{ ucfirst($k_status) }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 mb-2">Jenis: <span class="font-medium text-gray-700">{{ ucfirst($k->jenis_kerjasama) }}</span></p>
                        
                        @if($k->deskripsi)
                        <p class="text-gray-700 text-sm mb-4">{{ Str::limit($k->deskripsi, 150) }}</p>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm mb-4">
                    <div>
                        <p class="text-gray-500">Periode:</p>
                        <p class="font-semibold text-gray-900">
                            {{ $k->tanggal_mulai->format('d M Y') }}
                            @if($k->tanggal_berakhir)
                            - {{ $k->tanggal_berakhir->format('d M Y') }}
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-500">PIC Sekolah:</p>
                        <p class="font-semibold text-gray-900 truncate">{{ $k->pic_sekolah ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">PIC Industri:</p>
                        <p class="font-semibold text-gray-900 truncate">{{ $k->pic_industri ?? '-' }}</p>
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between pt-4 border-t border-gray-100 space-y-3 sm:space-y-0">
                    @if($k->dokumen_mou)
                    <a href="{{ Storage::url($k->dokumen_mou) }}" target="_blank" class="flex items-center text-indigo-600 hover:text-indigo-800 font-medium transition duration-150 text-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Download MoU
                    </a>
                    @else
                    <span class="text-xs text-gray-500">Dokumen MoU belum tersedia</span>
                    @endif

                    <a href="{{ route('perusahaan.kerjasama.show', $k->id) }}" class="text-indigo-600 hover:text-indigo-800 font-semibold transition duration-150 flex items-center space-x-1 text-sm">
                        <span>Lihat Detail</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </a>
                </div>
            </div>
            @empty
            <div class="text-center py-12 bg-gray-50 rounded-xl border border-gray-200">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <p class="text-lg font-medium text-gray-900 mb-2">Belum ada kerjasama</p>
                <p class="text-sm text-gray-500">Hubungi admin sekolah untuk memulai kerjasama</p>
            </div>
            @endforelse
        </div>
    </div>

    
</div>
@endsection