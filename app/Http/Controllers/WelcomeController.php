<?php

namespace App\Http\Controllers;

use App\Models\LowonganKerja;
use App\Models\Perusahaan;
use App\Models\Pelatihan;
use Illuminate\Http\Request;
use App\Models\TracerStudy;
use Illuminate\Support\Facades\DB;

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
                $q->where('posisi', 'like', "%{$keyword}%")
                  ->orWhere('judul', 'like', "%{$keyword}%")
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
    public function tracerStudy()
   {
    // Statistik Umum
    $statistik = [
        'total_responden' => TracerStudy::count(),

        // Status pekerjaan mengikuti ENUM pada migrasi
        'bekerja' => TracerStudy::where('status_pekerjaan', 'bekerja')->count(),
        'kuliah' => TracerStudy::where('status_pekerjaan', 'melanjutkan_studi')->count(),
        'wirausaha' => TracerStudy::where('status_pekerjaan', 'wirausaha')->count(),
        'belum_bekerja' => TracerStudy::where('status_pekerjaan', 'belum_bekerja')->count(),

        // Rata-rata gaji (penghasilan)
        'rata_gaji' => TracerStudy::where('status_pekerjaan', 'bekerja')
                                 ->whereNotNull('penghasilan')
                                 ->avg(DB::raw('CAST(penghasilan AS SIGNED)')) ?? 0,

        // Rata-rata omzet (omzet_usaha)
        'rata_omzet' => TracerStudy::where('status_pekerjaan', 'wirausaha')
                                   ->whereNotNull('omzet_usaha')
                                   ->avg(DB::raw('CAST(omzet_usaha AS SIGNED)')) ?? 0,
    ];

    // Waktu Tunggu Kerja (dalam bulan)
    $statistik['tunggu_3'] = TracerStudy::where('status_pekerjaan', 'bekerja')
                                        ->where('waktu_tunggu_kerja', '<', 3)
                                        ->count();

    $statistik['tunggu_6'] = TracerStudy::where('status_pekerjaan', 'bekerja')
                                        ->whereBetween('waktu_tunggu_kerja', [3, 6])
                                        ->count();

    $statistik['tunggu_12'] = TracerStudy::where('status_pekerjaan', 'bekerja')
                                         ->whereBetween('waktu_tunggu_kerja', [6, 12])
                                         ->count();

    $statistik['tunggu_lebih'] = TracerStudy::where('status_pekerjaan', 'bekerja')
                                            ->where('waktu_tunggu_kerja', '>', 12)
                                            ->count();

    // Kesesuaian pekerjaan berdasarkan relevansi_pekerjaan
    $kesesuaianBidang = [
        [
            'label' => 'Sangat Relevan',
            'jumlah' => TracerStudy::where('relevansi_pekerjaan', 'sangat_relevan')->count(),
            'persentase' => 0,
            'color' => 'bg-success'
        ],
        [
            'label' => 'Relevan',
            'jumlah' => TracerStudy::where('relevansi_pekerjaan', 'relevan')->count(),
            'persentase' => 0,
            'color' => 'bg-info'
        ],
        [
            'label' => 'Cukup Relevan',
            'jumlah' => TracerStudy::where('relevansi_pekerjaan', 'cukup_relevan')->count(),
            'persentase' => 0,
            'color' => 'bg-warning'
        ],
        [
            'label' => 'Tidak Relevan',
            'jumlah' => TracerStudy::where('relevansi_pekerjaan', 'tidak_relevan')->count(),
            'persentase' => 0,
            'color' => 'bg-danger'
        ],
    ];

    // Hitung persentase kesesuaian
    $totalKesesuaian = array_sum(array_column($kesesuaianBidang, 'jumlah'));
    if ($totalKesesuaian > 0) {
        foreach ($kesesuaianBidang as &$item) {
            $item['persentase'] = round(($item['jumlah'] / $totalKesesuaian) * 100, 1);
        }
    }

    // Top 10 Perusahaan (field status_kerja DIPERBAIKI → status_pekerjaan)
    $topCompanies = TracerStudy::select(
            'nama_perusahaan', 
            'bidang_pekerjaan', 
            DB::raw('COUNT(*) as total_alumni')
        )
        ->where('status_pekerjaan', 'bekerja')
        ->whereNotNull('nama_perusahaan')
        ->groupBy('nama_perusahaan', 'bidang_pekerjaan')
        ->orderBy('total_alumni', 'desc')
        ->take(10)
        ->get();

    // Testimoni Alumni (kolom kepuasan_alumni TIDAK ADA → diganti kepuasan_pendidikan)
    $testimoni = TracerStudy::with(['siswa.user', 'siswa.programStudi'])
                            ->whereNotNull('kepuasan_pendidikan')
                            ->where('kepuasan_pendidikan', '>=', 4)
                            ->inRandomOrder()
                            ->take(6)
                            ->get();

    return view('index.tracer-study', compact(
        'statistik',
        'kesesuaianBidang',
        'topCompanies',
        'testimoni'
    ));
}

}