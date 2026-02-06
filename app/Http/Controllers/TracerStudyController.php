<?php

namespace App\Http\Controllers;

use App\Models\TracerStudy;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\Fakultas;
use App\Models\Program_studi;

class TracerStudyController extends Controller
{
 
    public function index(Request $request)
    {
        $query = TracerStudy::with(['mahasiswa.user', 'mahasiswa.fakultas', 'mahasiswa.programStudi']);

        // 1. Filter Pencarian (Search)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q_search) use ($search) {
                $q_search->whereHas('mahasiswa.user', function($q) use ($search) {
                    $q->where('name', 'like', "%$search%");
                })
                ->orWhereHas('mahasiswa', function($q) use ($search) {
                    $q->where('nim', 'like', "%$search%");
                });
            });
        }

        // 2. Filter Status Pekerjaan
        if ($request->has('status_pekerjaan') && $request->status_pekerjaan != '') {
            $query->where('status_pekerjaan', $request->status_pekerjaan);
        }

        // 3. Filter Tahun Lulus
        if ($request->has('tahun_lulus') && $request->tahun_lulus != '') {
            $query->whereHas('mahasiswa', function($q) use ($request) {
                $q->where('tahun_lulus', $request->tahun_lulus);
            });
        }
        
        // 4. Filter Fakultas
        if ($request->has('fakultas_id') && $request->fakultas_id != '') {
            $query->whereHas('mahasiswa', function($q) use ($request) {
                $q->where('fakultas_id', $request->fakultas_id); 
            });
        }
        
        // 5. Filter Program Studi
        if ($request->has('program_studi_id') && $request->program_studi_id != '') {
            $query->whereHas('mahasiswa', function($q) use ($request) {
                $q->where('program_studi_id', $request->program_studi_id);
            });
        }

        // 6. Eksekusi Query dan Pagination
        $tracerStudy = $query->latest()->paginate(20)->withQueryString();

        // 7. Ambil data pendukung untuk filter di View
        $fakultas = Fakultas::all();
        $program_studi = Program_studi::all();

        // Statistik untuk dashboard
        $statistik = $this->getStatistik();

        return view('admin.tracer-study.index', compact('tracerStudy', 'statistik', 'fakultas', 'program_studi'));
    }

    public function create()
    {
        // Ambil mahasiswa yang sudah lulus dan belum mengisi tracer study
        $alumni = Mahasiswa::where('status', 'lulus')
                      ->whereDoesntHave('tracerStudy')
                      ->with('user')
                      ->get();

        return view('admin.tracer-study.create', compact('alumni'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'mahasiswa_id' => 'required|exists:mahasiswa,id|unique:tracer_study,mahasiswa_id',
            'status_pekerjaan' => 'required|in:bekerja,wirausaha,melanjutkan_studi,belum_bekerja,belum_memungkinkan_bekerja',
            
            // Data Pekerjaan/Usaha
            'nama_perusahaan' => 'nullable|string|max:255',
            'posisi' => 'nullable|string|max:255',
            'bidang_pekerjaan' => 'nullable|string|max:255',
            'alamat_perusahaan' => 'nullable|string',
            'jenis_perusahaan' => 'nullable|in:pemerintah,swasta,bumn,startup,lainnya',
            'penghasilan' => 'nullable|string',
            'relevansi_pekerjaan' => 'nullable|in:sangat_relevan,relevan,cukup_relevan,tidak_relevan',
            'cara_mendapat_pekerjaan' => 'nullable|string',
            'waktu_tunggu_kerja' => 'nullable|integer',
            
            // Data Melanjutkan Studi
            'nama_institusi' => 'nullable|string|max:255',
            'jenjang_studi' => 'nullable|in:d3,s1,s2,s3,kursus,pelatihan',
            'jurusan_studi' => 'nullable|string|max:255',
            'sumber_biaya' => 'nullable|string',
            
            // Data Wirausaha
            'nama_usaha' => 'nullable|string|max:255',
            'bidang_usaha' => 'nullable|string|max:255',
            'jumlah_karyawan' => 'nullable|integer',
            'omzet_usaha' => 'nullable|string',
            
            // Kepuasan & Feedback
            'kepuasan_pendidikan' => 'nullable|integer|min:1|max:5',
            'saran_kurikulum' => 'nullable|string',
            'saran_fasilitas' => 'nullable|string',
            'saran_umum' => 'nullable|string',
            'kompetensi_yang_digunakan' => 'nullable|json',
            
            // Kontak
            'email_saat_ini' => 'nullable|email',
            'no_telp_saat_ini' => 'nullable|string|max:15',
            'linkedin' => 'nullable|url',
            
            'tanggal_isi' => 'nullable|date',
        ]);

        TracerStudy::create($validated);

        return redirect()->route('admin.tracer-study.index')
                        ->with('success', 'Data tracer study berhasil ditambahkan');
    }

    public function show(TracerStudy $tracerStudy)
    {
        $tracerStudy->load(['mahasiswa.user', 'mahasiswa.fakultas', 'mahasiswa.programStudi']);
        return view('admin.tracer-study.show', compact('tracerStudy'));
    }

    public function edit(TracerStudy $tracerStudy)
    {
        return view('admin.tracer-study.edit', compact('tracerStudy'));
    }

    public function update(Request $request, TracerStudy $tracerStudy)
    {
        $validated = $request->validate([
            'status_pekerjaan' => 'required|in:bekerja,wirausaha,melanjutkan_studi,belum_bekerja,belum_memungkinkan_bekerja',
            'nama_perusahaan' => 'nullable|string|max:255',
            'posisi' => 'nullable|string|max:255',
            'bidang_pekerjaan' => 'nullable|string|max:255',
            'alamat_perusahaan' => 'nullable|string',
            'jenis_perusahaan' => 'nullable|in:pemerintah,swasta,bumn,startup,lainnya',
            'penghasilan' => 'nullable|string',
            'relevansi_pekerjaan' => 'nullable|in:sangat_relevan,relevan,cukup_relevan,tidak_relevan',
            'cara_mendapat_pekerjaan' => 'nullable|string',
            'waktu_tunggu_kerja' => 'nullable|integer',
            'nama_institusi' => 'nullable|string|max:255',
            'jenjang_studi' => 'nullable|in:d3,s1,s2,s3,kursus,pelatihan',
            'jurusan_studi' => 'nullable|string|max:255',
            'sumber_biaya' => 'nullable|string',
            'nama_usaha' => 'nullable|string|max:255',
            'bidang_usaha' => 'nullable|string|max:255',
            'jumlah_karyawan' => 'nullable|integer',
            'omzet_usaha' => 'nullable|string',
            'kepuasan_pendidikan' => 'nullable|integer|min:1|max:5',
            'saran_kurikulum' => 'nullable|string',
            'saran_fasilitas' => 'nullable|string',
            'saran_umum' => 'nullable|string',
            'kompetensi_yang_digunakan' => 'nullable|json',
            'email_saat_ini' => 'nullable|email',
            'no_telp_saat_ini' => 'nullable|string|max:15',
            'linkedin' => 'nullable|url',
        ]);

        $tracerStudy->update($validated);

        return redirect()->route('admin.tracer-study.index')
                        ->with('success', 'Data tracer study berhasil diperbarui');
    }

    public function destroy(TracerStudy $tracerStudy)
    {
        $tracerStudy->delete();
        return back()->with('success', 'Data tracer study berhasil dihapus');
    }

    // Laporan & Analisis
    public function laporan(Request $request)
    {
        $tahunLulus = $request->get('tahun_lulus', date('Y'));
        $fakultasId = $request->get('fakultas_id');

        $query = TracerStudy::with(['mahasiswa.fakultas', 'mahasiswa.programStudi']);

        if ($tahunLulus) {
            $query->whereHas('mahasiswa', function($q) use ($tahunLulus) {
                $q->where('tahun_lulus', $tahunLulus);
            });
        }

        if ($fakultasId) {
            $query->whereHas('mahasiswa', function($q) use ($fakultasId) {
                $q->where('fakultas_id', $fakultasId);
            });
        }

        $data = $query->get();

        // Analisis statistik
        $analisis = [
            'total_responden' => $data->count(),
            'bekerja' => $data->where('status_pekerjaan', 'bekerja')->count(),
            'wirausaha' => $data->where('status_pekerjaan', 'wirausaha')->count(),
            'melanjutkan_studi' => $data->where('status_pekerjaan', 'melanjutkan_studi')->count(),
            'belum_bekerja' => $data->where('status_pekerjaan', 'belum_bekerja')->count(),
            'belum_memungkinkan_bekerja' => $data->where('status_pekerjaan', 'belum_memungkinkan_bekerja')->count(),
            
            // Relevansi pekerjaan
            'sangat_relevan' => $data->where('relevansi_pekerjaan', 'sangat_relevan')->count(),
            'relevan' => $data->where('relevansi_pekerjaan', 'relevan')->count(),
            'cukup_relevan' => $data->where('relevansi_pekerjaan', 'cukup_relevan')->count(),
            'tidak_relevan' => $data->where('relevansi_pekerjaan', 'tidak_relevan')->count(),
            
            // Waktu tunggu kerja rata-rata
            'rata_waktu_tunggu' => round($data->where('waktu_tunggu_kerja', '>', 0)->avg('waktu_tunggu_kerja'), 2),
            
            // Kepuasan rata-rata
            'rata_kepuasan' => round($data->where('kepuasan_pendidikan', '>', 0)->avg('kepuasan_pendidikan'), 2),
        ];

        $fakultas = Fakultas::all();

        return view('admin.tracer-study.laporan', compact('data', 'analisis', 'fakultas', 'tahunLulus', 'fakultasId'));
    }

    public function exportExcel(Request $request)
    {
        // Basic export - bisa dikembangkan dengan Laravel Excel
        $query = TracerStudy::with(['mahasiswa.user', 'mahasiswa.fakultas', 'mahasiswa.programStudi']);
        
        // Apply same filters as index
        if ($request->has('status_pekerjaan') && $request->status_pekerjaan != '') {
            $query->where('status_pekerjaan', $request->status_pekerjaan);
        }
        if ($request->has('tahun_lulus') && $request->tahun_lulus != '') {
            $query->whereHas('mahasiswa', function($q) use ($request) {
                $q->where('tahun_lulus', $request->tahun_lulus);
            });
        }
        
        $data = $query->get();
        
        // Create CSV
        $filename = 'tracer-study-' . date('Y-m-d-His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            // Header CSV
            fputcsv($file, [
                'No',
                'Nama',
                'NIM',
                'Fakultas',
                'Program Studi',
                'Tahun Lulus',
                'Status Pekerjaan',
                'Nama Perusahaan/Usaha/Institusi',
                'Posisi/Bidang',
                'Penghasilan',
                'Relevansi',
                'Waktu Tunggu (Bulan)',
                'Kepuasan',
                'Tanggal Isi'
            ]);
            
            // Data
            foreach ($data as $index => $item) {
                $detail = '';
                if ($item->status_pekerjaan == 'bekerja') {
                    $detail = $item->nama_perusahaan;
                } elseif ($item->status_pekerjaan == 'wirausaha') {
                    $detail = $item->nama_usaha;
                } elseif ($item->status_pekerjaan == 'melanjutkan_studi') {
                    $detail = $item->nama_institusi;
                }
                
                fputcsv($file, [
                    $index + 1,
                    $item->mahasiswa->user->name ?? '-',
                    $item->mahasiswa->nim ?? '-',
                    $item->mahasiswa->fakultas->nama ?? '-',
                    $item->mahasiswa->programStudi->nama ?? '-',
                    $item->mahasiswa->tahun_lulus ?? '-',
                    $item->status_pekerjaan,
                    $detail,
                    $item->posisi ?? $item->bidang_usaha ?? $item->jurusan_studi ?? '-',
                    $item->penghasilan ?? '-',
                    $item->relevansi_pekerjaan ?? '-',
                    $item->waktu_tunggu_kerja ?? '-',
                    $item->kepuasan_pendidikan ?? '-',
                    $item->tanggal_isi ? $item->tanggal_isi->format('d-m-Y H:i') : '-'
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    // Mahasiswa/Alumni Methods
    public function alumniForm()
    {
        $mahasiswa = auth()->user()->mahasiswa;
        
        // Cek apakah mahasiswa sudah lulus
        if ($mahasiswa->status !== 'lulus') {
            return redirect()->route('dashboard')
                           ->with('error', 'Fitur ini hanya untuk alumni yang sudah lulus');
        }

        // Cek apakah sudah mengisi
        $tracerStudy = TracerStudy::where('mahasiswa_id', $mahasiswa->id)->first();

        return view('mahasiswa.tracer-study.form', compact('tracerStudy'));
    }

    public function alumniStore(Request $request)
    {
        $mahasiswa = auth()->user()->mahasiswa;

        // Validasi dasar + reCAPTCHA
        $validated = $request->validate([
            'status_pekerjaan' => 'required|in:bekerja,belum_memungkinkan_bekerja,wirausaha,melanjutkan_studi,belum_bekerja',
            
            // Data Pekerjaan (untuk status bekerja)
            'waktu_tunggu_kerja' => 'nullable|integer|min:0',
            'penghasilan_nominal' => 'nullable|numeric|min:0',
            'provinsi_kerja' => 'nullable|string|max:100',
            'kota_kerja' => 'nullable|string|max:100',
            'jenis_perusahaan' => 'nullable|string|max:100',
            'nama_perusahaan' => 'nullable|string|max:255',
            'tingkat_tempat_kerja' => 'nullable|string|max:100',
            'relevansi_pekerjaan' => 'nullable|in:sangat_relevan,relevan,cukup_relevan,kurang_erat,tidak_relevan',
            'tingkat_pendidikan_pekerjaan' => 'nullable|string|max:100',
            
            // Data Wirausaha
            'waktu_mulai_usaha' => 'nullable|integer|min:0',
            'nama_usaha' => 'nullable|string|max:255',
            'bidang_usaha' => 'nullable|string|max:255',
            'posisi_wirausaha' => 'nullable|string|max:100',
            'jumlah_karyawan' => 'nullable|integer|min:0',
            'omzet_usaha' => 'nullable|numeric|min:0',
            
            // Data Studi Lanjut
            'sumber_biaya' => 'nullable|string|max:100',
            'nama_institusi' => 'nullable|string|max:255',
            'jurusan_studi' => 'nullable|string|max:255',
            'tanggal_masuk_studi' => 'nullable|date',
            
            // Kompetensi
            'kompetensi_saat_lulus' => 'nullable|array',
            'kompetensi_diperlukan' => 'nullable|array',
            
            // Metode Pembelajaran
            'metode_pembelajaran' => 'nullable|array',
            
            // Pencarian Pekerjaan
            'kapan_mencari_kerja' => 'nullable|string',
            'bulan_sebelum_lulus' => 'nullable|integer|min:0',
            'bulan_setelah_lulus' => 'nullable|integer|min:0',
            'cara_cari_kerja' => 'nullable|array',
            'cara_cari_kerja_lainnya' => 'nullable|string',
            'jumlah_lamaran' => 'nullable|integer|min:0',
            'jumlah_respons' => 'nullable|integer|min:0',
            'jumlah_wawancara' => 'nullable|integer|min:0',
            
            // Kesesuaian Pekerjaan
            'alasan_tidak_sesuai' => 'nullable|array',
            'alasan_tidak_sesuai_lainnya' => 'nullable|string',
            
            // Kepuasan & Saran
            'kepuasan_pendidikan' => 'nullable|integer|min:1|max:5',
            'saran_kurikulum' => 'nullable|string',
            'saran_fasilitas' => 'nullable|string',
            'saran_umum' => 'nullable|string',
            
            // Kontak
            'email_saat_ini' => 'nullable|email',
            'no_telp_saat_ini' => 'nullable|string|max:15',
            'linkedin' => 'nullable|url',
        ], [
            'g-recaptcha-response.required' => 'Silakan verifikasi bahwa Anda bukan robot.',
            'status_pekerjaan.required' => 'Status pekerjaan harus diisi.',
        ]);

       

        // Siapkan data untuk disimpan
        $data = [
            'mahasiswa_id' => $mahasiswa->id,
            'status_pekerjaan' => $validated['status_pekerjaan'],
            'tanggal_isi' => now(),
        ];

        // Tambahkan data sesuai status
        if ($validated['status_pekerjaan'] === 'bekerja') {
            $data['waktu_tunggu_kerja'] = $validated['waktu_tunggu_kerja'] ?? null;
            $data['penghasilan'] = $validated['penghasilan_nominal'] ?? null;
            $data['nama_perusahaan'] = $validated['nama_perusahaan'] ?? null;
            $data['jenis_perusahaan'] = $validated['jenis_perusahaan'] ?? null;
            $data['relevansi_pekerjaan'] = $validated['relevansi_pekerjaan'] ?? null;
            $data['alamat_perusahaan'] = ($validated['kota_kerja'] ?? '') . ', ' . ($validated['provinsi_kerja'] ?? '');
            $data['tingkat_tempat_kerja'] = $validated['tingkat_tempat_kerja'] ?? null;
            $data['tingkat_pendidikan_pekerjaan'] = $validated['tingkat_pendidikan_pekerjaan'] ?? null;
        } elseif ($validated['status_pekerjaan'] === 'wirausaha') {
            $data['nama_usaha'] = $validated['nama_usaha'] ?? null;
            $data['bidang_usaha'] = $validated['bidang_usaha'] ?? null;
            $data['jumlah_karyawan'] = $validated['jumlah_karyawan'] ?? null;
            $data['omzet_usaha'] = $validated['omzet_usaha'] ?? null;
        } elseif ($validated['status_pekerjaan'] === 'melanjutkan_studi') {
            $data['nama_institusi'] = $validated['nama_institusi'] ?? null;
            $data['jurusan_studi'] = $validated['jurusan_studi'] ?? null;
            $data['sumber_biaya'] = $validated['sumber_biaya'] ?? null;
        }

        // Simpan data kompetensi dan metode pembelajaran sebagai JSON
        $data['kompetensi_yang_digunakan'] = json_encode([
            'saat_lulus' => $validated['kompetensi_saat_lulus'] ?? [],
            'diperlukan' => $validated['kompetensi_diperlukan'] ?? [],
            'metode_pembelajaran' => $validated['metode_pembelajaran'] ?? [],
            'pencarian_kerja' => [
                'kapan' => $validated['kapan_mencari_kerja'] ?? null,
                'bulan_sebelum' => $validated['bulan_sebelum_lulus'] ?? null,
                'bulan_setelah' => $validated['bulan_setelah_lulus'] ?? null,
                'cara' => $validated['cara_cari_kerja'] ?? [],
                'jumlah_lamaran' => $validated['jumlah_lamaran'] ?? null,
                'jumlah_respons' => $validated['jumlah_respons'] ?? null,
                'jumlah_wawancara' => $validated['jumlah_wawancara'] ?? null,
            ],
            'alasan_tidak_sesuai' => $validated['alasan_tidak_sesuai'] ?? [],
        ]);

        // Tambahkan kepuasan dan saran
        $data['kepuasan_pendidikan'] = $validated['kepuasan_pendidikan'] ?? null;
        $data['saran_kurikulum'] = $validated['saran_kurikulum'] ?? null;
        $data['saran_fasilitas'] = $validated['saran_fasilitas'] ?? null;
        $data['saran_umum'] = $validated['saran_umum'] ?? null;

        // Tambahkan kontak
        $data['email_saat_ini'] = $validated['email_saat_ini'] ?? null;
        $data['no_telp_saat_ini'] = $validated['no_telp_saat_ini'] ?? null;
        $data['linkedin'] = $validated['linkedin'] ?? null;

        // Update or Create
        TracerStudy::updateOrCreate(
            ['mahasiswa_id' => $mahasiswa->id],
            $data
        );

        return redirect()->route('mahasiswa.tracer-study.form')
                        ->with('success', 'Terima kasih telah mengisi tracer study! Data Anda telah tersimpan.');
    }

    // Private Methods
    private function getStatistik()
    {
        return [
            'total_alumni' => Mahasiswa::where('status', 'lulus')->count(),
            'total_responden' => TracerStudy::count(),
            'bekerja' => TracerStudy::where('status_pekerjaan', 'bekerja')->count(),
            'wirausaha' => TracerStudy::where('status_pekerjaan', 'wirausaha')->count(),
            'melanjutkan_studi' => TracerStudy::where('status_pekerjaan', 'melanjutkan_studi')->count(),
            'belum_bekerja' => TracerStudy::where('status_pekerjaan', 'belum_bekerja')->count(),
            'belum_memungkinkan_bekerja' => TracerStudy::where('status_pekerjaan', 'belum_memungkinkan_bekerja')->count(),
            'rata_waktu_tunggu' => round(TracerStudy::where('waktu_tunggu_kerja', '>', 0)->avg('waktu_tunggu_kerja'), 2),
            'rata_kepuasan' => round(TracerStudy::where('kepuasan_pendidikan', '>', 0)->avg('kepuasan_pendidikan'), 2),
        ];
    }
}