<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\Perusahaan;
use App\Models\Pkl;
use App\Models\LowonganKerja;
use App\Models\Pelatihan;
use App\Models\Lamaran;
use App\Models\Notifikasi;
use App\Models\KerjasamaIndustri;
use App\Models\KerjasamaPerusahaan;
use App\Models\TracerStudy;
use App\Models\TracerStudyMahasiswa;
use App\Models\Fakultas;
use App\Models\Program_studi;
use App\Models\User;
use App\Models\Otp;
use App\Notifications\SendOtpNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth; // <-- PASTIKAN BARIS INI ADA
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            return $this->adminDashboard();
        } elseif ($user->isMahasiswa()) {
            return $this->mahasiswaDashboard();
        } elseif ($user->isPerusahaan()) {
            return $this->perusahaanDashboard();
        }
    }

   private function adminDashboard()
{
    $data = [
        'total_mahasiswa' => Mahasiswa::where('status', 'aktif')->count(),
        'total_perusahaan' => Perusahaan::where('status_kerjasama', 'aktif')->count(),
        'pkl_berlangsung' => Pkl::where('status', 'berlangsung')->count(),
        'lowongan_aktif' => LowonganKerja::aktif()->count(),
        'pelatihan_mendatang' => Pelatihan::where('status', 'published')
                                             ->where('tanggal_mulai', '>', now())
                                             ->count(),
        'pkl_terbaru' => Pkl::with(['mahasiswa.user', 'perusahaan'])
                                ->latest()
                                ->take(5)
                                ->get(),
        'lowongan_terbaru' => LowonganKerja::with('perusahaan')
                                             ->aktif()
                                             ->latest()
                                             ->take(5)
                                             ->get(),
        'pelatihan_terbaru' => Pelatihan::published()
                                              ->latest()
                                              ->take(5)
                                              ->get(),
    ];

    return view('admin.dashboard', $data);
}

    private function mahasiswaDashboard()
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;

        // 1. Hitung jumlah lamaran kerja dengan status 'diproses' (pending)
        $lamaran_pending = Lamaran::where('mahasiswa_id', $mahasiswa->id)
            ->where('status', 'diproses')
            ->count();

        // 2. Hitung jumlah pelatihan yang sedang atau telah diikuti oleh mahasiswa
        $pelatihan_terdaftar = $mahasiswa->pelatihan()->count();

        // 3. Ambil 3 data lowongan pekerjaan terbaru yang aktif beserta info perusahaan
        $lowongan_terbaru = LowonganKerja::with('perusahaan')
            ->latest()
            ->take(3)
            ->get();

        // 4. Ambil 3 program pelatihan terbaru yang belum kadaluarsa
        $pelatihan_tersedia = Pelatihan::latest()
            ->take(3)
            ->get();

        // 5. Kirim seluruh data ke view dashboard mahasiswa
        return view('mahasiswa.dashboard', compact(
            'lamaran_pending',
            'pelatihan_terdaftar',
            'lowongan_terbaru',
            'pelatihan_tersedia'
        ));
    }

    private function perusahaanDashboard()
    {
        $perusahaan = auth()->user()->perusahaan;
        
        $data = [
            'lowongan_aktif' => LowonganKerja::where('perusahaan_id', $perusahaan->id)
                                            ->aktif()
                                            ->count(),
            'total_pelamar' => LowonganKerja::where('perusahaan_id', $perusahaan->id)
                                           ->sum('jumlah_pelamar'),
            'pkl_berlangsung' => Pkl::where('perusahaan_id', $perusahaan->id)
                                    ->where('status', 'berlangsung')
                                    ->count(),
            'lowongan_list' => LowonganKerja::where('perusahaan_id', $perusahaan->id)
                                           ->with('lamaran')
                                           ->latest()
                                           ->take(5)
                                           ->get(),
            'mahasiswa_pkl' => Pkl::where('perusahaan_id', $perusahaan->id)
                              ->with('mahasiswa.user')
                              ->latest()
                              ->take(5)
                              ->get(),
        ];

        return view('perusahaan.dashboard', $data);
    }
}