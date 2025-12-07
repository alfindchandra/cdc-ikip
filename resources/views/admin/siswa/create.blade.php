@extends('layouts.app')

@section('title', 'Tambah Mahasiswa')
@section('page-title', 'Tambah Data Mahasiswa')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Header Page -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Tambah Mahasiswa Baru</h2>
            <p class="text-sm text-gray-500 mt-1">Silakan lengkapi formulir di bawah ini untuk mendaftarkan Mahasiswa.</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
        <!-- Card Header -->
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex items-center">
            <div class="bg-blue-100 p-2 rounded-lg mr-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-800">Formulir Pendaftaran</h3>
        </div>
        
        <form action="{{ route('admin.siswa.store') }}" method="POST" class="p-6 sm:p-8 space-y-8">
            @csrf

            <!-- ========================
                 SECTION 1: DATA AKUN
            ========================== -->
            <div>
                <div class="flex items-center mb-4 pb-2 border-b border-gray-100">
                    <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11.536 9.464a1.5 1.5 0 00-2.122 0l-.354.353a1.5 1.5 0 000 2.122l1.5 1.5a1.5 1.5 0 002.122 0L15.414 11.243a6 6 0 010 8.486M4 20h4.242m-1.707-2.293a1 1 0 00-1.414 1.414L7.344 21.344A9 9 0 0014 5.558M14 5.558a9 9 0 01-8.355 13.766"></path></svg>
                    <h4 class="text-lg font-medium text-gray-700">Informasi Akun</h4>
                </div>

                <div class="grid md:grid-cols-2 gap-5">
                        <!-- Nama Lengkap -->
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap *</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                   placeholder="Masukkan nama lengkap" required>
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email *</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                   placeholder="nama@example.com" required>
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password *</label>
                            <input type="password" id="password" name="password" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                   placeholder="Minimal 6 karakter" required>
                        </div>

                        <!-- Konfirmasi Password -->
                        <div class="md:col-span-2">
                            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi Password *</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                   placeholder="Ketik ulang password" required>
                        </div>
                    </div>
            </div>

            <!-- ========================
                 SECTION 2: DATA PRIBADI
            ========================== -->
            <div>
                <div class="flex items-center mb-4 pb-2 border-b border-gray-100">
                    <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                    <h4 class="text-lg font-medium text-gray-700">Data Pribadi</h4>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">NIM <span class="text-red-500">*</span></label>
                        <input type="text" name="nim" value="{{ old('nim') }}" class="w-full p-2 rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition duration-200 shadow-sm" required>
                        @error('nim') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" class="w-full p-2 rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition duration-200 shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" class="w-full p-2 rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition duration-200 shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <select name="jenis_kelamin" class="w-full p-2 rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition duration-200 shadow-sm appearance-none" required>
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                            </div>
                        </div>
                        @error('jenis_kelamin') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Agama</label>
                        <select name="agama" class="w-full p-2 rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition duration-200 shadow-sm">
                            <option value="">Pilih Agama</option>
                            @foreach(['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu'] as $a)
                                <option value="{{ $a }}" {{ old('agama') == $a ? 'selected' : '' }}>{{ $a }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">No. Telepon</label>
                        <input type="text" name="no_telp" value="{{ old('no_telp') }}" class="w-full p-2 rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition duration-200 shadow-sm">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                        <textarea name="alamat" rows="3" class="w-full p-2 rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition duration-200 shadow-sm">{{ old('alamat') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- ========================
                 SECTION 3: DATA AKADEMIK
            ========================== -->
            <div>
                <div class="flex items-center mb-4 pb-2 border-b border-gray-100">
                    <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z"></path><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path></svg>
                    <h4 class="text-lg font-medium text-gray-700">Data Akademik</h4>
                </div>

                 <div class="grid md:grid-cols-2 gap-5">
                        <!-- Fakultas -->
                        <div>
                            <label for="fakultas_id" class="block text-sm font-semibold text-gray-700 mb-2">Fakultas *</label>
                            <select id="fakultas_id" name="fakultas_id" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                    onchange="filterProgramStudi()" required>
                                <option value="">Pilih Fakultas</option>
                                @foreach($fakultas as $fak)
                                <option value="{{ $fak->id }}" {{ old('fakultas_id') == $fak->id ? 'selected' : '' }}>
                                    {{ $fak->nama }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Program Studi -->
                        <div>
                            <label for="program_studi_id" class="block text-sm font-semibold text-gray-700 mb-2">Program Studi *</label>
                            <select id="program_studi_id" name="program_studi_id" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                <option value="">Pilih Program Studi</option>
                                @foreach($programStudis as $prodi)
                                <option value="{{ $prodi->id }}" data-fakultas="{{ $prodi->fakultas_id }}" {{ old('program_studi_id') == $prodi->id ? 'selected' : '' }}>
                                    {{ $prodi->nama }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Tahun Masuk -->
                        <div>
                            <label for="tahun_masuk" class="block text-sm font-semibold text-gray-700 mb-2">Tahun Masuk *</label>
                            <input type="number" id="tahun_masuk" name="tahun_masuk" value="{{ old('tahun_masuk', date('Y')) }}" 
                                   min="2000" max="{{ date('Y') + 1 }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        </div>
                    </div>
            </div>

            <!-- ========================
                 SECTION 4: DATA ORANG TUA
            ========================== -->
            <div>
                <div class="flex items-center mb-4 pb-2 border-b border-gray-100">
                    <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <h4 class="text-lg font-medium text-gray-700">Data Orang Tua / Wali</h4>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Orang Tua/Wali</label>
                        <input type="text" name="nama_ortu" value="{{ old('nama_ortu') }}" class="w-full p-2 rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition duration-200 shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pekerjaan</label>
                        <input type="text" name="pekerjaan_ortu" value="{{ old('pekerjaan_ortu') }}" class="w-full p-2 rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition duration-200 shadow-sm">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">No. Telepon Orang Tua</label>
                        <input type="text" name="no_telp_ortu" value="{{ old('no_telp_ortu') }}" class="w-full p-2 rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition duration-200 shadow-sm md:w-1/2">
                    </div>
                </div>
            </div>

            <!-- ========================
                 ACTION BUTTONS
            ========================== -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 bg-gray-50 -mx-6 -mb-6 p-6 sm:-mx-8 sm:-mb-8 sm:p-8 rounded-b-xl">
                <a href="{{ route('admin.siswa.index') }}" class="px-6 py-2.5 rounded-lg border border-gray-300 text-gray-700 font-medium hover:bg-gray-50 hover:text-gray-800 transition duration-200 focus:ring focus:ring-gray-200">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-lg bg-blue-600 text-white font-medium hover:bg-blue-700 shadow-md hover:shadow-lg transition duration-200 focus:ring focus:ring-blue-300">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                        </svg>
                        Simpan Data
                    </div>
                </button>
            </div>

        </form>
    </div>
</div>
<script>
        // Filter Program Studi berdasarkan Fakultas
        function filterProgramStudi() {
            const fakultasId = document.getElementById('fakultas_id').value;
            const prodiSelect = document.getElementById('program_studi_id');
            const prodiOptions = prodiSelect.querySelectorAll('option');

            prodiSelect.value = '';
            
            prodiOptions.forEach(option => {
                if (option.value === '') {
                    option.style.display = 'block';
                } else {
                    const prodiFakultasId = option.getAttribute('data-fakultas');
                    if (fakultasId === '' || prodiFakultasId === fakultasId) {
                        option.style.display = 'block';
                    } else {
                        option.style.display = 'none';
                    }
                }
            });
        }

        // Filter saat halaman dimuat jika ada old value
        window.addEventListener('DOMContentLoaded', function() {
            const fakultasId = document.getElementById('fakultas_id').value;
            if (fakultasId) {
                filterProgramStudi();
            }
        });
    </script>
@endsection