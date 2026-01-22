@extends('layouts.app')

@section('title', 'Edit Profil')
@section('page-title', 'Edit Profil')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Back Button -->
    <a href="{{ route('profile') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali ke Profil
    </a>

    @if(auth()->user()->isSiswa())
        @php $siswa = auth()->user()->siswa; @endphp

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')

            <!-- Foto & Akun -->
            <div class="p-8 space-y-8">

                    <!-- Avatar Section -->
                    <div class="pb-8 border-b border-slate-100">
                        <h3 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                            <span class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </span>
                            Foto Profil
                        </h3>

                        <div class="flex flex-col sm:flex-row items-start gap-6">
                            <!-- Avatar Preview -->
                            <div class="relative group mx-auto sm:mx-0">
                                <div class="w-32 h-32 rounded-2xl border-4 border-slate-100 shadow-lg overflow-hidden bg-slate-200 relative">
                                    @if(auth()->user()->avatar)
                                        <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="Avatar" class="w-full h-full object-cover" id="avatarPreview">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-emerald-500 to-teal-600 text-white text-4xl font-bold" id="avatarPreview">
                                            {{ substr(auth()->user()->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center cursor-pointer"
                                         onclick="document.getElementById('avatar').click()">
                                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Upload Instructions -->
                            <div class="flex-1 w-full">
                                <label class="block text-sm font-semibold text-slate-700 mb-3">Upload Foto Baru</label>
                                <div class="flex items-center gap-3">
                                    <label for="avatar" class="cursor-pointer px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-medium transition-all shadow-sm hover:shadow-md flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                        </svg>
                                        Pilih File
                                    </label>
                                    <input type="file" id="avatar" name="avatar" accept="image/*" class="hidden" onchange="previewAvatar(event)">
                                    <span class="text-sm text-slate-500" id="fileName">Belum ada file dipilih</span>
                                </div>
                                <p class="text-xs text-slate-500 mt-3 flex items-start gap-2">
                                    <svg class="w-4 h-4 text-emerald-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span>Format: JPG, PNG. Maksimal 2MB. Rekomendasi ukuran 400x400px</span>
                                </p>
                                @error('avatar')
                                    <p class="text-red-500 text-sm mt-2 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Data Akun -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                            <span class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </span>
                            Data Akun
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nama Lengkap -->
                            <div class="md:col-span-2">
                                <label for="name" class="block text-sm font-semibold text-slate-700 mb-2">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="name" name="name" 
                                       value="{{ old('name', auth()->user()->name) }}"
                                       class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all @error('name') border-red-300 @enderror"
                                       placeholder="Masukkan nama lengkap" required>
                                @error('name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input type="email" id="email" name="email" 
                                       value="{{ old('email', auth()->user()->email) }}"
                                       class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all @error('email') border-red-300 @enderror"
                                       placeholder="email@example.com" required>
                                @error('email')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- NIM -->
                            <div>
                                <label for="nim" class="block text-sm font-semibold text-slate-700 mb-2">
                                    NIM
                                </label>
                                <input type="text" id="nim" name="nim" 
                                       value="{{ old('nim', auth()->user()->siswa->nim ?? '') }}"
                                       class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                       placeholder="Nomor Induk Mahasiswa">
                                @error('nim')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Data Pribadi -->
                    <div class="space-y-6 pt-6 border-t border-slate-100">
                        <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                            <span class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                                </svg>
                            </span>
                            Data Pribadi
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Tempat Lahir -->
                            <div>
                                <label for="tempat_lahir" class="block text-sm font-semibold text-slate-700 mb-2">
                                    Tempat Lahir
                                </label>
                                <input type="text" id="tempat_lahir" name="tempat_lahir" 
                                       value="{{ old('tempat_lahir', auth()->user()->siswa->tempat_lahir ?? '') }}"
                                       class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                       placeholder="Kota kelahiran">
                                @error('tempat_lahir')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tanggal Lahir -->
                            <div>
                                <label for="tanggal_lahir" class="block text-sm font-semibold text-slate-700 mb-2">
                                    Tanggal Lahir
                                </label>
                                <input type="date" id="tanggal_lahir" name="tanggal_lahir" 
                                       value="{{ old('tanggal_lahir', auth()->user()->siswa->tanggal_lahir ?? '') }}"
                                       class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all">
                                @error('tanggal_lahir')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Jenis Kelamin -->
                            <div>
                                <label for="jenis_kelamin" class="block text-sm font-semibold text-slate-700 mb-2">
                                    Jenis Kelamin <span class="text-red-500">*</span>
                                </label>
                                <select id="jenis_kelamin" name="jenis_kelamin" required
                                        class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all appearance-none bg-white">
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="L" {{ old('jenis_kelamin', auth()->user()->siswa->jenis_kelamin ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('jenis_kelamin', auth()->user()->siswa->jenis_kelamin ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('jenis_kelamin')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Agama -->
                            <div>
                                <label for="agama" class="block text-sm font-semibold text-slate-700 mb-2">
                                    Agama
                                </label>
                                <select id="agama" name="agama"
                                        class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all appearance-none bg-white">
                                    <option value="">Pilih Agama</option>
                                    @foreach(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'] as $agama)
                                        <option value="{{ $agama }}" {{ old('agama', auth()->user()->siswa->agama ?? '') == $agama ? 'selected' : '' }}>{{ $agama }}</option>
                                    @endforeach
                                </select>
                                @error('agama')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- No Telepon -->
                            <div>
                                <label for="no_telp" class="block text-sm font-semibold text-slate-700 mb-2">
                                    No. Telepon
                                </label>
                                <input type="text" id="no_telp" name="no_telp" 
                                       value="{{ old('no_telp', auth()->user()->siswa->no_telp ?? '') }}"
                                       class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                       placeholder="08123456789">
                                @error('no_telp')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Alamat -->
                            <div class="md:col-span-2">
                                <label for="alamat" class="block text-sm font-semibold text-slate-700 mb-2">
                                    Alamat Lengkap
                                </label>
                                <textarea id="alamat" name="alamat" rows="3"
                                          class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all resize-none"
                                          placeholder="Jl. Contoh No. 123, RT/RW, Kelurahan, Kecamatan">{{ old('alamat', auth()->user()->siswa->alamat ?? '') }}</textarea>
                                @error('alamat')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    

                    <!-- Data Orang Tua/Wali -->
                    <div class="space-y-6 pt-6 border-t border-slate-100">
                        <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                            <span class="w-8 h-8 bg-rose-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </span>
                            Data Orang Tua / Wali
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nama Orang Tua -->
                            <div>
                                <label for="nama_ortu" class="block text-sm font-semibold text-slate-700 mb-2">
                                    Nama Orang Tua/Wali
                                </label>
                                <input type="text" id="nama_ortu" name="nama_ortu" 
                                       value="{{ old('nama_ortu', auth()->user()->siswa->nama_ortu ?? '') }}"
                                       class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                       placeholder="Nama lengkap orang tua/wali">
                                @error('nama_ortu')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Pekerjaan Orang Tua -->
                            <div>
                                <label for="pekerjaan_ortu" class="block text-sm font-semibold text-slate-700 mb-2">
                                    Pekerjaan
                                </label>
                                <input type="text" id="pekerjaan_ortu" name="pekerjaan_ortu" 
                                       value="{{ old('pekerjaan_ortu', auth()->user()->siswa->pekerjaan_ortu ?? '') }}"
                                       class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                       placeholder="Pekerjaan orang tua/wali">
                                @error('pekerjaan_ortu')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- No Telepon Orang Tua -->
                            <div>
                                <label for="no_telp_ortu" class="block text-sm font-semibold text-slate-700 mb-2">
                                    No. Telepon Orang Tua/Wali
                                </label>
                                <input type="text" id="no_telp_ortu" name="no_telp_ortu" 
                                       value="{{ old('no_telp_ortu', auth()->user()->siswa->no_telp_ortu ?? '') }}"
                                       class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                       placeholder="08123456789">
                                @error('no_telp_ortu')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Password Section -->
                    <div class="space-y-6 pt-6 border-t border-slate-100">
                        <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                            <span class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                                                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 11c1.657 0 3-1.343 3-3S13.657 5 12 5 9 6.343 9 8s1.343 3 3 3z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 21v-2a4 4 0 00-4-4H9a4 4 0 00-4 4v2"/>
                                </svg>
                            </span>
                            Ubah Password
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Password Baru -->
                            <div>
                                <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">
                                    Password Baru
                                </label>
                                <input type="password" id="password" name="password"
                                       class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                       placeholder="Minimal 8 karakter">
                                @error('password')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Konfirmasi Password -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-2">
                                    Konfirmasi Password
                                </label>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                       class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all"
                                       placeholder="Ulangi password">
                            </div>
                        </div>

                        <p class="text-xs text-slate-500 flex items-start gap-2">
                            <svg class="w-4 h-4 text-red-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Kosongkan password jika tidak ingin mengubahnya.
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-8 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <a href="{{ route('profile') }}"
                           class="w-full sm:w-auto px-6 py-3 rounded-xl border border-slate-300 text-slate-700 font-semibold hover:bg-slate-100 transition text-center">
                            Batal
                        </a>

                        <button type="submit"
                                class="w-full sm:w-auto px-8 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold transition shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M5 13l4 4L19 7"/>
                            </svg>
                            Simpan Perubahan
                        </button>
                    </div>

                </div>
            </form>


 

    @elseif(auth()->user()->isPerusahaan())
        <!-- PERUSAHAAN EDIT FORM -->
        @php $perusahaan = auth()->user()->perusahaan; @endphp
        
        <form action="{{ route('perusahaan.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Logo & Akun -->
            <div class="card">
               <div class="p-8 space-y-8">

                    <!-- Avatar Section -->
                    <div class="pb-8 border-b border-slate-100">
                        <h3 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                            <span class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </span>
                            Logo Perusahaan
                        </h3>

                        <div class="flex items-start gap-6">
                            <!-- Avatar Preview -->
                            <div class="relative group">
                                <div class="w-32 h-32 rounded-2xl border-4 border-slate-100 shadow-lg overflow-hidden bg-slate-200 relative">
                                    @if(auth()->user()->avatar)
                                        <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="Avatar" class="w-full h-full object-cover" id="avatarPreview">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-500 to-indigo-600 text-white text-4xl font-bold" id="avatarPreview">
                                            {{ substr(auth()->user()->perusahaan->nama_perusahaan ?? 'P', 0, 1) }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Upload Instructions -->
                            <div class="flex-1">
                                <label class="block text-sm font-semibold text-slate-700 mb-3">Upload Logo Baru</label>
                                <div class="flex items-center gap-3">
                                    <label for="avatar" class="cursor-pointer px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-all shadow-sm hover:shadow-md flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                        </svg>
                                        Pilih File
                                    </label>
                                    <input type="file" id="avatar" name="avatar" accept="image/*" class="hidden" onchange="previewAvatar(event)">
                                    <span class="text-sm text-slate-500" id="fileName">Belum ada file dipilih</span>
                                </div>
                                <p class="text-xs text-slate-500 mt-3 flex items-start gap-2">
                                    <svg class="w-4 h-4 text-blue-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span>Format: JPG, PNG. Maksimal 2MB. Rekomendasi ukuran 400x400px</span>
                                </p>
                                @error('avatar')
                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Perusahaan -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                            <span class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </span>
                            Informasi Perusahaan
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nama Perusahaan -->
                            <div class="md:col-span-2">
                                <label for="nama_perusahaan" class="block text-sm font-semibold text-slate-700 mb-2">
                                    Nama Perusahaan <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="nama_perusahaan" name="nama_perusahaan" 
                                       value="{{ old('nama_perusahaan', auth()->user()->perusahaan->nama_perusahaan) }}"
                                       class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('nama_perusahaan') border-red-300 @enderror"
                                       placeholder="PT. Nama Perusahaan" required>
                                @error('nama_perusahaan')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Bidang Usaha -->
                            <div>
                                <label for="bidang_usaha" class="block text-sm font-semibold text-slate-700 mb-2">
                                    Bidang Usaha
                                </label>
                                <input type="text" id="bidang_usaha" name="bidang_usaha" 
                                       value="{{ old('bidang_usaha', auth()->user()->perusahaan->bidang_usaha) }}"
                                       class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                       placeholder="Teknologi Informasi">
                                @error('bidang_usaha')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Website -->
                            <div>
                                <label for="website" class="block text-sm font-semibold text-slate-700 mb-2">
                                    Website
                                </label>
                                <input type="url" id="website" name="website" 
                                       value="{{ old('website', auth()->user()->perusahaan->website) }}"
                                       class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                       placeholder="https://www.perusahaan.com">
                                @error('website')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Deskripsi -->
                            <div class="md:col-span-2">
                                <label for="deskripsi" class="block text-sm font-semibold text-slate-700 mb-2">
                                    Deskripsi Perusahaan
                                </label>
                                <textarea id="deskripsi" name="deskripsi" rows="4"
                                          class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all resize-none"
                                          placeholder="Ceritakan tentang perusahaan Anda...">{{ old('deskripsi', auth()->user()->perusahaan->deskripsi) }}</textarea>
                                @error('deskripsi')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Alamat & Kontak -->
                    <div class="space-y-6 pt-6 border-t border-slate-100">
                        <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                            <span class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </span>
                            Alamat & Kontak
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Alamat -->
                            <div class="md:col-span-2">
                                <label for="alamat" class="block text-sm font-semibold text-slate-700 mb-2">
                                    Alamat Lengkap
                                </label>
                                <textarea id="alamat" name="alamat" rows="3"
                                          class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all resize-none"
                                          placeholder="Jl. Contoh No. 123">{{ old('alamat', auth()->user()->perusahaan->alamat) }}</textarea>
                                @error('alamat')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Kota -->
                            <div>
                                <label for="kota" class="block text-sm font-semibold text-slate-700 mb-2">
                                    Kota
                                </label>
                                <input type="text" id="kota" name="kota" 
                                       value="{{ old('kota', auth()->user()->perusahaan->kota) }}"
                                       class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                       placeholder="Jakarta">
                                @error('kota')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Provinsi -->
                            <div>
                                <label for="provinsi" class="block text-sm font-semibold text-slate-700 mb-2">
                                    Provinsi
                                </label>
                                <input type="text" id="provinsi" name="provinsi" 
                                       value="{{ old('provinsi', auth()->user()->perusahaan->provinsi) }}"
                                       class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                       placeholder="DKI Jakarta">
                                @error('provinsi')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Kode Pos -->
                            <div>
                                <label for="kode_pos" class="block text-sm font-semibold text-slate-700 mb-2">
                                    Kode Pos
                                </label>
                                <input type="text" id="kode_pos" name="kode_pos" 
                                       value="{{ old('kode_pos', auth()->user()->perusahaan->kode_pos) }}"
                                       class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                       placeholder="12345">
                                @error('kode_pos')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- No Telepon -->
                            <div>
                                <label for="no_telp" class="block text-sm font-semibold text-slate-700 mb-2">
                                    No. Telepon Perusahaan
                                </label>
                                <input type="text" id="no_telp" name="no_telp" 
                                       value="{{ old('no_telp', auth()->user()->perusahaan->no_telp) }}"
                                       class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                       placeholder="021-1234567">
                                @error('no_telp')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Person in Charge (PIC) -->
                    <div class="space-y-6 pt-6 border-t border-slate-100">
                        <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                            <span class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </span>
                            Contact Person (PIC)
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nama PIC -->
                            <div>
                                <label for="nama_pic" class="block text-sm font-semibold text-slate-700 mb-2">
                                    Nama PIC
                                </label>
                                <input type="text" id="nama_pic" name="nama_pic" 
                                       value="{{ old('nama_pic', auth()->user()->perusahaan->nama_pic) }}"
                                       class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                       placeholder="John Doe">
                                @error('nama_pic')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Jabatan PIC -->
                            <div>
                                <label for="jabatan_pic" class="block text-sm font-semibold text-slate-700 mb-2">
                                    Jabatan PIC
                                </label>
                                <input type="text" id="jabatan_pic" name="jabatan_pic" 
                                       value="{{ old('jabatan_pic', auth()->user()->perusahaan->jabatan_pic) }}"
                                       class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                       placeholder="HR Manager">
                                @error('jabatan_pic')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- No Telp PIC -->
                            <div>
                                <label for="no_telp_pic" class="block text-sm font-semibold text-slate-700 mb-2">
                                    No. Telepon PIC
                                </label>
                                <input type="text" id="no_telp_pic" name="no_telp_pic" 
                                       value="{{ old('no_telp_pic', auth()->user()->perusahaan->no_telp_pic) }}"
                                       class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                       placeholder="08123456789">
                                @error('no_telp_pic')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email PIC -->
                            <div>
                                <label for="email_pic" class="block text-sm font-semibold text-slate-700 mb-2">
                                    Email PIC
                                </label>
                                <input type="email" id="email_pic" name="email_pic" 
                                       value="{{ old('email_pic', auth()->user()->perusahaan->email_pic) }}"
                                       class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                       placeholder="pic@perusahaan.com">
                                @error('email_pic')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Password Section -->
                    <div class="space-y-6 pt-6 border-t border-slate-100">
                        <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                            <span class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </span>
                            Ubah Password
                        </h3>

                        <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-4">
                            <div class="flex gap-3">
                                <svg class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-sm text-blue-700">Kosongkan jika tidak ingin mengubah password</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Password Baru -->
                            <div>
                                <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">
                                    Password Baru
                                </label>
                                <input type="password" id="password" name="password" 
                                       class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('password') border-red-300 @enderror"
                                       placeholder="Minimal 6 karakter">
                                @error('password')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Konfirmasi Password -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-2">
                                    Konfirmasi Password
                                </label>
                                <input type="password" id="password_confirmation" name="password_confirmation" 
                                       class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                       placeholder="Ulangi password baru">
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Action Buttons -->
                <div class="px-8 py-6 bg-slate-50 border-t border-slate-100 flex flex-col sm:flex-row gap-3 justify-end">
                    <a href="{{ route('profile') }}" 
                       class="px-6 py-3 bg-white border border-slate-200 text-slate-700 rounded-xl font-medium hover:bg-slate-50 transition-all text-center">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-xl font-semibold transition-all shadow-lg shadow-blue-500/30 hover:shadow-xl hover:shadow-blue-500/40 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>
        </form>

    @else
        <!-- ADMIN EDIT FORM -->
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Avatar & Akun -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-gray-900">Foto & Akun</h3>
                </div>
                <div class="card-body space-y-4">
                    <div>
                        <label class="form-label">Foto Profil</label>
                        <div class="flex items-center space-x-4">
                            @if(auth()->user()->avatar)
                            <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="Avatar" class="w-20 h-20 rounded-full object-cover">
                            @else
                            <div class="w-20 h-20 rounded-full bg-blue-100 flex items-center justify-center">
                                <span class="text-blue-600 text-3xl font-bold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                            </div>
                            @endif
                            <div class="flex-1">
                                <input type="file" name="avatar" accept="image/*" class="form-input">
                                <p class="text-xs text-gray-500 mt-1">Format JPG/PNG, maksimal 2MB</p>
                            </div>
                        </div>
                        @error('avatar')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Nama Lengkap *</label>
                            <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" class="form-input" required>
                            @error('name')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="form-input" required>
                            @error('email')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ubah Password -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-gray-900">Keamanan</h3>
                </div>
                <div class="card-body">
                    <p class="text-sm text-gray-600 mb-4">Kosongkan jika tidak ingin mengubah password</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Password Baru</label>
                            <input type="password" name="password" class="form-input" placeholder="Minimal 6 karakter">
                            @error('password')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="form-label">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="form-input" placeholder="Ulangi password baru">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('profile') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    @endif

    <!-- Security Notice -->
    <div class="card bg-blue-50 border-blue-200">
        <div class="card-body">
            <div class="flex items-start space-x-3">
                <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <h4 class="font-semibold text-blue-900 mb-1">Tips Keamanan</h4>
                    <ul class="text-sm text-blue-800 space-y-1">
                        <li>• Gunakan password yang kuat minimal 6 karakter</li>
                        <li>• Jangan bagikan password Anda kepada siapapun</li>
                        <li>• Perbarui informasi profil Anda secara berkala</li>
                        <li>• Logout setelah selesai menggunakan sistem</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
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
</script>
@endsection