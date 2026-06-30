<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\LowonganKerja;
use App\Models\Lamaran;
use App\Models\Pelatihan;
use App\Models\KerjasamaIndustri;
use App\Models\Fakultas;

class LaporanController extends Controller
{
    /**
     * Halaman utama laporan — tampilkan 5 jenis laporan beserta ringkasan data
     */
    public function index(Request $request)
    {
        $jenis = $request->get('jenis', 'alumni');

        // Statistik ringkasan untuk semua kartu
        $stats = [
            'alumni'    => Mahasiswa::where('status', 'lulus')->count(),
            'lowongan'  => LowonganKerja::count(),
            'lamaran'   => Lamaran::count(),
            'pelatihan' => Pelatihan::count(),
            'kerjasama' => KerjasamaIndustri::count(),
        ];

        $data    = [];
        $fakultas = Fakultas::all();

        switch ($jenis) {
            case 'alumni':
                $query = Mahasiswa::with(['user', 'fakultas', 'programStudi'])
                    ->where('status', 'lulus');
                if ($request->filled('tahun_lulus')) {
                    $query->where('tahun_lulus', $request->tahun_lulus);
                }
                if ($request->filled('fakultas_id')) {
                    $query->where('fakultas_id', $request->fakultas_id);
                }
                $data = $query->latest()->paginate(15)->withQueryString();
                break;

            case 'lowongan':
                $query = LowonganKerja::with('perusahaan');
                if ($request->filled('status')) {
                    $query->where('status', $request->status);
                }
                if ($request->filled('tipe_pekerjaan')) {
                    $query->where('tipe_pekerjaan', $request->tipe_pekerjaan);
                }
                $data = $query->latest()->paginate(15)->withQueryString();
                break;

            case 'lamaran':
                $query = Lamaran::with(['mahasiswa.user', 'lowongan.perusahaan']);
                if ($request->filled('status')) {
                    $query->where('status', $request->status);
                }
                $data = $query->latest()->paginate(15)->withQueryString();
                break;

            case 'pelatihan':
                $query = Pelatihan::query();
                if ($request->filled('jenis_pelatihan')) {
                    $query->where('jenis', $request->jenis_pelatihan);
                }
                if ($request->filled('status')) {
                    $query->where('status', $request->status);
                }
                $data = $query->latest()->paginate(15)->withQueryString();
                break;

            case 'kerjasama':
                $query = KerjasamaIndustri::with('perusahaan');
                if ($request->filled('status')) {
                    $query->where('status', $request->status);
                }
                if ($request->filled('jenis_kerjasama')) {
                    $query->where('jenis_kerjasama', $request->jenis_kerjasama);
                }
                $data = $query->latest()->paginate(15)->withQueryString();
                break;
        }

        return view('admin.laporan.index', compact('jenis', 'stats', 'data', 'fakultas'));
    }

    /**
     * Download CSV (bisa dibuka di Excel)
     */
    public function download(Request $request)
    {
        $jenis   = $request->get('jenis', 'alumni');
        $filters = $request->except(['jenis', '_token', 'page']);
        $now     = now()->format('Y-m-d');
        $filename = "laporan-{$jenis}-{$now}.csv";

        $rows = $this->buildRows($jenis, $filters);

        $callback = function () use ($rows) {
            $handle = fopen('php://output', 'w');
            // BOM untuk UTF-8 agar Excel bisa baca karakter Indonesia
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            foreach ($rows as $row) {
                fputcsv($handle, $row, ';');
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ]);
    }

    private function buildRows(string $jenis, array $filters): array
    {
        $rows = [];

        switch ($jenis) {
            case 'alumni':
                $rows[] = ['No', 'NIM', 'Nama', 'Email', 'No. Telepon', 'Jenis Kelamin', 'Fakultas', 'Program Studi', 'Tahun Masuk', 'Tahun Lulus', 'Status'];
                $query = Mahasiswa::with(['user', 'fakultas', 'programStudi'])->where('status', 'lulus');
                if (!empty($filters['tahun_lulus'])) $query->where('tahun_lulus', $filters['tahun_lulus']);
                if (!empty($filters['fakultas_id'])) $query->where('fakultas_id', $filters['fakultas_id']);
                $query->each(function ($m, $i) use (&$rows) {
                    $rows[] = [
                        $i + 1,
                        $m->nim,
                        $m->user->name ?? '-',
                        $m->user->email ?? '-',
                        $m->no_telp ?? '-',
                        $m->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan',
                        $m->fakultas->nama ?? '-',
                        $m->programStudi->nama_prodi ?? '-',
                        $m->tahun_masuk,
                        $m->tahun_lulus ?? '-',
                        ucfirst($m->status),
                    ];
                });
                break;

            case 'lowongan':
                $rows[] = ['No', 'Judul Lowongan', 'Posisi', 'Perusahaan', 'Tipe Pekerjaan', 'Lokasi', 'Gaji Min', 'Gaji Max', 'Kuota', 'Jumlah Pelamar', 'Tanggal Mulai', 'Batas Lamaran', 'Status'];
                $query = LowonganKerja::with('perusahaan');
                if (!empty($filters['status'])) $query->where('status', $filters['status']);
                if (!empty($filters['tipe_pekerjaan'])) $query->where('tipe_pekerjaan', $filters['tipe_pekerjaan']);
                $query->latest()->each(function ($l, $i) use (&$rows) {
                    $rows[] = [
                        $i + 1,
                        $l->judul,
                        $l->posisi,
                        $l->perusahaan->nama_perusahaan ?? '-',
                        ucfirst(str_replace('_', ' ', $l->tipe_pekerjaan)),
                        $l->lokasi,
                        $l->gaji_min ? number_format($l->gaji_min, 0, ',', '.') : '-',
                        $l->gaji_max ? number_format($l->gaji_max, 0, ',', '.') : '-',
                        $l->kuota ?? 'Tidak terbatas',
                        $l->jumlah_pelamar,
                        $l->tanggal_mulai ? $l->tanggal_mulai->format('d/m/Y') : '-',
                        $l->tanggal_berakhir ? $l->tanggal_berakhir->format('d/m/Y') : '-',
                        ucfirst($l->status),
                    ];
                });
                break;

            case 'lamaran':
                $rows[] = ['No', 'Nama Pelamar', 'NIM', 'Email', 'Posisi Dilamar', 'Perusahaan', 'Tanggal Melamar', 'Status', 'Catatan'];
                $query = Lamaran::with(['mahasiswa.user', 'lowongan.perusahaan']);
                if (!empty($filters['status'])) $query->where('status', $filters['status']);
                $query->latest()->each(function ($lm, $i) use (&$rows) {
                    $rows[] = [
                        $i + 1,
                        $lm->mahasiswa->user->name ?? '-',
                        $lm->mahasiswa->nim ?? '-',
                        $lm->mahasiswa->user->email ?? '-',
                        $lm->lowongan->posisi ?? '-',
                        $lm->lowongan->perusahaan->nama_perusahaan ?? '-',
                        $lm->tanggal_melamar ? $lm->tanggal_melamar->format('d/m/Y H:i') : '-',
                        ucfirst($lm->status),
                        $lm->catatan ?? '-',
                    ];
                });
                break;

            case 'pelatihan':
                $rows[] = ['No', 'Judul Pelatihan', 'Jenis', 'Instruktur', 'Tempat', 'Tanggal Mulai', 'Tanggal Selesai', 'Kuota', 'Jumlah Peserta', 'Biaya', 'Status'];
                $query = Pelatihan::query();
                if (!empty($filters['jenis_pelatihan'])) $query->where('jenis', $filters['jenis_pelatihan']);
                if (!empty($filters['status'])) $query->where('status', $filters['status']);
                $query->latest()->each(function ($p, $i) use (&$rows) {
                    $rows[] = [
                        $i + 1,
                        $p->judul,
                        ucfirst(str_replace('_', ' ', $p->jenis)),
                        $p->instruktur ?? '-',
                        $p->tempat ?? '-',
                        $p->tanggal_mulai ? $p->tanggal_mulai->format('d/m/Y') : '-',
                        $p->tanggal_selesai ? $p->tanggal_selesai->format('d/m/Y') : '-',
                        $p->kuota ?? 'Tidak terbatas',
                        $p->jumlah_peserta,
                        $p->biaya ? 'Rp ' . number_format($p->biaya, 0, ',', '.') : 'Gratis',
                        ucfirst($p->status),
                    ];
                });
                break;

            case 'kerjasama':
                $rows[] = ['No', 'Judul Kerjasama', 'Perusahaan', 'Jenis Kerjasama', 'PIC Sekolah', 'PIC Industri', 'Nilai Kontrak', 'Tanggal Mulai', 'Tanggal Berakhir', 'Status', 'Catatan'];
                $query = KerjasamaIndustri::with('perusahaan');
                if (!empty($filters['status'])) $query->where('status', $filters['status']);
                if (!empty($filters['jenis_kerjasama'])) $query->where('jenis_kerjasama', $filters['jenis_kerjasama']);
                $query->latest()->each(function ($k, $i) use (&$rows) {
                    $rows[] = [
                        $i + 1,
                        $k->judul,
                        $k->perusahaan->nama_perusahaan ?? '-',
                        ucfirst(str_replace('_', ' ', $k->jenis_kerjasama)),
                        $k->pic_sekolah ?? '-',
                        $k->pic_industri ?? '-',
                        $k->nilai_kontrak ? 'Rp ' . number_format($k->nilai_kontrak, 0, ',', '.') : '-',
                        $k->tanggal_mulai ? $k->tanggal_mulai->format('d/m/Y') : '-',
                        $k->tanggal_berakhir ? $k->tanggal_berakhir->format('d/m/Y') : '-',
                        ucfirst($k->status),
                        $k->catatan ?? '-',
                    ];
                });
                break;
        }

        return $rows;
    }
}
