
@extends('layouts.app')

@section('title', 'Detail PKL')
@section('page-title', 'Detail PKL')

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <a href="{{ route('siswa.pkl.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali
    </a>

    <!-- PKL Info -->
    <div class="card">
        <div class="card-body">
            <div class="flex items-start justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $pkl->perusahaan->nama_perusahaan }}</h2>
                    <p class="text-lg text-gray-600">{{ $pkl->posisi ?? 'Peserta PKL' }}</p>
                </div>
                <span class="badge 
                    @if($pkl->status == 'selesai') badge-success
                    @elseif($pkl->status == 'berlangsung') badge-info  
                    @elseif($pkl->status == 'diterima') badge-primary
                    @elseif($pkl->status == 'ditolak') badge-danger
                    @else badge-warning
                    @endif text-sm px-4 py-2">
                    {{ ucfirst($pkl->status) }}
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
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
                <div>
                    <p class="text-sm text-gray-600 mb-1">Divisi/Bagian</p>
                    <p class="font-semibold text-gray-900">{{ $pkl->divisi ?? '-' }}</p>
                </div>
            </div>

            @if($pkl->catatan)
            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                <p class="text-sm font-medium text-gray-700 mb-2">Catatan:</p>
                <p class="text-gray-600">{{ $pkl->catatan }}</p>
            </div>
            @endif

            @if($pkl->nilai_akhir)
            <div class="mt-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-sm font-medium text-green-800 mb-1">Nilai Akhir PKL</p>
                <p class="text-3xl font-bold text-green-600">{{ $pkl->nilai_akhir }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Jurnal Harian -->
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Jurnal Harian PKL</h3>
                @if($pkl->status == 'berlangsung')
                <button onclick="document.getElementById('modalTambahJurnal').classList.remove('hidden')" class="btn btn-primary btn-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Jurnal
                </button>
                @endif
            </div>
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
                        @if($pkl->status == 'berlangsung' && $jurnal->status_validasi == 'pending')
                        <div class="flex space-x-2">
                            <button onclick="editJurnal({{ $jurnal->id }}, '{{ $jurnal->tanggal->format('Y-m-d') }}', '{{ addslashes($jurnal->kegiatan) }}')" 
                                    class="text-blue-600 hover:text-blue-700" title="Edit">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                            <form action="{{ route('siswa.pkl.jurnal.delete', $jurnal->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus jurnal ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-700" title="Hapus">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
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
                @empty
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="text-gray-500">Belum ada jurnal harian</p>
                    @if($pkl->status == 'berlangsung')
                    <button onclick="document.getElementById('modalTambahJurnal').classList.remove('hidden')" class="btn btn-primary mt-4">
                        Tambah Jurnal Pertama
                    </button>
                    @endif
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Jurnal -->
@if($pkl->status == 'berlangsung')
<div id="modalTambahJurnal" class="modal-overlay hidden" onclick="if(event.target === this) this.classList.add('hidden')">
    <div class="modal-content" onclick="event.stopPropagation()">
        <form action="{{ route('siswa.pkl.jurnal.add', $pkl->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-gray-900">Tambah Jurnal Harian</h3>
                    <button type="button" onclick="document.getElementById('modalTambahJurnal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="px-6 py-4 space-y-4">
                <div>
                    <label class="form-label">Tanggal *</label>
                    <input type="date" name="tanggal" class="form-input" required 
                           min="{{ $pkl->tanggal_mulai->format('Y-m-d') }}" 
                           max="{{ min($pkl->tanggal_selesai, now())->format('Y-m-d') }}"
                           value="{{ date('Y-m-d') }}">
                </div>

                <div>
                    <label class="form-label">Kegiatan *</label>
                    <textarea name="kegiatan" rows="6" class="form-textarea" placeholder="Tuliskan kegiatan yang dilakukan hari ini..." required></textarea>
                </div>

                <div>
                    <label class="form-label">Foto Dokumentasi (Opsional)</label>
                    <input type="file" name="foto" accept="image/*" class="form-input">
                    <p class="text-xs text-gray-500 mt-1">Format JPG/PNG, maksimal 2MB</p>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                <button type="button" onclick="document.getElementById('modalTambahJurnal').classList.add('hidden')" class="btn btn-outline">
                    Batal
                </button>
                <button type="submit" class="btn btn-primary">
                    Simpan Jurnal
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Jurnal -->
<div id="modalEditJurnal" class="modal-overlay hidden" onclick="if(event.target === this) this.classList.add('hidden')">
    <div class="modal-content" onclick="event.stopPropagation()">
        <form id="formEditJurnal" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-gray-900">Edit Jurnal Harian</h3>
                    <button type="button" onclick="document.getElementById('modalEditJurnal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="px-6 py-4 space-y-4">
                <div>
                    <label class="form-label">Tanggal *</label>
                    <input type="date" id="edit_tanggal" name="tanggal" class="form-input" required>
                </div>

                <div>
                    <label class="form-label">Kegiatan *</label>
                    <textarea id="edit_kegiatan" name="kegiatan" rows="6" class="form-textarea" required></textarea>
                </div>

                <div>
                    <label class="form-label">Foto Dokumentasi Baru (Opsional)</label>
                    <input type="file" name="foto" accept="image/*" class="form-input">
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                <button type="button" onclick="document.getElementById('modalEditJurnal').classList.add('hidden')" class="btn btn-outline">
                    Batal
                </button>
                <button type="submit" class="btn btn-primary">
                    Update Jurnal
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function editJurnal(id, tanggal, kegiatan) {
    document.getElementById('formEditJurnal').action = `/siswa/pkl/jurnal/${id}`;
    document.getElementById('edit_tanggal').value = tanggal;
    document.getElementById('edit_kegiatan').value = kegiatan;
    document.getElementById('modalEditJurnal').classList.remove('hidden');
}
</script>
@endif
@endsection