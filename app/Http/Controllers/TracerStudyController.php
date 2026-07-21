<?php

namespace App\Http\Controllers;
 
use App\Models\TracerStudy;
use App\Models\TracerStudyQuestion;
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

        // Pertanyaan tambahan yang dibuat admin (di luar field baku form)
        $additionalQuestions = TracerStudyQuestion::active()->ordered()->get()->groupBy('section');

        return view('mahasiswa.tracer-study.form', compact('tracerStudy', 'additionalQuestions'));
    }

    public function alumniStore(Request $request)
    {
        $mahasiswa = auth()->user()->mahasiswa;

        // Pertanyaan tambahan yang dibuat admin lewat menu Konfigurasi Pertanyaan
        $additionalQuestions = TracerStudyQuestion::active()->get();
        $additionalRules = [];
        $additionalMessages = [];
        foreach ($additionalQuestions as $aq) {
            $rule = $aq->is_required ? 'required' : 'nullable';
            $rule .= $aq->type === 'checkbox' ? '|array' : '|string|max:2000';
            $additionalRules["pertanyaan_tambahan.{$aq->field_name}"] = $rule;
            if ($aq->is_required) {
                $additionalMessages["pertanyaan_tambahan.{$aq->field_name}.required"]
                    = "Pertanyaan \"{$aq->label}\" wajib diisi.";
            }
        }

        // Validasi dasar + reCAPTCHA
        $validated = $request->validate(array_merge([
            // Tambahkan 'ppg' ke dalam aturan 'in'
            'status_pekerjaan' => 'required|in:bekerja,belum_memungkinkan_bekerja,wirausaha,melanjutkan_studi,belum_bekerja,ppg',
            
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

            // Data Baru: Pendidikan Profesi Guru (PPG)
            'jenis_ppg' => 'nullable|string|max:100', // Prajabatan / Dalam Jabatan
            'lptk_ppg' => 'nullable|string|max:255',   // Universitas Penyelenggara
            'tahun_ppg' => 'nullable|integer|min:2000',
            
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
        ], $additionalRules), array_merge([
            'g-recaptcha-response.required' => 'Silakan verifikasi bahwa Anda bukan robot.',
            'status_pekerjaan.required' => 'Status pekerjaan harus diisi.',
        ], $additionalMessages));

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
        } elseif ($validated['status_pekerjaan'] === 'ppg') {
            // Mapping kolom opsional disesuaikan dengan struktur tabel TracerStudy Anda
            // Disimpan ke kolom yang ada atau di-serialize jika kolom spesifik belum dibuat di database
            $data['nama_institusi'] = $validated['lptk_ppg'] ?? null;
            $data['jurusan_studi'] = 'Pendidikan Profesi Guru (' . ($validated['jenis_ppg'] ?? '') . ')';
            $data['sumber_biaya'] = $validated['jenis_ppg'] == 'prajabatan' ? 'Beasiswa Kemendikbud' : 'Mandiri/Pemerintah';
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
            'ppg_detail' => $validated['status_pekerjaan'] === 'ppg' ? [
                'jenis_ppg' => $validated['jenis_ppg'] ?? null,
                'tahun_ppg' => $validated['tahun_ppg'] ?? null,
            ] : null
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

        // Simpan jawaban pertanyaan tambahan (dibuat admin) sebagai JSON
        $data['additional_answers'] = $request->input('pertanyaan_tambahan', []);

        // Update or Create
        TracerStudy::updateOrCreate(
            ['mahasiswa_id' => $mahasiswa->id],
            $data
        );

        return redirect()->route('mahasiswa.tracer-study.form')
                        ->with('success', 'Terima kasih telah mengisi tracer study! Data Anda telah tersimpan.');
    }

    // ─── Manajemen Pertanyaan ────────────────────────────────────────────────

    /**
     * Tampilkan halaman edit pertanyaan tracer study.
     */
    public function editPertanyaan()
    {
        $questions = TracerStudyQuestion::ordered()->get()->groupBy('section');
        $sectionLabels = [
            'status_pekerjaan'  => 'Status Pekerjaan',
            'data_pekerjaan'    => 'Data Pekerjaan',
            'pencarian_kerja'   => 'Pencarian Kerja',
            'data_wirausaha'    => 'Data Wirausaha',
            'data_studi'        => 'Data Melanjutkan Studi',
            'data_ppg'          => 'Pendidikan Profesi Guru (PPG)',
            'kompetensi'        => 'Kompetensi & Metode Pembelajaran',
            'kepuasan_feedback' => 'Kepuasan & Saran',
            'kontak'            => 'Informasi Kontak',
        ];
        return view('admin.tracer-study.pertanyaan', compact('questions', 'sectionLabels'));
    }

    /**
     * Simpan perubahan konfigurasi pertanyaan.
     */
    public function updatePertanyaan(Request $request)
    {
        $data = $request->input('questions', []);

        foreach ($data as $id => $values) {
            $q = TracerStudyQuestion::find($id);
            if (!$q) continue;

            $q->label       = $values['label']      ?? $q->label;
            $q->helper_text = $values['helper_text'] ?? null;
            $q->is_active   = isset($values['is_active']) ? true : false;
            $q->is_required = isset($values['is_required']) ? true : false;
            $q->sort_order  = (int) ($values['sort_order'] ?? $q->sort_order);
            $q->save();
        }

        return redirect()->route('admin.tracer-study.pertanyaan')
                        ->with('success', 'Konfigurasi pertanyaan berhasil disimpan.');
    }

    /**
     * Tambah pertanyaan baru ke bank pertanyaan tracer study.
     */
    public function storePertanyaan(Request $request)
    {
        $validated = $request->validate([
            'section'     => 'required|string|max:100',
            'field_name'  => 'required|string|max:100|alpha_dash|unique:tracer_study_questions,field_name',
            'label'       => 'required|string|max:255',
            'type'        => 'required|string|in:text,textarea,number,email,url,radio,select,checkbox,date',
            'options_text'=> 'nullable|string',
            'is_required' => 'nullable|boolean',
            'is_active'   => 'nullable|boolean',
            'sort_order'  => 'nullable|integer|min:0|max:999',
            'helper_text' => 'nullable|string|max:1000',
        ], [
            'section.required'    => 'Bagian/section wajib dipilih.',
            'field_name.required' => 'Nama field wajib diisi.',
            'field_name.alpha_dash' => 'Nama field hanya boleh berisi huruf, angka, strip, dan underscore (tanpa spasi).',
            'field_name.unique'   => 'Nama field ini sudah digunakan, gunakan nama lain.',
            'label.required'      => 'Label pertanyaan wajib diisi.',
            'type.required'       => 'Tipe input wajib dipilih.',
        ]);

        // Ubah opsi (satu baris = satu pilihan, format "value|Teks Tampilan" atau cukup "Teks Tampilan")
        $options = null;
        if (in_array($validated['type'], ['radio', 'select', 'checkbox']) && !empty($validated['options_text'])) {
            $options = [];
            $lines = preg_split('/\r\n|\r|\n/', trim($validated['options_text']));
            foreach ($lines as $line) {
                $line = trim($line);
                if ($line === '') continue;

                if (str_contains($line, '|')) {
                    [$val, $text] = array_map('trim', explode('|', $line, 2));
                } else {
                    $val = $line;
                    $text = $line;
                }
                $options[$val] = $text;
            }
        }

        TracerStudyQuestion::create([
            'section'     => $validated['section'],
            'field_name'  => $validated['field_name'],
            'label'       => $validated['label'],
            'type'        => $validated['type'],
            'options'     => $options,
            'is_required' => $request->boolean('is_required'),
            'is_active'   => $request->boolean('is_active', true),
            'sort_order'  => $validated['sort_order'] ?? (
                TracerStudyQuestion::where('section', $validated['section'])->max('sort_order') + 1
            ),
            'helper_text' => $validated['helper_text'] ?? null,
        ]);

        return redirect()->route('admin.tracer-study.pertanyaan')
                        ->with('success', 'Pertanyaan baru berhasil ditambahkan.');
    }

    /**
     * Hapus pertanyaan dari bank pertanyaan tracer study.
     */
    public function destroyPertanyaan($id)
    {
        $question = TracerStudyQuestion::find($id);

        if (!$question) {
            return redirect()->route('admin.tracer-study.pertanyaan')
                            ->with('error', 'Pertanyaan tidak ditemukan.');
        }

        $question->delete();

        return redirect()->route('admin.tracer-study.pertanyaan')
                        ->with('success', 'Pertanyaan berhasil dihapus.');
    }

    // ─── Import Tracer Study ─────────────────────────────────────────────────

    /**
     * Download template CSV untuk import tracer study.
     */
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="template-import-tracer-study.csv"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            // BOM UTF-8 agar Excel bisa baca karakter Indonesia
            fwrite($file, "\xEF\xBB\xBF");
            fputcsv($file, [
                'nim',
                'status_pekerjaan',
                'nama_perusahaan',
                'posisi',
                'bidang_pekerjaan',
                'jenis_perusahaan',
                'penghasilan',
                'relevansi_pekerjaan',
                'waktu_tunggu_kerja',
                'nama_usaha',
                'bidang_usaha',
                'jumlah_karyawan',
                'omzet_usaha',
                'nama_institusi',
                'jurusan_studi',
                'sumber_biaya',
                'kepuasan_pendidikan',
                'saran_kurikulum',
                'saran_fasilitas',
                'saran_umum',
                'email_saat_ini',
                'no_telp_saat_ini',
                'linkedin',
                'tanggal_isi',
            ]);
            // Baris contoh
            fputcsv($file, [
                '123456789',
                'bekerja',
                'PT Contoh Maju',
                'Staff IT',
                'Teknologi Informasi',
                'swasta_nasional',
                '5000000',
                'sangat_relevan',
                '3',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '5',
                '',
                '',
                'Tingkatkan praktik lapangan',
                'alumni@email.com',
                '08123456789',
                '',
                date('Y-m-d'),
            ]);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Proses import data tracer study dari file CSV.
     */
    public function importTracerStudy(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:5120',
        ], [
            'file.required' => 'File CSV harus diunggah.',
            'file.mimes'    => 'File harus berformat CSV.',
            'file.max'      => 'Ukuran file maksimal 5 MB.',
        ]);

        $file    = $request->file('file');
        $path    = $file->getRealPath();
        $handle  = fopen($path, 'r');

        if (!$handle) {
            return back()->with('error', 'Gagal membaca file CSV.');
        }

        // Baca header
        $header = fgetcsv($handle);
        // Hapus BOM jika ada
        if ($header) {
            $header[0] = ltrim($header[0], "\xEF\xBB\xBF");
            $header    = array_map('trim', $header);
        }

        $required = ['nim', 'status_pekerjaan'];
        foreach ($required as $col) {
            if (!in_array($col, $header)) {
                fclose($handle);
                return back()->with('error', "Kolom wajib '{$col}' tidak ditemukan di file CSV.");
            }
        }

        $imported = 0;
        $skipped  = 0;
        $errors   = [];
        $row      = 1;

        $validStatus = [
            'bekerja', 'wirausaha', 'melanjutkan_studi',
            'ppg', 'belum_bekerja', 'belum_memungkinkan_bekerja',
        ];

        while (($line = fgetcsv($handle)) !== false) {
            $row++;
            if (count($line) < 2) continue;

            $data = array_combine($header, array_pad($line, count($header), null));
            $nim  = trim($data['nim'] ?? '');

            if (empty($nim)) {
                $skipped++;
                continue;
            }

            $mahasiswa = Mahasiswa::where('nim', $nim)->first();
            if (!$mahasiswa) {
                $errors[] = "Baris {$row}: NIM '{$nim}' tidak ditemukan.";
                $skipped++;
                continue;
            }

            $status = trim($data['status_pekerjaan'] ?? '');
            if (!in_array($status, $validStatus)) {
                $errors[] = "Baris {$row}: Status pekerjaan '{$status}' tidak valid.";
                $skipped++;
                continue;
            }

            $tracerData = [
                'mahasiswa_id'        => $mahasiswa->id,
                'status_pekerjaan'    => $status,
                'nama_perusahaan'     => $data['nama_perusahaan']     ?? null,
                'posisi'              => $data['posisi']              ?? null,
                'bidang_pekerjaan'    => $data['bidang_pekerjaan']    ?? null,
                'jenis_perusahaan'    => $data['jenis_perusahaan']    ?? null,
                'penghasilan'         => $data['penghasilan']         ?? null,
                'relevansi_pekerjaan' => $data['relevansi_pekerjaan'] ?? null,
                'waktu_tunggu_kerja'  => is_numeric($data['waktu_tunggu_kerja'] ?? '') ? (int)$data['waktu_tunggu_kerja'] : null,
                'nama_usaha'          => $data['nama_usaha']          ?? null,
                'bidang_usaha'        => $data['bidang_usaha']        ?? null,
                'jumlah_karyawan'     => is_numeric($data['jumlah_karyawan'] ?? '') ? (int)$data['jumlah_karyawan'] : null,
                'omzet_usaha'         => $data['omzet_usaha']         ?? null,
                'nama_institusi'      => $data['nama_institusi']      ?? null,
                'jurusan_studi'       => $data['jurusan_studi']       ?? null,
                'sumber_biaya'        => $data['sumber_biaya']        ?? null,
                'kepuasan_pendidikan' => is_numeric($data['kepuasan_pendidikan'] ?? '') ? (int)$data['kepuasan_pendidikan'] : null,
                'saran_kurikulum'     => $data['saran_kurikulum']     ?? null,
                'saran_fasilitas'     => $data['saran_fasilitas']     ?? null,
                'saran_umum'          => $data['saran_umum']          ?? null,
                'email_saat_ini'      => $data['email_saat_ini']      ?? null,
                'no_telp_saat_ini'    => $data['no_telp_saat_ini']    ?? null,
                'linkedin'            => $data['linkedin']            ?? null,
                'tanggal_isi'         => !empty($data['tanggal_isi']) ? $data['tanggal_isi'] : now(),
            ];

            TracerStudy::updateOrCreate(
                ['mahasiswa_id' => $mahasiswa->id],
                $tracerData
            );
            $imported++;
        }

        fclose($handle);

        $message = "Import selesai: {$imported} data berhasil diimpor, {$skipped} dilewati.";
        if (!empty($errors)) {
            $message .= ' Catatan: ' . implode('; ', array_slice($errors, 0, 5));
            if (count($errors) > 5) {
                $message .= ' ... dan ' . (count($errors) - 5) . ' kesalahan lainnya.';
            }
        }

        return redirect()->route('admin.tracer-study.index')
                        ->with('success', $message);
    }

    // ─── Private Helpers ─────────────────────────────────────────────────────

    private function getStatistik()
    {
        return [
            'total_alumni'               => Mahasiswa::where('status', 'lulus')->count(),
            'total_responden'            => TracerStudy::count(),
            'bekerja'                    => TracerStudy::where('status_pekerjaan', 'bekerja')->count(),
            'wirausaha'                  => TracerStudy::where('status_pekerjaan', 'wirausaha')->count(),
            'melanjutkan_studi'          => TracerStudy::where('status_pekerjaan', 'melanjutkan_studi')->count(),
            'belum_bekerja'              => TracerStudy::where('status_pekerjaan', 'belum_bekerja')->count(),
            'belum_memungkinkan_bekerja' => TracerStudy::where('status_pekerjaan', 'belum_memungkinkan_bekerja')->count(),
            'ppg'                        => TracerStudy::where('status_pekerjaan', 'ppg')->count(),
            'rata_waktu_tunggu'          => round(TracerStudy::where('waktu_tunggu_kerja', '>', 0)->avg('waktu_tunggu_kerja'), 2),
            'rata_kepuasan'              => round(TracerStudy::where('kepuasan_pendidikan', '>', 0)->avg('kepuasan_pendidikan'), 2),
        ];
    }
}