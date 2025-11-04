<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Perusahaan;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Check if user is active
            if (!Auth::user()->is_active) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akun Anda tidak aktif. Silakan hubungi administrator.',
                ]);
            }

            // Create notification for login
            Auth::user()->notifikasi()->create([
                'judul' => 'Login Berhasil',
                'pesan' => 'Anda telah login ke sistem CDC pada ' . now()->format('d F Y H:i'),
                'tipe' => 'success',
                'kategori' => 'sistem',
            ]);

            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    /**
     * Show register form
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle register request
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:siswa,perusahaan',
            'terms' => 'accepted',
        ]);

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create role-specific profile
        if ($validated['role'] === 'siswa') {
            // Create siswa profile with minimal data
            // Full data will be filled later in profile page
            Siswa::create([
                'user_id' => $user->id,
                'nis' => 'TEMP-' . $user->id, // Temporary NIS, should be updated by admin
                'jenis_kelamin' => 'L', // Default, should be updated
                'status' => 'aktif',
            ]);
        } elseif ($validated['role'] === 'perusahaan') {
            // Create perusahaan profile with minimal data
            Perusahaan::create([
                'user_id' => $user->id,
                'nama_perusahaan' => $validated['name'],
                'email' => $validated['email'],
                'status_kerjasama' => 'pending', // Needs admin approval
            ]);
        }

        // Create welcome notification
        $user->notifikasi()->create([
            'judul' => 'Selamat Datang!',
            'pesan' => 'Terima kasih telah mendaftar di CDC SMK Negeri 1 Baureno. Silakan lengkapi profil Anda.',
            'tipe' => 'info',
            'kategori' => 'sistem',
            'link' => route('profile'),
        ]);

        // Auto login after registration
        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Registrasi berhasil! Silakan lengkapi profil Anda.');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah logout.');
    }

    /**
     * Show profile page
     */
    public function profile()
    {
        $user = Auth::user();

        if ($user->isSiswa()) {
            return view('profile.siswa', compact('user'));
        } elseif ($user->isPerusahaan()) {
            return view('profile.perusahaan', compact('user'));
        } else {
            return view('profile.admin', compact('user'));
        }
    }

    /**
     * Update profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // Common validation
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:6|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Update user data
        $user->name = $validated['name'];
        $user->email = $validated['email'];

        // Update password if provided
        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
            }

            $user->password = Hash::make($request->new_password);
        }

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }

        $user->save();

        // Update role-specific data
        if ($user->isSiswa()) {
            $this->updateSiswaProfile($request, $user->siswa);
        } elseif ($user->isPerusahaan()) {
            $this->updatePerusahaanProfile($request, $user->perusahaan);
        }

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Update siswa profile
     */
    private function updateSiswaProfile(Request $request, $siswa)
    {
        $validated = $request->validate([
            'nis' => 'required|unique:siswa,nis,' . $siswa->id,
            'nisn' => 'nullable|unique:siswa,nisn,' . $siswa->id,
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'required|in:L,P',
            'agama' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'no_telp' => 'nullable|string|max:15',
            'kelas' => 'nullable|string|max:10',
            'jurusan' => 'nullable|string|max:50',
            'tahun_masuk' => 'nullable|integer|min:2000|max:' . date('Y'),
            'nama_ortu' => 'nullable|string|max:255',
            'pekerjaan_ortu' => 'nullable|string|max:100',
            'no_telp_ortu' => 'nullable|string|max:15',
        ]);

        $siswa->update($validated);
    }

    /**
     * Update perusahaan profile
     */
    private function updatePerusahaanProfile(Request $request, $perusahaan)
    {
        $validated = $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'bidang_usaha' => 'nullable|string|max:100',
            'alamat' => 'nullable|string',
            'kota' => 'nullable|string|max:100',
            'provinsi' => 'nullable|string|max:100',
            'kode_pos' => 'nullable|string|max:10',
            'no_telp' => 'nullable|string|max:15',
            'website' => 'nullable|url|max:255',
            'nama_pic' => 'nullable|string|max:255',
            'jabatan_pic' => 'nullable|string|max:100',
            'no_telp_pic' => 'nullable|string|max:15',
            'email_pic' => 'nullable|email|max:255',
            'deskripsi' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($perusahaan->logo) {
                Storage::disk('public')->delete($perusahaan->logo);
            }

            $logoPath = $request->file('logo')->store('logos', 'public');
            $validated['logo'] = $logoPath;
        }

        // Update email perusahaan
        $validated['email'] = $request->email;

        $perusahaan->update($validated);
    }

    /**
     * Delete account
     */
    public function deleteAccount(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Password tidak sesuai.']);
        }

        // Delete avatar
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Logout
        Auth::logout();

        // Delete user (will cascade delete related data)
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Akun Anda telah dihapus.');
    }

    /**
     * Show email verification notice
     */
    public function verificationNotice()
    {
        return view('auth.verify-email');
    }

    /**
     * Resend email verification link
     */
    public function resendVerification(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('success', 'Link verifikasi telah dikirim ke email Anda!');
    }
}