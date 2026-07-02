<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LowonganKerja;
use App\Models\Perusahaan;
use App\Models\Pelatihan;

class HomeController extends Controller
{
public function lowongan(Request $request)
{
    // Menggunakan scopeAktif() dari Model LowonganKerja
    $query = LowonganKerja::with(['perusahaan.user'])->aktif();

    // Filter berdasarkan keyword
    if ($request->filled('keyword')) {
        $keyword = $request->keyword;
        $query->where(function($q) use ($keyword) {
            $q->where('posisi', 'like', "%{$keyword}%")
              ->orWhere('deskripsi', 'like', "%{$keyword}%")
              ->orWhereHas('perusahaan', function($q) use ($keyword) {
                  $q->where('nama_perusahaan', 'like', "%{$keyword}%");
              });
        });
    }

    
        if ($request->filled('lokasi')) {
            $query->where('lokasi', 'like', "%{$request->lokasi}%");
        }

    // Filter berdasarkan tipe pekerjaan
    if ($request->filled('tipe')) {
        $query->where('tipe_pekerjaan', $request->tipe);
    }

    // Mengambil 8 data terbaru
    $lowonganTerbaru = $query->latest()->take(8)->get();

    return view('home.lowongan.index', compact('lowonganTerbaru'));
}
    public function lowonganShow(LowonganKerja $lowongan)
    {
        return view('home.lowongan.show', compact('lowongan'));
    }
    public function perusahaan(Request $request)
    {
        $query = Perusahaan::with('user');

        // Filter berdasarkan keyword
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('nama_perusahaan', 'like', "%{$keyword}%")
                  ->orWhere('deskripsi', 'like', "%{$keyword}%");
            });
        }

        // Mengambil 8 data terbaru
        $perusahaanTerbaru = $query->latest()->take(8)->get();

        return view('home.perusahaan.index', compact('perusahaanTerbaru'));
    }
        public function showPerusahaan($id)
    {
        // Mengambil data perusahaan berdasarkan ID beserta relasi user (avatar) dan lowongan kerja yang aktif
        $perusahaan = Perusahaan::with(['user', 'lowonganKerja' => function($query) {
            $query->latest(); // Mengurutkan lowongan dari yang terbaru
        }])->findOrFail($id);

        return view('home.perusahaan.show', compact('perusahaan'));
    }
    public function pelatihan(Request $request)
    {
        // Menggunakan scopePublished() dari Model untuk memfilter data yang statusnya 'published'
        $query = Pelatihan::published();

        // Filter berdasarkan kata kunci (Judul, Deskripsi, atau Instruktur)
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('judul', 'like', "%{$keyword}%")
                  ->orWhere('deskripsi', 'like', "%{$keyword}%")
                  ->orWhere('instruktur', 'like', "%{$keyword}%");
            });
        }

        // Filter berdasarkan tipe/jenis pelatihan (workshop, sertifikasi, reguler)
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        // Mengambil data terbaru dengan pagination (misal 8 data per halaman)
        // Jika tidak ingin pagination, Anda bisa menggantinya dengan ->get()
        $pelatihans = $query->latest()->get();

        return view('home.pelatihan.index', compact('pelatihans'));
    }
    public function showPelatihan($id)
    {
        // Cari data pelatihan berdasarkan ID, jika tidak ada tampilkan 404
        $kelas = Pelatihan::findOrFail($id);

        // Opsional: Jika kolom tanggal belum otomatis dicasting sebagai Carbon instans di Model,
        // pastikan di Model Pelatihan Anda sudah ditambahkan property protected $casts = ['tanggal_mulai' => 'date'];
        
        // Ambil kelas serupa/rekomendasi (opsional untuk mempercantik halaman detail)
        $kelasLainnya = Pelatihan::where('id', '!=', $id)
            ->where('jenis', $kelas->jenis)
            ->take(3)
            ->get();

        return view('home.pelatihan.show', compact('kelas', 'kelasLainnya'));
    }
    public function tracerStudy(Request $request)
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

        // Ambil kerjasama yang aktif
        $kerjasamaTerbaru = KerjasamaIndustri::with('perusahaan')
            ->where('status', 'aktif')
            ->where('tanggal_berakhir', '>=', now())
            ->latest()
            ->take(6)
            ->get();

        // Statistik
        $statistik = [
            'total_lowongan' => LowonganKerja::where('status', 'aktif')->count(),
            'total_perusahaan' => Perusahaan::where('status_kerjasama', 'aktif')->count(),
            'total_mahasiswa_pkl' => \App\Models\Pkl::where('status', 'berlangsung')->count(),
            'total_alumni' => \App\Models\Mahasiswa::where('status', 'lulus')->count(),
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
        // Statistik Umum 
   
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
    $testimoni = TracerStudy::with(['mahasiswa.user', 'mahasiswa.programStudi'])
                            ->whereNotNull('kepuasan_pendidikan')
                            ->where('kepuasan_pendidikan', '>=', 4)
                            ->inRandomOrder()
                            ->take(6)
                            ->get();

        // Kirim semua data ke view
        return view('index.tracer-study', compact(
            'user',
            'lowonganTerbaru',
            'perusahaanMitra',
            'pelatihanTerbaru',
            'kerjasamaTerbaru',
            'statistik',
            'kesesuaianBidang',
            'topCompanies',
            'testimoni'
        ));
    }
    public function faxFaq()
    {
        // Data FAQ opsional jika ingin dilempar dinamis, atau bisa langsung ditulis di Blade
        $faqs = [
            [
                'question' => 'Bagaimana cara perusahaan mengajukan kerja sama industri?',
                'answer' => 'Perusahaan dapat mendaftar akun terlebih dahulu melalui halaman Register Perusahaan, kemudian masuk ke dashboard untuk mengisi formulir pengajuan kerja sama.'
            ],
            [
                'question' => 'Apakah mahasiswa alumni wajib mengisi Tracer Study?',
                'answer' => 'Ya, pengisian Tracer Study sangat penting untuk membantu pemetaan karier alumni serta peningkatan mutu akreditasi kampus.'
            ],
            [
                'question' => 'Berapa lama proses verifikasi akun perusahaan oleh Admin?',
                'answer' => 'Proses verifikasi berkas dan status akun perusahaan biasanya memakan waktu 1-3 hari kerja.'
            ]
        ];

        return view('home.faq.fax', compact('faqs'));
    }
    
}