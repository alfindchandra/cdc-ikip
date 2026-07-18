<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Mahasiswa - CDC {{ config('app.ikip') }}</title>
    @vite(['resources/css/app.css'])
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="bg-gradient-to-br from-slate-50 via-white to-slate-50 min-h-screen py-8 px-4">
    <div class="max-w-4xl mx-auto">
        <!-- Back Button -->
        <a href="{{ route('register') }}" class="inline-flex items-center text-sm font-semibold text-indigo-600 hover:text-indigo-700 mb-6 transition-colors gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke pilihan akun
        </a>

        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-indigo-50 to-indigo-100 border border-indigo-200 rounded-2xl mb-4 shadow-sm">
                <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight mb-2">Registrasi Akun</h1>
            <p class="text-sm font-medium text-slate-500">Lengkapi data diri Anda untuk membuat akun baru</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 sm:p-8">
            @if($errors->any())
            <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl">
                <div class="flex items-start space-x-3">
                    <svg class="w-5 h-5 flex-shrink-0 text-rose-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <ul class="text-xs font-medium space-y-1">
                        @foreach($errors->all() as $error)
                        <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            <form method="POST" action="{{ route('register.mahasiswa') }}" class="space-y-8">
                @csrf

                <!-- Section I: Data Akun -->
                <div class="space-y-5">
                    <h3 class="text-sm font-bold text-indigo-600 uppercase tracking-wide border-b border-slate-100 pb-2">
                        Data Akun
                    </h3>

                    <div class="grid md:grid-cols-2 gap-5">
                        <div class="md:col-span-2">
                            <label for="name" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">Nama Lengkap <span class="text-rose-500">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" 
                                   class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5" 
                                   placeholder="Masukkan nama lengkap sesuai identitas" required>
                        </div>

                        <div>
                            <label for="email" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">Email <span class="text-rose-500">*</span></label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" 
                                   class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5" 
                                   placeholder="nama@example.com" required>
                        </div>

                        <div>
                            <label for="password" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">Password <span class="text-rose-500">*</span></label>
                            <input type="password" id="password" name="password" 
                                   class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5" 
                                   placeholder="Minimal 6 karakter" required>
                        </div>

                        <div class="md:col-span-2">
                            <label for="password_confirmation" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">Konfirmasi Password <span class="text-rose-500">*</span></label>
                            <input type="password" id="password_confirmation" name="password_confirmation" 
                                   class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5" 
                                   placeholder="Ketik ulang password" required>
                        </div>
                    </div>
                </div>

                <!-- Section II: Data Pribadi -->
                <div class="space-y-5">
                    <h3 class="text-sm font-bold text-indigo-600 uppercase tracking-wide border-b border-slate-100 pb-2">
                        Data Pribadi
                    </h3>

                    <div class="grid md:grid-cols-2 gap-5">
                        <div>
                            <label for="nim" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">NIM / Nomor Induk <span class="text-rose-500">*</span></label>
                            <input type="text" id="nim" name="nim" value="{{ old('nim') }}" 
                                   class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5" 
                                   placeholder="Nomor Induk Mahasiswa / Siswa" required>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">Jenis Kelamin <span class="text-rose-500">*</span></label>
                            <div class="flex gap-5 mt-2.5">
                                <label class="flex items-center cursor-pointer text-sm font-medium text-slate-700">
                                    <input type="radio" name="jenis_kelamin" value="L" {{ old('jenis_kelamin') == 'L' ? 'checked' : '' }} 
                                           class="w-4 h-4 text-indigo-600 border-slate-300 focus:ring-indigo-500/20" required>
                                    <span class="ml-2">Laki-laki</span>
                                </label>
                                <label class="flex items-center cursor-pointer text-sm font-medium text-slate-700">
                                    <input type="radio" name="jenis_kelamin" value="P" {{ old('jenis_kelamin') == 'P' ? 'checked' : '' }} 
                                           class="w-4 h-4 text-indigo-600 border-slate-300 focus:ring-indigo-500/20" required>
                                    <span class="ml-2">Perempuan</span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label for="tempat_lahir" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">Tempat Lahir <span class="text-rose-500">*</span></label>
                            <input type="text" id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir') }}" 
                                   class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5" 
                                   placeholder="Contoh: Bojonegoro" required>
                        </div>

                        <div>
                            <label for="tanggal_lahir" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">Tanggal Lahir <span class="text-rose-500">*</span></label>
                            <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" 
                                   class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5" required>
                        </div>

                        <div>
                            <label for="agama" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">Agama <span class="text-rose-500">*</span></label>
                            <select id="agama" name="agama" 
                                    class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5" required>
                                <option value="">Pilih Agama</option>
                                @foreach(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'] as $agama)
                                    <option value="{{ $agama }}" {{ old('agama') == $agama ? 'selected' : '' }}>{{ $agama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="no_telp" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">No. Telepon <span class="text-rose-500">*</span></label>
                            <input type="text" id="no_telp" name="no_telp" value="{{ old('no_telp') }}" 
                                   class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5" 
                                   placeholder="08xxxxxxxxxx" required>
                        </div>

                        <div class="md:col-span-2">
                            <label for="alamat" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">Alamat Lengkap <span class="text-rose-500">*</span></label>
                            <textarea id="alamat" name="alamat" rows="3" 
                                      class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5" 
                                      placeholder="Masukkan alamat domisili lengkap" required>{{ old('alamat') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Section III: Data Pendidikan -->
                <div class="space-y-5">
                    <h3 class="text-sm font-bold text-indigo-600 uppercase tracking-wide border-b border-slate-100 pb-2">
                        Data Pendidikan
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div class="md:col-span-3">
                            <label for="tingkat_pendidikan" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">Tingkat Pendidikan <span class="text-rose-500">*</span></label>
                            <select id="tingkat_pendidikan" name="tingkat_pendidikan"
                                    class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5" required>
                                <option value="">Pilih Tingkat Pendidikan</option>
                                @foreach(['SD', 'SMP', 'SMA', 'SMK', 'D1', 'D2', 'D3', 'S1', 'S2', 'S3'] as $tingkat)
                                    <option value="{{ $tingkat }}" {{ old('tingkat_pendidikan') == $tingkat ? 'selected' : '' }}>{{ $tingkat }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Fakultas -->
                        <div id="fakultas_field" class="md:col-span-1">
                            <label for="fakultas_input" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">Fakultas</label>
                            <input type="text" id="fakultas_input" name="fakultas" value="{{ old('fakultas') }}"
                                   class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5"
                                   placeholder="Nama Fakultas">
                        </div>

                        <!-- Program Studi / Jurusan -->
                        <div id="prodi_field" class="md:col-span-1">
                            <label id="prodi_label" for="program_studi_input" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">Program Studi</label>
                            <input type="text" id="program_studi_input" name="program_studi" value="{{ old('program_studi') }}"
                                   class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5"
                                   placeholder="Nama Program Studi / Jurusan">
                        </div>

                        <!-- Asal Sekolah / Kampus -->
                        <div id="asal_sekolah_field" class="md:col-span-1">
                            <label id="sekolah_label" for="nama_sekolah" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">Asal Sekolah</label>
                            <input type="text" id="nama_sekolah" name="asal_sekolah" value="{{ old('asal_sekolah') }}"
                                   class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5"
                                   placeholder="Nama Sekolah Asal">
                        </div>

                        <div>
                            <label for="tahun_masuk" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">Tahun Masuk <span class="text-rose-500">*</span></label>
                            <input type="number" id="tahun_masuk" name="tahun_masuk" value="{{ old('tahun_masuk', date('Y')) }}" 
                                   min="2000" max="{{ date('Y') + 1 }}"
                                   class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5" required>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">Status <span class="text-rose-500">*</span></label>
                            <div class="flex gap-5 mt-2.5">
                                <label class="flex items-center cursor-pointer text-sm font-medium text-slate-700">
                                    <input type="radio" name="status" value="aktif" id="status_aktif"
                                           {{ old('status', 'aktif') == 'aktif' ? 'checked' : '' }}
                                           class="w-4 h-4 text-indigo-600 border-slate-300 focus:ring-indigo-500/20" required>
                                    <span class="ml-2">Aktif</span>
                                </label>
                                <label class="flex items-center cursor-pointer text-sm font-medium text-slate-700">
                                    <input type="radio" name="status" value="lulus" id="status_lulus"
                                           {{ old('status') == 'lulus' ? 'checked' : '' }}
                                           class="w-4 h-4 text-indigo-600 border-slate-300 focus:ring-indigo-500/20" required>
                                    <span class="ml-2">Lulus</span>
                                </label>
                            </div>
                        </div>

                        <!-- Tahun Lulus -->
                        <div id="tahun_lulus_field" class="md:col-span-1 hidden">
                            <label for="tahun_lulus" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">Tahun Lulus <span class="text-rose-500">*</span></label>
                            <input type="number" id="tahun_lulus" name="tahun_lulus" value="{{ old('tahun_lulus') }}" 
                                   min="2000" max="{{ date('Y') + 1 }}"
                                   class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5" 
                                   placeholder="Contoh: {{ date('Y') }}">
                        </div>
                    </div>
                </div>

                <!-- Section IV: Data Orang Tua -->
                <div class="space-y-5">
                    <h3 class="text-sm font-bold text-indigo-600 uppercase tracking-wide border-b border-slate-100 pb-2">
                        Data Orang Tua / Wali
                    </h3>

                    <div class="grid md:grid-cols-2 gap-5">
                        <div>
                            <label for="nama_ortu" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">Nama Orang Tua / Wali <span class="text-rose-500">*</span></label>
                            <input type="text" id="nama_ortu" name="nama_ortu" value="{{ old('nama_ortu') }}" 
                                   class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5" 
                                   placeholder="Nama lengkap orang tua/wali" required>
                        </div>

                        <div>
                            <label for="pekerjaan_ortu" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">Pekerjaan Orang Tua / Wali</label>
                            <input type="text" id="pekerjaan_ortu" name="pekerjaan_ortu" value="{{ old('pekerjaan_ortu') }}" 
                                   class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5" 
                                   placeholder="Pekerjaan orang tua/wali">
                        </div>

                        <div class="md:col-span-2">
                            <label for="no_telp_ortu" class="block text-xs font-semibold text-slate-500 uppercase mb-1.5">No. Telepon Orang Tua / Wali</label>
                            <input type="text" id="no_telp_ortu" name="no_telp_ortu" value="{{ old('no_telp_ortu') }}" 
                                   class="w-full border-slate-200 rounded-xl shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500/10 text-sm p-2.5" 
                                   placeholder="08xxxxxxxxxx">
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-4 border-t border-slate-100">
                    <button type="submit" 
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 rounded-xl shadow-sm shadow-indigo-600/10 transition-all flex items-center justify-center space-x-2">
                        <span>Daftar Akun Baru</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </button>
                </div>

                <!-- Login Link -->
                <div class="text-center pt-2">
                    <p class="text-sm text-slate-500">
                        Sudah punya akun? 
                        <a href="{{ route('login') }}" class="font-semibold text-indigo-600 hover:text-indigo-700 transition-colors">
                            Login di sini →
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tingkatPendidikan = document.getElementById('tingkat_pendidikan');
            const fakultasField = document.getElementById('fakultas_field');
            const prodiField = document.getElementById('prodi_field');
            const prodiLabel = document.getElementById('prodi_label');
            const asalSekolahField = document.getElementById('asal_sekolah_field');
            const sekolahLabel = document.getElementById('sekolah_label');
            
            const statusAktif = document.getElementById('status_aktif');
            const statusLulus = document.getElementById('status_lulus');
            const tahunLulusField = document.getElementById('tahun_lulus_field');
            const tahunLulusInput = document.getElementById('tahun_lulus');

            function toggleJenjangPendidikan() {
                const val = tingkatPendidikan.value;

                if (!val) return;

                if (['SD', 'SMP'].includes(val)) {
                    fakultasField.classList.add('hidden');
                    prodiField.classList.add('hidden');
                    document.getElementById('fakultas_input').value = '';
                    document.getElementById('program_studi_input').value = '';
                    
                    sekolahLabel.innerText = "Asal Sekolah";
                    asalSekolahField.className = "md:col-span-3";
                } 
                else if (['SMA', 'SMK'].includes(val)) {
                    fakultasField.classList.add('hidden');
                    prodiField.classList.remove('hidden');
                    prodiLabel.innerText = "Jurusan";
                    document.getElementById('fakultas_input').value = '';
                    
                    sekolahLabel.innerText = "Asal Sekolah";
                    prodiField.className = "md:col-span-1";
                    asalSekolahField.className = "md:col-span-2";
                } 
                else {
                    fakultasField.classList.remove('hidden');
                    prodiField.classList.remove('hidden');
                    prodiLabel.innerText = "Program Studi";
                    sekolahLabel.innerText = "Nama Kampus/Universitas";
                    
                    fakultasField.className = "md:col-span-1";
                    prodiField.className = "md:col-span-1";
                    asalSekolahField.className = "md:col-span-1";
                }
            }

            function toggleTahunLulus() {
                if (statusLulus.checked) {
                    tahunLulusField.classList.remove('hidden');
                    tahunLulusInput.setAttribute('required', 'required');
                } else {
                    tahunLulusField.classList.add('hidden');
                    tahunLulusInput.removeAttribute('required');
                    tahunLulusInput.value = '';
                }
            }

            // Inisialisasi awal event listener
            tingkatPendidikan.addEventListener('change', toggleJenjangPendidikan);
            statusAktif.addEventListener('change', toggleTahunLulus);
            statusLulus.addEventListener('change', toggleTahunLulus);

            // Eksekusi fungsi saat reload/old data ada
            toggleJenjangPendidikan();
            toggleTahunLulus();
        });
    </script>
</body>
</html>