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

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $loginType = $request->input('login_type');

        // Validasi berbeda tergantung tab yang dipilih:
        // - Mahasiswa Aktif -> login pakai email
        // - Alumni (Lulus)  -> login pakai NIM
        $rules = [
            'password' => 'required',
            'login_type' => 'required|in:aktif,lulus',
            'g-recaptcha-response' => 'required',
        ];

        if ($loginType === 'lulus') {
            $rules['nim'] = 'required|string';
        } else {
            $rules['email'] = 'required|email';
        }

        $request->validate($rules, [
            'login_type.required' => 'Silakan pilih jenis akun (Mahasiswa Aktif / Alumni).',
            'login_type.in' => 'Jenis akun yang dipilih tidak valid.',
            'nim.required' => 'NIM wajib diisi.',
            'email.required' => 'Email wajib diisi.',
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
            ])->onlyInput('email', 'nim', 'login_type');
        }

        // Tentukan email yang dipakai untuk otentikasi.
        // Untuk tab alumni, cari email lewat NIM terlebih dahulu.
        if ($loginType === 'lulus') {
            $mahasiswaByNim = Mahasiswa::where('nim', $request->input('nim'))->first();

            if (!$mahasiswaByNim || !$mahasiswaByNim->user) {
                return back()->withErrors([
                    'nim' => 'NIM tidak ditemukan.',
                ])->onlyInput('nim', 'login_type');
            }

            $emailUntukLogin = $mahasiswaByNim->user->email;
        } else {
            $emailUntukLogin = $request->input('email');
        }

        // Proses login
        if (!Auth::attempt(['email' => $emailUntukLogin, 'password' => $request->password], $request->filled('remember'))) {
            $pesanGagal = $loginType === 'lulus' ? 'NIM atau password salah.' : 'Email atau password salah.';
            $fieldGagal = $loginType === 'lulus' ? 'nim' : 'email';

            return back()->withErrors([
                $fieldGagal => $pesanGagal,
            ])->onlyInput('email', 'nim', 'login_type');
        }

        $user = Auth::user();

        // Jika yang login adalah mahasiswa, pastikan status akun (aktif/lulus)
        // sesuai dengan tab yang dipilih di halaman login.
        if ($user->isMahasiswa()) {
            $mahasiswa = $user->mahasiswa;
            $statusAktual = $mahasiswa->status ?? null;

            $cocok = ($loginType === 'aktif' && $statusAktual === 'aktif')
                  || ($loginType === 'lulus' && $statusAktual === 'lulus');

            if (!$cocok) {
                Auth::logout();

                $pesan = $loginType === 'aktif'
                    ? 'Akun ini bukan akun mahasiswa aktif. Silakan pilih tab "Alumni (Lulus)" untuk masuk.'
                    : 'Akun ini bukan akun alumni. Silakan pilih tab "Mahasiswa Aktif" untuk masuk.';

                $fieldGagal = $loginType === 'lulus' ? 'nim' : 'email';

                return back()->withErrors([
                    $fieldGagal => $pesan,
                ])->onlyInput('email', 'nim', 'login_type');
            }
        }

        $request->session()->regenerate();

        // Alumni diarahkan langsung ke halaman/menu tracer study
        if ($user->isMahasiswa() && $loginType === 'lulus') {
            return redirect()->intended(route('mahasiswa.tracer-study.form'));
        }

        return redirect()->intended('dashboard');
    }

    // Tampilkan halaman pilihan role
    public function showRegisterChoice()
    {
        return view('auth.register-choice');
    }

    // Tampilkan form register mahasiswa
    public function showRegisterMahasiswa()
    {
        $fakultas = Fakultas::orderBy('nama')->get();
        $programStudis = Program_studi::orderBy('nama')->get();
        return view('auth.register-mahasiswa', compact('fakultas', 'programStudis'));
    }

    // Tampilkan form register perusahaan
    public function showRegisterPerusahaan()
    {
        return view('auth.register-perusahaan');
    }

    // Proses register mahasiswa
       public function registerMahasiswa(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'nim' => 'required|string|max:20|unique:mahasiswa,nim',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'agama' => 'required|string|max:20',
            'alamat' => 'required|string',
            'no_telp' => 'required|string|max:15',
            'fakultas_nama' => 'required|string|max:150',
            'program_studi_nama' => 'required|string|max:200',
            'tahun_masuk' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'status' => 'required|in:aktif,lulus',
            'tahun_lulus' => 'required_if:status,lulus|nullable|integer|min:2000|max:' . (date('Y') + 1),
            'nama_ortu' => 'required|string|max:255',
            'pekerjaan_ortu' => 'nullable|string|max:100',
            'no_telp_ortu' => 'nullable|string|max:15',
        ]);
 
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'mahasiswa',
            'is_active' => true,
        ]);
 
        // Cari fakultas berdasarkan nama (tanpa memandang huruf besar/kecil),
        // buat baru jika belum ada di database.
        $fakultas = Fakultas::whereRaw('LOWER(nama) = ?', [strtolower(trim($validated['fakultas_nama']))])
            ->first();
        if (!$fakultas) {
            $fakultas = Fakultas::create([
                'nama' => trim($validated['fakultas_nama']),
            ]);
        }
 
        // Cari program studi berdasarkan nama (kolom nama unique secara global di tabel program_studis),
        // buat baru di bawah fakultas yang diketik jika belum ada di database.
        $programStudi = Program_studi::whereRaw('LOWER(nama) = ?', [strtolower(trim($validated['program_studi_nama']))])
            ->first();
        if (!$programStudi) {
            $programStudi = Program_studi::create([
                'fakultas_id' => $fakultas->id,
                'nama' => trim($validated['program_studi_nama']),
            ]);
        }
 
        Mahasiswa::create([
            'user_id' => $user->id,
            'nim' => $validated['nim'],
            'tempat_lahir' => $validated['tempat_lahir'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'agama' => $validated['agama'],
            'alamat' => $validated['alamat'],
            'no_telp' => $validated['no_telp'], 
            'fakultas_id' => $fakultas->id,
            'program_studi_id' => $programStudi->id,
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

    // Proses register perusahaan
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

        // Generate dan kirim OTP
        $this->sendOtpToUser($user);

        // Store email untuk proses verifikasi
        session(['otp_email' => $user->email, 'otp_user_id' => $user->id]);

        return redirect()->route('otp.verify.show')->with('success', 'Registrasi berhasil! Silakan verifikasi email Anda dengan kode OTP yang telah dikirim.');
    }

    /**
     * Kirim OTP ke user
     */
    private function sendOtpToUser(User $user)
    {
        try {
            // Generate OTP
            $otpCode = Otp::generateOtp();

            // Hapus OTP lama yang belum terverifikasi
            $user->otps()->where('is_verified', false)->delete();

            // Buat OTP baru
            $user->otps()->create([
                'otp' => $otpCode,
                'expires_at' => now()->addMinutes(10),
            ]);

            // Kirim notifikasi OTP
            $user->notify(new SendOtpNotification($otpCode));
        } catch (\Exception $e) {
            // Log error tapi jangan stop proses
            \Log::error('Failed to send OTP: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan form verifikasi OTP
     */
    public function showOtpVerify()
    {
        $email = session('otp_email');
        
        if (!$email) {
            return redirect()->route('register')->with('error', 'Silakan registrasi terlebih dahulu');
        }

        return view('auth.otp-verify', compact('email'));
    }

    /**
     * Verifikasi OTP
     */
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

        // Ambil OTP terbaru user yang belum terverifikasi
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

        // Verifikasi OTP dan email user
        $otp->verify();
        $user->update(['email_verified_at' => now()]);

        // Login user
        Auth::login($user);

        // Clear session
        session()->forget(['otp_email', 'otp_user_id']);

        return redirect()->route('dashboard')->with('success', 'Email berhasil diverifikasi! Selamat datang.');
    }

    /**
     * Kirim ulang OTP
     */
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