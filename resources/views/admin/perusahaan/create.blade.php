@extends('layouts.app')

@section('title', 'Tambah Perusahaan')
@section('page-title', 'Tambah Perusahaan Mitra')

@section('content')
<div class="max-w-6xl mx-auto p-4 sm:p-6 lg:p-8">
    <div class="bg-white shadow-2xl rounded-xl overflow-hidden border-t-4 border-blue-600">
        
        {{-- Card Header --}}
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h3 class="text-xl font-bold text-gray-800">Form Data Perusahaan Baru</h3>
            <p class="text-sm text-gray-500">Isi data akun dan informasi detail perusahaan mitra.</p>
        </div>
        
        <form action="{{ route('admin.perusahaan.store') }}" method="POST" enctype="multipart/form-data" class="p-6 lg:p-8 space-y-8">
            @csrf

            <!-- Data Akun -->
            <div class="space-y-4 border p-4 sm:p-6 rounded-lg bg-blue-50">
                <h4 class="text-lg font-bold text-blue-800 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    1. Data Akun Pengguna
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-150 shadow-sm" required>
                        @error('email')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password *</label>
                        <input type="password" id="password" name="password" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-150 shadow-sm" required>
                        @error('password')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Data Perusahaan -->
            <div class="space-y-4 border p-4 sm:p-6 rounded-lg bg-white shadow-inner">
                <h4 class="text-lg font-bold text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    2. Data Perusahaan
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Nama Perusahaan --}}
                    <div class="md:col-span-2">
                        <label for="nama_perusahaan" class="block text-sm font-medium text-gray-700 mb-1">Nama Perusahaan *</label>
                        <input type="text" id="nama_perusahaan" name="nama_perusahaan" value="{{ old('nama_perusahaan') }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm transition duration-150" required>
                        @error('nama_perusahaan')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Bidang Usaha --}}
                    <div>
                        <label for="bidang_usaha" class="block text-sm font-medium text-gray-700 mb-1">Bidang Usaha</label>
                        <input type="text" id="bidang_usaha" name="bidang_usaha" value="{{ old('bidang_usaha') }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm transition duration-150">
                        @error('bidang_usaha')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Website --}}
                    <div>
                        <label for="website" class="block text-sm font-medium text-gray-700 mb-1">Website</label>
                        <input type="url" id="website" name="website" value="{{ old('website') }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm transition duration-150" placeholder="https://example.com">
                        @error('website')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Deskripsi --}}
                    <div class="md:col-span-2">
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Perusahaan</label>
                        <textarea id="deskripsi" name="deskripsi" rows="3" 
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm transition duration-150">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Logo --}}
                    <div class="md:col-span-2">
                        <label for="logo" class="block text-sm font-medium text-gray-700 mb-1">Logo Perusahaan</label>
                        <input type="file" id="logo" name="logo" accept="image/*" 
                               class="w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <p class="text-xs text-gray-500 mt-1">Format JPG/PNG, maksimal 2MB. Disarankan: persegi, resolusi tinggi.</p>
                        @error('logo')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Alamat -->
            <div class="space-y-4 border p-4 sm:p-6 rounded-lg bg-white shadow-inner">
                <h4 class="text-lg font-bold text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    3. Alamat & Kontak Perusahaan
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Alamat Lengkap --}}
                    <div class="md:col-span-2">
                        <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                        <textarea id="alamat" name="alamat" rows="3" 
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm transition duration-150">{{ old('alamat') }}</textarea>
                        @error('alamat')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Kota --}}
                    <div>
                        <label for="kota" class="block text-sm font-medium text-gray-700 mb-1">Kota</label>
                        <input type="text" id="kota" name="kota" value="{{ old('kota') }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm transition duration-150">
                        @error('kota')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Provinsi --}}
                    <div>
                        <label for="provinsi" class="block text-sm font-medium text-gray-700 mb-1">Provinsi</label>
                        <input type="text" id="provinsi" name="provinsi" value="{{ old('provinsi') }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm transition duration-150">
                        @error('provinsi')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Kode Pos --}}
                    <div>
                        <label for="kode_pos" class="block text-sm font-medium text-gray-700 mb-1">Kode Pos</label>
                        <input type="text" id="kode_pos" name="kode_pos" value="{{ old('kode_pos') }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm transition duration-150">
                        @error('kode_pos')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- No. Telepon --}}
                    <div>
                        <label for="no_telp" class="block text-sm font-medium text-gray-700 mb-1">No. Telepon</label>
                        <input type="text" id="no_telp" name="no_telp" value="{{ old('no_telp') }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm transition duration-150">
                        @error('no_telp')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- PIC -->
            <div class="space-y-4 border p-4 sm:p-6 rounded-lg bg-white shadow-inner">
                <h4 class="text-lg font-bold text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    4. Person in Charge (PIC)
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Nama PIC --}}
                    <div>
                        <label for="nama_pic" class="block text-sm font-medium text-gray-700 mb-1">Nama PIC</label>
                        <input type="text" id="nama_pic" name="nama_pic" value="{{ old('nama_pic') }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm transition duration-150">
                        @error('nama_pic')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Jabatan PIC --}}
                    <div>
                        <label for="jabatan_pic" class="block text-sm font-medium text-gray-700 mb-1">Jabatan PIC</label>
                        <input type="text" id="jabatan_pic" name="jabatan_pic" value="{{ old('jabatan_pic') }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm transition duration-150">
                        @error('jabatan_pic')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- No. Telp PIC --}}
                    <div>
                        <label for="no_telp_pic" class="block text-sm font-medium text-gray-700 mb-1">No. Telepon PIC</label>
                        <input type="text" id="no_telp_pic" name="no_telp_pic" value="{{ old('no_telp_pic') }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm transition duration-150">
                        @error('no_telp_pic')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Email PIC --}}
                    <div>
                        <label for="email_pic" class="block text-sm font-medium text-gray-700 mb-1">Email PIC</label>
                        <input type="email" id="email_pic" name="email_pic" value="{{ old('email_pic') }}" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm transition duration-150">
                        @error('email_pic')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.perusahaan.index') }}" 
                   class="inline-flex items-center px-6 py-2 border border-gray-300 text-sm font-medium rounded-lg shadow-sm text-gray-700 bg-white hover:bg-gray-100 transition duration-150">
                    Batal
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-medium rounded-lg shadow-md text-white bg-blue-600 hover:bg-blue-700 transition duration-150 transform hover:scale-[1.01]">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection