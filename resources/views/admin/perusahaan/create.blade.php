@extends('layouts.app')

@section('title', 'Tambah Perusahaan')
@section('page-title', 'Tambah Perusahaan Mitra')

@section('content')
<div class="max-w-4xl">
    <div class="card">
        <div class="card-header">
            <h3 class="text-lg font-semibold text-gray-900">Form Data Perusahaan</h3>
        </div>
        
        <form action="{{ route('admin.perusahaan.store') }}" method="POST" enctype="multipart/form-data" class="card-body space-y-6">
            @csrf

            <!-- Data Akun -->
            <div>
                <h4 class="text-md font-semibold text-gray-900 mb-4">Data Akun</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-input" required>
                        @error('email')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="password" class="form-label">Password *</label>
                        <input type="password" id="password" name="password" class="form-input" required>
                        @error('password')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <hr class="border-gray-200">

            <!-- Data Perusahaan -->
            <div>
                <h4 class="text-md font-semibold text-gray-900 mb-4">Data Perusahaan</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label for="nama_perusahaan" class="form-label">Nama Perusahaan *</label>
                        <input type="text" id="nama_perusahaan" name="nama_perusahaan" value="{{ old('nama_perusahaan') }}" class="form-input" required>
                        @error('nama_perusahaan')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="bidang_usaha" class="form-label">Bidang Usaha</label>
                        <input type="text" id="bidang_usaha" name="bidang_usaha" value="{{ old('bidang_usaha') }}" class="form-input">
                        @error('bidang_usaha')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="website" class="form-label">Website</label>
                        <input type="url" id="website" name="website" value="{{ old('website') }}" class="form-input" placeholder="https://example.com">
                        @error('website')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="deskripsi" class="form-label">Deskripsi Perusahaan</label>
                        <textarea id="deskripsi" name="deskripsi" rows="3" class="form-textarea">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="logo" class="form-label">Logo Perusahaan</label>
                        <input type="file" id="logo" name="logo" accept="image/*" class="form-input">
                        <p class="text-xs text-gray-500 mt-1">Format JPG/PNG, maksimal 2MB</p>
                        @error('logo')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <hr class="border-gray-200">

            <!-- Alamat -->
            <div>
                <h4 class="text-md font-semibold text-gray-900 mb-4">Alamat</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label for="alamat" class="form-label">Alamat Lengkap</label>
                        <textarea id="alamat" name="alamat" rows="3" class="form-textarea">{{ old('alamat') }}</textarea>
                        @error('alamat')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="kota" class="form-label">Kota</label>
                        <input type="text" id="kota" name="kota" value="{{ old('kota') }}" class="form-input">
                        @error('kota')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="provinsi" class="form-label">Provinsi</label>
                        <input type="text" id="provinsi" name="provinsi" value="{{ old('provinsi') }}" class="form-input">
                        @error('provinsi')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="kode_pos" class="form-label">Kode Pos</label>
                        <input type="text" id="kode_pos" name="kode_pos" value="{{ old('kode_pos') }}" class="form-input">
                        @error('kode_pos')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="no_telp" class="form-label">No. Telepon</label>
                        <input type="text" id="no_telp" name="no_telp" value="{{ old('no_telp') }}" class="form-input">
                        @error('no_telp')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <hr class="border-gray-200">

            <!-- PIC -->
            <div>
                <h4 class="text-md font-semibold text-gray-900 mb-4">Person in Charge (PIC)</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="nama_pic" class="form-label">Nama PIC</label>
                        <input type="text" id="nama_pic" name="nama_pic" value="{{ old('nama_pic') }}" class="form-input">
                        @error('nama_pic')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="jabatan_pic" class="form-label">Jabatan PIC</label>
                        <input type="text" id="jabatan_pic" name="jabatan_pic" value="{{ old('jabatan_pic') }}" class="form-input">
                        @error('jabatan_pic')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="no_telp_pic" class="form-label">No. Telepon PIC</label>
                        <input type="text" id="no_telp_pic" name="no_telp_pic" value="{{ old('no_telp_pic') }}" class="form-input">
                        @error('no_telp_pic')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="email_pic" class="form-label">Email PIC</label>
                        <input type="email" id="email_pic" name="email_pic" value="{{ old('email_pic') }}" class="form-input">
                        @error('email_pic')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.perusahaan.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Data</button>
            </div>
        </form>
    </div>
</div>
@endsection