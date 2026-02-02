@extends('layouts.app')

@section('title', 'Edit Data Mahamahasiswa')
@section('page-title', 'Edit Data Mahamahasiswa')

@section('content')
<div class="py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white shadow-xl rounded-lg overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200">
                <h3 class="text-xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-user-edit mr-3 text-indigo-600"></i>Form Edit Data Mahamahasiswa
                </h3>
            </div>
            
            <form action="{{ route('admin.mahasiswa.update', $mahasiswa->id) }}" method="POST" class="p-6 space-y-8">
                @csrf
                @method('PUT')

                {{-- Bagian I: Data Akun --}}
                <div class="space-y-6">
                    <h4 class="text-lg font-semibold text-indigo-600 border-b pb-2">Data Akun</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name', $mahasiswa->user->name ?? '') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2" required>
                            @error('name')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                            <input type="email" id="email" name="email" value="{{ old('email', $mahasiswa->user->email ?? '') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2" required>
                            @error('email')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                            <input type="password" id="password" name="password" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2" placeholder="Kosongkan jika tidak diubah">
                            <p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak ingin mengubah password.</p>
                            @error('password')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <hr class="border-gray-200">

                {{-- Bagian II: Data Pribadi --}}
                <div class="space-y-6">
                    <h4 class="text-lg font-semibold text-indigo-600 border-b pb-2">Data Pribadi</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nim" class="block text-sm font-medium text-gray-700 mb-1">NIM <span class="text-red-500">*</span></label>
                            <input type="text" id="nim" name="nim" value="{{ old('nim', $mahasiswa->nim ?? '') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2" required>
                            @error('nim')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        
                        <div>
                            <label for="tempat_lahir" class="block text-sm font-medium text-gray-700 mb-1">Tempat Lahir</label>
                            <input type="text" id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir', $mahasiswa->tempat_lahir ?? '') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2">
                            @error('tempat_lahir')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                            <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir', $mahasiswa->tanggal_lahir?->format('Y-m-d')) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2">
                            @error('tanggal_lahir')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin <span class="text-red-500">*</span></label>
                            <select id="jenis_kelamin" name="jenis_kelamin" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2" required>
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="L" {{ old('jenis_kelamin', $mahasiswa->jenis_kelamin ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin', $mahasiswa->jenis_kelamin ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="agama" class="block text-sm font-medium text-gray-700 mb-1">Agama</label>
                            <select id="agama" name="agama" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2">
                                <option value="">Pilih Agama</option>
                                <option value="Islam" {{ old('agama', $mahasiswa->agama ?? '') == 'Islam' ? 'selected' : '' }}>Islam</option>
                                <option value="Kristen" {{ old('agama', $mahasiswa->agama ?? '') == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                <option value="Katolik" {{ old('agama', $mahasiswa->agama ?? '') == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                                <option value="Hindu" {{ old('agama', $mahasiswa->agama ?? '') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                <option value="Buddha" {{ old('agama', $mahasiswa->agama ?? '') == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                                <option value="Konghucu" {{ old('agama', $mahasiswa->agama ?? '') == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                            </select>
                            @error('agama')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="no_telp" class="block text-sm font-medium text-gray-700 mb-1">No. Telepon</label>
                            <input type="text" id="no_telp" name="no_telp" value="{{ old('no_telp', $mahasiswa->no_telp ?? '') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2">
                            @error('no_telp')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="md:col-span-2">
                            <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                            <textarea id="alamat" name="alamat" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2">{{ old('alamat', $mahasiswa->alamat ?? '') }}</textarea>
                            @error('alamat')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <hr class="border-gray-200">

                {{-- Bagian III: Data Akademik --}}
                <div class="space-y-6">
                    <h4 class="text-lg font-semibold text-indigo-600 border-b pb-2">Data Akademik</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="fakultas" class="block text-sm font-medium text-gray-700 mb-1">Fakultas</label>
                            <select id="fakultas" name="fakultas_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2">
                                <option value="">Pilih Fakultas</option>
                                @foreach ($fakultas as $f)
                                    <option value="{{ $f->id }}" {{ old('fakultas_id', $mahasiswa->fakultas_id ?? '') == $f->id ? 'selected' : '' }}>{{ $f->nama }}</option>
                                @endforeach
                            </select>
                            @error('fakultas_id')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="program_studi" class="block text-sm font-medium text-gray-700 mb-1">Program Studi</label>
                            <select id="program_studi" name="program_studi_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2">
                                <option value="">Pilih Program Studi</option>
                                @foreach ($program_studi as $ps)
                                    <option value="{{ $ps->id }}" {{ old('program_studi_id', $mahasiswa->program_studi_id ?? '') == $ps->id ? 'selected' : '' }}>{{ $ps->nama }}</option>
                                @endforeach
                            </select>
                            @error('program_studi_id')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="tahun_masuk" class="block text-sm font-medium text-gray-700 mb-1">Tahun Masuk</label>
                            <input type="number" id="tahun_masuk" name="tahun_masuk" value="{{ old('tahun_masuk', $mahasiswa->tahun_masuk ?? '') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2" min="2000" max="{{ date('Y') }}">
                            @error('tahun_masuk')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <hr class="border-gray-200">

                {{-- Bagian IV: Data Orang Tua & Status --}}
                <div class="space-y-6">
                    <h4 class="text-lg font-semibold text-indigo-600 border-b pb-2">Data Orang Tua/Wali & Status</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nama_ortu" class="block text-sm font-medium text-gray-700 mb-1">Nama Orang Tua/Wali</label>
                            <input type="text" id="nama_ortu" name="nama_ortu" value="{{ old('nama_ortu', $mahasiswa->nama_ortu ?? '') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2">
                            @error('nama_ortu')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="pekerjaan_ortu" class="block text-sm font-medium text-gray-700 mb-1">Pekerjaan Orang Tua</label>
                            <input type="text" id="pekerjaan_ortu" name="pekerjaan_ortu" value="{{ old('pekerjaan_ortu', $mahasiswa->pekerjaan_ortu ?? '') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2">
                            @error('pekerjaan_ortu')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="no_telp_ortu" class="block text-sm font-medium text-gray-700 mb-1">No. Telepon Orang Tua</label>
                            <input type="text" id="no_telp_ortu" name="no_telp_ortu" value="{{ old('no_telp_ortu', $mahasiswa->no_telp_ortu ?? '') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2">
                            @error('no_telp_ortu')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                            <select id="status" name="status" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2" required>
                                <option value="aktif" {{ old('status', $mahasiswa->status ?? '') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="lulus" {{ old('status', $mahasiswa->status ?? '') == 'lulus' ? 'selected' : '' }}>Lulus</option>
                                <option value="pindah" {{ old('status', $mahasiswa->status ?? '') == 'pindah' ? 'selected' : '' }}>Pindah</option>
                                <option value="keluar" {{ old('status', $mahasiswa->status ?? '') == 'keluar' ? 'selected' : '' }}>Keluar</option>
                            </select>
                            @error('status')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        
                        {{-- Tambahkan field tahun lulus jika statusnya LULUS --}}
                        @if (old('status', $mahasiswa->status ?? '') == 'lulus' || !old('status'))
                        <div id="tahun_lulus_field" class="transition-all duration-300 ease-out {{ (old('status', $mahasiswa->status ?? '') == 'lulus') ? 'md:col-span-2' : 'hidden md:col-span-2' }}">
                            <label for="tahun_lulus" class="block text-sm font-medium text-gray-700 mb-1">Tahun Lulus</label>
                            <input type="number" id="tahun_lulus" name="tahun_lulus" value="{{ old('tahun_lulus', $mahasiswa->tahun_lulus ?? '') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2" min="2000" max="{{ date('Y') }}" placeholder="Tahun kelulusan">
                            @error('tahun_lulus')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        @endif
                    </div>
                </div>
                
                <hr class="border-gray-200">

                <div class="flex justify-end space-x-3 pt-4">
                    <a href="{{ route('admin.mahasiswa.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                        <i class="fas fa-times-circle mr-2"></i>Batal
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                        <i class="fas fa-save mr-2"></i>Update Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Script untuk menampilkan/menyembunyikan field Tahun Lulus --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const statusSelect = document.getElementById('status');
        const tahunLulusField = document.getElementById('tahun_lulus_field');
        const tahunLulusInput = document.getElementById('tahun_lulus');

        function toggleTahunLulus() {
            if (statusSelect.value === 'lulus') {
                tahunLulusField.classList.remove('hidden');
                tahunLulusInput.setAttribute('required', 'required');
            } else {
                tahunLulusField.classList.add('hidden');
                tahunLulusInput.removeAttribute('required');
            }
        }

        // Jalankan saat halaman dimuat
        toggleTahunLulus();

        // Jalankan saat status berubah
        statusSelect.addEventListener('change', toggleTahunLulus);
    });
</script>
@endpush
@endsection