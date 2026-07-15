@extends('layouts.app')

@section('title', 'Edit Profil Perusahaan - CDC')

@section('content')
<div class="px-6 py-8 mx-auto max-w-6xl">

    <!-- BREADCRUMB & HEADER ACTION -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <nav class="flex items-center space-x-2 text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1">
                <a href="{{ route('admin.perusahaan.index') }}" class="hover:text-blue-600 transition">Perusahaan</a>
                <span class="text-gray-300">/</span>
                <a href="{{ route('admin.perusahaan.show', $perusahaan) }}" class="hover:text-blue-600 transition">{{ Str::limit($perusahaan->nama_perusahaan, 20) }}</a>
                <span class="text-gray-300">/</span>
                <span class="text-gray-600">Edit Profil</span>
            </nav>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">Perbarui Profil Instansi</h1>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.perusahaan.show', $perusahaan) }}" 
               class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 hover:text-gray-900 shadow-sm transition">
                <i class="fas fa-times mr-2 text-gray-400"></i> Batal
            </a>
        </div>
    </div>

    <!-- MAIN FORM -->
    <form action="{{ route('admin.perusahaan.update', $perusahaan) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- PANEL KIRI: LOGO, STATUS, & KREDENSIAL UTAMA -->
            <div class="lg:col-span-1 space-y-6">
                
                <!-- KARTU LOGO -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 text-center relative overflow-hidden">
                    <div class="absolute inset-x-0 top-0 h-16 bg-gradient-to-r from-blue-500 to-indigo-600"></div>
                    
                    <div class="relative pt-4 mb-4 flex justify-center">
                        <div class="h-24 w-24 bg-white p-2 rounded-2xl shadow-md border border-gray-100 flex items-center justify-center overflow-hidden group relative">
                            @if($perusahaan->logo)
                                <img src="{{ asset('storage/'.$perusahaan->logo) }}" class="max-h-full max-w-full object-contain">
                            @else
                                <i class="fas fa-building text-3xl text-gray-300"></i>
                            @endif
                        </div>
                    </div>
                    
                    <h3 class="font-bold text-gray-800 text-base mb-1">{{ old('nama_perusahaan', $perusahaan->nama_perusahaan) }}</h3>
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-4">{{ $perusahaan->bidang_usaha ?? 'Sektor Usaha' }}</p>
                    
                    <div class="mt-4 pt-4 border-t border-gray-50">
                        <label class="block text-xs font-bold text-left text-gray-400 uppercase tracking-wider mb-2">Unggah Logo Baru</label>
                        <input type="file" name="logo" accept="image/*"
                               class="block w-full p-2 text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition cursor-pointer">
                    </div>
                </div>

                <!-- KARTU KREDENSIAL & MANAGEMENT STATUS (FIX PENDING) -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-5">
                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider border-b border-gray-50 pb-2 mb-2 flex items-center">
                        <i class="fas fa-sliders-h text-gray-400 mr-2"></i> Kontrol & Akses Akun
                    </h4>

                    <!-- Input Status Kerjasama (Fix Pending Handled) -->
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Status Kemitraan *</label>
                        <div class="relative">
                            <select name="status_kerjasama" class="block p-2 w-full text-sm bg-white border border-gray-200 rounded-xl px-3 py-2 text-gray-700 appearance-none focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition cursor-pointer">
                                <option value="pending" {{ old('status_kerjasama', $perusahaan->status_kerjasama) === 'pending' ? 'selected' : '' }}>🟡 Pending (Tinjauan)</option>
                                <option value="aktif" {{ old('status_kerjasama', $perusahaan->status_kerjasama) === 'aktif' ? 'selected' : '' }}>🟢 Aktif</option>
                                <option value="nonaktif" {{ old('status_kerjasama', $perusahaan->status_kerjasama) === 'nonaktif' ? 'selected' : '' }}>🔴 Nonaktif</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-400">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                        @error('status_kerjasama')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Akun -->
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Email Akun *</label>
                        <input type="email" name="email" value="{{ old('email', $perusahaan->user->email ?? '') }}"
                               class="w-full p-2 text-sm bg-gray-50 rounded-xl border-gray-200 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" required>
                    </div>

                    <!-- Password Reset -->
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Ubah Kata Sandi</label>
                        <input type="password" name="password" 
                               class="w-full p-2 text-sm bg-gray-50 rounded-xl border-gray-200 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" 
                               placeholder="••••••••">
                        <p class="text-[10px] text-gray-400 mt-1">Biarkan kosong jika tidak ingin memperbarui password login instansi ini.</p>
                    </div>
                </div>
            </div>

            <!-- PANEL KANAN: FORMULIR ATRIBUT IDENTITAS -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- CARD 1: IDENTITAS UTAMA & LEGALITAS -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wide border-b border-gray-100 pb-3 mb-5 flex items-center">
                        <i class="fas fa-file-alt text-blue-500 mr-2.5"></i> Profil & Legalitas Hukum
                    </h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Nama Perusahaan / PT *</label>
                            <input type="text" name="nama_perusahaan" value="{{ old('nama_perusahaan', $perusahaan->user->name) }}"
                                   class="w-full p-2 text-sm rounded-xl border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" required>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Sektor / Bidang Usaha *</label>
                            <select name="bidang_usaha" class="w-full p-2 text-sm rounded-xl border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" required>
                                <option value="">Pilih Sektor</option>
                                @foreach(['Teknologi Informasi', 'Pendidikan', 'Kesehatan', 'Manufaktur', 'Perdagangan', 'Jasa', 'Konstruksi', 'Pariwisata', 'Keuangan', 'Lainnya'] as $opt)
                                    <option value="{{ $opt }}" {{ old('bidang_usaha', $perusahaan->bidang_usaha) == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Jenis Badan Hukum (PT) *</label>
                            <select name="jenis_pt" class="w-full p-2 text-sm rounded-xl border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                <option value="">Pilih Jenis</option>
                                @php
                                    $jenisPtOptions = method_exists('\App\Models\Perusahaan', 'jenisPtOptions') 
                                        ? \App\Models\Perusahaan::jenisPtOptions() 
                                        : ['lokal' => 'PT Lokal / Nasional', 'multinasional' => 'PT Multinasional / Asing', 'bumn' => 'BUMN / BUMD', 'startup' => 'Startup / Rintisan', 'umkm' => 'UMKM / Toko / CV'];
                                @endphp
                                @foreach($jenisPtOptions as $val => $lbl)
                                    <option value="{{ $val }}" {{ old('jenis_pt', $perusahaan->jenis_pt) == $val ? 'selected' : '' }}>{{ $lbl }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Tahun Berdiri</label>
                            <input type="number" name="tahun_berdiri" min="1900" max="{{ date('Y') }}" value="{{ old('tahun_berdiri', $perusahaan->tahun_berdiri) }}"
                                   class="w-full p-2 text-sm rounded-xl border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" placeholder="Contoh: 2015">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Jumlah Anggota Karyawan</label>
                            <input type="number" name="jumlah_karyawan" min="0" value="{{ old('jumlah_karyawan', $perusahaan->jumlah_karyawan) }}"
                                   class="w-full p-2 text-sm rounded-xl border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" placeholder="Contoh: 45">
                        </div>
                    </div>
                </div>

                <!-- CARD 2: KONTAK & STRUKTUR PIMPINAN -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wide border-b border-gray-100 pb-3 mb-5 flex items-center">
                        <i class="fas fa-address-book text-blue-500 mr-2.5"></i> Struktur Pimpinan & Kontak Operasional
                    </h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Nama Pimpinan / Direktur Utama *</label>
                            <input type="text" name="nama_pimpinan" value="{{ old('nama_pimpinan', $perusahaan->nama_pimpinan) }}"
                                   class="w-full p-2 text-sm rounded-xl border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" required>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">No. HP Pimpinan / Kantor</label>
                            <input type="text" name="no_hp" value="{{ old('no_hp', $perusahaan->no_hp) }}"
                                   class="w-full p-2 text-sm rounded-xl border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" placeholder="08xxxxxxxxxx">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Nomor Telepon Kantor (Fixline) *</label>
                            <input type="text" name="no_telp" value="{{ old('no_telp', $perusahaan->no_telp) }}"
                                   class="w-full p-2 text-sm rounded-xl border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" placeholder="021xxxxxxx" required>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Situs Web Resmi (URL)</label>
                            <input type="url" name="website" value="{{ old('website', $perusahaan->website) }}"
                                   class="w-full p-2 text-sm rounded-xl border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" placeholder="https://www.company.com">
                        </div>
                    </div>
                </div>

                <!-- CARD 3: LOKASI GEOGRAFIS -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wide border-b border-gray-100 pb-3 mb-5 flex items-center">
                        <i class="fas fa-map-marker-alt text-blue-500 mr-2.5"></i> Lokasi & Domisili Utama
                    </h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Alamat Jalan Kantor *</label>
                            <textarea name="alamat" rows="2" class="w-full p-2 text-sm rounded-xl border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" required>{{ old('alamat', $perusahaan->alamat) }}</textarea>
                        </div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Kota / Kabupaten *</label>
                                <input type="text" name="kota" value="{{ old('kota', $perusahaan->kota) }}"
                                       class="w-full p-2 text-sm rounded-xl border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Provinsi *</label>
                                <input type="text" name="provinsi" value="{{ old('provinsi', $perusahaan->provinsi) }}"
                                       class="w-full p-2 text-sm rounded-xl border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Kode Pos</label>
                                <input type="text" name="kode_pos" value="{{ old('kode_pos', $perusahaan->kode_pos) }}"
                                       class="w-full p-2 text-sm rounded-xl border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CARD 4: STRATEGIS (VISI, MISI, TENTANG) & DOKUMEN -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 grid grid-cols-1 gap-5">
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wide border-b border-gray-100 pb-3 flex items-center">
                        <i class="fas fa-bullseye text-blue-500 mr-2.5"></i> Informasi Visi, Misi & Dokumen
                    </h3>

                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Visi Perusahaan</label>
                        <textarea name="visi" rows="2" class="w-full p-2 text-sm rounded-xl border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" placeholder="Tulis visi strategis instansi...">{{ old('visi', $perusahaan->visi) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Misi Perusahaan</label>
                        <textarea name="misi" rows="2" class="w-full p-2 text-sm rounded-xl border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" placeholder="Tulis poin-poin misi instansi...">{{ old('misi', $perusahaan->misi) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Tentang Perusahaan / Deskripsi Tambahan</label>
                        <textarea name="deskripsi" rows="3" class="w-full p-2 text-sm rounded-xl border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" placeholder="Ringkasan profil umum perusahaan...">{{ old('deskripsi', $perusahaan->deskripsi) }}</textarea>
                    </div>

                    <div class="pt-2">
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Dokumen Pendukung Profil PT (PDF)</label>
                        <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                            <input type="file" name="cv_perusahaan" accept="application/pdf"
                                   class="block w-full p-2 text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition cursor-pointer">
                            @if($perusahaan->cv_perusahaan)
                                <a href="{{ asset('storage/'.$perusahaan->cv_perusahaan) }}" target="_blank"
                                   class="inline-flex items-center text-xs font-semibold text-blue-600 bg-blue-50 border border-blue-100 px-3 py-2 rounded-xl hover:bg-blue-100 transition whitespace-nowrap">
                                    <i class="fas fa-external-link-alt mr-1.5"></i> Lihat PDF
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- BOTTOM BUTTON BAR -->
                <div class="flex items-center justify-end gap-3 pt-4">
                    <a href="{{ route('admin.perusahaan.show', $perusahaan) }}" 
                       class="px-5 py-2.5 text-sm font-semibold text-gray-500 hover:text-gray-700 transition">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-6 py-2.5 text-sm font-bold bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl shadow-md hover:from-blue-700 hover:to-indigo-700 transition transform active:scale-95 shadow-blue-200">
                        <i class="fas fa-save mr-2"></i> Simpan Perubahan
                    </button>
                </div>

            </div>
        </div>
    </form>
</div>
@endsection