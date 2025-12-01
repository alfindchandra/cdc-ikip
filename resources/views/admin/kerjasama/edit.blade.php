@extends('layouts.app')

@section('title', 'Edit Kerjasama')
@section('page-title', 'Edit Kerjasama Industri')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="card shadow-xl border border-gray-100">
        <div class="card-header bg-gradient-to-r from-indigo-500 to-blue-600 text-white">
            <h3 class="text-lg font-semibold flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Form Edit Kerjasama Industri
            </h3>
        </div>
        
        <form action="{{ route('admin.kerjasama.update', $kerjasama->id) }}" method="POST" enctype="multipart/form-data" class="card-body space-y-6">
            @csrf
            @method('PUT')

            <!-- Informasi Dasar -->
            <div>
                <h4 class="text-md font-semibold text-gray-900 mb-4">Informasi Dasar</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label for="perusahaan_id" class="form-label">Perusahaan Mitra *</label>
                        <select id="perusahaan_id" name="perusahaan_id" class="form-select" required>
                            <option value="">-- Pilih Perusahaan --</option>
                            @foreach($perusahaan as $p)
                            <option value="{{ $p->id }}" {{ old('perusahaan_id', $kerjasama->perusahaan_id) == $p->id ? 'selected' : '' }}>
                                {{ $p->nama_perusahaan }}
                            </option>
                            @endforeach
                        </select>
                        @error('perusahaan_id')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="jenis_kerjasama" class="form-label">Jenis Kerjasama *</label>
                        <select id="jenis_kerjasama" name="jenis_kerjasama" class="form-select" required>
                            <option value="">-- Pilih Jenis --</option>
                            <option value="pkl" {{ old('jenis_kerjasama', $kerjasama->jenis_kerjasama) == 'pkl' ? 'selected' : '' }}>PKL</option>
                            <option value="rekrutmen" {{ old('jenis_kerjasama', $kerjasama->jenis_kerjasama) == 'rekrutmen' ? 'selected' : '' }}>Rekrutmen</option>
                            <option value="pelatihan" {{ old('jenis_kerjasama', $kerjasama->jenis_kerjasama) == 'pelatihan' ? 'selected' : '' }}>Pelatihan</option>
                            <option value="penelitian" {{ old('jenis_kerjasama', $kerjasama->jenis_kerjasama) == 'penelitian' ? 'selected' : '' }}>Penelitian</option>
                            <option value="sponsorship" {{ old('jenis_kerjasama', $kerjasama->jenis_kerjasama) == 'sponsorship' ? 'selected' : '' }}>Sponsorship</option>
                            <option value="lainnya" {{ old('jenis_kerjasama', $kerjasama->jenis_kerjasama) == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('jenis_kerjasama')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="judul" class="form-label">Judul Kerjasama *</label>
                        <input type="text" id="judul" name="judul" value="{{ old('judul', $kerjasama->judul) }}" class="form-input" required>
                        @error('judul')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea id="deskripsi" name="deskripsi" rows="4" class="form-textarea">{{ old('deskripsi', $kerjasama->deskripsi) }}</textarea>
                        @error('deskripsi')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <hr class="border-gray-200">

            <!-- Periode -->
            <div>
                <h4 class="text-md font-semibold text-gray-900 mb-4">Periode Kerjasama</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="tanggal_mulai" class="form-label">Tanggal Mulai *</label>
                        <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai', $kerjasama->tanggal_mulai->format('Y-m-d')) }}" class="form-input" required>
                        @error('tanggal_mulai')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="tanggal_berakhir" class="form-label">Tanggal Berakhir</label>
                        <input type="date" id="tanggal_berakhir" name="tanggal_berakhir" value="{{ old('tanggal_berakhir', $kerjasama->tanggal_berakhir?->format('Y-m-d')) }}" class="form-input">
                        <p class="text-xs text-gray-500 mt-1">Kosongkan jika kerjasama tidak memiliki batas waktu</p>
                        @error('tanggal_berakhir')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <hr class="border-gray-200">

            <!-- Dokumen & Nilai Kontrak -->
            <div>
                <h4 class="text-md font-semibold text-gray-900 mb-4">Dokumen & Nilai Kontrak</h4>
                
                @if($kerjasama->dokumen_mou)
                <div class="mb-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <p class="text-sm font-medium text-blue-900 mb-2">Dokumen MoU Saat Ini:</p>
                    <a href="{{ Storage::url($kerjasama->dokumen_mou) }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Lihat Dokumen
                    </a>
                </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label for="dokumen_mou" class="form-label">Dokumen MoU (PDF) - Upload Baru</label>
                        <input type="file" id="dokumen_mou" name="dokumen_mou" accept=".pdf" class="form-input">
                        <p class="text-xs text-gray-500 mt-1">Format PDF, maksimal 10MB. Kosongkan jika tidak ingin mengubah dokumen.</p>
                        @error('dokumen_mou')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="nilai_kontrak" class="form-label">Nilai Kontrak (Rp)</label>
                        <input type="number" id="nilai_kontrak" name="nilai_kontrak" value="{{ old('nilai_kontrak', $kerjasama->nilai_kontrak) }}" class="form-input" placeholder="0" step="0.01">
                        @error('nilai_kontrak')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <hr class="border-gray-200">

            <!-- PIC -->
            <div>
                <h4 class="text-md font-semibold text-gray-900 mb-4">Penanggung Jawab</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="pic_sekolah" class="form-label">PIC Sekolah</label>
                        <input type="text" id="pic_sekolah" name="pic_sekolah" value="{{ old('pic_sekolah', $kerjasama->pic_sekolah) }}" class="form-input" placeholder="Nama PIC dari sekolah">
                        @error('pic_sekolah')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="pic_industri" class="form-label">PIC Industri</label>
                        <input type="text" id="pic_industri" name="pic_industri" value="{{ old('pic_industri', $kerjasama->pic_industri) }}" class="form-input" placeholder="Nama PIC dari perusahaan">
                        @error('pic_industri')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <hr class="border-gray-200">

            <!-- Catatan -->
            <div>
                <label for="catatan" class="form-label">Catatan</label>
                <textarea id="catatan" name="catatan" rows="3" class="form-textarea" placeholder="Catatan tambahan...">{{ old('catatan', $kerjasama->catatan) }}</textarea>
                @error('catatan')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.kerjasama.show', $kerjasama->id) }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">Perbarui Kerjasama</button>
            </div>
        </form>
    </div>
</div>
@endsection