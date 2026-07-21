<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CDC {{ config('app.ikip') }}</title>
    @vite(['resources/css/app.css'])
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .float-animation {
            animation: float 6s ease-in-out infinite;
        }
        .bg-pattern {
            background-image: 
                radial-gradient(circle at 20% 50%, rgba(59, 130, 246, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(147, 197, 253, 0.1) 0%, transparent 50%);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-white to-blue-50 min-h-screen flex items-center justify-center p-4 bg-pattern">
    <div class="w-full flex items-center justify-center">
        <div class="max-w-md w-full mx-auto">
            <div class="text-center mb-8 lg:hidden">
                <h1 class="text-2xl font-bold text-gray-900">Login</h1>
                <p class="text-gray-600 mt-1 text-sm">CDC IKIP PGRI Bojonegoro</p>
            </div>

            <div class="bg-white rounded-3xl shadow-2xl p-8 lg:p-10 border border-gray-100">
                <div class="mb-8 items-center text-center">
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Login</h2>
                    <p class="text-gray-600">CDC IKIP PGRI Bojonegoro</p>
                </div>

                @if($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-800 px-4 py-4 rounded-lg flex items-start space-x-3">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        @foreach($errors->all() as $error)
                            <p class="text-sm font-medium">{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
                @endif

                @if(session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-800 px-4 py-4 rounded-lg flex items-start space-x-3">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
                @endif

                <form method="POST" action="{{ route('login.submit') }}" class="space-y-6" id="loginForm">
                    @csrf
                    <input type="hidden" name="login_type" id="login_type" value="{{ old('login_type', 'aktif') }}">

                   

                    <div id="field-nim">
                        <label for="nim" class="block text-sm font-semibold text-gray-700 mb-2">
                            NIM (Nomor Induk Mahasiswa)
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                                </svg>
                            </div>
                            <input type="text" 
                                   id="nim" 
                                   name="nim" 
                                   value="{{ old('nim') }}"
                                   inputmode="numeric" pattern="[0-9]*"
                                   class="w-full pl-12 pr-4 py-3.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('nim') border-red-500 ring-2 ring-red-200 @enderror" 
                                   placeholder="Masukkan NIM Anda"
                                   autofocus>
                        </div>
                    </div>

                    <div id="field-tanggal-lahir">
                        <label for="tanggal_lahir" class="block text-sm font-semibold text-gray-700 mb-2">
                            Tanggal Lahir
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <input type="password" 
                                   id="tanggal_lahir" 
                                   name="tanggal_lahir" 
                                   value="{{ old('tanggal_lahir') }}"
                                   class="w-full pl-12 pr-12 py-3.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('tanggal_lahir') border-red-500 ring-2 ring-red-200 @enderror"
                                   placeholder="DD/MM/YYYY">
                            <button type="button" 
                                    onclick="togglePassword('tanggal_lahir', 'eye-icon-tgllahir')"
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                                <svg id="eye-icon-tgllahir" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Sesuai tanggal lahir yang didaftarkan saat registrasi.</p>
                    </div>

                    <div id="field-email" class="hidden">
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                            Email
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                </svg>
                            </div>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}"
                                   class="w-full pl-12 pr-4 py-3.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('email') border-red-500 ring-2 ring-red-200 @enderror" 
                                   placeholder="nama@example.com">
                        </div>
                    </div>

                    <div id="field-password" class="hidden">
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                            Password
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   class="w-full pl-12 pr-12 py-3.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('password') border-red-500 ring-2 ring-red-200 @enderror" 
                                   placeholder="••••••••">
                            <button type="button" 
                                    onclick="togglePassword('password', 'eye-icon-pass')"
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                                <svg id="eye-icon-pass" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center cursor-pointer group">
                            <input type="checkbox" 
                                   name="remember" 
                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500 transition-all cursor-pointer">
                            <span class="ml-2 text-sm text-gray-700 group-hover:text-gray-900 transition-colors">Ingat saya</span>
                        </label>
                    </div>
                     <!-- DROPDOWN TIPE LOGIN (DIPINDAH KE ATAS) -->
                    <div class="mb-2">
                        <label for="login-type-select" class="block text-sm font-semibold text-gray-700 mb-2"></label>
                        <div class="relative">
                            <select 
                                id="login-type-select" 
                                onchange="setLoginType(this.value)"
                                class="w-full appearance-none bg-gray-50 border border-gray-300 text-gray-900 py-3 px-4 pr-8 rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 cursor-pointer"
                            >
                                <option value="aktif">Mahasiswa Aktif</option>
                                <option value="umum">Alumni</option>
                            </select>
                            
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                                </svg>
                            </div>
                        </div>
                    </div>


                    <div class="flex justify-center mt-5">
                        <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.sitekey') }}"></div>
                    </div>
                    @error('g-recaptcha-response')
                        <p class="text-sm text-red-600 text-center">{{ $message }}</p>
                    @enderror

                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-3.5 px-4 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center space-x-2">
                        <span>Masuk</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </button>

                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-white text-gray-500">atau</span>
                        </div>
                    </div>

                    <div class="text-center space-y-2">
                        <p class="text-sm text-gray-600">
                            Belum punya akun? 
                            <a href="{{ route('register') }}" class="font-semibold text-blue-600 hover:text-blue-700 transition-colors">
                                Daftar sekarang &rarr;
                            </a>
                        </p>
                       
                    </div>
                </form>
            </div>

            <p class="text-center text-sm text-gray-500 mt-8">
                &copy; {{ date('Y') }} {{ config('app.ikip') }}. All rights reserved.
            </p>
        </div>
    </div>

    <script>
        function setLoginType(type) {
            // Update input hidden
            document.getElementById('login_type').value = type;

            // Sync dropdown value (berguna jika fungsi dipanggil saat DOMContentLoaded)
            const selectElement = document.getElementById('login-type-select');
            if (selectElement.value !== type) {
                selectElement.value = type;
            }

            const fieldNim = document.getElementById('field-nim');
            const fieldTanggalLahir = document.getElementById('field-tanggal-lahir');
            const fieldEmail = document.getElementById('field-email');
            const fieldPassword = document.getElementById('field-password');

            const nimInput = document.getElementById('nim');
            const tanggalLahirInput = document.getElementById('tanggal_lahir');
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');

            if (type === 'aktif') {
                fieldNim.classList.remove('hidden');
                fieldTanggalLahir.classList.remove('hidden');
                fieldEmail.classList.add('hidden');
                fieldPassword.classList.add('hidden');

                nimInput.setAttribute('required', 'required');
                tanggalLahirInput.setAttribute('required', 'required');
                emailInput.removeAttribute('required');
                passwordInput.removeAttribute('required');

            } else {
                fieldNim.classList.add('hidden');
                fieldTanggalLahir.classList.add('hidden');
                fieldEmail.classList.remove('hidden');
                fieldPassword.classList.remove('hidden');

                nimInput.removeAttribute('required');
                tanggalLahirInput.removeAttribute('required');
                emailInput.setAttribute('required', 'required');
                passwordInput.setAttribute('required', 'required');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Membaca tipe login dari session old() yang tersimpan di input hidden
            const currentType = document.getElementById('login_type').value;
            setLoginType(currentType === 'umum' ? 'umum' : 'aktif');
        });

        // FUNGSI TOGGLE PASSWORD
        function togglePassword(inputId, iconId) {
            const inputField = document.getElementById(inputId);
            const eyeIcon = document.getElementById(iconId);
            
            if (inputField.type === 'password') {
                inputField.type = 'text';
                eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>';
            } else {
                inputField.type = 'password';
                eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
            }
        }

        // AUTO-HIDE ALERT
        setTimeout(() => {
            const alerts = document.querySelectorAll('[class*="bg-red-50"], [class*="bg-green-50"]');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);  
            });
        }, 5000);
    </script>
</body>
</html>