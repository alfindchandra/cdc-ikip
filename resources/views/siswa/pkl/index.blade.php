@extends('layouts.app')

@section('title', 'PKL Saya')
@section('page-title', 'Praktik Kerja Lapangan (PKL)')

@section('content')
<div class="space-y-6">
    @php
        $pklAktif = auth()->user()->siswa->pkl()
                    ->whereIn('status', ['berlangsung', 'diterima'])
                    ->first();
    @endphp

    @if($pklAktif)
    <!-- PKL Aktif Card -->
    <div class="card border-l-4 border-blue-500">
        <div class="card-body">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <span class="badge badge-info mb-2">PKL Aktif</span>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $pklAktif->perusahaan->nama_perusahaan }}</h2>
                    <p class="text-gray-600 mt-1">{{ $pklAktif->posisi ?? 'Peserta PKL' }}</p>
                </div>
                <span class="badge badge-{{ $pklAktif->status == 'berlangsung' ? 'success' : 'warning' }} text-sm px-3 py-1">
                    {{ ucfirst($pklAktif->status) }}
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <p class="text-sm text-gray-600">Periode PKL</p>
                    <p class="font-semibold text-gray-900">
                        {{ $pklAktif->tanggal_mulai->format('d M Y') }} - {{ $pklAktif->tanggal_selesai->format('d M Y') }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Pembimbing Sekolah</p>
                    <p class="font-semibold text-gray-900">{{ $pklAktif->pembimbing_sekolah ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Pembimbing Industri</p>
                    <p class="font-semibold text-gray-900">{{ $pklAktif->pembimbing_industri ?? '-' }}</p>
                </div>
            </div>

            <div class="flex space-x-3">
                <a href="{{ route('siswa.pkl.show', $pklAktif->id) }}" class="btn btn-primary">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    Lihat Detail & Jurnal
                </a>
                <button onclick="document.getElementById('modalUploadLaporan').classList.remove('hidden')" class="btn btn-secondary">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                    Upload Laporan PKL
                </button>
            </div>
        </div>
    </div>
    @else
    <!-- Daftar PKL -->
    <div class="card bg-blue-50 border-blue-200">
        <div class="card-body text-center">
            <svg class="w-16 h-16 mx-auto mb-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Belum Ada PKL Aktif</h3>
            <p class="text-gray-600 mb-6">Daftarkan diri Anda untuk PKL di perusahaan mitra</p>
            <button onclick="document.getElementById('modalDaftarPKL').classList.remove('hidden')" class="btn btn-primary">
                <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Daftar PKL
            </button>
        </div>
    </div>
    @endif

    <!-- Riwayat PKL -->
    <div class="card">
        <div class="card-header">
            <h3 class="text-lg font-semibold text-gray-900">Riwayat PKL</h3>
        </div>
        <div class="card-body">
            <div class="space-y-4">
                @forelse(auth()->user()->siswa->pkl()->latest()->get() as $pkl)
                <div class="border-l-4 {{ $pkl->status == 'selesai' ? 'border-green-500' : 'border-gray-300' }} pl-4 py-3">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="font-semibold text-gray-900">{{ $pkl->perusahaan->nama_perusahaan }}</p>
                            <p class="text-sm text-gray-600 mt-1">{{ $pkl->posisi ?? 'Peserta PKL' }}</p>
                            <p class="text-sm text-gray-500 mt-1">
                                {{ $pkl->tanggal_mulai->format('d M Y') }} - {{ $pkl->tanggal_selesai->format('d M Y') }}
                            </p>
                            @if($pkl->nilai_akhir)
                            <p class="text-sm text-green-600 font-semibold mt-2">
                                Nilai: {{ $pkl->nilai_akhir }}
                            </p>
                            @endif
                        </div>
                        <div class="text-right">
                            <span class="badge 
                                @if($pkl->status == 'selesai') badge-success
                                @elseif($pkl->status == 'berlangsung') badge-info
                                @elseif($pkl->status == 'diterima') badge-primary
                                @elseif($pkl->status == 'ditolak') badge-danger
                                @else badge-warning
                                @endif mb-2">
                                {{ ucfirst($pkl->status) }}
                            </span>
                            <br>
                            <a href="{{ route('siswa.pkl.show', $pkl->id) }}" class="text-sm text-blue-600 hover:text-blue-700">
                                Lihat Detail →
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-center text-gray-500 py-8">Belum ada riwayat PKL</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Modal Daftar PKL -->
<div id="modalDaftarPKL" class="modal-overlay hidden" onclick="if(event.target === this) this.classList.add('hidden')">
    <div class="modal-content" onclick="event.stopPropagation()">
        <form action="{{ route('siswa.pkl.daftar') }}" method="POST">
            @csrf
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-gray-900">Daftar PKL</h3>
                    <button type="button" onclick="document.getElementById('modalDaftarPKL').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="px-6 py-4 space-y-4">
                <div>
                    <label class="form-label">Pilih Perusahaan *</label>
                    <select name="perusahaan_id" class="form-select" required>
                        <option value="">-- Pilih Perusahaan --</option>
                        @foreach(\App\Models\Perusahaan::where('status_kerjasama', 'aktif')->get() as $perusahaan)
                        <option value="{{ $perusahaan->id }}">{{ $perusahaan->nama_perusahaan }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Tanggal Mulai *</label>
                        <input type="date" name="tanggal_mulai" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Tanggal Selesai *</label>
                        <input type="date" name="tanggal_selesai" class="form-input" required>
                    </div>
                </div>

                <div>
                    <label class="form-label">Posisi yang Diinginkan</label>
                    <input type="text" name="posisi" class="form-input" placeholder="Contoh: Web Developer">
                </div>

                <div>
                    <label class="form-label">Catatan</label>
                    <textarea name="catatan" rows="3" class="form-textarea" placeholder="Tulis alasan atau catatan tambahan..."></textarea>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                <button type="button" onclick="document.getElementById('modalDaftarPKL').classList.add('hidden')" class="btn btn-outline">
                    Batal
                </button>
                <button type="submit" class="btn btn-primary">
                    Kirim Pengajuan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Upload Laporan -->
@if($pklAktif)
<div id="modalUploadLaporan" class="modal-overlay hidden" onclick="if(event.target === this) this.classList.add('hidden')">
    <div class="modal-content" onclick="event.stopPropagation()">
        <form action="{{ route('siswa.pkl.laporan', $pklAktif->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-gray-900">Upload Laporan PKL</h3>
                    <button type="button" onclick="document.getElementById('modalUploadLaporan').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="px-6 py-4 space-y-4">
                <div>
                    <label class="form-label">File Laporan PKL (PDF) *</label>
                    <input type="file" name="laporan_pkl" accept=".pdf" class="form-input" required>
                    <p class="text-xs text-gray-500 mt-1">Format PDF, maksimal 10MB</p>
                </div>

                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex items-start space-x-2">
                        <svg class="w-5 h-5 text-yellow-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="text-sm text-yellow-800">
                            <p class="font-semibold">Pastikan laporan PKL Anda sudah sesuai format:</p>
                            <ul class="list-disc list-inside mt-2 space-y-1">
                                <li>Cover dengan identitas lengkap</li>
                                <li>Lembar pengesahan</li>
                                <li>Isi laporan kegiatan PKL</li>
                                <li>Dokumentasi kegiatan</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                <button type="button" onclick="document.getElementById('modalUploadLaporan').classList.add('hidden')" class="btn btn-outline">
                    Batal
                </button>
                <button type="submit" class="btn btn-primary">
                    Upload Laporan
                </button>
            </div>
        </form>
    </div>
</div>
@endif
@endsection