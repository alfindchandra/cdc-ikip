@extends('layouts.app')

@section('title', 'Buat Lowongan')
@section('page-title', 'Buat Lowongan Kerja Baru')

@section('content')
<div class="max-w-4xl">
    <div class="card">
        <div class="card-header">
            <h3 class="text-lg font-semibold text-gray-900">Form Lowongan Kerja</h3>
        </div>

        <form action="{{ route('perusahaan.lowongan.store') }}" method="POST" enctype="multipart/form-data" class="card-body space-y-6">
            @csrf

            <div>
                <label class="form-label">Judul Lowongan *</label>
                <input type="text" name="judul" value="{{ old('judul') }}" class="form-input" placeholder="Contoh: Web Developer" required>
                @error('judul')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">Posisi *</label>
                <input type="text" name="posisi" value="{{ old('posisi') }}" class="form-input" placeholder="Contoh: Full Stack Developer" required>
                @error('posisi')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">Deskripsi Pekerjaan *</label>
                <textarea name="deskripsi" rows="5" class="form-textarea" placeholder="Jelaskan tugas dan tanggung jawab..." required>{{ old('deskripsi') }}</textarea>
                @error('deskripsi')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">Kualifikasi *</label>
                <textarea name="kualifikasi" rows="5" class="form-textarea" placeholder="Tuliskan kualifikasi yang dibutuhkan..." required>{{ old('kualifikasi') }}</textarea>
                @error('kualifikasi')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">Benefit</label>
                <textarea name="benefit" rows="3" class="form-textarea" placeholder="Benefit yang didapatkan...">{{ old('benefit') }}</textarea>
                @error('benefit')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Tipe Pekerjaan *</label>
                    <select name="tipe_pekerjaan" class="form-select" required>
                        <option value="">Pilih Tipe</option>
                        <option value="full_time" {{ old('tipe_pekerjaan') == 'full_time' ? 'selected' : '' }}>Full Time</option>
                        <option value="part_time" {{ old('tipe_pekerjaan') == 'part_time' ? 'selected' : '' }}>Part Time</option>
                        <option value="kontrak" {{ old('tipe_pekerjaan') == 'kontrak' ? 'selected' : '' }}>Kontrak</option>
                        <option value="magang" {{ old('tipe_pekerjaan') == 'magang' ? 'selected' : '' }}>Magang</option>
                    </select>
                    @error('tipe_pekerjaan')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="form-label">Lokasi *</label>
                    <input type="text" name="lokasi" value="{{ old('lokasi') }}" class="form-input" placeholder="Contoh: Surabaya" required>
                    @error('lokasi')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Gaji Minimum (Rp)</label>
                    <input type="number" name="gaji_min" value="{{ old('gaji_min') }}" class="form-input" placeholder="3000000">
                    @error('gaji_min')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="form-label">Gaji Maksimum (Rp)</label>
                    <input type="number" name="gaji_max" value="{{ old('gaji_max') }}" class="form-input" placeholder="5000000">
                    @error('gaji_max')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Kuota</label>
                    <input type="number" name="kuota" value="{{ old('kuota') }}" class="form-input" placeholder="5">
                    <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ada batasan</p>
                    @error('kuota')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="form-label">Thumbnail</label>
                    <input type="file" name="thumbnail" accept="image/*" class="form-input">
                    <p class="text-xs text-gray-500 mt-1">Maksimal 2MB</p>
                    @error('thumbnail')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Tanggal Mulai *</label>
                    <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', date('Y-m-d')) }}" class="form-input" required>
                    @error('tanggal_mulai')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="form-label">Tanggal Berakhir *</label>
                    <input type="date" name="tanggal_berakhir" value="{{ old('tanggal_berakhir') }}" class="form-input" required>
                    @error('tanggal_berakhir')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('perusahaan.lowongan.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Lowongan</button>
            </div>
        </form>
    </div>
</div>
@endsection