<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
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
        } elseif ($user->isSiswa()) {
            return $this->siswaDashboard();
        } elseif ($user->isPerusahaan()) {
            return $this->perusahaanDashboard();
        }
    }

    private function adminDashboard()
    {
        $data = [
            'total_siswa' => Siswa::where('status', 'aktif')->count(),
            'total_perusahaan' => Perusahaan::where('status_kerjasama', 'aktif')->count(),
            'pkl_berlangsung' => Pkl::where('status', 'berlangsung')->count(),
            'lowongan_aktif' => LowonganKerja::aktif()->count(),
            'pelatihan_mendatang' => Pelatihan::where('status', 'published')
                                             ->where('tanggal_mulai', '>', now())
                                             ->count(),
            'pkl_terbaru' => Pkl::with(['siswa.user', 'perusahaan'])
                                ->latest()
                                ->take(5)
                                ->get(),
            'lowongan_terbaru' => LowonganKerja::with('perusahaan')
                                               ->aktif()
                                               ->latest()
                                               ->take(5)
                                               ->get(),
        ];

        return view('admin.dashboard', $data);
    }

    private function siswaDashboard()
    {
        $siswa = auth()->user()->siswa;
        
        $data = [
            'pkl_aktif' => Pkl::where('siswa_id', $siswa->id)
                              ->whereIn('status', ['berlangsung', 'diterima'])
                              ->first(),
            'lamaran_pending' => $siswa->lamaran()
                                       ->whereIn('status', ['dikirim', 'dilihat', 'diproses'])
                                       ->count(),
            'pelatihan_terdaftar' => $siswa->pelatihan()
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

        return view('siswa.dashboard', $data);
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
            'siswa_pkl' => Pkl::where('perusahaan_id', $perusahaan->id)
                              ->with('siswa.user')
                              ->latest()
                              ->take(5)
                              ->get(),
        ];

        return view('perusahaan.dashboard', $data);
    }
}