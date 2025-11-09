@extends('layouts.app')

@section('title', 'Edit Data Siswa')
@section('page-title', 'Edit Data Siswa')

@section('content')
<div class="max-w-4xl">
    <div class="card">
        <div class="card-header">
            <h3 class="text-lg font-semibold text-gray-900">Form Edit Data Siswa</h3>
        </div>
        
        <form action="{{ route('admin.siswa.update', $siswa->id) }}" method="POST" class="card-body space-y-6">
            @csrf
            @method('PUT')

            <!-- Data Akun -->
            <div>
                <h4 class="text-md font-semibold text-gray-900 mb-4">Data Akun</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label for="name" class="form-label">Nama Lengkap *</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $siswa->user->name) }}" class="form-input" required>
                        @error('name')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $siswa->user->email) }}" class="form-input" required>
                        @error('email')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="password" class="form-label">Password Baru</label>
                        <input type="password" id="password" name="password" class="form-input" placeholder="Kosongkan jika tidak diubah">
                        <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah password</p>
                        @error('password')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <hr class="border-gray-200">

            <!-- Data Pribadi -->
            <div>
                <h4 class="text-md font-semibold text-gray-900 mb-4">Data Pribadi</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="nim" class="form-label">NIM *</label>
                        <input type="text" id="nim" name="nim" value="{{ old('nim', $siswa->nim) }}" class="form-input" required>
                        @error('nim')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                   
                    <div>
                        <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                        <input type="text" id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir', $siswa->tempat_lahir) }}" class="form-input">
                    </div>
                    <div>
                        <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                        <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir', $siswa->tanggal_lahir?->format('Y-m-d')) }}" class="form-input">
                    </div>
                    <div>
                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin *</label>
                        <select id="jenis_kelamin" name="jenis_kelamin" class="form-select" required>
                            <option value="">Pilih</option>
                            <option value="L" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="agama" class="form-label">Agama</label>
                        <select id="agama" name="agama" class="form-select">
                            <option value="">Pilih</option>
                            <option value="Islam" {{ old('agama', $siswa->agama) == 'Islam' ? 'selected' : '' }}>Islam</option>
                            <option value="Kristen" {{ old('agama', $siswa->agama) == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                            <option value="Katolik" {{ old('agama', $siswa->agama) == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                            <option value="Hindu" {{ old('agama', $siswa->agama) == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                            <option value="Buddha" {{ old('agama', $siswa->agama) == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                            <option value="Konghucu" {{ old('agama', $siswa->agama) == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea id="alamat" name="alamat" rows="3" class="form-textarea">{{ old('alamat', $siswa->alamat) }}</textarea>
                    </div>
                    <div>
                        <label for="no_telp" class="form-label">No. Telepon</label>
                        <input type="text" id="no_telp" name="no_telp" value="{{ old('no_telp', $siswa->no_telp) }}" class="form-input">
                    </div>
                </div>
            </div>

            <hr class="border-gray-200">

            <!-- Data Akademik -->
            <div>
                <h4 class="text-md font-semibold text-gray-900 mb-4">Data Akademik</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="fakultas" class="form-label">fakultas</label>
                        <select id="fakultas" name="fakultas" class="form-select">
                            <option value="">Pilih</option>
                            <option value="X" {{ old('fakultas', $siswa->fakultas) == 'X' ? 'selected' : '' }}>X</option>
                            <option value="XI" {{ old('fakultas', $siswa->fakultas) == 'XI' ? 'selected' : '' }}>XI</option>
                            <option value="XII" {{ old('fakultas', $siswa->fakultas) == 'XII' ? 'selected' : '' }}>XII</option>
                        </select>
                    </div>
                    <div>
                        <label for="program_studi" class="form-label">program_studi</label>
                        <select id="program_studi" name="program_studi" class="form-select">
                            <option value="">Pilih</option>
                            <option value="Rekayasa Perangkat Lunak" {{ old('program_studi', $siswa->program_studi) == 'Rekayasa Perangkat Lunak' ? 'selected' : '' }}>RPL</option>
                            <option value="Teknik Komputer dan Jaringan" {{ old('program_studi', $siswa->program_studi) == 'Teknik Komputer dan Jaringan' ? 'selected' : '' }}>TKJ</option>
                            <option value="Multimedia" {{ old('program_studi', $siswa->program_studi) == 'Multimedia' ? 'selected' : '' }}>Multimedia</option>
                        </select>
                    </div>
                    <div>
                        <label for="tahun_masuk" class="form-label">Tahun Masuk</label>
                        <input type="number" id="tahun_masuk" name="tahun_masuk" value="{{ old('tahun_masuk', $siswa->tahun_masuk) }}" class="form-input" min="2000" max="{{ date('Y') }}">
                    </div>
                </div>
            </div>

            <hr class="border-gray-200">

            <!-- Data Orang Tua -->
            <div>
                <h4 class="text-md font-semibold text-gray-900 mb-4">Data Orang Tua/Wali</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="nama_ortu" class="form-label">Nama Orang Tua/Wali</label>
                        <input type="text" id="nama_ortu" name="nama_ortu" value="{{ old('nama_ortu', $siswa->nama_ortu) }}" class="form-input">
                    </div>
                    <div>
                        <label for="pekerjaan_ortu" class="form-label">Pekerjaan</label>
                        <input type="text" id="pekerjaan_ortu" name="pekerjaan_ortu" value="{{ old('pekerjaan_ortu', $siswa->pekerjaan_ortu) }}" class="form-input">
                    </div>
                    <div>
                        <label for="no_telp_ortu" class="form-label">No. Telepon</label>
                        <input type="text" id="no_telp_ortu" name="no_telp_ortu" value="{{ old('no_telp_ortu', $siswa->no_telp_ortu) }}" class="form-input">
                    </div>
                    <div>
                        <label for="status" class="form-label">Status *</label>
                        <select id="status" name="status" class="form-select" required>
                            <option value="aktif" {{ old('status', $siswa->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="lulus" {{ old('status', $siswa->status) == 'lulus' ? 'selected' : '' }}>Lulus</option>
                            <option value="pindah" {{ old('status', $siswa->status) == 'pindah' ? 'selected' : '' }}>Pindah</option>
                            <option value="keluar" {{ old('status', $siswa->status) == 'keluar' ? 'selected' : '' }}>Keluar</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.siswa.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">Update Data</button>
            </div>
        </form>
    </div>
</div>
@endsection
