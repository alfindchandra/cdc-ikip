<?php

// app/Http/Controllers/AuthController.php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Siswa;
use App\Models\Perusahaan;
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

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:siswa,perusahaan',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'is_active' => true,
        ]);

        // Create profile based on role
        if ($validated['role'] === 'siswa') {
            Siswa::create([
                'user_id' => $user->id,
                'nis' => 'AUTO-' . time(),
                'jenis_kelamin' => 'L',
                'status' => 'aktif',
            ]);
        } elseif ($validated['role'] === 'perusahaan') {
            Perusahaan::create([
                'user_id' => $user->id,
                'nama_perusahaan' => $validated['name'],
                'status_kerjasama' => 'pending',
            ]);
        }

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Registrasi berhasil! Selamat datang.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Anda telah logout.');
    }

}
