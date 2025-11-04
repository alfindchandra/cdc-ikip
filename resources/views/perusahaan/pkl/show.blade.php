@extends('layouts.app')

@section('title', 'Detail PKL')
@section('page-title', 'Detail PKL Siswa')

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <a href="{{ route('perusahaan.pkl.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali
    </a>

    <!-- PKL Info Card -->
    <div class="card">
        <div class="card-body">
            <div class="flex items-start justify-between mb-6">
                <div class="flex items-start space-x-4">
                    <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 text-2xl font-bold">
                        {{ substr($pkl->siswa->user->name, 0, 1) }}
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-1">{{ $pkl->siswa->user->name }}</h2>
                        <p class="text-gray-600 mb-2">{{ $pkl->siswa->nis }} - {{ $pkl->siswa->jurusan }}</p>
                        <p class="text-sm text-gray-500">{{ $pkl->posisi ?? 'Peserta PKL' }}</p>
                    </div>
                </div>
                <span class="badge 
                    @if($pkl->status == 'pengajuan') badge-warning
                    @elseif($pkl->status == 'diterima') badge-primary
                    @elseif($pkl->status == 'berlangsung') badge-info
                    @elseif($pkl->status == 'selesai') badge-success
                    @else badge-danger
                    @endif text-sm px-4 py-2">
                    {{ ucfirst($pkl->status) }}
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Periode PKL</p>
                    <p class="font-semibold text-gray-900">{{ $pkl->tanggal_mulai->format('d M Y') }}</p>
                    <p class="text-sm text-gray-500">s/d</p>
                    <p class="font-semibold text-gray-900">{{ $pkl->tanggal_selesai->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Pembimbing Sekolah</p>
                    <p class="font-semibold text-gray-900">{{ $pkl->pembimbing_sekolah ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Pembimbing Industri</p>
                    <p class="font-semibold text-gray-900">{{ $pkl->pembimbing_industri ?? '-' }}</p>
                </div>
            </div>

            @if($pkl->catatan)
            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                <p class="text-sm font-medium text-gray-700 mb-2">Catatan:</p>
                <p class="text-gray-600">{{ $pkl->catatan }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Actions for Pengajuan -->
    @if($pkl->status == 'pengajuan')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="card border-green-200">
            <div class="card-body">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Terima Pengajuan PKL</h3>
                <form action="{{ route('perusahaan.pkl.terima', $pkl->id) }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="form-label">Pembimbing Industri *</label>
                            <input type="text" name="pembimbing_industri" class="form-input" placeholder="Nama pembimbing dari perusahaan" required>
                        </div>
                        <div>
                            <label class="form-label">Divisi/Bagian</label>
                            <input type="text" name="divisi" class="form-input" placeholder="IT Department">
                        </div>
                        <button type="submit" class="btn btn-success w-full">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Terima Pengajuan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card border-red-200">
            <div class="card-body">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Tolak Pengajuan PKL</h3>
                <form action="{{ route('perusahaan.pkl.tolak', $pkl->id) }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="form-label">Alasan Penolakan *</label>
                            <textarea name="catatan" rows="4" class="form-textarea" placeholder="Berikan alasan penolakan..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger w-full">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Tolak Pengajuan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Jurnal Harian -->
    @if(in_array($pkl->status, ['berlangsung', 'selesai']))
    <div class="card">
        <div class="card-header">
            <h3 class="text-lg font-semibold text-gray-900">Jurnal Harian PKL</h3>
        </div>
        <div class="card-body">
            <div class="space-y-4">
                @forelse($pkl->jurnalPkl()->latest('tanggal')->get() as $jurnal)
                <div class="border rounded-lg p-4 {{ $jurnal->status_validasi == 'disetujui' ? 'border-green-200 bg-green-50' : ($jurnal->status_validasi == 'ditolak' ? 'border-red-200 bg-red-50' : 'border-gray-200') }}">
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <p class="font-semibold text-gray-900">{{ $jurnal->tanggal->format('d F Y') }}</p>
                            <span class="badge 
                                @if($jurnal->status_validasi == 'disetujui') badge-success
                                @elseif($jurnal->status_validasi == 'ditolak') badge-danger
                                @else badge-warning
                                @endif text-xs mt-1">
                                {{ ucfirst($jurnal->status_validasi) }}
                            </span>
                        </div>
                        @if($jurnal->status_validasi == 'pending')
                        <button onclick="document.getElementById('modalValidasi{{ $jurnal->id }}').classList.remove('hidden')" class="btn btn-primary btn-sm">
                            Validasi
                        </button>
                        @endif
                    </div>

                    <div class="prose max-w-none">
                        <p class="text-gray-700 whitespace-pre-line">{{ $jurnal->kegiatan }}</p>
                    </div>

                    @if($jurnal->foto)
                    <div class="mt-3">
                        <img src="{{ Storage::url($jurnal->foto) }}" alt="Dokumentasi" class="rounded-lg max-h-64 object-cover">
                    </div>
                    @endif

                    @if($jurnal->catatan_pembimbing)
                    <div class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded">
                        <p class="text-sm font-medium text-blue-900 mb-1">Catatan Pembimbing:</p>
                        <p class="text-sm text-blue-800">{{ $jurnal->catatan_pembimbing }}</p>
                    </div>
                    @endif
                </div>

                <!-- Modal Validasi -->
                <div id="modalValidasi{{ $jurnal->id }}" class="modal-overlay hidden" onclick="if(event.target === this) this.classList.add('hidden')">
                    <div class="modal-content" onclick="event.stopPropagation()">
                        <form action="{{ route('perusahaan.pkl.jurnal.validasi', $jurnal->id) }}" method="POST">
                            @csrf
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h3 class="text-xl font-bold text-gray-900">Validasi Jurnal</h3>
                            </div>
                            <div class="px-6 py-4 space-y-4">
                                <div>
                                    <label class="form-label">Status Validasi *</label>
                                    <select name="status_validasi" class="form-select" required>
                                        <option value="disetujui">Disetujui</option>
                                        <option value="ditolak">Ditolak</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label">Catatan Pembimbing</label>
                                    <textarea name="catatan_pembimbing" rows="3" class="form-textarea" placeholder="Berikan feedback untuk siswa..."></textarea>
                                </div>
                            </div>
                            <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                                <button type="button" onclick="document.getElementById('modalValidasi{{ $jurnal->id }}').classList.add('hidden')" class="btn btn-outline">
                                    Batal
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    Simpan Validasi
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                @empty
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="text-gray-500">Belum ada jurnal harian</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
    @endif

    <!-- Contact Info -->
    <div class="card bg-blue-50 border-blue-200">
        <div class="card-body">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Informasi Kontak Siswa</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Email</p>
                    <a href="mailto:{{ $pkl->siswa->user->email }}" class="text-blue-600 hover:text-blue-700 font-medium">
                        {{ $pkl->siswa->user->email }}
                    </a>
                </div>
                <div>
                    <p class="text-sm text-gray-600">No. Telepon</p>
                    <a href="tel:{{ $pkl->siswa->no_telp }}" class="text-blue-600 hover:text-blue-700 font-medium">
                        {{ $pkl->siswa->no_telp ?? '-' }}
                    </a>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Alamat</p>
                    <p class="font-medium text-gray-900">{{ $pkl->siswa->alamat ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Orang Tua/Wali</p>
                    <p class="font-medium text-gray-900">{{ $pkl->siswa->nama_ortu ?? '-' }}</p>
                    @if($pkl->siswa->no_telp_ortu)
                    <p class="text-sm text-gray-600">{{ $pkl->siswa->no_telp_ortu }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Kerjasama List -->
    <div class="space-y-4">
        @forelse($kerjasama as $k)
        <div class="card hover:shadow-lg transition-shadow">
            <div class="card-body">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-2">
                            <h3 class="text-xl font-bold text-gray-900">{{ $k->judul }}</h3>
                            <span class="badge 
                                @if($k->status == 'aktif') badge-success
                                @elseif($k->status == 'proposal' || $k->status == 'negosiasi') badge-warning
                                @elseif($k->status == 'selesai') badge-info
                                @else badge-gray
                                @endif">
                                {{ ucfirst($k->status) }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 mb-3">
                            Jenis: <span class="font-medium">{{ ucfirst($k->jenis_kerjasama) }}</span>
                        </p>
                        
                        @if($k->deskripsi)
                        <p class="text-gray-700 mb-4">{{ Str::limit($k->deskripsi, 200) }}</p>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Periode</p>
                                <p class="font-semibold text-gray-900">
                                    {{ $k->tanggal_mulai->format('d M Y') }}
                                    @if($k->tanggal_berakhir)
                                    - {{ $k->tanggal_berakhir->format('d M Y') }}
                                    @endif
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">PIC Sekolah</p>
                                <p class="font-semibold text-gray-900">{{ $k->pic_sekolah ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">PIC Industri</p>
                                <p class="font-semibold text-gray-900">{{ $k->pic_industri ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    @if($k->dokumen_mou)
                    <a href="{{ Storage::url($k->dokumen_mou) }}" target="_blank" class="flex items-center text-blue-600 hover:text-blue-700">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Download MoU
                    </a>
                    @else
                    <span class="text-sm text-gray-500">Dokumen MoU belum tersedia</span>
                    @endif

                    <a href="{{ route('perusahaan.kerjasama.show', $k->id) }}" class="text-blue-600 hover:text-blue-700 font-medium">
                        Lihat Detail →
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="card">
            <div class="card-body text-center py-12">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <p class="text-lg font-medium text-gray-900 mb-2">Belum ada kerjasama</p>
                <p class="text-sm text-gray-500">Hubungi admin sekolah untuk memulai kerjasama</p>
            </div>
        </div>
        @endforelse
    </div>

    @if($kerjasama->hasPages())
    <div class="flex justify-center">
        {{ $kerjasama->links() }}
    </div>
    @endif
</div>
@endsection