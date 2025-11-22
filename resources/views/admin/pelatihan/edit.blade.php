@extends('layouts.app')

@section('title', 'Edit Pelatihan')
@section('page-title', 'Edit Pelatihan Industri')

@section('content')
<div class="max-w-6xl mx-auto p-4 sm:p-6 lg:p-8">
    <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
        
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-xl font-bold text-gray-800 flex items-center">
                <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Formulir Edit Pelatihan
            </h3>
            <p class="text-sm text-gray-500 mt-1">Perbarui detail pelatihan: **{{ $pelatihan->judul }}**</p>
        </div>

        <form action="{{ route('admin.pelatihan.update', $pelatihan->id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-8">
            @csrf
            @method('PUT')

            <fieldset class="space-y-6">
                <legend class="text-lg font-bold text-indigo-600 border-b pb-2 mb-4">Informasi Dasar</legend>
                
                <div>
                    <label for="judul" class="block text-sm font-medium text-gray-700 mb-1">Judul Pelatihan <span class="text-red-500">*</span></label>
                    <input type="text" id="judul" name="judul" value="{{ old('judul', $pelatihan->judul) }}"
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    @error('judul')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi <span class="text-red-500">*</span></label>
                    <textarea id="deskripsi" name="deskripsi" rows="5"
                              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>{{ old('deskripsi', $pelatihan->deskripsi) }}</textarea>
                    @error('deskripsi')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="jenis" class="block text-sm font-medium text-gray-700 mb-1">Jenis Pelatihan <span class="text-red-500">*</span></label>
                        <select id="jenis" name="jenis" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            <option value="soft_skill" {{ old('jenis', $pelatihan->jenis) == 'soft_skill' ? 'selected' : '' }}>Soft Skill</option>
                            <option value="hard_skill" {{ old('jenis', $pelatihan->jenis) == 'hard_skill' ? 'selected' : '' }}>Hard Skill</option>
                            <option value="sertifikasi" {{ old('jenis', $pelatihan->jenis) == 'sertifikasi' ? 'selected' : '' }}>Sertifikasi</option>
                            <option value="pembekalan" {{ old('jenis', $pelatihan->jenis) == 'pembekalan' ? 'selected' : '' }}>Pembekalan</option>
                        </select>
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                        <select id="status" name="status" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="draft" {{ old('status', $pelatihan->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status', $pelatihan->status) == 'published' ? 'selected' : '' }}>Published (Segera Dibuka)</option>
                            <option value="ongoing" {{ old('status', $pelatihan->status) == 'ongoing' ? 'selected' : '' }}>Ongoing (Sedang Berlangsung)</option>
                            <option value="completed" {{ old('status', $pelatihan->status) == 'completed' ? 'selected' : '' }}>Completed (Selesai)</option>
                            <option value="cancelled" {{ old('status', $pelatihan->status) == 'cancelled' ? 'selected' : '' }}>Cancelled (Dibatalkan)</option>
                        </select>
                    </div>
                </div>
            </fieldset>

            <hr class="border-gray-100">

            <fieldset class="space-y-4">
                <legend class="text-lg font-bold text-indigo-600 border-b pb-2 mb-4">Media</legend>
                
                @if($pelatihan->thumbnail)
                <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-3 sm:space-y-0 sm:space-x-6 p-4 border border-gray-200 rounded-lg bg-gray-50">
                    <div class="flex-shrink-0">
                        <label class="block text-sm font-medium text-gray-700">Thumbnail Saat Ini</label>
                        <img src="{{ Storage::url($pelatihan->thumbnail) }}" alt="Thumbnail Pelatihan" class="w-32 h-20 object-cover rounded-lg mt-1 shadow">
                    </div>
                    <p class="text-sm text-gray-600">Anda dapat mengganti thumbnail di bawah. Jika tidak, gambar di atas akan tetap digunakan.</p>
                </div>
                @endif

                <div>
                    <label for="thumbnail" class="block text-sm font-medium text-gray-700 mb-1">Upload Thumbnail Baru</label>
                    <input type="file" id="thumbnail" name="thumbnail" accept="image/*"
                           class="w-full file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 block text-sm text-gray-500 border border-gray-300 rounded-lg shadow-sm cursor-pointer focus:outline-none">
                    <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Ukuran maksimal 2MB. Kosongkan jika tidak ingin mengubah.</p>
                    @error('thumbnail')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </fieldset>
            
            <hr class="border-gray-100">

            <fieldset class="space-y-6">
                <legend class="text-lg font-bold text-indigo-600 border-b pb-2 mb-4">Detail Pelaksanaan</legend>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="instruktur" class="block text-sm font-medium text-gray-700 mb-1">Nama Instruktur</label>
                        <input type="text" id="instruktur" name="instruktur" value="{{ old('instruktur', $pelatihan->instruktur) }}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="tempat" class="block text-sm font-medium text-gray-700 mb-1">Tempat / Lokasi</label>
                        <input type="text" id="tempat" name="tempat" value="{{ old('tempat', $pelatihan->tempat) }}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-1">Tanggal & Waktu Mulai <span class="text-red-500">*</span></label>
                        <input type="datetime-local" id="tanggal_mulai" name="tanggal_mulai"
                               value="{{ old('tanggal_mulai', $pelatihan->tanggal_mulai->format('Y-m-d\TH:i')) }}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    </div>
                    <div>
                        <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 mb-1">Tanggal & Waktu Selesai <span class="text-red-500">*</span></label>
                        <input type="datetime-local" id="tanggal_selesai" name="tanggal_selesai"
                               value="{{ old('tanggal_selesai', $pelatihan->tanggal_selesai->format('Y-m-d\TH:i')) }}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="kuota" class="block text-sm font-medium text-gray-700 mb-1">Kuota Peserta</label>
                        <input type="number" id="kuota" name="kuota" value="{{ old('kuota', $pelatihan->kuota) }}" min="1"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="biaya" class="block text-sm font-medium text-gray-700 mb-1">Biaya (Rp)</label>
                        <input type="number" id="biaya" name="biaya" value="{{ old('biaya', $pelatihan->biaya) }}" min="0"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>
            </fieldset>

            <hr class="border-gray-100">

            <fieldset class="space-y-4">
                <legend class="text-lg font-bold text-indigo-600 border-b pb-2 mb-4">Dokumen</legend>

                @if($pelatihan->materi)
                <div class="p-3 bg-blue-50 border-l-4 border-blue-500 rounded-lg">
                    <p class="text-sm font-medium text-blue-700 mb-1">Materi Saat Ini Tersedia:</p>
                    <a href="{{ Storage::url($pelatihan->materi) }}" target="_blank"
                       class="text-blue-600 hover:text-blue-800 text-sm font-semibold flex items-center transition duration-150">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Download dan Pratinjau Materi (PDF)
                    </a>
                </div>
                @endif
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="materi" class="block text-sm font-medium text-gray-700 mb-1">Upload Materi Baru (PDF)</label>
                        <input type="file" id="materi" name="materi" accept=".pdf"
                               class="w-full file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 block text-sm text-gray-500 border border-gray-300 rounded-lg shadow-sm cursor-pointer focus:outline-none">
                        <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah materi.</p>
                        @error('materi')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="sertifikat_template" class="block text-sm font-medium text-gray-700 mb-1">Template Sertifikat Baru (PDF)</label>
                        <input type="file" id="sertifikat_template" name="sertifikat_template" accept=".pdf"
                               class="w-full file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 block text-sm text-gray-500 border border-gray-300 rounded-lg shadow-sm cursor-pointer focus:outline-none">
                        <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah template.</p>
                        @error('sertifikat_template')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
            </fieldset>

            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-100">
                <a href="{{ route('admin.pelatihan.index') }}" class="btn btn-outline border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium py-2 px-4 rounded-lg transition duration-150">Batal</a>
                <button type="submit" class="btn btn-primary bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-150 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection