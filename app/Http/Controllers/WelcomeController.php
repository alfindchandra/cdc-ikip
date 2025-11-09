<?php

namespace App\Http\Controllers;

use App\Models\LowonganKerja;

use App\Models\Perusahaan;
use App\Models\Pelatihan;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
{
    $user = auth()->user();

    // Ambil lowongan kerja aktif terbatas
    $lowonganTerbaru = LowonganKerja::with('perusahaan')
        ->where('status', 'aktif')
        ->where('tanggal_berakhir', '>=', now())
        ->latest()
        ->take(8)
        ->get();

    // Ambil perusahaan dengan status kerjasama aktif
    $perusahaanMitra = Perusahaan::where('status_kerjasama', 'aktif')
        ->latest()
        ->take(12)
        ->get();

    // Ambil pelatihan yang published
    $pelatihanTerbaru = Pelatihan::where('status', 'published')
        ->where('tanggal_mulai', '>=', now())
        ->latest()
        ->take(3)
        ->get();

    // Statistik
    $statistik = [
        'total_lowongan' => LowonganKerja::where('status', 'aktif')->count(),
        'total_perusahaan' => Perusahaan::where('status_kerjasama', 'aktif')->count(),
        'total_siswa_pkl' => \App\Models\Pkl::where('status', 'berlangsung')->count(),
        'total_alumni' => \App\Models\Siswa::where('status', 'lulus')->count(),
    ];

    // Kirim semua data ke view
    return view('welcome', compact(
        'user',
        'lowonganTerbaru',
        'perusahaanMitra',
        'pelatihanTerbaru',
        'statistik'
    ));
}

}