@extends('layouts.app')

@section('title', 'Edit Profil')
@section('page-title', 'Edit Profil')

@section('content')
<div class="max-w-4xl mx-auto p-4 sm:p-6 lg:p-8 space-y-6">
    <!-- Back Button -->
    <a href="{{ route('profile') }}" class="inline-flex items-center text-sm font-semibold text-indigo-600 hover:text-indigo-700 transition-colors gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali ke Profil
    </a>

    @if(auth()->user()->isMahasiswa())
        @php $mahasiswa = auth()->user()->mahasiswa; @endphp

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden space-y-8 p-6 sm:p-8">
            @csrf
            @method('PUT')

            <!-- Foto Profil Section -->
            <div class="pb-6 border-b border-slate-100">
                <h3 class="text-sm font-bold text-indigo-600 uppercase tracking-wide mb-6 flex items-center gap-2.5">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Foto Profil
                </h3>

                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">
                    <div class="relative group shrink-0">
                        <div class="w-28 h-28 rounded-2xl border-4 border-slate-100 shadow-sm overflow-hidden bg-slate-100 relative">
                            @if(auth()->user()->avatar)
                                <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="Avatar" class="w-full h-full object-cover" id="avatarPreview">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-indigo-500 to-indigo-600 text-white text-3xl font-bold" id="avatarPreview">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="flex-1 w-full text-center sm:text-left space-y-3">
                        <label class="block text-sm font-semibold text-slate-700">Perbarui Foto</label>
                        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-3">
                            <label for="avatar" class="cursor-pointer px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold shadow-sm transition-colors duration-200">
                                Pilih File Baru
                            </label>
                            <input type="file" id="avatar" name="avatar" accept="image/*" class="hidden" onchange="previewAvatar(event)">
                            <span class="text-xs font-medium text-slate-400" id="fileName">Belum ada berkas terpilih</span>
                        </div>
                        <p class="text-xs text-slate-400 leading-relaxed">Format: JPG, PNG. Maksimal ukuran file 2MB.</p>
                        @error('avatar')<p class="text-xs font-medium text-rose-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Data Akun & Pendidikan Section -->
            <div class="space-y-5">
                <h3 class="text-sm font-bold text-indigo-600 uppercase tracking-wide border-b border-slate-100 pb-2">
                    Data Akun & Pendidikan
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label for="name" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">Nama Lengkap <span class="text-rose-500">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name', auth()->user()->name) }}" class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5" required>
                        @error('name')<p class="text-xs font-medium text-rose-600 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">Email Akun <span class="text-rose-500">*</span></label>
                        <input type="email" id="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5" required>
                        @error('email')<p class="text-xs font-medium text-rose-600 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="nim" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">NIM / Nomor Induk <span class="text-rose-500">*</span></label>
                        <!-- Menambahkan atribut readonly, bg-slate-50, dan cursor-not-allowed -->
                        <input type="text" id="nim" name="nim" value="{{ old('nim', $mahasiswa->nim ?? '') }}" class="w-full border-slate-200 rounded-xl shadow-sm bg-slate-50 cursor-not-allowed text-sm p-2.5 text-slate-500" readonly>
                        @error('nim')<p class="text-xs font-medium text-rose-600 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="tingkat_pendidikan" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">Tingkat Pendidikan <span class="text-rose-500">*</span></label>
                        <select id="tingkat_pendidikan" name="tingkat_pendidikan" class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5" required>
                            @foreach(['SD', 'SMP', 'SMA', 'SMK', 'D1', 'D2', 'D3', 'S1', 'S2', 'S3'] as $tingkat)
                                <option value="{{ $tingkat }}" {{ old('tingkat_pendidikan', $mahasiswa->tingkat_pendidikan ?? '') == $tingkat ? 'selected' : '' }}>{{ $tingkat }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="fakultas_field">
                        <label for="fakultas" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">Fakultas</label>
                        <input type="text" id="fakultas" name="fakultas" value="{{ old('fakultas', $mahasiswa->fakultas_id ?? '') }}" class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5" placeholder="Nama Fakultas">
                    </div>

                    <div id="prodi_field">
                        <label id="prodi_label" for="program_studi" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">Program Studi</label>
                        <input type="text" id="program_studi" name="program_studi" value="{{ old('program_studi', $mahasiswa->program_studi_id ?? '') }}" class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5" placeholder="Nama Prodi / Jurusan">
                    </div>

                    <div id="asal_sekolah_field">
                        <label id="sekolah_label" for="asal_sekolah" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">Asal Sekolah</label>
                        <input type="text" id="asal_sekolah" name="asal_sekolah" value="{{ old('asal_sekolah', $mahasiswa->asal_sekolah ?? '') }}" class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5" placeholder="Nama Institusi Asal">
                    </div>
                </div>
            </div>

            <!-- Data Pribadi Section -->
            <div class="space-y-5 pt-4">
                <h3 class="text-sm font-bold text-indigo-600 uppercase tracking-wide border-b border-slate-100 pb-2">
                    Data Pribadi & Orang Tua
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="tempat_lahir" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">Tempat Lahir</label>
                        <input type="text" id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir', $mahasiswa->tempat_lahir ?? '') }}" class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5">
                    </div>

                    <div>
                        <label for="tanggal_lahir" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">Tanggal Lahir</label>
                        <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir', $mahasiswa->tanggal_lahir ? $mahasiswa->tanggal_lahir->format('Y-m-d') : '') }}" class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5">
                    </div>

                    <div>
                        <label for="jenis_kelamin" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">Jenis Kelamin <span class="text-rose-500">*</span></label>
                        <select id="jenis_kelamin" name="jenis_kelamin" class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5" required>
                            <option value="L" {{ old('jenis_kelamin', $mahasiswa->jenis_kelamin ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin', $mahasiswa->jenis_kelamin ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <div>
                        <label for="agama" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">Agama</label>
                        <select id="agama" name="agama" class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5">
                            <option value="">Pilih Agama</option>
                            @foreach(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'] as $agama)
                                <option value="{{ $agama }}" {{ old('agama', $mahasiswa->agama ?? '') == $agama ? 'selected' : '' }}>{{ $agama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="no_telp" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">No. Telepon Pribadi</label>
                        <input type="text" id="no_telp" name="no_telp" value="{{ old('no_telp', $mahasiswa->no_telp ?? '') }}" class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5">
                    </div>

                    <div>
                        <label for="nama_ortu" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">Nama Orang Tua / Wali</label>
                        <input type="text" id="nama_ortu" name="nama_ortu" value="{{ old('nama_ortu', $mahasiswa->nama_ortu ?? '') }}" class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5">
                    </div>

                    <div class="md:col-span-2">
                        <label for="alamat" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">Alamat Lengkap</label>
                        <textarea id="alamat" name="alamat" rows="2" class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5 resize-none">{{ old('alamat', $mahasiswa->alamat ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Ubah Password Section -->
            <div class="space-y-5 pt-4">
                <h3 class="text-sm font-bold text-rose-600 uppercase tracking-wide border-b border-slate-100 pb-2">
                    Ubah Password Akun
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="password" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">Password Baru</label>
                        <input type="password" id="password" name="password" class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5" placeholder="Minimal 6 karakter">
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">Konfirmasi Password Baru</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5" placeholder="Ketik ulang password">
                    </div>
                </div>
                <p class="text-xs text-slate-400 font-medium">Biarkan kolom password kosong jika tidak ingin mengubah password masuk Anda.</p>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('profile') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition-colors duration-200">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-sm font-semibold text-white shadow-sm transition-colors duration-200">
                    Simpan Perubahan
                </button>
            </div>
        </form>

    @elseif(auth()->user()->isPerusahaan())
        @php $perusahaan = auth()->user()->perusahaan; @endphp
        
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden space-y-8 p-6 sm:p-8">
            @csrf
            @method('PUT')

            <!-- Logo Perusahaan -->
            <div class="pb-6 border-b border-slate-100">
                <h3 class="text-sm font-bold text-indigo-600 uppercase tracking-wide mb-6 flex items-center gap-2.5">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Logo Perusahaan
                </h3>

                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">
                    <div class="w-28 h-28 rounded-2xl border-4 border-slate-100 shadow-sm overflow-hidden bg-slate-100 relative shrink-0">
                        @if(auth()->user()->avatar)
                            <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="Logo" class="w-full h-full object-cover" id="avatarPreview">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-indigo-500 to-indigo-600 text-white text-3xl font-bold" id="avatarPreview">
                                {{ substr(auth()->user()->name ?? 'P', 0, 1) }}
                            </div>
                        @endif
                    </div>

                    <div class="flex-1 w-full text-center sm:text-left space-y-3">
                        <label class="block text-sm font-semibold text-slate-700">Perbarui Logo Berkas</label>
                        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-3">
                            <label for="avatar" class="cursor-pointer px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold shadow-sm transition-colors duration-200">
                                Pilih Logo Baru
                            </label>
                            <input type="file" id="avatar" name="avatar" accept="image/*" class="hidden" onchange="previewAvatar(event)">
                            <span class="text-xs font-medium text-slate-400" id="fileName">Belum ada logo terpilih</span>
                        </div>
                        <p class="text-xs text-slate-400">Rekomendasi format persegi, ukuran maksimal 2MB.</p>
                    </div>
                </div>
            </div>

            <!-- Detail Profil Perusahaan -->
            <div class="space-y-5">
                <h3 class="text-sm font-bold text-indigo-600 uppercase tracking-wide border-b border-slate-100 pb-2">
                    Informasi Instansi Perusahaan
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="name" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">Nama Perusahaan <span class="text-rose-500">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name', auth()->user()->name) }}" class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5" required>
                    </div>
                    <div>
                        <label for="email" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">Email Operasional Akun <span class="text-rose-500">*</span></label>
                        <input type="email" id="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5" required>
                    </div>
                    <div>
                        <label for="bidang_usaha" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">Bidang Usaha</label>
                        <input type="text" id="bidang_usaha" name="bidang_usaha" value="{{ old('bidang_usaha', $perusahaan->bidang_usaha) }}" class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5">
                    </div>
                    <div>
                        <label for="jenis_pt" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">Jenis PT / Badan Hukum</label>
                        <select id="jenis_pt" name="jenis_pt" class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5">
                            <option value="">Pilih Jenis PT</option>
                            @foreach(\App\Models\Perusahaan::jenisPtOptions() as $value => $label)
                                <option value="{{ $value }}" {{ old('jenis_pt', $perusahaan->jenis_pt) == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="nama_pimpinan" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">Nama Pimpinan / Direktur <span class="text-rose-500">*</span></label>
                        <input type="text" id="nama_pimpinan" name="nama_pimpinan" value="{{ old('nama_pimpinan', $perusahaan->nama_pimpinan) }}" class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5" required>
                    </div>
                    <div>
                        <label for="website" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">Website Resmi URL</label>
                        <input type="url" id="website" name="website" value="{{ old('website', $perusahaan->website) }}" class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5" placeholder="https://example.com">
                    </div>
                    <div class="md:col-span-2">
                        <label for="deskripsi" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">Tentang & Deskripsi Singkat</label>
                        <textarea id="deskripsi" name="deskripsi" rows="3" class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5 resize-none">{{ old('deskripsi', $perusahaan->deskripsi) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Kontak & Berkas Berkas Dokumen -->
            <div class="space-y-5 pt-4">
                <h3 class="text-sm font-bold text-indigo-600 uppercase tracking-wide border-b border-slate-100 pb-2">
                    Lokasi, Kontak & Dokumen Pendukung
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label for="alamat" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">Alamat Kantor Utama <span class="text-rose-500">*</span></label>
                        <textarea id="alamat" name="alamat" rows="2" class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5 resize-none" required>{{ old('alamat', $perusahaan->alamat) }}</textarea>
                    </div>
                    <div>
                        <label for="kota" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">Kota / Kabupaten <span class="text-rose-500">*</span></label>
                        <input type="text" id="kota" name="kota" value="{{ old('kota', $perusahaan->kota) }}" class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5" required>
                    </div>
                    <div>
                        <label for="provinsi" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">Provinsi Wilayah <span class="text-rose-500">*</span></label>
                        <input type="text" id="provinsi" name="provinsi" value="{{ old('provinsi', $perusahaan->provinsi) }}" class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5" required>
                    </div>
                    <div>
                        <label for="no_telp" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">No. Telepon Kantor Resmi <span class="text-rose-500">*</span></label>
                        <input type="text" id="no_telp" name="no_telp" value="{{ old('no_telp', $perusahaan->no_telp) }}" class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5" required>
                    </div>
                    <div>
                        <label for="no_hp" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">No. WhatsApp Narahubung (PIC)</label>
                        <input type="text" id="no_hp" name="no_hp" value="{{ old('no_hp', $perusahaan->no_hp) }}" class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5">
                    </div>
                    <div class="md:col-span-2">
                        <label for="cv_perusahaan" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">Unggah Ulang Company Profile / Legalitas Berkas (PDF)</label>
                        <input type="file" id="cv_perusahaan" name="cv_perusahaan" accept="application/pdf" class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-1.5">
                        @if($perusahaan->cv_perusahaan)
                            <p class="text-xs font-medium text-emerald-600 mt-2 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Dokumen aktif tersedia. <a href="{{ Storage::url($perusahaan->cv_perusahaan) }}" target="_blank" class="underline font-bold hover:text-emerald-700">Buka PDF Terunggah</a>
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Password & Kirim -->
            <div class="space-y-5 pt-4">
                <h3 class="text-sm font-bold text-rose-600 uppercase tracking-wide border-b border-slate-100 pb-2">
                    Keamanan Password
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="password" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">Kata Sandi Baru</label>
                        <input type="password" id="password" name="password" class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5">
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">Ulangi Kata Sandi Baru</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5">
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('profile') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition-colors duration-200">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-sm font-semibold text-white shadow-sm transition-colors duration-200">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    @endif
</div>

<script>
function previewAvatar(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('avatarPreview');
    const fileName = document.getElementById('fileName');
    
    if (file) {
        fileName.textContent = file.name;
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" alt="Preview" class="w-full h-full object-cover">`;
        }
        reader.readAsDataURL(file);
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const tingkatPendidikan = document.getElementById('tingkat_pendidikan');
    const fakultasField = document.getElementById('fakultas_field');
    const prodiField = document.getElementById('prodi_field');
    const prodiLabel = document.getElementById('prodi_label');
    const asalSekolahField = document.getElementById('asal_sekolah_field');
    const sekolahLabel = document.getElementById('sekolah_label');

    function toggleFields() {
        if (!tingkatPendidikan) return;
        const val = tingkatPendidikan.value;

        if (['SD', 'SMP'].includes(val)) {
            if(fakultasField) fakultasField.classList.add('hidden');
            if(prodiField) prodiField.classList.add('hidden');
            if(asalSekolahField) asalSekolahField.className = "md:col-span-2";
        } 
        else if (['SMA', 'SMK'].includes(val)) {
            if(fakultasField) fakultasField.classList.add('hidden');
            if(prodiField) {
                prodiField.classList.remove('hidden');
                prodiLabel.innerText = "Jurusan";
            }
            if(asalSekolahField) asalSekolahField.className = "md:col-span-1";
        } 
        else {
            if(fakultasField) fakultasField.classList.remove('hidden');
            if(prodiField) {
                prodiField.classList.remove('hidden');
                prodiLabel.innerText = "Program Studi";
            }
            if(asalSekolahField) asalSekolahField.className = "md:col-span-1";
        }
    }

    if(tingkatPendidikan) {
        tingkatPendidikan.addEventListener('change', toggleFields);
        toggleFields();
    }
});
</script>
@endsection