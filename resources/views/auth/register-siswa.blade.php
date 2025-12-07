<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Siswa - CDC {{ config('app.ikip') }}</title>
    @vite(['resources/css/app.css'])
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

<link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="bg-gradient-to-br from-blue-50 via-white to-blue-50 min-h-screen py-8 px-4">
    <div class="max-w-4xl mx-auto">
        <!-- Back Button -->
        <a href="{{ route('register') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 mb-6 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke pilihan akun
        </a>

        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-600 to-blue-700 rounded-2xl mb-4 shadow-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Registrasi Mahasiswa</h1>
            <p class="text-gray-600">Lengkapi data diri Anda untuk membuat akun</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-3xl shadow-xl p-8 border border-gray-100">
            @if($errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-800 px-4 py-4 rounded-lg">
                <div class="flex items-start space-x-3">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <ul class="text-sm font-medium space-y-1">
                        @foreach($errors->all() as $error)
                        <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            <form method="POST" action="{{ route('register.siswa') }}" class="space-y-6">
                @csrf

                <!-- Data Akun Section -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                        </svg>
                        Data Akun
                    </h3>

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

                <!-- Data Pribadi Section -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Data Pribadi
                    </h3>

                    <div class="grid md:grid-cols-2 gap-5">
                        <!-- NIM -->
                        <div>
                            <label for="nim" class="block text-sm font-semibold text-gray-700 mb-2">NIM *</label>
                            <input type="text" id="nim" name="nim" value="{{ old('nim') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                   placeholder="Nomor Induk Mahasiswa" maxlength="20" required>
                        </div>

                        <!-- Jenis Kelamin -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Kelamin *</label>
                            <div class="flex gap-4 mt-3">
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="jenis_kelamin" value="L" {{ old('jenis_kelamin') == 'L' ? 'checked' : '' }} 
                                           class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500" required>
                                    <span class="ml-2 text-gray-700">Laki-laki</span>
                                </label>
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="jenis_kelamin" value="P" {{ old('jenis_kelamin') == 'P' ? 'checked' : '' }} 
                                           class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500" required>
                                    <span class="ml-2 text-gray-700">Perempuan</span>
                                </label>
                            </div>
                        </div>

                        <!-- Tempat Lahir -->
                        <div>
                            <label for="tempat_lahir" class="block text-sm font-semibold text-gray-700 mb-2">Tempat Lahir *</label>
                            <input type="text" id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                   placeholder="Contoh: Bojonegoro" maxlength="100" required>
                        </div>

                        <!-- Tanggal Lahir -->
                        <div>
                            <label for="tanggal_lahir" class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Lahir *</label>
                            <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        </div>

                        <!-- Agama -->
                        <div>
                            <label for="agama" class="block text-sm font-semibold text-gray-700 mb-2">Agama *</label>
                            <select id="agama" name="agama" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                <option value="">Pilih Agama</option>
                                <option value="Islam" {{ old('agama') == 'Islam' ? 'selected' : '' }}>Islam</option>
                                <option value="Kristen" {{ old('agama') == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                <option value="Katolik" {{ old('agama') == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                                <option value="Hindu" {{ old('agama') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                <option value="Buddha" {{ old('agama') == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                                <option value="Konghucu" {{ old('agama') == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                            </select>
                        </div>

                        <!-- No Telepon -->
                        <div>
                            <label for="no_telp" class="block text-sm font-semibold text-gray-700 mb-2">No. Telepon *</label>
                            <input type="tel" id="no_telp" name="no_telp" value="{{ old('no_telp') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                   placeholder="08xxxxxxxxxx" maxlength="15" required>
                        </div>

                        <!-- Alamat -->
                        <div class="md:col-span-2">
                            <label for="alamat" class="block text-sm font-semibold text-gray-700 mb-2">Alamat Lengkap *</label>
                            <textarea id="alamat" name="alamat" rows="3" 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                      placeholder="Masukkan alamat lengkap" required>{{ old('alamat') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Data Pendidikan Section -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        Data Akademik
                    </h3>

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

                <!-- Data Orang Tua Section -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Data Orang Tua / Wali
                    </h3>

                    <div class="grid md:grid-cols-2 gap-5">
                        <!-- Nama Orang Tua -->
                        <div>
                            <label for="nama_ortu" class="block text-sm font-semibold text-gray-700 mb-2">Nama Orang Tua / Wali *</label>
                            <input type="text" id="nama_ortu" name="nama_ortu" value="{{ old('nama_ortu') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                   placeholder="Nama lengkap orang tua/wali" required>
                        </div>

                        <!-- Pekerjaan Orang Tua -->
                        <div>
                            <label for="pekerjaan_ortu" class="block text-sm font-semibold text-gray-700 mb-2">Pekerjaan Orang Tua / Wali</label>
                            <input type="text" id="pekerjaan_ortu" name="pekerjaan_ortu" value="{{ old('pekerjaan_ortu') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                   placeholder="Pekerjaan orang tua/wali" maxlength="100">
                        </div>

                        <!-- No Telepon Orang Tua -->
                        <div class="md:col-span-2">
                            <label for="no_telp_ortu" class="block text-sm font-semibold text-gray-700 mb-2">No. Telepon Orang Tua / Wali</label>
                            <input type="tel" id="no_telp_ortu" name="no_telp_ortu" value="{{ old('no_telp_ortu') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                   placeholder="08xxxxxxxxxx" maxlength="15">
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold py-3.5 rounded-xl hover:from-blue-700 hover:to-blue-800 shadow-lg transform hover:scale-[1.02] transition-all flex items-center justify-center space-x-2">
                        <span>Daftar Sebagai Mahasiswa</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </button>
                </div>

                <!-- Login Link -->
                <div class="text-center pt-4">
                    <p class="text-sm text-gray-600">
                        Sudah punya akun? 
                        <a href="{{ route('login') }}" class="font-semibold text-blue-600 hover:text-blue-700 transition-colors">
                            Login di sini →
                        </a>
                    </p>
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
</body>
</html>