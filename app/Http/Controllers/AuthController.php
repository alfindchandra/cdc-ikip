<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Otp;
use App\Models\Mahasiswa;
use App\Models\Perusahaan;
use App\Models\Fakultas;
use App\Models\Program_studi;
use App\Notifications\SendOtpNotification;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $loginType = $request->input('login_type');

        $rules = [
            'login_type' => 'required|in:aktif,umum',
            'g-recaptcha-response' => 'required',
        ];

        if ($loginType === 'aktif') {
            $rules['nim'] = 'required|string';
            $rules['tanggal_lahir'] = 'required|date_format:d/m/Y'; 
        } else {
            $rules['email'] = 'required|email';
            $rules['password'] = 'required';
        }

        $request->validate($rules, [
            'login_type.required' => 'Silakan pilih jenis akun (Mahasiswa Aktif / Alumni & Perusahaan).',
            'login_type.in' => 'Jenis akun yang dipilih tidak valid.',
            'nim.required' => 'NIM wajib diisi.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.date_format' => 'Format tanggal lahir harus DD/MM/YYYY (contoh: 17/08/2000).',
            'email.required' => 'Email wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'g-recaptcha-response.required' => 'Silakan verifikasi bahwa Anda bukan robot.',
        ]);

        // Validasi reCAPTCHA
        $recaptchaResponse = $request->input('g-recaptcha-response');
        $recaptchaSecret = config('services.recaptcha.secret');

        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $recaptchaSecret,
            'response' => $recaptchaResponse,
            'remoteip' => $request->ip()
        ]);

        $recaptchaResult = $response->json();

        if (!$recaptchaResult['success']) {
            return back()->withErrors([
                'g-recaptcha-response' => 'Verifikasi reCAPTCHA gagal. Silakan coba lagi.',
            ])->onlyInput('email', 'nim', 'tanggal_lahir', 'login_type');
        }

        if ($loginType === 'aktif') {
            $mahasiswa = Mahasiswa::where('nim', $request->input('nim'))->first();

            if (!$mahasiswa) {
                return back()->withErrors([
                    'nim' => 'NIM tidak ditemukan.',
                ])->onlyInput('nim', 'login_type');
            }

            if (!$mahasiswa->user) {
                return back()->withErrors([
                    'nim' => 'Akun pengguna untuk NIM ini belum terdaftar.',
                ])->onlyInput('nim', 'login_type');
            }

            if (($mahasiswa->status ?? null) !== 'aktif') {
                return back()->withErrors([
                    'nim' => 'Akun ini bukan mahasiswa aktif. Silakan gunakan tab "Alumni/Perusahaan" untuk masuk.',
                ])->onlyInput('nim', 'login_type');
            }

            try {
                $tanggalLahirInput = \Carbon\Carbon::createFromFormat('d/m/Y', $request->input('tanggal_lahir'))->format('Y-m-d');
            } catch (\Exception $e) {
                return back()->withErrors([
                    'tanggal_lahir' => 'Format tanggal lahir tidak dikenali.',
                ])->onlyInput('nim', 'login_type');
            }

            $tanggalLahirDb = $mahasiswa->tanggal_lahir ? \Carbon\Carbon::parse($mahasiswa->tanggal_lahir)->format('Y-m-d') : null;

            if (!$tanggalLahirDb || $tanggalLahirDb !== $tanggalLahirInput) {
                return back()->withErrors([
                    'tanggal_lahir' => 'NIM atau tanggal lahir salah.',
                ])->onlyInput('nim', 'login_type');
            }

            if (!$mahasiswa->user->is_active) {
                return back()->withErrors([
                    'nim' => 'Akun Anda tidak aktif. Silakan hubungi admin.',
                ])->onlyInput('nim', 'login_type');
            }

            Auth::login($mahasiswa->user, $request->filled('remember'));
            $request->session()->regenerate();

            return redirect()->intended('dashboard');
        }

        if (!Auth::attempt(['email' => $request->input('email'), 'password' => $request->password], $request->filled('remember'))) {
            return back()->withErrors([
                'email' => 'Email atau password salah.',
            ])->onlyInput('email', 'login_type');
        }

        $user = Auth::user();

        if ($user->isMahasiswa()) {
            $mahasiswa = $user->mahasiswa;
            $statusAktual = $mahasiswa->status ?? null;

            if ($statusAktual !== 'lulus') {
                Auth::logout();

                return back()->withErrors([
                    'email' => 'Akun mahasiswa aktif harus login melalui tab "Mahasiswa Aktif" menggunakan NIM dan tanggal lahir.',
                ])->onlyInput('email', 'login_type');
            }
        }

        if (!$user->is_active) {
            Auth::logout();
            return back()->withErrors([
                'email' => 'Akun Anda dinonaktifkan. Silakan hubungi admin.',
            ])->onlyInput('email', 'login_type');
        }

        $request->session()->regenerate();

        if ($user->isMahasiswa()) {
            return redirect()->intended(route('mahasiswa.tracer-study.form'));
        }

        return redirect()->intended('dashboard');
    }

   public function showRegisterChoice()
{
    // Perbaikan: gunakan tanda titik (.) atau slash (/) untuk memisahkan folder
    return view('auth.register-choice');
}

    public function showRegisterMahasiswa()
    {
        return view('auth.register-mahasiswa');
    }

    public function showRegisterPerusahaan()
    {
        return view('auth.register-perusahaan');
    }

    // ============================================================
    // PERBAIKAN PROSES REGISTER MAHASISWA / SISWA (DINAMIS)
    // ============================================================
    public function registerMahasiswa(Request $request)
    {
        $tingkat = $request->input('tingkat_pendidikan');
        $isSekolahDasar = in_array($tingkat, ['SD', 'SMP']);
        $isMenengahAtas = in_array($tingkat, ['SMA', 'SMK']);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'nim' => 'required|string|max:20|unique:mahasiswa,nim',
            'tingkat_pendidikan' => 'required|in:SD,SMP,SMA,SMK,D1,D2,D3,S1,S2,S3',
            
            // Asal sekolah wajib untuk semua kategori sekolah (SD, SMP, SMA, SMK)
            'asal_sekolah' => ($isSekolahDasar || $isMenengahAtas) ? 'required|string|max:200' : 'nullable|string|max:200',
            
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'agama' => 'required|string|max:20',
            'alamat' => 'required|string',
            'no_telp' => 'required|string|max:15',
            
            // Fakultas hanya wajib untuk pendidikan tinggi (D1-S3)
            'fakultas' => (!$isSekolahDasar && !$isMenengahAtas) ? 'required|string|max:150' : 'nullable|string|max:150',
            
            // Program studi / Jurusan wajib untuk Kuliah atau SMA/SMK
            'program_studi' => ($isMenengahAtas || (!$isSekolahDasar && !$isMenengahAtas)) ? 'required|string|max:200' : 'nullable|string|max:200',
            
            'tahun_masuk' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'status' => 'required|in:aktif,lulus',
            'tahun_lulus' => 'required_if:status,lulus|nullable|integer|min:2000|max:' . (date('Y') + 1),
            'nama_ortu' => 'required|string|max:255',
            'pekerjaan_ortu' => 'nullable|string|max:100',
            'no_telp_ortu' => 'nullable|string|max:15',
        ]);
 
        // 1. Buat User Baru
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'mahasiswa',
            'is_active' => true,
        ]);
 
        // 2. Simpan profil mahasiswa menggunakan struktur string bebas mentah (bukan ID relasi)
        Mahasiswa::create([
            'user_id' => $user->id,
            'nim' => $validated['nim'],
            'tingkat_pendidikan' => $validated['tingkat_pendidikan'],
            'asal_sekolah' => $validated['asal_sekolah'],
            'tempat_lahir' => $validated['tempat_lahir'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'agama' => $validated['agama'],
            'alamat' => $validated['alamat'],
            'no_telp' => $validated['no_telp'], 
            
            // Logika Penyimpanan String: set null jika memang tipenya sekolah SD/SMP/SMA/SMK
            'fakultas_id' => (!$isSekolahDasar && !$isMenengahAtas) ? trim($validated['fakultas']) : null,
            'program_studi_id' => ($isMenengahAtas || (!$isSekolahDasar && !$isMenengahAtas)) ? trim($validated['program_studi']) : null,
            
            'tahun_masuk' => $validated['tahun_masuk'],
            'nama_ortu' => $validated['nama_ortu'],
            'pekerjaan_ortu' => $validated['pekerjaan_ortu'],
            'no_telp_ortu' => $validated['no_telp_ortu'],
            'status' => $validated['status'],
            'tahun_lulus' => $validated['status'] === 'lulus' ? $validated['tahun_lulus'] : null,
        ]);
 
        // Generate dan kirim OTP
        $this->sendOtpToUser($user);
 
        // Store email untuk proses verifikasi
        session(['otp_email' => $user->email, 'otp_user_id' => $user->id]);
 
        return redirect()->route('otp.verify.show')->with('success', 'Registrasi berhasil! Silakan verifikasi email Anda dengan kode OTP yang telah dikirim.');
    }

    public function registerPerusahaan(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'bidang_usaha' => 'required|string|max:100',
            'jenis_pt' => 'required|string|max:100',
            'alamat' => 'required|string',
            'kota' => 'required|string|max:100',
            'provinsi' => 'required|string|max:100',
            'kode_pos' => 'nullable|string|max:10',
            'no_telp' => 'required|string|max:15',
            'no_hp' => 'nullable|string|max:15',
            'website' => 'nullable|url',
            'nama_pimpinan' => 'required|string|max:255',
            'tahun_berdiri' => 'nullable|integer|min:1900|max:' . (date('Y')),
            'jumlah_karyawan' => 'nullable|integer|min:0',
            'visi' => 'nullable|string',
            'misi' => 'nullable|string',
            'deskripsi' => 'nullable|string',
            'cv_perusahaan' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'perusahaan',
            'is_active' => true,
        ]);

        $cvPath = null;
        if ($request->hasFile('cv_perusahaan')) {
            $cvPath = $request->file('cv_perusahaan')->store('perusahaan/cv', 'public');
        }

        Perusahaan::create([
            'user_id' => $user->id,
            'bidang_usaha' => $validated['bidang_usaha'],
            'jenis_pt' => $validated['jenis_pt'],
            'alamat' => $validated['alamat'],
            'kota' => $validated['kota'],
            'provinsi' => $validated['provinsi'],
            'kode_pos' => $validated['kode_pos'],
            'no_telp' => $validated['no_telp'],
            'no_hp' => $validated['no_hp'] ?? null,
            'website' => $validated['website'],
            'nama_pimpinan' => $validated['nama_pimpinan'],
            'tahun_berdiri' => $validated['tahun_berdiri'] ?? null,
            'jumlah_karyawan' => $validated['jumlah_karyawan'] ?? null,
            'visi' => $validated['visi'] ?? null,
            'misi' => $validated['misi'] ?? null,
            'cv_perusahaan' => $cvPath,
            'deskripsi' => $validated['deskripsi'],
            'status_kerjasama' => 'pending',
        ]);

        $this->sendOtpToUser($user);

        session(['otp_email' => $user->email, 'otp_user_id' => $user->id]);

        return redirect()->route('otp.verify.show')->with('success', 'Registrasi berhasil! Silakan verifikasi email Anda dengan kode OTP yang telah dikirim.');
    }

    private function sendOtpToUser(User $user)
    {
        try {
            $otpCode = Otp::generateOtp();

            $user->otps()->where('is_verified', false)->delete();

            $user->otps()->create([
                'otp' => $otpCode,
                'expires_at' => now()->addMinutes(10),
            ]);

            $user->notify(new SendOtpNotification($otpCode));
        } catch (\Exception $e) {
            Log::error('Failed to send OTP: ' . $e->getMessage());
        }
    }

    public function showOtpVerify()
    {
        $email = session('otp_email');
        
        if (!$email) {
            return redirect()->route('register')->with('error', 'Silakan registrasi terlebih dahulu');
        }

        return view('auth.otp-verify', compact('email'));
    }

    public function verifyOtp(Request $request)
    {
        $validated = $request->validate([
            'otp' => 'required|string|size:6',
        ], [
            'otp.required' => 'Kode OTP wajib diisi',
            'otp.size' => 'Kode OTP harus 6 digit',
        ]);

        $email = session('otp_email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('register')->with('error', 'User tidak ditemukan');
        }

        $otp = $user->otps()
            ->where('is_verified', false)
            ->latest()
            ->first();

        if (!$otp) {
            return back()->withErrors(['otp' => 'OTP tidak ditemukan atau sudah kadaluarsa']);
        }

        if ($otp->isExpired()) {
            return back()->withErrors(['otp' => 'Kode OTP telah kadaluarsa. Silakan minta kode baru.']);
        }

        if ($otp->otp !== $validated['otp']) {
            $otp->incrementAttempt();
            
            if ($otp->attempts >= 3) {
                return back()->withErrors(['otp' => 'Jumlah percobaan melebihi batas. Silakan minta kode OTP baru.']);
            }

            $remaining = 3 - $otp->attempts;
            return back()->withErrors(['otp' => "Kode OTP salah. Sisa percobaan: {$remaining}"]);
        }

        $otp->verify();
        $user->update(['email_verified_at' => now()]);

        Auth::login($user);

        session()->forget(['otp_email', 'otp_user_id']);

        return redirect()->route('dashboard')->with('success', 'Email berhasil diverifikasi! Selamat datang.');
    }

    public function resendOtp(Request $request)
    {
        $email = session('otp_email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('register')->with('error', 'User tidak ditemukan');
        }

        $this->sendOtpToUser($user);

        return back()->with('success', 'Kode OTP baru telah dikirim ke email Anda');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Anda telah logout.');
    }
}