<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{
    /**
     * Dataset resmi program studi se-Indonesia, dimuat sekali dari
     * resources/data/prodi_indonesia.php (Kepdirjen Dikti No. 96/B/KPT/2025).
     */
    protected function prodiDataset(): array
    {
        static $data = null;
        if ($data === null) {
            $data = require resource_path('data/prodi_indonesia.php');
        }
        return $data;
    }

    /**
     * Dataset referensi nama fakultas se-Indonesia, diturunkan dari
     * pengelompokan rumpun ilmu pada Kepdirjen Dikti No. 96/B/KPT/2025.
     */
    protected function fakultasDataset(): array
    {
        static $data = null;
        if ($data === null) {
            $data = require resource_path('data/fakultas_indonesia.php');
        }
        return $data;
    }

    /**
     * Pencarian program studi (seluruh Indonesia).
     * Endpoint: GET /api/prodi/search?q=keyword
     *
     * Sumber utama: dataset resmi Kepdirjen Dikti No. 96/B/KPT/2025 (offline,
     * selalu tersedia, mencakup 587 program studi akademik + 21 program
     * profesi). Jika tersedia, hasil dilengkapi dengan data real-time dari
     * PDDikti (nama kampus penyelenggara) tanpa membuat pencarian gagal
     * apabila API eksternal sedang tidak responsif.
     */
    public function searchProdi(Request $request)
    {
        $keyword = trim($request->get('q', ''));

        if (strlen($keyword) < 2) {
            return response()->json(['data' => [], 'message' => 'Keyword terlalu pendek']);
        }

        $cacheKey = 'prodi_search_v2_' . md5(strtolower($keyword));

        $results = Cache::remember($cacheKey, 3600, function () use ($keyword) {
            $local = $this->searchLocalProdi($keyword);

            // Lengkapi dengan hasil PDDikti (best-effort, tidak memblokir pencarian)
            $remote = $this->fetchProdiFromPddikti($keyword);

            return $this->mergeProdiResults($local, $remote);
        });

        return response()->json(['data' => $results]);
    }

    /**
     * Cari pada dataset resmi lokal (588+ program studi, seluruh Indonesia).
     */
    protected function searchLocalProdi(string $keyword): array
    {
        $kw = strtolower($keyword);
        $matches = [];

        foreach ($this->prodiDataset() as $item) {
            if (str_contains(strtolower($item['nama']), $kw)) {
                $matches[] = [
                    'nama'     => $item['nama'],
                    'jenjang'  => '',
                    'pt'       => '',
                    'fakultas' => $item['kelompok'],
                    'label'    => $item['nama'],
                ];
            }
        }

        // Prioritaskan hasil yang namanya diawali dengan kata kunci
        usort($matches, function ($a, $b) use ($kw) {
            $aStarts = str_starts_with(strtolower($a['nama']), $kw) ? 0 : 1;
            $bStarts = str_starts_with(strtolower($b['nama']), $kw) ? 0 : 1;
            if ($aStarts !== $bStarts) {
                return $aStarts <=> $bStarts;
            }
            return strlen($a['nama']) <=> strlen($b['nama']);
        });

        return array_slice($matches, 0, 20);
    }

    /**
     * Gabungkan hasil lokal (utama) dengan hasil PDDikti (pelengkap),
     * tanpa duplikasi nama program studi.
     */
    protected function mergeProdiResults(array $local, array $remote): array
    {
        $seen = [];
        $merged = [];

        foreach (array_merge($local, $remote) as $item) {
            $key = strtolower(trim($item['nama']));
            if (isset($seen[$key])) {
                continue;
            }
            $seen[$key] = true;
            $merged[] = $item;
        }

        return array_slice($merged, 0, 20);
    }

    /**
     * Proxy pencarian perguruan tinggi (universitas/kampus) dari PDDikti API.
     * Endpoint: GET /api/universitas/search?q=keyword
     */
    public function searchUniversitas(Request $request)
    {
        $keyword = trim($request->get('q', ''));

        if (strlen($keyword) < 2) {
            return response()->json(['data' => [], 'message' => 'Keyword terlalu pendek']);
        }

        $cacheKey = 'pddikti_universitas_v2_' . md5(strtolower($keyword));

        $results = Cache::remember($cacheKey, 3600, function () use ($keyword) {
            $remote = $this->fetchUniversitasFromPddikti($keyword);

            if (!empty($remote)) {
                return $remote;
            }

            // Fallback offline apabila seluruh endpoint PDDikti gagal diakses
            return $this->getStaticUniversitasSuggestions($keyword);
        });

        return response()->json(['data' => $results]);
    }

    /**
     * Daftar nama fakultas (seluruh Indonesia), diturunkan dari rumpun ilmu
     * resmi pada Kepdirjen Dikti No. 96/B/KPT/2025.
     * Endpoint: GET /api/fakultas/list?q=keyword
     */
    public function listFakultas(Request $request)
    {
        $keyword = trim($request->get('q', ''));
        $kw = strtolower($keyword);

        $matches = [];
        foreach ($this->fakultasDataset() as $item) {
            $hit = $keyword === '';
            $matchedAlias = $item['nama'];

            if (!$hit) {
                foreach ($item['aliases'] as $alias) {
                    if (str_contains(strtolower($alias), $kw)) {
                        $hit = true;
                        $matchedAlias = $alias;
                        break;
                    }
                }
                if (!$hit && str_contains(strtolower($item['kelompok_asal']), $kw)) {
                    $hit = true;
                }
            }

            if ($hit) {
                $matches[$matchedAlias] = [
                    'nama'  => $matchedAlias,
                    'kota'  => '',
                    'label' => $matchedAlias,
                ];
            }
        }

        $data = array_slice(array_values($matches), 0, 30);

        return response()->json(['data' => $data]);
    }

    /**
     * Ambil daftar prodi dari PDDikti (best-effort, pelengkap dataset lokal).
     */
    private function fetchProdiFromPddikti(string $keyword): array
    {
        $endpoints = [
            "https://api-pddikti.ridwaanhall.com/prodi/search/{$keyword}/",
            "https://api-pddikti.vercel.app/api/search/prodi/{$keyword}",
        ];

        foreach ($endpoints as $url) {
            try {
                $response = Http::timeout(5)
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

        return [];
    }

    /**
     * Ambil daftar universitas/PT dari PDDikti.
     */
    private function fetchUniversitasFromPddikti(string $keyword): array
    {
        $endpoints = [
            "https://api-pddikti.ridwaanhall.com/pt/search/{$keyword}/",
            "https://api-pddikti.vercel.app/api/search/pt/{$keyword}",
            "https://api-pddikti.vercel.app/api/search/{$keyword}",
        ];

        foreach ($endpoints as $url) {
            try {
                $response = Http::timeout(6)
                    ->withHeaders([
                        'Accept' => 'application/json',
                        'User-Agent' => 'Mozilla/5.0 (compatible; CDCApp/1.0)',
                    ])
                    ->get($url);

                if ($response->successful()) {
                    $body = $response->json();
                    $items = $body['pt'] ?? $body['data'] ?? $body ?? [];

                    if (is_array($items) && count($items) > 0) {
                        $normalized = $this->normalizeUniversitasData($items);
                        if (!empty($normalized)) {
                            return $normalized;
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::warning("PDDikti PT API failed ({$url}): " . $e->getMessage());
            }
        }

        return [];
    }

    /**
     * Normalisasi data prodi dari berbagai format API PDDikti.
     */
    private function normalizeProdiData(array $items): array
    {
        $results = [];
        foreach (array_slice($items, 0, 20) as $item) {
            if (!is_array($item)) {
                continue;
            }

            $nama = $item['nama_program_studi']
                ?? $item['nama_prodi']
                ?? $item['nama']
                ?? $item['prodi']
                ?? $item['program_studi']
                ?? null;
            $jenjang = $item['jenjang_didik']
                ?? $item['jenjang']
                ?? $item['strata']
                ?? '';
            $pt = $item['nama_perguruan_tinggi']
                ?? $item['nama_pt']
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
     * Normalisasi data universitas dari berbagai format API PDDikti.
     */
    private function normalizeUniversitasData(array $items): array
    {
        $results = [];
        foreach (array_slice($items, 0, 20) as $item) {
            if (!is_array($item)) {
                continue;
            }

            $nama = $item['nama_perguruan_tinggi']
                ?? $item['nama_pt']
                ?? $item['nama']
                ?? $item['pt']
                ?? null;
            $kota = $item['kota'] ?? $item['kab_kota'] ?? $item['alamat'] ?? '';
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
     * Fallback offline: daftar perguruan tinggi utama Indonesia, dipakai
     * hanya bila seluruh endpoint PDDikti tidak dapat diakses.
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
            'Universitas Jenderal Soedirman', 'Universitas Jember', 'Universitas Negeri Padang',
            'Universitas Negeri Medan', 'Universitas Negeri Makassar', 'Universitas Negeri Gorontalo',
            'Universitas Negeri Manado', 'Universitas Terbuka', 'Universitas Pendidikan Indonesia',
            'Institut Teknologi Sumatera', 'Institut Teknologi Kalimantan', 'Universitas Katolik Parahyangan',
            'Universitas Atma Jaya Yogyakarta', 'Universitas Sanata Dharma', 'Universitas Kristen Petra',
            'Universitas Kristen Satya Wacana', 'Universitas Muhammadiyah Yogyakarta',
            'Universitas Muhammadiyah Jakarta', 'Universitas Islam Negeri Alauddin Makassar',
            'Universitas Islam Negeri Sunan Ampel', 'Universitas Islam Negeri Sumatera Utara',
            'IKIP PGRI Bojonegoro', 'Universitas PGRI Yogyakarta', 'Universitas PGRI Semarang',
            'Politeknik Negeri Jakarta', 'Politeknik Negeri Bandung', 'Politeknik Negeri Malang',
            'Politeknik Elektronika Negeri Surabaya', 'Politeknik Manufaktur Bandung',
        ];

        $kw = strtolower($keyword);
        $filtered = array_filter($allUniversitas, function ($u) use ($kw) {
            return str_contains(strtolower($u), $kw);
        });

        return array_values(array_map(function ($nama) {
            return ['nama' => $nama, 'kota' => '', 'jenis' => '', 'label' => $nama];
        }, array_slice($filtered, 0, 20)));
    }
}