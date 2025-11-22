@extends('layouts.app')

@section('title', 'Tambah Kerjasama Industri')
@section('page-title', 'Tambah Kerjasama Industri')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="card">
        <div class="card-header">
            <h3 class="text-lg font-semibold text-gray-900">Form Kerjasama Industri</h3>
        </div>
        
        <form action="{{ route('admin.kerjasama.store') }}" method="POST" enctype="multipart/form-data" class="card-body space-y-6">
            @csrf

            <!-- Informasi Dasar -->
            <div>
                <h4 class="text-md font-semibold text-gray-900 mb-4">Informasi Dasar</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label for="perusahaan_id" class="form-label">Perusahaan Mitra *</label>
                        <select id="perusahaan_id" name="perusahaan_id" class="form-select" required>
                            <option value="">-- Pilih Perusahaan --</option>
                            @foreach($perusahaan as $p)
                            <option value="{{ $p->id }}" {{ old('perusahaan_id') == $p->id ? 'selected' : '' }}>
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
                            <option value="pkl" {{ old('jenis_kerjasama') == 'pkl' ? 'selected' : '' }}>PKL</option>
                            <option value="rekrutmen" {{ old('jenis_kerjasama') == 'rekrutmen' ? 'selected' : '' }}>Rekrutmen</option>
                            <option value="pelatihan" {{ old('jenis_kerjasama') == 'pelatihan' ? 'selected' : '' }}>Pelatihan</option>
                            <option value="penelitian" {{ old('jenis_kerjasama') == 'penelitian' ? 'selected' : '' }}>Penelitian</option>
                            <option value="sponsorship" {{ old('jenis_kerjasama') == 'sponsorship' ? 'selected' : '' }}>Sponsorship</option>
                            <option value="lainnya" {{ old('jenis_kerjasama') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('jenis_kerjasama')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="judul" class="form-label">Judul Kerjasama *</label>
                        <input type="text" id="judul" name="judul" value="{{ old('judul') }}" class="form-input" required>
                        @error('judul')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea id="deskripsi" name="deskripsi" rows="4" class="form-textarea">{{ old('deskripsi') }}</textarea>
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
                        <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" class="form-input" required>
                        @error('tanggal_mulai')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="tanggal_berakhir" class="form-label">Tanggal Berakhir</label>
                        <input type="date" id="tanggal_berakhir" name="tanggal_berakhir" value="{{ old('tanggal_berakhir') }}" class="form-input">
                        <p class="text-xs text-gray-500 mt-1">Kosongkan jika kerjasama tidak memiliki batas waktu</p>
                        @error('tanggal_berakhir')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <hr class="border-gray-200">

            <!-- Dokumen & Nilai Kontrak -->
            <div>
                <h4 class="text-md font-semibold text-gray-900 mb-4">Dokumen & Nilai Kontrak</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label for="dokumen_mou" class="form-label">Dokumen MoU (PDF)</label>
                        <input type="file" id="dokumen_mou" name="dokumen_mou" accept=".pdf" class="form-input">
                        <p class="text-xs text-gray-500 mt-1">Format PDF, maksimal 10MB</p>
                        @error('dokumen_mou')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="nilai_kontrak" class="form-label">Nilai Kontrak (Rp)</label>
                        <input type="number" id="nilai_kontrak" name="nilai_kontrak" value="{{ old('nilai_kontrak') }}" class="form-input" placeholder="0" step="0.01">
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
                        <input type="text" id="pic_sekolah" name="pic_sekolah" value="{{ old('pic_sekolah') }}" class="form-input" placeholder="Nama PIC dari sekolah">
                        @error('pic_sekolah')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="pic_industri" class="form-label">PIC Industri</label>
                        <input type="text" id="pic_industri" name="pic_industri" value="{{ old('pic_industri') }}" class="form-input" placeholder="Nama PIC dari perusahaan">
                        @error('pic_industri')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <hr class="border-gray-200">

            <!-- Catatan -->
            <div>
                <label for="catatan" class="form-label">Catatan</label>
                <textarea id="catatan" name="catatan" rows="3" class="form-textarea" placeholder="Catatan tambahan...">{{ old('catatan') }}</textarea>
                @error('catatan')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.kerjasama.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Kerjasama</button>
            </div>
        </form>
    </div>
</div>
@endsection