@extends('layouts.app')

@section('title', 'Edit Perusahaan')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">

    <!-- HEADER -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Edit Perusahaan</h1>
        <nav class="text-sm text-gray-500 mt-1">
            <a href="{{ route('admin.perusahaan.index') }}" class="hover:text-blue-600">
                Perusahaan
            </a>
            <span class="mx-1">/</span>
            <span class="text-gray-700">Edit</span>
        </nav>
    </div>

    <!-- FORM CARD -->
    <div class="bg-white rounded-2xl shadow p-6">

        <h2 class="text-lg font-semibold text-blue-600 mb-6">
            Form Edit Perusahaan
        </h2>

        <form action="{{ route('admin.perusahaan.update', $perusahaan) }}"
              method="POST"
              enctype="multipart/form-data"
              class="space-y-8">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                <!-- KIRI -->
                <div class="space-y-5">
                    <h3 class="font-semibold text-gray-700 border-b pb-2">
                        Informasi Perusahaan
                    </h3>

                    <!-- Nama -->
                    <div>
                        <label class="block text-sm font-medium mb-1">
                            Nama Perusahaan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_perusahaan"
                               value="{{ old('nama_perusahaan', $perusahaan->nama_perusahaan) }}"
                               class="w-full rounded-lg border-gray-300 focus:ring focus:ring-blue-200 @error('nama_perusahaan') border-red-500 @enderror">
                        @error('nama_perusahaan')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium mb-1">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email"
                               value="{{ old('email', $perusahaan->user->email) }}"
                               class="w-full rounded-lg border-gray-300 focus:ring focus:ring-blue-200 @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-sm font-medium mb-1">
                            Password
                        </label>
                        <input type="password" name="password"
                               class="w-full rounded-lg border-gray-300 focus:ring focus:ring-blue-200 @error('password') border-red-500 @enderror">
                        <p class="text-xs text-gray-500 mt-1">
                            Kosongkan jika tidak ingin mengubah password.
                        </p>
                        @error('password')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Bidang Usaha -->
                    <div>
                        <label class="block text-sm font-medium mb-1">
                            Bidang Usaha
                        </label>
                        <input type="text" name="bidang_usaha"
                               value="{{ old('bidang_usaha', $perusahaan->bidang_usaha) }}"
                               class="w-full rounded-lg border-gray-300 focus:ring focus:ring-blue-200">
                    </div>

                    <!-- Logo -->
                    <div>
                        <label class="block text-sm font-medium mb-2">
                            Logo Perusahaan
                        </label>

                        @if($perusahaan->logo)
                            <img src="{{ asset('storage/'.$perusahaan->logo) }}"
                                 class="h-24 rounded-lg mb-3">
                        @endif

                        <input type="file" name="logo" accept="image/*"
                               class="block w-full text-sm text-gray-600
                                      file:mr-4 file:py-2 file:px-4
                                      file:rounded-lg file:border-0
                                      file:bg-blue-50 file:text-blue-700
                                      hover:file:bg-blue-100">
                        <p class="text-xs text-gray-500 mt-1">
                            JPG, PNG, JPEG. Maks 2MB.
                        </p>
                        @error('logo')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Website -->
                    <div>
                        <label class="block text-sm font-medium mb-1">
                            Website
                        </label>
                        <input type="url" name="website"
                               value="{{ old('website', $perusahaan->website) }}"
                               placeholder="https://example.com"
                               class="w-full rounded-lg border-gray-300 focus:ring focus:ring-blue-200">
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label class="block text-sm font-medium mb-1">
                            Deskripsi
                        </label>
                        <textarea name="deskripsi" rows="4"
                                  class="w-full rounded-lg border-gray-300 focus:ring focus:ring-blue-200">{{ old('deskripsi', $perusahaan->deskripsi) }}</textarea>
                    </div>
                </div>

                <!-- KANAN -->
                <div class="space-y-5">
                    <h3 class="font-semibold text-gray-700 border-b pb-2">
                        Kontak & Alamat
                    </h3>

                    <div>
                        <label class="block text-sm font-medium mb-1">No. Telepon</label>
                        <input type="text" name="no_telp"
                               value="{{ old('no_telp', $perusahaan->no_telp) }}"
                               class="w-full rounded-lg border-gray-300 focus:ring focus:ring-blue-200">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Alamat</label>
                        <textarea name="alamat" rows="3"
                                  class="w-full rounded-lg border-gray-300 focus:ring focus:ring-blue-200">{{ old('alamat', $perusahaan->alamat) }}</textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Kota</label>
                            <input type="text" name="kota"
                                   value="{{ old('kota', $perusahaan->kota) }}"
                                   class="w-full rounded-lg border-gray-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Provinsi</label>
                            <input type="text" name="provinsi"
                                   value="{{ old('provinsi', $perusahaan->provinsi) }}"
                                   class="w-full rounded-lg border-gray-300">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Kode Pos</label>
                        <input type="text" name="kode_pos"
                               value="{{ old('kode_pos', $perusahaan->kode_pos) }}"
                               class="w-full rounded-lg border-gray-300">
                    </div>

                    <h3 class="font-semibold text-gray-700 border-b pb-2 mt-6">
                        Person in Charge (PIC)
                    </h3>

                    <div>
                        <label class="block text-sm font-medium mb-1">Nama PIC</label>
                        <input type="text" name="nama_pic"
                               value="{{ old('nama_pic', $perusahaan->nama_pic) }}"
                               class="w-full rounded-lg border-gray-300">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Jabatan PIC</label>
                        <input type="text" name="jabatan_pic"
                               value="{{ old('jabatan_pic', $perusahaan->jabatan_pic) }}"
                               class="w-full rounded-lg border-gray-300">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Email PIC</label>
                        <input type="email" name="email_pic"
                               value="{{ old('email_pic', $perusahaan->email_pic) }}"
                               class="w-full rounded-lg border-gray-300">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">No. Telepon PIC</label>
                        <input type="text" name="no_telp_pic"
                               value="{{ old('no_telp_pic', $perusahaan->no_telp_pic) }}"
                               class="w-full rounded-lg border-gray-300">
                    </div>
                </div>
            </div>

            <!-- ACTION -->
            <div class="flex justify-end gap-3 pt-6 border-t">
                <a href="{{ route('admin.perusahaan.show', $perusahaan) }}"
                   class="px-5 py-2 border rounded-lg hover:bg-gray-100">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    💾 Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
