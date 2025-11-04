@extends('layouts.app')

@section('title', 'Edit Profil Perusahaan')
@section('page-title', 'Edit Profil Perusahaan')

@section('content')
<div class="max-w-4xl">
    <div class="card">
        <div class="card-header">
            <h3 class="text-lg font-semibold text-gray-900">Profil Perusahaan</h3>
        </div>

        <form action="{{ route('perusahaan.profile.update') }}" method="POST" enctype="multipart/form-data" class="card-body space-y-6">
            @csrf
            @method('PUT')

            <!-- Logo -->
            <div>
                <label class="form-label">Logo Perusahaan</label>
                <div class="flex items-center space-x-4">
                    @if(auth()->user()->perusahaan->logo)
                    <img src="{{ Storage::url(auth()->user()->perusahaan->logo) }}" alt="Logo" class="w-20 h-20 rounded-lg object-cover">
                    @else
                    <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                        <span class="text-gray-600 text-2xl font-bold">{{ substr(auth()->user()->perusahaan->nama_perusahaan, 0, 1) }}</span>
                    </div>
                    @endif
                    <div class="flex-1">
                        <input type="file" name="logo" accept="image/*" class="form-input">
                        <p class="text-xs text-gray-500 mt-1">Format JPG/PNG, maksimal 2MB</p>
                    </div>
                </div>
                @error('logo')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <!-- Informasi Dasar -->
            <div>
                <h4 class="text-md font-semibold text-gray-900 mb-4">Informasi Dasar</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="form-label">Nama Perusahaan *</label>
                        <input type="text" name="nama_perusahaan" value="{{ old('nama_perusahaan', auth()->user()->perusahaan->nama_perusahaan) }}" class="form-input" required>
                        @error('nama_perusahaan')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="form-label">Bidang Usaha</label>
                        <input type="text" name="bidang_usaha" value="{{ old('bidang_usaha', auth()->user()->perusahaan->bidang_usaha) }}" class="form-input">
                        @error('bidang_usaha')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="form-label">Website</label>
                        <input type="url" name="website" value="{{ old('website', auth()->user()->perusahaan->website) }}" class="form-input" placeholder="https://example.com">
                        @error('website')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="form-label">Deskripsi Perusahaan</label>
                        <textarea name="deskripsi" rows="4" class="form-textarea" placeholder="Jelaskan tentang perusahaan Anda...">{{ old('deskripsi', auth()->user()->perusahaan->deskripsi) }}</textarea>
                        @error('deskripsi')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <hr class="border-gray-200">

            <!-- Alamat -->
            <div>
                <h4 class="text-md font-semibold text-gray-900 mb-4">Alamat</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="form-label">Alamat Lengkap</label>
                        <textarea name="alamat" rows="3" class="form-textarea">{{ old('alamat', auth()->user()->perusahaan->alamat) }}</textarea>
                        @error('alamat')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="form-label">Kota</label>
                        <input type="text" name="kota" value="{{ old('kota', auth()->user()->perusahaan->kota) }}" class="form-input">
                        @error('kota')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="form-label">Provinsi</label>
                        <input type="text" name="provinsi" value="{{ old('provinsi', auth()->user()->perusahaan->provinsi) }}" class="form-input">
                        @error('provinsi')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="form-label">Kode Pos</label>
                        <input type="text" name="kode_pos" value="{{ old('kode_pos', auth()->user()->perusahaan->kode_pos) }}" class="form-input">
                        @error('kode_pos')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="form-label">No. Telepon</label>
                        <input type="text" name="no_telp" value="{{ old('no_telp', auth()->user()->perusahaan->no_telp) }}" class="form-input">
                        @error('no_telp')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <hr class="border-gray-200">

            <!-- PIC (Person in Charge) -->
            <div>
                <h4 class="text-md font-semibold text-gray-900 mb-4">Person in Charge (PIC)</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Nama PIC</label>
                        <input type="text" name="nama_pic" value="{{ old('nama_pic', auth()->user()->perusahaan->nama_pic) }}" class="form-input">
                        @error('nama_pic')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="form-label">Jabatan PIC</label>
                        <input type="text" name="jabatan_pic" value="{{ old('jabatan_pic', auth()->user()->perusahaan->jabatan_pic) }}" class="form-input">
                        @error('jabatan_pic')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="form-label">No. Telepon PIC</label>
                        <input type="text" name="no_telp_pic" value="{{ old('no_telp_pic', auth()->user()->perusahaan->no_telp_pic) }}" class="form-input">
                        @error('no_telp_pic')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="form-label">Email PIC</label>
                        <input type="email" name="email_pic" value="{{ old('email_pic', auth()->user()->perusahaan->email_pic) }}" class="form-input">
                        @error('email_pic')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('dashboard') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
