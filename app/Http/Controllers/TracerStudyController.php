<?php

namespace App\Http\Controllers;

use App\Models\TracerStudy;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Fakultas;
use App\Models\Program_studi;

class TracerStudyController extends Controller
{
 
public function index(Request $request)
{
    $query = TracerStudy::with(['siswa.user', 'siswa.fakultas', 'siswa.programStudi']);

    // 1. Filter Pencarian (Search)
    if ($request->has('search') && $request->search != '') {
        $search = $request->search;
        // PENTING: Bungkus semua kondisi OR (Nama ATAU NIM) dalam satu where() closure
        $query->where(function($q_search) use ($search) {
            // Mencari berdasarkan Nama Alumni (Siswa -> User -> Name)
            $q_search->whereHas('siswa.user', function($q) use ($search) {
                $q->where('name', 'like', "%$search%");
            })
            // ATAU Mencari berdasarkan NIM (Siswa -> NIM)
            ->orWhereHas('siswa', function($q) use ($search) {
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
        $query->whereHas('siswa', function($q) use ($request) {
            $q->where('tahun_lulus', $request->tahun_lulus);
        });
    }
    
    // 4. Filter Fakultas (Menambahkan logika cek empty string)
    if ($request->has('fakultas_id') && $request->fakultas_id != '') {
        $query->whereHas('siswa', function($q) use ($request) {
            $q->where('fakultas_id', $request->fakultas_id);
        });
    }
    
    // 5. Filter Program Studi (TAMBAHAN, jika Anda ingin menyertakannya)
    if ($request->has('program_studi_id') && $request->program_studi_id != '') {
        $query->whereHas('siswa', function($q) use ($request) {
            $q->where('program_studi_id', $request->program_studi_id);
        });
    }

    // 6. Eksekusi Query dan Pagination
    $tracerStudy = $query->latest()->paginate(20)->withQueryString();

    // 7. Ambil data pendukung untuk filter di View
    $fakultas = Fakultas::all();
    $program_studi = Program_studi::all(); // Opsional, jika filter prodi disertakan

    // Statistik untuk dashboard
    $statistik = $this->getStatistik();

    // Mengirimkan data filter tambahan ke View
    return view('admin.tracer-study.index', compact('tracerStudy', 'statistik', 'fakultas', 'program_studi'));
}

    public function create()
    {
        // Ambil siswa yang sudah lulus dan belum mengisi tracer study
        $alumni = Siswa::where('status', 'lulus')
                      ->whereDoesntHave('tracerStudy')
                      ->with('user')
                      ->get();

        return view('admin.tracer-study.create', compact('alumni'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswa,id|unique:tracer_study,siswa_id',
            'status_pekerjaan' => 'required|in:bekerja,wirausaha,melanjutkan_studi,belum_bekerja',
            
            // Data Pekerjaan/Usaha
            'nama_perusahaan' => 'nullable|string|max:255',
            'posisi' => 'nullable|string|max:255',
            'bidang_pekerjaan' => 'nullable|string|max:255',
            'alamat_perusahaan' => 'nullable|string',
            'jenis_perusahaan' => 'nullable|in:pemerintah,swasta,bumn,startup,lainnya',
            'penghasilan' => 'nullable|string',
            'relevansi_pekerjaan' => 'nullable|in:sangat_relevan,relevan,cukup_relevan,tidak_relevan',
            'cara_mendapat_pekerjaan' => 'nullable|string',
            'waktu_tunggu_kerja' => 'nullable|integer', // dalam bulan
            
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
        $tracerStudy->load(['siswa.user', 'siswa.fakultas', 'siswa.programStudi']);
        return view('admin.tracer-study.show', compact('tracerStudy'));
    }

    public function edit(TracerStudy $tracerStudy)
    {
        return view('admin.tracer-study.edit', compact('tracerStudy'));
    }

    public function update(Request $request, TracerStudy $tracerStudy)
    {
        $validated = $request->validate([
            'status_pekerjaan' => 'required|in:bekerja,wirausaha,melanjutkan_studi,belum_bekerja',
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

        $query = TracerStudy::with(['siswa.fakultas', 'siswa.programStudi']);

        if ($tahunLulus) {
            $query->whereHas('siswa', function($q) use ($tahunLulus) {
                $q->where('tahun_lulus', $tahunLulus);
            });
        }

        if ($fakultasId) {
            $query->whereHas('siswa', function($q) use ($fakultasId) {
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

        $fakultas = \App\Models\Fakultas::all();

        return view('admin.tracer-study.laporan', compact('data', 'analisis', 'fakultas', 'tahunLulus', 'fakultasId'));
    }

    public function exportExcel(Request $request)
    {
        // Implementasi export ke Excel menggunakan Laravel Excel
        // return Excel::download(new TracerStudyExport($request->all()), 'tracer-study.xlsx');
    }

    // Siswa/Alumni Methods
    public function alumniForm()
    {
        $siswa = auth()->user()->siswa;
        
        // Cek apakah siswa sudah lulus
        if ($siswa->status !== 'lulus') {
            return redirect()->route('dashboard')
                           ->with('error', 'Fitur ini hanya untuk alumni yang sudah lulus');
        }

        // Cek apakah sudah mengisi
        $tracerStudy = TracerStudy::where('siswa_id', $siswa->id)->first();

        return view('siswa.tracer-study.form', compact('tracerStudy'));
    }

    public function alumniStore(Request $request)
    {
        $siswa = auth()->user()->siswa;

        $validated = $request->validate([
            'status_pekerjaan' => 'required|in:bekerja,wirausaha,melanjutkan_studi,belum_bekerja',
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
            'kompetensi_yang_digunakan' => 'nullable|array',
            'email_saat_ini' => 'nullable|email',
            'no_telp_saat_ini' => 'nullable|string|max:15',
            'linkedin' => 'nullable|url',
        ]);

        $validated['siswa_id'] = $siswa->id;
        $validated['tanggal_isi'] = now();

        if (isset($validated['kompetensi_yang_digunakan'])) {
            $validated['kompetensi_yang_digunakan'] = json_encode($validated['kompetensi_yang_digunakan']);
        }

        TracerStudy::updateOrCreate(
            ['siswa_id' => $siswa->id],
            $validated
        );

        return redirect()->route('siswa.tracer-study.form')
                        ->with('success', 'Terima kasih telah mengisi tracer study!');
    }

    // Private Methods
    private function getStatistik()
    {
        return [
            'total_alumni' => Siswa::where('status', 'lulus')->count(),
            'total_responden' => TracerStudy::count(),
            'bekerja' => TracerStudy::where('status_pekerjaan', 'bekerja')->count(),
            'wirausaha' => TracerStudy::where('status_pekerjaan', 'wirausaha')->count(),
            'melanjutkan_studi' => TracerStudy::where('status_pekerjaan', 'melanjutkan_studi')->count(),
            'belum_bekerja' => TracerStudy::where('status_pekerjaan', 'belum_bekerja')->count(),
            'rata_waktu_tunggu' => round(TracerStudy::where('waktu_tunggu_kerja', '>', 0)->avg('waktu_tunggu_kerja'), 2),
            'rata_kepuasan' => round(TracerStudy::where('kepuasan_pendidikan', '>', 0)->avg('kepuasan_pendidikan'), 2),
        ];
    }
}