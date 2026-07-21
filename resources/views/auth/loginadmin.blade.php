<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Login Admin - CDC {{ config('app.ikip') }}</title>
    @vite(['resources/css/app.css'])
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    {{-- <link rel="stylesheet" href="{{ asset('css/app.css') }}"> --}} {{-- Komentari jika menggunakan Vite --}}
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); } /* Mengurangi jarak float sedikit */
        }
        .float-animation {
            animation: float 6s ease-in-out infinite;
        }
        .bg-pattern {
            /* Pola background yang lebih halus untuk tema terang */
            background-image:
                radial-gradient(circle at 20% 50%, rgba(203, 213, 225, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(203, 213, 225, 0.15) 0%, transparent 50%);
        }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4 bg-pattern font-sans antialiased">
    <div class="w-full flex items-center justify-center">
        <div class="max-w-md w-full mx-auto">
            
            {{-- Bagian Header (Logo & Judul) --}}
            <div class="text-center mb-8">
                {{-- Logo Container - Diubah jadi putih/terang --}}
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-3xl bg-white shadow-lg border border-slate-100 mb-4 float-animation">
                    {{-- Icon - Warna disesuaikan --}}
                    <svg class="w-8 h-8 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                {{-- Teks Header - Diubah jadi gelap --}}
                <h1 class="text-3xl font-extrabold text-slate-950 trackting-tight">Admin Panel</h1>
                <p class="text-slate-600 mt-1.5 text-base font-medium">CDC {{ config('app.ikip') }}</p>
            </div>

            {{-- Card Utama - Diubah jadi Putih Bersih --}}
            <div class="bg-white rounded-3xl shadow-xl shadow-slate-100 p-8 lg:p-10 border border-slate-100">
                
                {{-- Judul Form - Diubah jadi gelap --}}
                <div class="mb-10 text-center">
                    <h2 class="text-2xl font-bold text-slate-900 mb-2.5">Login Administrator</h2>
                    <p class="text-slate-600 text-sm leading-relaxed max-w-sm mx-auto">Halaman ini khusus untuk akun admin. Mahasiswa dan perusahaan tidak dapat masuk melalui halaman ini.</p>
                </div>

                {{-- Alert Errors - Disesuaikan warnanya agar kontras di bg putih --}}
                @if($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-4 rounded-xl flex items-start space-x-3">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        @foreach($errors->all() as $error)
                            <p class="text-sm font-semibold">{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Alert Success - Disesuaikan warnanya --}}
                @if(session('success'))
                <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-900 px-4 py-4 rounded-xl flex items-start space-x-3">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm font-semibold">{{ session('success') }}</p>
                </div>
                @endif

                <form method="POST" action="{{ route('login.admin.submit') }}" class="space-y-6" id="loginAdminForm">
                    @csrf

                    {{-- Input Email --}}
                    <div>
                        {{-- Label - Diubah jadi gelap --}}
                        <label for="email" class="block text-sm font-semibold text-slate-800 mb-2">
                            Email Admin
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                {{-- Icon warna disesuaikan --}}
                                <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                </svg>
                            </div>
                            {{-- Input Style - BG Putih, Border Halus, Teks Gelap --}}
                            <input type="email"
                                   id="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   required
                                   autofocus
                                   class="w-full pl-12 pr-4 py-3.5 bg-white border border-slate-200 text-slate-900 placeholder-slate-400 rounded-xl focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition-all duration-200 @error('email') border-red-400 ring-2 ring-red-100 @enderror"
                                   placeholder="admin@example.com">
                        </div>
                        @error('email')
                            <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Input Password --}}
                    <div>
                        <label for="password" class="block text-sm font-semibold text-slate-800 mb-2">
                            Password
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            {{-- Input Style - Sama dengan email --}}
                            <input type="password"
                                   id="password"
                                   name="password"
                                   required
                                   class="w-full pl-12 pr-12 py-3.5 bg-white border border-slate-200 text-slate-900 placeholder-slate-400 rounded-xl focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition-all duration-200 @error('password') border-red-400 ring-2 ring-red-100 @enderror"
                                   placeholder="••••••••">
                            {{-- Tombol Mata - Warna disesuaikan --}}
                            <button type="button"
                                    onclick="togglePassword('password', 'eye-icon-pass')"
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 transition-colors">
                                <svg id="eye-icon-pass" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Remember Me --}}
                    <div class="flex items-center justify-between">
                        <label class="flex items-center cursor-pointer group">
                            <input type="checkbox"
                                   name="remember"
                                   class="w-4 h-4 text-blue-600 bg-white border-slate-300 rounded focus:ring-2 focus:ring-blue-200 transition-all cursor-pointer">
                            <span class="ml-2.5 text-sm text-slate-600 group-hover:text-slate-900 transition-colors">Ingat saya</span>
                        </label>
                    </div>

                    {{-- reCAPTCHA Container --}}
                    <div class="flex justify-center mt-5">
                        <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.sitekey') }}"></div>
                    </div>
                    @error('g-recaptcha-response')
                        <p class="text-xs text-red-600 font-medium text-center">{{ $message }}</p>
                    @enderror

                    {{-- Tombol Submit - Diubah menjadi Biru agar menonjol di tema putih --}}
                    <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-4 px-4 rounded-xl shadow-blue-100 shadow-lg hover:shadow-blue-200 hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center space-x-2">
                        <span>Masuk sebagai Admin</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </button>
                </form>
            </div>

            {{-- Footer - Teks disesuaikan --}}
            <p class="text-center text-sm text-slate-500 mt-10 mb-4">
                &copy; {{ date('Y') }} {{ config('app.ikip') }}. All rights reserved.
            </p>
        </div>
    </div>

    <script>
        // FUNGSI TOGGLE PASSWORD (Sama seperti asli, hanya path icon disesuaikan sedikit jika perlu)
        function togglePassword(inputId, iconId) {
            const inputField = document.getElementById(inputId);
            const eyeIcon = document.getElementById(iconId);

            if (inputField.type === 'password') {
                inputField.type = 'text';
                // Icon Mata Dicoret (Show)
                eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>';
            } else {
                inputField.type = 'password';
                // Icon Mata Biasa (Hide)
                eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
            }
        }

        // AUTO-HIDE ALERT (Disesuaikan selector class-nya)
        setTimeout(() => {
            const alerts = document.querySelectorAll('[class*="bg-red-50"], [class*="bg-emerald-50"]');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s, transform 0.5s';
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-10px)';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    </script>
</body>
</html>