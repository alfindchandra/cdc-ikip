<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{
    /**
     * Proxy pencarian program studi dari PDDikti API
     * Endpoint: GET /api/prodi/search?q=keyword
     */
    public function searchProdi(Request $request)
    {
        $keyword = trim($request->get('q', ''));

        if (strlen($keyword) < 2) {
            return response()->json(['data' => [], 'message' => 'Keyword terlalu pendek']);
        }

        $cacheKey = 'pddikti_prodi_' . md5(strtolower($keyword));

        $results = Cache::remember($cacheKey, 3600, function () use ($keyword) {
            return $this->fetchProdiFromPddikti($keyword);
        });

        return response()->json(['data' => $results]);
    }

    /**
     * Proxy pencarian perguruan tinggi (universitas/kampus) dari PDDikti API
     * Endpoint: GET /api/universitas/search?q=keyword
     */
    public function searchUniversitas(Request $request)
    {
        $keyword = trim($request->get('q', ''));

        if (strlen($keyword) < 2) {
            return response()->json(['data' => [], 'message' => 'Keyword terlalu pendek']);
        }

        $cacheKey = 'pddikti_universitas_' . md5(strtolower($keyword));

        $results = Cache::remember($cacheKey, 3600, function () use ($keyword) {
            return $this->fetchUniversitasFromPddikti($keyword);
        });

        return response()->json(['data' => $results]);
    }

    /**
     * Ambil daftar prodi dari PDDikti
     */
    private function fetchProdiFromPddikti(string $keyword): array
    {
        $endpoints = [
            "https://api-pddikti.vercel.app/api/search/prodi/{$keyword}",
            "https://pddikti.kemdikbud.go.id/api/prodi/search/{$keyword}",
        ];

        foreach ($endpoints as $url) {
            try {
                $response = Http::timeout(8)
                    ->withHeaders([
                        'Accept' => 'application/json',
                        'User-Agent' => 'Mozilla/5.0 (compatible; CDCApp/1.0)',
                    ])
                    ->get($url);

                if ($response->successful()) {
                    $body = $response->json();
                    $items = $body['mahasiswa'] ?? $body['data'] ?? $body ?? [];

                    if (is_array($items) && count($items) > 0) {
                        return $this->normalizeProdiData($items);
                    }
                }
            } catch (\Exception $e) {
                Log::warning("PDDikti prodi API failed ({$url}): " . $e->getMessage());
            }
        }

        // Fallback: data prodi umum
        return $this->getStaticProdiSuggestions($keyword);
    }

    /**
     * Ambil daftar universitas/PT dari PDDikti
     */
    private function fetchUniversitasFromPddikti(string $keyword): array
    {
        $endpoints = [
            "https://api-pddikti.vercel.app/api/search/pt/{$keyword}",
            "https://pddikti.kemdikbud.go.id/api/pt/search/{$keyword}",
        ];

        foreach ($endpoints as $url) {
            try {
                $response = Http::timeout(8)
                    ->withHeaders([
                        'Accept' => 'application/json',
                        'User-Agent' => 'Mozilla/5.0 (compatible; CDCApp/1.0)',
                    ])
                    ->get($url);

                if ($response->successful()) {
                    $body = $response->json();
                    $items = $body['mahasiswa'] ?? $body['data'] ?? $body ?? [];

                    if (is_array($items) && count($items) > 0) {
                        return $this->normalizeUniversitasData($items);
                    }
                }
            } catch (\Exception $e) {
                Log::warning("PDDikti PT API failed ({$url}): " . $e->getMessage());
            }
        }

        // Fallback: data universitas umum
        return $this->getStaticUniversitasSuggestions($keyword);
    }

    /**
     * Normalisasi data prodi dari berbagai format API
     */
    private function normalizeProdiData(array $items): array
    {
        $results = [];
        foreach (array_slice($items, 0, 20) as $item) {
            $nama = $item['nama_program_studi'] 
                ?? $item['nama'] 
                ?? $item['prodi'] 
                ?? $item['program_studi'] 
                ?? null;
            $jenjang = $item['jenjang_didik'] 
                ?? $item['jenjang'] 
                ?? $item['strata'] 
                ?? '';
            $pt = $item['nama_perguruan_tinggi'] 
                ?? $item['pt'] 
                ?? $item['universitas'] 
                ?? '';
            $fakultas = $item['nama_fakultas'] 
                ?? $item['fakultas'] 
                ?? '';

            if ($nama) {
                $results[] = [
                    'nama'     => $nama,
                    'jenjang'  => $jenjang,
                    'pt'       => $pt,
                    'fakultas' => $fakultas,
                    'label'    => $nama . ($jenjang ? " ({$jenjang})" : '') . ($pt ? " - {$pt}" : ''),
                ];
            }
        }
        return $results;
    }

    /**
     * Normalisasi data universitas dari berbagai format API
     */
    private function normalizeUniversitasData(array $items): array
    {
        $results = [];
        foreach (array_slice($items, 0, 20) as $item) {
            $nama = $item['nama_perguruan_tinggi'] 
                ?? $item['nama'] 
                ?? $item['pt'] 
                ?? null;
            $kota = $item['kota'] ?? $item['alamat'] ?? '';
            $jenis = $item['bentuk_pt'] ?? $item['jenis'] ?? '';

            if ($nama) {
                $results[] = [
                    'nama'  => $nama,
                    'kota'  => $kota,
                    'jenis' => $jenis,
                    'label' => $nama . ($kota ? " - {$kota}" : ''),
                ];
            }
        }
        return $results;
    }

    /**
     * Fallback: data prodi static untuk saran dasar
     */
    private function getStaticProdiSuggestions(string $keyword): array
    {
        $allProdi = [
            'Teknik Informatika', 'Sistem Informasi', 'Ilmu Komputer', 'Teknologi Informasi',
            'Rekayasa Perangkat Lunak', 'Teknik Elektro', 'Teknik Mesin', 'Teknik Sipil',
            'Teknik Kimia', 'Teknik Industri', 'Arsitektur', 'Matematika', 'Fisika', 'Kimia', 'Biologi',
            'Manajemen', 'Akuntansi', 'Ekonomi', 'Ekonomi Pembangunan', 'Bisnis Digital',
            'Ilmu Hukum', 'Ilmu Administrasi Negara', 'Ilmu Komunikasi', 'Hubungan Internasional',
            'Sosiologi', 'Psikologi', 'Pendidikan Guru SD', 'Pendidikan Matematika',
            'Pendidikan Bahasa Indonesia', 'Pendidikan Bahasa Inggris', 'Pendidikan IPA',
            'Pendidikan IPS', 'Pendidikan Jasmani', 'Pendidikan Anak Usia Dini',
            'Keperawatan', 'Kesehatan Masyarakat', 'Farmasi', 'Kedokteran', 'Kedokteran Gigi',
            'Gizi', 'Kebidanan', 'Fisioterapi', 'Radiologi', 'Analis Kesehatan',
            'Agribisnis', 'Agroteknologi', 'Kehutanan', 'Peternakan', 'Perikanan',
            'Teknologi Pangan', 'Teknik Pertanian',
            'Desain Komunikasi Visual', 'Desain Interior', 'Seni Rupa', 'Seni Tari',
            'Perbankan dan Keuangan', 'Perpajakan', 'Administrasi Bisnis', 'Pariwisata',
            'Perhotelan', 'Bahasa Jepang', 'Bahasa Mandarin', 'Sastra Indonesia', 'Sastra Inggris',
        ];

        $keyword = strtolower($keyword);
        $filtered = array_filter($allProdi, function ($p) use ($keyword) {
            return strpos(strtolower($p), $keyword) !== false;
        });

        return array_values(array_map(function ($nama) {
            return ['nama' => $nama, 'jenjang' => '', 'pt' => '', 'fakultas' => '', 'label' => $nama];
        }, array_slice($filtered, 0, 15)));
    }

    /**
     * Fallback: data universitas static
     */
    private function getStaticUniversitasSuggestions(string $keyword): array
    {
        $allUniversitas = [
            'Universitas Indonesia', 'Universitas Gadjah Mada', 'Institut Teknologi Bandung',
            'Universitas Brawijaya', 'Universitas Airlangga', 'Institut Pertanian Bogor',
            'Universitas Diponegoro', 'Universitas Padjadjaran', 'Universitas Sebelas Maret',
            'Universitas Hasanuddin', 'Universitas Negeri Surabaya', 'Universitas Negeri Malang',
            'Universitas Negeri Yogyakarta', 'Universitas Negeri Semarang', 'Universitas Negeri Jakarta',
            'Institut Teknologi Sepuluh Nopember', 'Universitas Telkom', 'Universitas Bina Nusantara',
            'Universitas Multimedia Nusantara', 'Universitas Mercu Buana', 'Universitas Trisakti',
            'Universitas Tarumanagara', 'Universitas Kristen Indonesia', 'Universitas Pelita Harapan',
            'Universitas Islam Indonesia', 'Universitas Muhammadiyah Surakarta',
            'Universitas Muhammadiyah Malang', 'Universitas Ahmad Dahlan', 'IAIN Tulungagung',
            'UIN Sunan Kalijaga', 'UIN Maulana Malik Ibrahim', 'UIN Syarif Hidayatullah',
            'Universitas Sam Ratulangi', 'Universitas Lampung', 'Universitas Bengkulu',
            'Universitas Sriwijaya', 'Universitas Riau', 'Universitas Andalas', 'Universitas Syiah Kuala',
            'Universitas Sumatera Utara', 'Universitas Tanjungpura', 'Universitas Lambung Mangkurat',
            'Universitas Mulawarman', 'Universitas Udayana', 'Universitas Mataram', 'Universitas Nusa Cendana',
        ];

        $keyword = strtolower($keyword);
        $filtered = array_filter($allUniversitas, function ($u) use ($keyword) {
            return strpos(strtolower($u), $keyword) !== false;
        });

        return array_values(array_map(function ($nama) {
            return ['nama' => $nama, 'kota' => '', 'jenis' => '', 'label' => $nama];
        }, array_slice($filtered, 0, 15)));
    }

    /**
     * Daftar nama fakultas umum untuk autocomplete
     * Endpoint: GET /api/fakultas/list
     */
    public function listFakultas(Request $request)
    {
        $keyword = trim($request->get('q', ''));

        $allFakultas = [
            'Fakultas Teknik', 'Fakultas Ilmu Komputer', 'Fakultas Teknologi Informasi',
            'Fakultas Sains dan Teknologi', 'Fakultas Matematika dan Ilmu Pengetahuan Alam',
            'Fakultas Ekonomi dan Bisnis', 'Fakultas Ekonomi', 'Fakultas Bisnis',
            'Fakultas Hukum', 'Fakultas Ilmu Sosial dan Ilmu Politik', 'Fakultas Ilmu Sosial',
            'Fakultas Ilmu Komunikasi', 'Fakultas Ilmu Administrasi', 'Fakultas Psikologi',
            'Fakultas Keguruan dan Ilmu Pendidikan', 'Fakultas Pendidikan',
            'Fakultas Kedokteran', 'Fakultas Kedokteran Gigi', 'Fakultas Farmasi',
            'Fakultas Kesehatan Masyarakat', 'Fakultas Keperawatan', 'Fakultas Ilmu Kesehatan',
            'Fakultas Pertanian', 'Fakultas Peternakan', 'Fakultas Perikanan', 'Fakultas Kehutanan',
            'Fakultas Teknologi Pertanian', 'Fakultas Agribisnis', 'Fakultas Agroteknologi',
            'Fakultas Seni dan Desain', 'Fakultas Seni Rupa', 'Fakultas Ilmu Budaya',
            'Fakultas Sastra', 'Fakultas Bahasa dan Seni', 'Fakultas Humaniora',
            'Fakultas Agama Islam', 'Fakultas Ushuluddin', 'Fakultas Tarbiyah',
            'Fakultas Dakwah', 'Fakultas Syariah dan Hukum',
            'Fakultas Vokasi', 'Fakultas Pariwisata', 'Fakultas Arsitektur',
        ];

        if ($keyword !== '') {
            $kw = strtolower($keyword);
            $allFakultas = array_values(array_filter($allFakultas, function ($f) use ($kw) {
                return strpos(strtolower($f), $kw) !== false;
            }));
        }

        $data = array_map(function ($nama) {
            return ['nama' => $nama, 'label' => $nama];
        }, array_slice($allFakultas, 0, 20));

        return response()->json(['data' => $data]);
    }
}
