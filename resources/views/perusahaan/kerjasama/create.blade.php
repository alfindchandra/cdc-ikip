@extends('layouts.app')

@section('title', 'Ajukan Kerjasama')
@section('page-title', 'Ajukan Kerjasama Baru')

@section('content')
<div class="max-w-5xl mx-auto pb-12">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Ajukan Kerjasama Baru</h2>
            <p class="text-sm text-gray-500">Kirim penawaran kerjasama kepada sekolah. Pengajuan Anda akan ditinjau dan disetujui (ACC) oleh admin.</p>
        </div>
        <a href="{{ route('perusahaan.kerjasama.index') }}" class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-indigo-600 transition-colors">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar
        </a>
    </div>

    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-xl flex items-start space-x-3">
        <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div class="text-sm text-blue-800">
            <p>Pengajuan ini akan dikirim sebagai <strong>{{ auth()->user()->perusahaan->nama_perusahaan }}</strong> dan berstatus <strong>Proposal</strong> sampai disetujui (ACC) oleh admin.</p>
            <p class="mt-2 text-xs text-blue-700">Anda dapat memilih sendiri jenis dokumen yang ingin diunggah: <strong>MoU</strong>, <strong>MoA</strong>, atau <strong>Surat Kerjasama</strong>. Setelah dikirim, admin akan langsung meninjau dan meng-ACC pengajuan Anda.</p>
        </div>
    </div>

    <form action="{{ route('perusahaan.kerjasama.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden transition-all hover:shadow-md">
            <div class="p-6 border-b border-gray-50 bg-gray-50/50">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-indigo-100 rounded-lg text-indigo-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Informasi Dasar</h3>
                </div>
            </div>

            <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                <div>
                    <label for="jenis_kerjasama" class="block text-sm font-semibold text-gray-700 mb-2">Jenis Kerjasama <span class="text-red-500">*</span></label>
                    <select id="jenis_kerjasama" name="jenis_kerjasama" class="block w-full px-4 py-3 rounded-xl border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all shadow-sm" required>
                        <option value="">-- Pilih Jenis --</option>
                        @foreach(['magang' => 'Magang', 'rekrutmen' => 'Rekrutmen', 'pelatihan' => 'Pelatihan', 'penelitian' => 'Penelitian', 'sponsorship' => 'Sponsorship', 'lainnya' => 'Lainnya'] as $val => $label)
                            <option value="{{ $val }}" {{ old('jenis_kerjasama') == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('jenis_kerjasama')<p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="judul" class="block text-sm font-semibold text-gray-700 mb-2">Judul Kerjasama <span class="text-red-500">*</span></label>
                    <input type="text" id="judul" name="judul" value="{{ old('judul') }}" placeholder="Contoh: Penawaran Kerjasama Rekrutmen Lulusan" class="block w-full px-4 py-3 rounded-xl border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all shadow-sm" required>
                    @error('judul')<p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="lingkup_kerjasama" class="block text-sm font-semibold text-gray-700 mb-2">Lingkup Kerjasama <span class="text-red-500">*</span></label>
                    <select id="lingkup_kerjasama" name="lingkup_kerjasama" class="block w-full px-4 py-3 rounded-xl border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all shadow-sm" required>
                        <option value="">-- Pilih Lingkup --</option>
                        @foreach(\App\Models\KerjasamaIndustri::lingkupOptions() as $val => $label)
                            <option value="{{ $val }}" {{ old('lingkup_kerjasama') == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('lingkup_kerjasama')<p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label for="deskripsi" class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi Kerjasama</label>
                    <textarea id="deskripsi" name="deskripsi" rows="4" placeholder="Jelaskan poin-poin penting kerjasama yang ditawarkan..." class="block w-full px-4 py-3 rounded-xl border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all shadow-sm">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')<p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 space-y-6">
                <div class="flex items-center space-x-3 mb-2">
                    <div class="p-2 bg-emerald-100 rounded-lg text-emerald-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Periode Waktu</h3>
                </div>

                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="tanggal_mulai" class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Mulai <span class="text-red-500">*</span></label>
                        <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" class="block w-full px-4 py-3 rounded-xl border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all shadow-sm" required>
                        @error('tanggal_mulai')<p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="tanggal_berakhir" class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Berakhir</label>
                        <input type="date" id="tanggal_berakhir" name="tanggal_berakhir" value="{{ old('tanggal_berakhir') }}" class="block w-full px-4 py-3 rounded-xl border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all shadow-sm">
                        <p class="mt-2 text-xs text-gray-400 italic font-medium">Kosongkan jika kerjasama berlaku selamanya.</p>
                        @error('tanggal_berakhir')<p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 space-y-6">
                <div class="flex items-center space-x-3 mb-2">
                    <div class="p-2 bg-amber-100 rounded-lg text-amber-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Administrasi & Nilai</h3>
                </div>

                <div>
                    <label for="nilai_kontrak" class="block text-sm font-semibold text-gray-700 mb-2">Nilai Kontrak (Rp)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400 font-medium">Rp</span>
                        <input type="number" id="nilai_kontrak" name="nilai_kontrak" value="{{ old('nilai_kontrak') }}" class="block w-full pl-12 pr-4 py-3 rounded-xl border-gray-200 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-all shadow-sm" placeholder="0">
                    </div>
                    @error('nilai_kontrak')<p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="jenis_dokumen" class="block text-sm font-semibold text-gray-700 mb-2">Jenis Dokumen yang Diunggah <span class="text-red-500">*</span></label>
                    <select id="jenis_dokumen" name="jenis_dokumen" class="block w-full px-4 py-3 rounded-xl border-gray-200 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-all shadow-sm" required>
                        <option value="">-- Pilih Jenis Dokumen --</option>
                        @foreach(\App\Models\KerjasamaIndustri::jenisDokumenOptions() as $val => $label)
                            <option value="{{ $val }}" {{ old('jenis_dokumen') == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('jenis_dokumen')<p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>@enderror
                    <p class="mt-1 text-xs text-gray-400 italic">Pilih dokumen yang paling sesuai dengan tahap kerjasama Anda dengan sekolah.</p>
                </div>

                <div class="mt-6">
                    <label for="dokumen_kerjasama" class="block text-sm font-semibold text-gray-700 mb-2">Unggah Dokumen (PDF) <span class="text-red-500">*</span></label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-200 border-dashed rounded-xl hover:border-indigo-400 transition-colors cursor-pointer bg-gray-50/30">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-10 w-10 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="dokumen_kerjasama" class="relative cursor-pointer rounded-md font-semibold text-indigo-600 hover:text-indigo-500">
                                    <span>Upload file</span>
                                </label>
                                <p class="pl-1">atau drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-400">PDF hingga 10MB</p>
                        </div>
                    </div>
                    <input id="dokumen_kerjasama" name="dokumen_kerjasama" type="file" accept=".pdf" class="block w-full mt-2 text-sm text-gray-600" required>
                    @error('dokumen_kerjasama')<p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>@enderror
                    <p class="mt-1 text-xs text-gray-400 italic">Dokumen akan langsung ditinjau dan di-ACC oleh admin, tanpa tahap tambahan.</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            <div class="flex items-center space-x-3 mb-6">
                <div class="p-2 bg-blue-100 rounded-lg text-blue-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-800">Person in Charge (PIC) & Catatan</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="pic_industri" class="block text-sm font-semibold text-gray-700 mb-2">Nama PIC Perusahaan</label>
                    <input type="text" id="pic_industri" name="pic_industri" value="{{ old('pic_industri') }}" placeholder="Nama kontak di perusahaan Anda" class="block w-full px-4 py-3 rounded-xl border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm">
                    @error('pic_industri')<p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="jabatan_pic_industri" class="block text-sm font-semibold text-gray-700 mb-2">Jabatan PIC</label>
                    <input type="text" id="jabatan_pic_industri" name="jabatan_pic_industri" value="{{ old('jabatan_pic_industri') }}" placeholder="HRD Manager, Direktur, dll" class="block w-full px-4 py-3 rounded-xl border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm">
                    @error('jabatan_pic_industri')<p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="no_telp_pic_industri" class="block text-sm font-semibold text-gray-700 mb-2">No. Telepon PIC</label>
                    <input type="text" id="no_telp_pic_industri" name="no_telp_pic_industri" value="{{ old('no_telp_pic_industri') }}" placeholder="08xxxxxxxxxx" class="block w-full px-4 py-3 rounded-xl border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm">
                    @error('no_telp_pic_industri')<p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="email_pic_industri" class="block text-sm font-semibold text-gray-700 mb-2">Email PIC</label>
                    <input type="email" id="email_pic_industri" name="email_pic_industri" value="{{ old('email_pic_industri') }}" placeholder="pic@company.com" class="block w-full px-4 py-3 rounded-xl border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm">
                    @error('email_pic_industri')<p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="mt-6">
                <label for="catatan" class="block text-sm font-semibold text-gray-700 mb-2">Catatan Tambahan</label>
                <textarea id="catatan" name="catatan" rows="3" placeholder="Informasi tambahan untuk admin..." class="block w-full px-4 py-3 rounded-xl border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm">{{ old('catatan') }}</textarea>
                @error('catatan')<p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="flex items-center justify-end space-x-4 pt-4">
            <a href="{{ route('perusahaan.kerjasama.index') }}" class="px-6 py-3 text-sm font-semibold text-gray-600 hover:text-gray-800 transition-colors">Batal</a>
            <button type="submit" class="px-8 py-3 bg-indigo-600 text-white rounded-xl font-bold shadow-lg shadow-indigo-200 hover:bg-indigo-700 hover:-translate-y-0.5 transition-all focus:ring-4 focus:ring-indigo-100">
                Kirim Dokumen & Ajukan Kerjasama
            </button>
        </div>
    </form>
</div>
@endsection