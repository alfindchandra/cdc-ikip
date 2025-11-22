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

 

    @elseif(auth()->user()->isPerusahaan())
        <!-- PERUSAHAAN EDIT FORM -->
        @php $perusahaan = auth()->user()->perusahaan; @endphp
        
        <form action="{{ route('perusahaan.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Logo & Akun -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-gray-900">Logo & Akun</h3>
                </div>
                <div class="card-body space-y-4">
                    <div>
                        <label class="form-label">Logo Perusahaan</label>
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
                        @error('logo')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="form-label">Nama Perusahaan *</label>
                            <input type="text" name="nama_perusahaan" value="{{ old('nama_perusahaan', $perusahaan->nama_perusahaan) }}" class="form-input" required>
                            @error('nama_perusahaan')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="form-label">Email *</label>
                            <input type="email" value="{{ auth()->user()->email }}" class="form-input bg-gray-100" readonly>
                            <p class="text-xs text-gray-500 mt-1">Email tidak dapat diubah</p>
                        </div>
                        <div>
                            <label class="form-label">Bidang Usaha</label>
                            <input type="text" name="bidang_usaha" value="{{ old('bidang_usaha', $perusahaan->bidang_usaha) }}" class="form-input">
                            @error('bidang_usaha')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label class="form-label">Website</label>
                        <input type="url" name="website" value="{{ old('website', $perusahaan->website) }}" class="form-input" placeholder="https://example.com">
                        @error('website')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="form-label">Deskripsi Perusahaan</label>
                        <textarea name="deskripsi" rows="4" class="form-textarea" placeholder="Jelaskan tentang perusahaan Anda...">{{ old('deskripsi', $perusahaan->deskripsi) }}</textarea>
                        @error('deskripsi')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Alamat -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-gray-900">Alamat</h3>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="form-label">Alamat Lengkap</label>
                            <textarea name="alamat" rows="3" class="form-textarea">{{ old('alamat', $perusahaan->alamat) }}</textarea>
                            @error('alamat')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="form-label">Kota</label>
                            <input type="text" name="kota" value="{{ old('kota', $perusahaan->kota) }}" class="form-input">
                            @error('kota')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="form-label">Provinsi</label>
                            <input type="text" name="provinsi" value="{{ old('provinsi', $perusahaan->provinsi) }}" class="form-input">
                            @error('provinsi')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="form-label">Kode Pos</label>
                            <input type="text" name="kode_pos" value="{{ old('kode_pos', $perusahaan->kode_pos) }}" class="form-input">
                            @error('kode_pos')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="form-label">No. Telepon</label>
                            <input type="text" name="no_telp" value="{{ old('no_telp', $perusahaan->no_telp) }}" class="form-input">
                            @error('no_telp')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- PIC -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-gray-900">Person in Charge (PIC)</h3>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Nama PIC</label>
                            <input type="text" name="nama_pic" value="{{ old('nama_pic', $perusahaan->nama_pic) }}" class="form-input">
                            @error('nama_pic')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="form-label">Jabatan PIC</label>
                            <input type="text" name="jabatan_pic" value="{{ old('jabatan_pic', $perusahaan->jabatan_pic) }}" class="form-input">
                            @error('jabatan_pic')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="form-label">No. Telepon PIC</label>
                            <input type="text" name="no_telp_pic" value="{{ old('no_telp_pic', $perusahaan->no_telp_pic) }}" class="form-input">
                            @error('no_telp_pic')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="form-label">Email PIC</label>
                            <input type="email" name="email_pic" value="{{ old('email_pic', $perusahaan->email_pic) }}" class="form-input">
                            @error('email_pic')<p class="form-error">{{ $message }}</p>@enderror
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
@endsection