<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Perusahaan - CDC {{ config('app.ikip') }}</title>
    @vite(['resources/css/app.css'])
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

<link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="bg-gradient-to-br from-purple-50 via-white to-purple-50 min-h-screen py-8 px-4">
    <div class="max-w-4xl mx-auto">
        <!-- Back Button -->
        <a href="{{ route('register') }}" class="inline-flex items-center text-purple-600 hover:text-purple-700 mb-6 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke pilihan akun
        </a>

        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-purple-600 to-purple-700 rounded-2xl mb-4 shadow-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Registrasi Perusahaan</h1>
            <p class="text-gray-600">Lengkapi data perusahaan Anda untuk membuat akun</p>
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

            <form method="POST" action="{{ route('register.perusahaan') }}" class="space-y-6">
                @csrf

                <!-- Data Akun Section -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                        </svg>
                        Data Akun
                    </h3>

                    <div class="grid md:grid-cols-2 gap-5">
                        <!-- Nama PIC -->
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nama PIC (Person In Charge) *</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent" 
                                   placeholder="Nama lengkap penanggung jawab" required>
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Login *</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent" 
                                   placeholder="email.login@example.com" required>
                            <p class="text-xs text-gray-500 mt-1">Email ini akan digunakan untuk login</p>
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password *</label>
                            <input type="password" id="password" name="password" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent" 
                                   placeholder="Minimal 6 karakter" required>
                        </div>

                        <!-- Konfirmasi Password -->
                        <div class="md:col-span-2">
                            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi Password *</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent" 
                                   placeholder="Ketik ulang password" required>
                        </div>
                    </div>
                </div>

                <!-- Data Perusahaan Section -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        Data Perusahaan
                    </h3>

                    <div class="grid md:grid-cols-2 gap-5">
                        <!-- Nama Perusahaan -->
                        <div class="md:col-span-2">
                            <label for="nama_perusahaan" class="block text-sm font-semibold text-gray-700 mb-2">Nama Perusahaan *</label>
                            <input type="text" id="nama_perusahaan" name="nama_perusahaan" value="{{ old('nama_perusahaan') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent" 
                                   placeholder="PT. Nama Perusahaan" required>
                        </div>

                        <!-- Bidang Usaha -->
                        <div>
                            <label for="bidang_usaha" class="block text-sm font-semibold text-gray-700 mb-2">Bidang Usaha *</label>
                            <select id="bidang_usaha" name="bidang_usaha" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent" required>
                                <option value="">Pilih Bidang Usaha</option>
                                <option value="Teknologi Informasi" {{ old('bidang_usaha') == 'Teknologi Informasi' ? 'selected' : '' }}>Teknologi Informasi</option>
                                <option value="Pendidikan" {{ old('bidang_usaha') == 'Pendidikan' ? 'selected' : '' }}>Pendidikan</option>
                                <option value="Kesehatan" {{ old('bidang_usaha') == 'Kesehatan' ? 'selected' : '' }}>Kesehatan</option>
                                <option value="Manufaktur" {{ old('bidang_usaha') == 'Manufaktur' ? 'selected' : '' }}>Manufaktur</option>
                                <option value="Perdagangan" {{ old('bidang_usaha') == 'Perdagangan' ? 'selected' : '' }}>Perdagangan</option>
                                <option value="Jasa" {{ old('bidang_usaha') == 'Jasa' ? 'selected' : '' }}>Jasa</option>
                                <option value="Konstruksi" {{ old('bidang_usaha') == 'Konstruksi' ? 'selected' : '' }}>Konstruksi</option>
                                <option value="Pariwisata" {{ old('bidang_usaha') == 'Pariwisata' ? 'selected' : '' }}>Pariwisata</option>
                                <option value="Keuangan" {{ old('bidang_usaha') == 'Keuangan' ? 'selected' : '' }}>Keuangan</option>
                                <option value="Lainnya" {{ old('bidang_usaha') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>

                        <!-- No Telepon Perusahaan -->
                        <div>
                            <label for="no_telp" class="block text-sm font-semibold text-gray-700 mb-2">No. Telepon Perusahaan *</label>
                            <input type="tel" id="no_telp" name="no_telp" value="{{ old('no_telp') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent" 
                                   placeholder="021xxxxxxxx atau 08xxxxxxxxxx" maxlength="15" required>
                        </div>

                        <!-- Email Perusahaan -->
                        <div>
                            <label for="email_perusahaan" class="block text-sm font-semibold text-gray-700 mb-2">Email Perusahaan *</label>
                            <input type="email" id="email_perusahaan" name="email_perusahaan" value="{{ old('email_perusahaan') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent" 
                                   placeholder="company@example.com" required>
                        </div>

                        <!-- Website -->
                        <div>
                            <label for="website" class="block text-sm font-semibold text-gray-700 mb-2">Website Perusahaan</label>
                            <input type="url" id="website" name="website" value="{{ old('website') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent" 
                                   placeholder="https://www.company.com">
                        </div>

                        <!-- Alamat -->
                        <div class="md:col-span-2">
                            <label for="alamat" class="block text-sm font-semibold text-gray-700 mb-2">Alamat Kantor *</label>
                            <textarea id="alamat" name="alamat" rows="2" 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent" 
                                      placeholder="Jl. Contoh No. 123" required>{{ old('alamat') }}</textarea>
                        </div>

                        <!-- Kota -->
                        <div>
                            <label for="kota" class="block text-sm font-semibold text-gray-700 mb-2">Kota *</label>
                            <input type="text" id="kota" name="kota" value="{{ old('kota') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent" 
                                   placeholder="Contoh: Bojonegoro" maxlength="100" required>
                        </div>

                        <!-- Provinsi -->
                        <div>
                            <label for="provinsi" class="block text-sm font-semibold text-gray-700 mb-2">Provinsi *</label>
                            <select id="provinsi" name="provinsi" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent" required>
                                <option value="">Pilih Provinsi</option>
                                <option value="Jawa Timur" {{ old('provinsi') == 'Jawa Timur' ? 'selected' : '' }}>Jawa Timur</option>
                                <option value="Jawa Tengah" {{ old('provinsi') == 'Jawa Tengah' ? 'selected' : '' }}>Jawa Tengah</option>
                                <option value="Jawa Barat" {{ old('provinsi') == 'Jawa Barat' ? 'selected' : '' }}>Jawa Barat</option>
                                <option value="DKI Jakarta" {{ old('provinsi') == 'DKI Jakarta' ? 'selected' : '' }}>DKI Jakarta</option>
                                <option value="Banten" {{ old('provinsi') == 'Banten' ? 'selected' : '' }}>Banten</option>
                                <option value="DI Yogyakarta" {{ old('provinsi') == 'DI Yogyakarta' ? 'selected' : '' }}>DI Yogyakarta</option>
                                <option value="Lainnya" {{ old('provinsi') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>

                        <!-- Kode Pos -->
                        <div>
                            <label for="kode_pos" class="block text-sm font-semibold text-gray-700 mb-2">Kode Pos</label>
                            <input type="text" id="kode_pos" name="kode_pos" value="{{ old('kode_pos') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent" 
                                   placeholder="62111" maxlength="10">
                        </div>

                        <!-- Deskripsi -->
                        <div class="md:col-span-2">
                            <label for="deskripsi" class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi Perusahaan</label>
                            <textarea id="deskripsi" name="deskripsi" rows="4" 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent" 
                                      placeholder="Ceritakan tentang perusahaan Anda, visi, misi, dan bidang kegiatan...">{{ old('deskripsi') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Data PIC Section -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Data Person In Charge (PIC)
                    </h3>

                    <div class="grid md:grid-cols-2 gap-5">
                        <!-- Nama PIC -->
                        <div>
                            <label for="nama_pic" class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap PIC *</label>
                            <input type="text" id="nama_pic" name="nama_pic" value="{{ old('nama_pic') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent" 
                                   placeholder="Nama lengkap PIC" required>
                        </div>

                        <!-- Jabatan PIC -->
                        <div>
                            <label for="jabatan_pic" class="block text-sm font-semibold text-gray-700 mb-2">Jabatan PIC *</label>
                            <input type="text" id="jabatan_pic" name="jabatan_pic" value="{{ old('jabatan_pic') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent" 
                                   placeholder="HRD Manager, Direktur, dll" maxlength="100" required>
                        </div>

                        <!-- No Telepon PIC -->
                        <div>
                            <label for="no_telp_pic" class="block text-sm font-semibold text-gray-700 mb-2">No. Telepon PIC *</label>
                            <input type="tel" id="no_telp_pic" name="no_telp_pic" value="{{ old('no_telp_pic') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent" 
                                   placeholder="08xxxxxxxxxx" maxlength="15" required>
                        </div>

                        <!-- Email PIC -->
                        <div>
                            <label for="email_pic" class="block text-sm font-semibold text-gray-700 mb-2">Email PIC *</label>
                            <input type="email" id="email_pic" name="email_pic" value="{{ old('email_pic') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent" 
                                   placeholder="pic@company.com" required>
                        </div>
                    </div>

                    <!-- Info Note -->
                    <div class="mt-4 bg-purple-50 border-l-4 border-purple-500 p-4 rounded-lg">
                        <div class="flex">
                            <svg class="w-5 h-5 text-purple-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <div class="text-sm text-purple-700">
                                <p class="font-semibold mb-1">Informasi Penting:</p>
                                <p>Akun perusahaan akan diverifikasi oleh admin sebelum dapat menggunakan semua fitur. Proses verifikasi membutuhkan waktu 1-3 hari kerja.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-purple-600 to-purple-700 text-white font-semibold py-3.5 rounded-xl hover:from-purple-700 hover:to-purple-800 shadow-lg transform hover:scale-[1.02] transition-all flex items-center justify-center space-x-2">
                        <span>Daftar Sebagai Perusahaan</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </button>
                </div>

                <!-- Login Link -->
                <div class="text-center pt-4">
                    <p class="text-sm text-gray-600">
                        Sudah punya akun? 
                        <a href="{{ route('login') }}" class="font-semibold text-purple-600 hover:text-purple-700 transition-colors">
                            Login di sini →
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>