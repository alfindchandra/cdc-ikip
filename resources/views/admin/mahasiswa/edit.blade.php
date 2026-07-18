@extends('layouts.app')

@section('title', 'Edit Data Mahasiswa')
@section('page-title', 'Edit Data Mahasiswa')

@section('content')
<div class="max-w-4xl mx-auto p-4 sm:p-6 lg:p-8">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        
        <!-- Header -->
        <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50">
            <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2.5">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Form Edit Data Mahasiswa
            </h3>
        </div>
        
        <form action="{{ route('admin.mahasiswa.update', $mahasiswa->id) }}" method="POST" class="p-6 sm:p-8 space-y-8">
            @csrf
            @method('PUT')

            {{-- Bagian I: Data Akun --}}
            <div class="space-y-5">
                <h4 class="text-sm font-bold text-indigo-600 uppercase tracking-wide border-b border-slate-100 pb-2">Data Akun</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label for="name" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">Nama Lengkap <span class="text-rose-500">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name', $mahasiswa->user->name ?? '') }}" class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5" required>
                        @error('name')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="email" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">Email <span class="text-rose-500">*</span></label>
                        <input type="email" id="email" name="email" value="{{ old('email', $mahasiswa->user->email ?? '') }}" class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5" required>
                        @error('email')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="password" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">Password Baru</label>
                        <input type="password" id="password" name="password" class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5" placeholder="Kosongkan jika tidak diubah">
                        <p class="mt-1 text-xs text-slate-400">Kosongkan jika tidak ingin mengubah password.</p>
                        @error('password')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            {{-- Bagian II: Data Pribadi --}}
            <div class="space-y-5">
                <h4 class="text-sm font-bold text-indigo-600 uppercase tracking-wide border-b border-slate-100 pb-2">Data Pribadi</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="nim" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">NIM / Nomor Induk <span class="text-rose-500">*</span></label>
                        <input type="text" id="nim" name="nim" value="{{ old('nim', $mahasiswa->nim ?? '') }}" class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5" required>
                        @error('nim')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="tingkat_pendidikan" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">Tingkat Pendidikan <span class="text-rose-500">*</span></label>
                        <select id="tingkat_pendidikan" name="tingkat_pendidikan" class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5" required>
                            <option value="">Pilih Tingkat Pendidikan</option>
                            @foreach(['SD', 'SMP', 'SMA', 'SMK', 'D1', 'D2', 'D3', 'S1', 'S2', 'S3'] as $tingkat)
                                <option value="{{ $tingkat }}" {{ old('tingkat_pendidikan', $mahasiswa->tingkat_pendidikan ?? '') == $tingkat ? 'selected' : '' }}>
                                    {{ $tingkat }}
                                </option>
                            @endforeach
                        </select>
                        @error('tingkat_pendidikan')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="tempat_lahir" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">Tempat Lahir</label>
                        <input type="text" id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir', $mahasiswa->tempat_lahir ?? '') }}" class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5">
                        @error('tempat_lahir')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="tanggal_lahir" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">Tanggal Lahir</label>
                        <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir', $mahasiswa->tanggal_lahir?->format('Y-m-d')) }}" class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5">
                        @error('tanggal_lahir')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="jenis_kelamin" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">Jenis Kelamin <span class="text-rose-500">*</span></label>
                        <select id="jenis_kelamin" name="jenis_kelamin" class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5" required>
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="L" {{ old('jenis_kelamin', $mahasiswa->jenis_kelamin ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin', $mahasiswa->jenis_kelamin ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="agama" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">Agama</label>
                        <select id="agama" name="agama" class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5">
                            <option value="">Pilih Agama</option>
                            @foreach(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'] as $agama)
                                <option value="{{ $agama }}" {{ old('agama', $mahasiswa->agama ?? '') == $agama ? 'selected' : '' }}>{{ $agama }}</option>
                            @endforeach
                        </select>
                        @error('agama')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="no_telp" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">No. Telepon</label>
                        <input type="text" id="no_telp" name="no_telp" value="{{ old('no_telp', $mahasiswa->no_telp ?? '') }}" class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5">
                        @error('no_telp')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="md:col-span-2">
                        <label for="alamat" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">Alamat</label>
                        <textarea id="alamat" name="alamat" rows="3" class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5">{{ old('alamat', $mahasiswa->alamat ?? '') }}</textarea>
                        @error('alamat')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            {{-- Bagian III: Data Akademik / Sekolah --}}
            <div class="space-y-5">
                <h4 class="text-sm font-bold text-indigo-600 uppercase tracking-wide border-b border-slate-100 pb-2">Data Pendidikan</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    
                    <!-- Elemen Kuliah (Fakultas) - Tersembunyi jika SD, SMP, SMA, SMK -->
                    <div id="fakultas_field" class="pt-field md:col-span-1">
                        <label for="fakultas" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">Fakultas</label>
                        <input type="text" id="fakultas" name="fakultas" value="{{ old('fakultas', $mahasiswa->fakultas_id) }}" class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5" placeholder="Nama Fakultas">
                        @error('fakultas')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    <!-- Elemen Program Studi / Jurusan (Dinamis) -->
                    <div id="prodi_field" class="md:col-span-1">
                        <label id="prodi_label" for="program_studi" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">Program Studi</label>
                        <input type="text" id="program_studi" name="program_studi" value="{{ old('program_studi', $mahasiswa->program_studi_id) }}" class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5" placeholder="Nama Program Studi / Jurusan">
                        @error('program_studi')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    <!-- Elemen Sekolah (Asal Sekolah) -->
                    <div id="asal_sekolah_field" class="md:col-span-1">
                        <label for="asal_sekolah" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">Asal Sekolah</label>
                        <input type="text" id="asal_sekolah" name="asal_sekolah" value="{{ old('asal_sekolah', $mahasiswa->asal_sekolah ?? '') }}" class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5" placeholder="Nama Sekolah Asal">
                        @error('asal_sekolah')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="tahun_masuk" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">Tahun Masuk</label>
                        <input type="number" id="tahun_masuk" name="tahun_masuk" value="{{ old('tahun_masuk', $mahasiswa->tahun_masuk ?? '') }}" class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5" min="2000" max="{{ date('Y') }}">
                        @error('tahun_masuk')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            {{-- Bagian IV: Data Orang Tua & Status --}}
            <div class="space-y-5">
                <h4 class="text-sm font-bold text-indigo-600 uppercase tracking-wide border-b border-slate-100 pb-2">Data Orang Tua/Wali & Status</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="nama_ortu" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">Nama Orang Tua/Wali</label>
                        <input type="text" id="nama_ortu" name="nama_ortu" value="{{ old('nama_ortu', $mahasiswa->nama_ortu ?? '') }}" class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5">
                        @error('nama_ortu')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="pekerjaan_ortu" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">Pekerjaan Orang Tua</label>
                        <input type="text" id="pekerjaan_ortu" name="pekerjaan_ortu" value="{{ old('pekerjaan_ortu', $mahasiswa->pekerjaan_ortu ?? '') }}" class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5">
                        @error('pekerjaan_ortu')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="no_telp_ortu" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">No. Telepon Orang Tua</label>
                        <input type="text" id="no_telp_ortu" name="no_telp_ortu" value="{{ old('no_telp_ortu', $mahasiswa->no_telp_ortu ?? '') }}" class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5">
                        @error('no_telp_ortu')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="status" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">Status <span class="text-rose-500">*</span></label>
                        <select id="status" name="status" class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5" required>
                            <option value="aktif" {{ old('status', $mahasiswa->status ?? '') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="lulus" {{ old('status', $mahasiswa->status ?? '') == 'lulus' ? 'selected' : '' }}>Lulus</option>
                            <option value="pindah" {{ old('status', $mahasiswa->status ?? '') == 'pindah' ? 'selected' : '' }}>Pindah</option>
                            <option value="keluar" {{ old('status', $mahasiswa->status ?? '') == 'keluar' ? 'selected' : '' }}>Keluar</option>
                        </select>
                        @error('status')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    
                    <!-- Field Tahun Lulus -->
                    <div id="tahun_lulus_field" class="md:col-span-2 hidden">
                        <label for="tahun_lulus" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">Tahun Lulus</label>
                        <input type="number" id="tahun_lulus" name="tahun_lulus" value="{{ old('tahun_lulus', $mahasiswa->tahun_lulus ?? '') }}" class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5" min="2000" max="{{ date('Y') }}" placeholder="Tahun kelulusan">
                        @error('tahun_lulus')<p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.mahasiswa.index') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition-colors duration-200">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-sm font-semibold text-white shadow-sm shadow-indigo-600/10 transition-colors duration-200">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tingkatPendidikan = document.getElementById('tingkat_pendidikan');
        const fakultasField = document.getElementById('fakultas_field');
        const prodiField = document.getElementById('prodi_field');
        const prodiLabel = document.getElementById('prodi_label');
        const asalSekolahField = document.getElementById('asal_sekolah_field');
        const statusSelect = document.getElementById('status');
        const tahunLulusField = document.getElementById('tahun_lulus_field');
        const tahunLulusInput = document.getElementById('tahun_lulus');

        function toggleJenjangPendidikan() {
            const val = tingkatPendidikan.value;

            if (['SD', 'SMP'].includes(val)) {
                // Jika SD/SMP: Sembunyikan Fakultas, Prodi/Jurusan, Tampilkan Asal Sekolah
                fakultasField.classList.add('hidden');
                prodiField.classList.add('hidden');
                document.getElementById('fakultas').value = '';
                document.getElementById('program_studi').value = '';
                
                asalSekolahField.className = "md:col-span-3"; // Ambil ruang penuh
            } 
            else if (['SMA', 'SMK'].includes(val)) {
                // Jika SMA/SMK: Sembunyikan Fakultas, Tampilkan Jurusan & Asal Sekolah
                fakultasField.classList.add('hidden');
                prodiField.classList.remove('hidden');
                prodiLabel.innerText = "Jurusan"; // Ubah Label menjadi Jurusan
                document.getElementById('fakultas').value = '';
                
                prodiField.className = "md:col-span-1";
                asalSekolahField.className = "md:col-span-2";
            } 
            else {
                // Jika Kuliah (D1-S3): Tampilkan Semua (Fakultas, Prodi, Asal Sekolah)
                fakultasField.classList.remove('hidden');
                prodiField.classList.remove('hidden');
                prodiLabel.innerText = "Program Studi"; // Kembalikan ke Program Studi
                
                fakultasField.className = "md:col-span-1";
                prodiField.className = "md:col-span-1";
                asalSekolahField.className = "md:col-span-1";
            }
        }

        function toggleTahunLulus() {
            if (statusSelect.value === 'lulus') {
                tahunLulusField.classList.remove('hidden');
                tahunLulusInput.setAttribute('required', 'required');
            } else {
                tahunLulusField.classList.add('hidden');
                tahunLulusInput.removeAttribute('required');
            }
        }

        toggleJenjangPendidikan();
        toggleTahunLulus();

        tingkatPendidikan.addEventListener('change', toggleJenjangPendidikan);
        statusSelect.addEventListener('change', toggleTahunLulus);
    });
</script>
@endpush
@endsection