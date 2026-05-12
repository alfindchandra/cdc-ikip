<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP - CDC {{ config('app.ikip') }}</title>
    @vite(['resources/css/app.css'])
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
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
        .otp-input {
            width: 3rem;
            height: 3rem;
            text-align: center;
            font-size: 1.5rem;
            font-weight: bold;
        }
        .countdown {
            font-size: 0.875rem;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-white to-blue-50 min-h-screen flex items-center justify-center p-4 bg-pattern">
    <div class="w-full flex items-center justify-center">
        <!-- OTP Verification Card -->
        <div class="max-w-md w-full mx-auto">
            <!-- Logo & Header (Mobile) -->
            <div class="text-center mb-8 lg:hidden">
                <h1 class="text-2xl font-bold text-gray-900">Verifikasi Email</h1>
                <p class="text-gray-600 mt-1 text-sm">CDC IKIP PGRI Bojonegoro</p>
            </div>

            <!-- OTP Card -->
            <div class="bg-white rounded-3xl shadow-2xl p-8 lg:p-10 border border-gray-100">
                <div class="mb-8 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Verifikasi Email</h2>
                    <p class="text-gray-600 text-sm">Kami telah mengirim kode OTP ke:</p>
                    <p class="text-gray-900 font-semibold text-lg mt-1">{{ $email }}</p>
                </div>

                <!-- Success Message -->
                @if(session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-800 px-4 py-4 rounded-lg flex items-start space-x-3">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <p class="text-sm font-medium">{{ session('success') }}</p>
                    </div>
                </div>
                @endif

                <!-- Error Messages -->
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

                <!-- OTP Input Form -->
                <form action="{{ route('otp.verify') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label for="otp" class="block text-sm font-medium text-gray-700 mb-3">Masukkan Kode OTP</label>
                        <input 
                            type="text" 
                            name="otp" 
                            id="otp" 
                            placeholder="000000"
                            maxlength="6"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg text-center text-2xl font-bold tracking-widest focus:border-blue-500 focus:outline-none transition-colors @error('otp') border-red-500 @enderror"
                            inputmode="numeric"
                            pattern="[0-9]*"
                            value="{{ old('otp') }}"
                            required
                        >
                        <p class="text-gray-600 text-xs mt-2">Kode OTP terdiri dari 6 digit</p>
                    </div>

                    <!-- Timer -->
                    <div class="text-center">
                        <p class="text-sm text-gray-600 mb-2">Kode OTP berlaku selama:</p>
                        <div id="timer" class="inline-block">
                            <p class="text-2xl font-bold text-blue-600">10:00</p>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition-colors duration-200 flex items-center justify-center space-x-2"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Verifikasi</span>
                    </button>
                </form>

                <!-- Resend OTP -->
                <div class="mt-6 text-center border-t border-gray-200 pt-6">
                    <p class="text-gray-600 text-sm mb-3">Belum menerima kode OTP?</p>
                    <form action="{{ route('otp.resend') }}" method="POST" class="inline">
                        @csrf
                        <button 
                            type="submit" 
                            class="text-blue-600 hover:text-blue-700 font-semibold text-sm transition-colors duration-200 flex items-center justify-center space-x-1 mx-auto"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            <span>Kirim Ulang OTP</span>
                        </button>
                    </form>
                </div>

                <!-- Help Text -->
                <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                    <p class="text-gray-700 text-xs leading-relaxed">
                        <span class="font-semibold">Catatan:</span> Jika Anda tidak menerima kode OTP dalam beberapa saat, silakan cek folder spam/junked email. Pastikan juga email yang Anda daftarkan benar.
                    </p>
                </div>
            </div>

            <!-- Footer Links -->
            <div class="mt-6 text-center">
                <p class="text-gray-600 text-sm">
                    Kembali ke <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-700 font-semibold">registrasi</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        // Auto focus and format OTP input
        const otpInput = document.getElementById('otp');
        
        otpInput.addEventListener('input', function(e) {
            // Hanya terima angka
            this.value = this.value.replace(/[^0-9]/g, '');
            
            // Limit to 6 digits
            if (this.value.length > 6) {
                this.value = this.value.slice(0, 6);
            }
        });

        // Timer countdown
        function startTimer() {
            let timeLeft = 600; // 10 minutes in seconds
            const timerElement = document.getElementById('timer');
            
            const interval = setInterval(() => {
                const minutes = Math.floor(timeLeft / 60);
                const seconds = timeLeft % 60;
                
                timerElement.innerHTML = `<p class="text-2xl font-bold ${timeLeft <= 60 ? 'text-red-600' : 'text-blue-600'}">
                    ${minutes}:${seconds.toString().padStart(2, '0')}
                </p>`;
                
                if (timeLeft <= 0) {
                    clearInterval(interval);
                    timerElement.innerHTML = '<p class="text-2xl font-bold text-red-600">Kadaluarsa</p>';
                    otpInput.disabled = true;
                }
                
                timeLeft--;
            }, 1000);
        }

        // Start timer when page loads
        document.addEventListener('DOMContentLoaded', startTimer);
    </script>
</body>
</html>
