<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Perusahaan;
use App\Models\Fakultas;
use App\Models\Program_studi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
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
            'fakultas_id' => 'required|exists:fakultas,id',
            'program_studi_id' => 'required|exists:program_studis,id',
            'tahun_masuk' => 'required|integer|min:2000|max:' . (date('Y') + 1),
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

        Mahasiswa::create([
            'user_id' => $user->id,
            'nim' => $validated['nim'],
            'tempat_lahir' => $validated['tempat_lahir'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'agama' => $validated['agama'],
            'alamat' => $validated['alamat'],
            'no_telp' => $validated['no_telp'], 
            'fakultas_id' => $validated['fakultas_id'],
            'program_studi_id' => $validated['program_studi_id'],
            'tahun_masuk' => $validated['tahun_masuk'],
            'nama_ortu' => $validated['nama_ortu'],
            'pekerjaan_ortu' => $validated['pekerjaan_ortu'],
            'no_telp_ortu' => $validated['no_telp_ortu'],
            'status' => 'aktif',
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Registrasi mahasiswa berhasil! Selamat datang.');
    }

    // Proses register perusahaan
    public function registerPerusahaan(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'nama_perusahaan' => 'required|string|max:255',
            'bidang_usaha' => 'required|string|max:100',
            'alamat' => 'required|string',
            'kota' => 'required|string|max:100',
            'provinsi' => 'required|string|max:100',
            'kode_pos' => 'nullable|string|max:10',
            'no_telp' => 'required|string|max:15',
            'email_perusahaan' => 'required|email',
            'website' => 'nullable|url',
            'nama_pic' => 'required|string|max:255',
            'jabatan_pic' => 'required|string|max:100',
            'no_telp_pic' => 'required|string|max:15',
            'email_pic' => 'required|email',
            'deskripsi' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'perusahaan',
            'is_active' => true,
        ]);

        Perusahaan::create([
            'user_id' => $user->id,
            'nama_perusahaan' => $validated['nama_perusahaan'],
            'bidang_usaha' => $validated['bidang_usaha'],
            'alamat' => $validated['alamat'],
            'kota' => $validated['kota'],
            'provinsi' => $validated['provinsi'],
            'kode_pos' => $validated['kode_pos'],
            'no_telp' => $validated['no_telp'],
            'email' => $validated['email_perusahaan'],
            'website' => $validated['website'],
            'nama_pic' => $validated['nama_pic'],
            'jabatan_pic' => $validated['jabatan_pic'],
            'no_telp_pic' => $validated['no_telp_pic'],
            'email_pic' => $validated['email_pic'],
            'deskripsi' => $validated['deskripsi'],
            'status_kerjasama' => 'pending',
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Registrasi perusahaan berhasil! Akun Anda akan diverifikasi oleh admin.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Anda telah logout.');
    }
}