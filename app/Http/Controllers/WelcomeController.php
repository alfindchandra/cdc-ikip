<?php

namespace App\Http\Controllers;

use App\Models\LowonganKerja;
use App\Models\Perusahaan;
use App\Models\Pelatihan;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // Query builder untuk lowongan kerja
        $query = LowonganKerja::with('perusahaan')
            ->where('status', 'aktif')
            ->where('tanggal_berakhir', '>=', now());

        // Filter berdasarkan keyword
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('judul', 'like', "%{$keyword}%")
                  ->orWhere('deskripsi', 'like', "%{$keyword}%")
                  ->orWhereHas('perusahaan', function($q) use ($keyword) {
                      $q->where('nama_perusahaan', 'like', "%{$keyword}%");
                  });
            });
        }

        // Filter berdasarkan lokasi
        if ($request->filled('lokasi')) {
            $query->where('lokasi', $request->lokasi);
        }

        // Filter berdasarkan tipe pekerjaan
        if ($request->filled('tipe')) {
            $query->where('tipe_pekerjaan', $request->tipe);
        }

        // Ambil hasil dengan pagination atau limit
        $lowonganTerbaru = $query->latest()
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
    public function lowongan(Request $request)
    {
        $query = LowonganKerja::with('perusahaan')
            ->where('status', 'aktif')
            ->where('tanggal_berakhir', '>=', now());

        // Filter berdasarkan keyword
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('judul', 'like', "%{$keyword}%")
                  ->orWhere('deskripsi', 'like', "%{$keyword}%")
                  ->orWhereHas('perusahaan', function($q) use ($keyword) {
                      $q->where('nama_perusahaan', 'like', "%{$keyword}%");
                  });
            });
        }
         $lowonganTerbaru = $query->latest()
            ->take(8)
            ->get();
        return view('index.lowongan', compact('lowonganTerbaru'));

    }
    public function lowonganShow(LowonganKerja $lowongan)
    {
        return view('index.lowonganshow', compact('lowongan'));
    }
}