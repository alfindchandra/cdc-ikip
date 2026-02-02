<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\Perusahaan;
use App\Models\Pkl;
use App\Models\LowonganKerja;
use App\Models\Pelatihan;

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
        $mahasiswa = auth()->user()->mahasiswa;
        
        $data = [
            'pkl_aktif' => Pkl::where('mahasiswa_id', $mahasiswa->id)
                              ->whereIn('status', ['berlangsung', 'diterima'])
                              ->first(),
            'lamaran_pending' => $mahasiswa->lamaran()
                                       ->whereIn('status', ['dikirim', 'dilihat', 'diproses'])
                                       ->count(),
            'pelatihan_terdaftar' => $mahasiswa->pelatihan()
                                           ->wherePivot('status_pendaftaran', 'diterima')
                                           ->count(),
            'lowongan_terbaru' => LowonganKerja::with('perusahaan')
                                               ->aktif()
                                               ->latest()
                                               ->take(6)
                                               ->get(),
            'pelatihan_tersedia' => Pelatihan::published()
                                             ->where('tanggal_mulai', '>', now())
                                             ->take(4)
                                             ->get(),
        ];

        return view('mahasiswa.dashboard', $data);
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