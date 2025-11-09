@extends('layouts.app')

@section('title', 'Edit Profil')
@section('page-title', 'Edit Profil')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

    <!-- Back Button -->
    <a href="{{ route('profile') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 transition-colors duration-200">
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
            <div class="bg-white shadow-md rounded-2xl p-6 border border-gray-100 hover:shadow-lg transition">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Foto & Akun
                </h3>

                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Foto Profil</label>
                        <div class="flex items-center gap-4 flex-wrap">
                            @if(auth()->user()->avatar)
                                <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="Avatar" class="w-20 h-20 rounded-full object-cover border border-gray-200 shadow-sm">
                            @else
                                <div class="w-20 h-20 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 text-3xl font-bold">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                            @endif
                            <div class="flex-1">
                                <input type="file" name="avatar" accept="image/*" class="block w-full text-sm text-gray-700 border border-gray-300 rounded-lg cursor-pointer focus:ring focus:ring-blue-200 focus:outline-none">
                                <p class="text-xs text-gray-500 mt-2">Format JPG/PNG, maksimal 2MB</p>
                            </div>
                        </div>
                        @error('avatar') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap *</label>
                            <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}"
                                class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition">
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                            <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}"
                                class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition">
                            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Pribadi -->
            <div class="bg-white shadow-md rounded-2xl p-6 border border-gray-100 hover:shadow-lg transition">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Data Pribadi
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $siswa->tempat_lahir) }}"
                            class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $siswa->tanggal_lahir?->format('Y-m-d')) }}"
                            class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                        <select name="jenis_kelamin"
                            class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition">
                            <option value="L" @selected(old('jenis_kelamin', $siswa->jenis_kelamin) == 'L')>Laki-laki</option>
                            <option value="P" @selected(old('jenis_kelamin', $siswa->jenis_kelamin) == 'P')>Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Agama</label>
                        <select name="agama"
                            class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition">
                            <option value="">Pilih Agama</option>
                            @foreach(['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu'] as $agama)
                                <option value="{{ $agama }}" @selected(old('agama', $siswa->agama) == $agama)>{{ $agama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                        <textarea name="alamat" rows="3"
                            class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition">{{ old('alamat', $siswa->alamat) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">No. Telepon</label>
                        <input type="text" name="no_telp" value="{{ old('no_telp', $siswa->no_telp) }}"
                            class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition" placeholder="08xxxxxxxxxx">
                    </div>
                </div>
            </div>

            <!-- Tombol -->
            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('profile') }}"
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition">
                    Batal
                </a>
                <button type="submit"
                    class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>

    @endif
</div>
@endsection
