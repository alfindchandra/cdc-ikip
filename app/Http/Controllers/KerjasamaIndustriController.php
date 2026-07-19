<?php

namespace App\Http\Controllers;

use App\Models\KerjasamaIndustri;
use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KerjasamaIndustriController extends Controller
{

   public function index(Request $request)
{
    $query = KerjasamaIndustri::with('perusahaan');
    $perusahaan = Perusahaan::where('status_kerjasama', 'aktif')->get();

    // Gunakan ->filled() untuk memastikan input ada dan tidak kosong ("")
    if ($request->filled('search')) {
        $query->where('judul', 'like', "%{$request->search}%");
    }

    if ($request->filled('jenis')) {
        $query->where('jenis_kerjasama', $request->jenis);
    }

    // Perbaikan filter lingkup
    if ($request->filled('lingkup')) {
        // PASTIKAN kolom di database Anda memang bernama 'lingkup_kerjasama'
        $query->where('lingkup_kerjasama', $request->lingkup);
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // Tambahkan ->withQueryString() agar filter tidak hilang saat pindah halaman pagination
    $kerjasama = $query->latest()->paginate(20)->withQueryString();

    return view('admin.kerjasama.index', compact('kerjasama', 'perusahaan'));
}
    public function show(KerjasamaIndustri $kerjasama)
    {
        $kerjasama->load('perusahaan');

        if (auth()->user()->isAdmin()) {
            return view('admin.kerjasama.show', compact('kerjasama'));
        } elseif (auth()->user()->isPerusahaan() && $kerjasama->perusahaan_id == auth()->user()->perusahaan->id) {
            return view('perusahaan.kerjasama.show', compact('kerjasama'));
        }

        abort(403);
    }

    public function edit(KerjasamaIndustri $kerjasama)
    {
        $perusahaan = Perusahaan::where('status_kerjasama', 'aktif')->get();
        return view('admin.kerjasama.edit', compact('kerjasama', 'perusahaan'));
    }

    /**
     * [Admin] Membuat & mengirimkan dokumen kerjasama ke perusahaan tertentu.
     * Status awal: menunggu_persetujuan_perusahaan — Perusahaan harus ACC atau tolak.
     */
    public function create()
    {
        $perusahaan = Perusahaan::where('status_kerjasama', 'aktif')->get();
        return view('admin.kerjasama.create', compact('perusahaan'));
    }

    /**
     * [Admin] Simpan kerjasama yang dikirim admin ke perusahaan.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'perusahaan_id'    => 'required|exists:perusahaan,id',
            'jenis_kerjasama'  => 'required|in:pkl,rekrutmen,pelatihan,penelitian,sponsorship,lainnya',
            'jenis_dokumen'    => 'required|in:mou,moa,surat_kerjasama',
            'bentuk_kegiatan'    => 'required|string|max:100',
            'lingkup_kerjasama'=> 'required|in:dalam_negeri,luar_negeri,swasta,lainnya',
            'judul'            => 'required|string|max:255',
            'deskripsi'        => 'nullable|string',
            'tanggal_mulai'    => 'required|date',
            'tanggal_berakhir' => 'nullable|date|after:tanggal_mulai',
            'dokumen_kerjasama'=> 'required|file|mimes:pdf|max:10240',
            'pic_sekolah'      => 'nullable|string',
            'nilai_kontrak'    => 'nullable|numeric',
            'catatan'          => 'nullable|string',
        ]);

        $kolomDokumen = [
            'mou'             => 'dokumen_mou',
            'moa'             => 'dokumen_moa',
            'surat_kerjasama' => 'dokumen_surat_kerjasama',
        ][$validated['jenis_dokumen']];

        $folderDokumen = [
            'mou'             => 'mou',
            'moa'             => 'moa',
            'surat_kerjasama' => 'surat-kerjasama',
        ][$validated['jenis_dokumen']];

        $validated[$kolomDokumen] = $request->file('dokumen_kerjasama')->store('kerjasama/' . $folderDokumen, 'public');
        unset($validated['dokumen_kerjasama']);

        $validated['pengirim'] = 'admin';
        $validated['status']   = 'menunggu_persetujuan_perusahaan';

        KerjasamaIndustri::create($validated);

        return redirect()->route('admin.kerjasama.index')
                         ->with('success', 'Dokumen kerjasama berhasil dikirim ke perusahaan. Menunggu persetujuan (ACC) perusahaan.');
    }

    public function update(Request $request, KerjasamaIndustri $kerjasama)
    {
        $validated = $request->validate([
            'perusahaan_id' => 'required|exists:perusahaan,id',
            'jenis_kerjasama' => 'required|in:pkl,rekrutmen,pelatihan,penelitian,sponsorship,lainnya',
            'jenis_dokumen' => 'required|in:mou,moa,surat_kerjasama',
            'lingkup_kerjasama' => 'required|in:dalam_negeri,luar_negeri,swasta,lainnya',
            'judul' => 'required|string|max:255',
            'bentuk_kegiatan' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_berakhir' => 'nullable|date|after:tanggal_mulai',
            'dokumen_mou' => 'nullable|file|mimes:pdf|max:10240',
            'dokumen_moa' => 'nullable|file|mimes:pdf|max:10240',
            'dokumen_kontrak' => 'nullable|file|mimes:pdf|max:10240',
            'dokumen_surat_kerjasama' => 'nullable|file|mimes:pdf|max:10240',
            'pic_sekolah' => 'nullable|string',
            'pic_industri' => 'nullable|string',
            'nilai_kontrak' => 'nullable|numeric',
            'catatan' => 'nullable|string',
        ]);

        foreach (['dokumen_mou', 'dokumen_moa', 'dokumen_kontrak', 'dokumen_surat_kerjasama'] as $field) {
            if ($request->hasFile($field)) {
                if ($kerjasama->$field) {
                    Storage::disk('public')->delete($kerjasama->$field);
                }
                $validated[$field] = $request->file($field)->store('kerjasama/' . str_replace('dokumen_', '', $field), 'public');
            }
        }

        $kerjasama->update($validated);

        return redirect()->route('admin.kerjasama.index')
                        ->with('success', 'Kerjasama berhasil diperbarui');
    }

    public function destroy(KerjasamaIndustri $kerjasama)
    {
        foreach (['dokumen_mou', 'dokumen_moa', 'dokumen_kontrak'] as $field) {
            if ($kerjasama->$field) {
                Storage::disk('public')->delete($kerjasama->$field);
            }
        }

        $kerjasama->delete();

        return back()->with('success', 'Kerjasama berhasil dihapus');
    }

    /**
     * [Tahap 2] Admin meng-ACC (menyetujui) pengajuan kerja sama beserta
     * dokumen (MoU/MoA/Surat Kerjasama) yang dipilih & dikirim perusahaan.
     * Begitu di-ACC, kerja sama langsung berstatus Aktif.
     */
    public function approveMou(KerjasamaIndustri $kerjasama)
    {
        if (!in_array($kerjasama->status, ['draft', 'proposal', 'negosiasi'])) {
            return back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        $kerjasama->update([
            'status' => 'aktif',
            'disetujui_at' => now(),
            'mou_disetujui_at' => now(),
            'alasan_penolakan' => null,
        ]);

        return back()->with('success', 'Pengajuan kerja sama berhasil disetujui (ACC). Status kini Aktif.');
    }

    /**
     * [Tahap 2] Admin menolak pengajuan kerja sama yang dikirim perusahaan.
     */
    public function rejectMou(Request $request, KerjasamaIndustri $kerjasama)
    {
        if (!in_array($kerjasama->status, ['draft', 'proposal', 'negosiasi'])) {
            return back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        $validated = $request->validate([
            'alasan_penolakan' => 'nullable|string',
        ]);

        $kerjasama->update([
            'status' => 'batal',
            'alasan_penolakan' => $validated['alasan_penolakan'] ?? null,
        ]);

        return back()->with('success', 'Pengajuan kerja sama telah ditolak.');
    }

    /**
     * Pengaturan status lanjutan oleh admin di luar alur utama
     * (mis. menandai Selesai atau Nonaktif).
     */
    public function updateStatus(Request $request, KerjasamaIndustri $kerjasama)
    {
        $validated = $request->validate([
            'status' => 'required|in:draft,proposal,negosiasi,mou_disetujui,menunggu_persetujuan_perusahaan,aktif,selesai,batal,nonaktif',
        ]);

        $kerjasama->update(['status' => $validated['status']]);

        return back()->with('success', 'Status kerjasama berhasil diperbarui');
    }

    // ===================== Perusahaan Methods =====================

    /**
     * Daftar pengajuan kerjasama milik perusahaan yang sedang login.
     */
    public function perusahaanIndex()
    {
        $perusahaan = auth()->user()->perusahaan;
        $kerjasama = KerjasamaIndustri::where('perusahaan_id', $perusahaan->id)
                                      ->latest()
                                      ->paginate(20);

        return view('perusahaan.kerjasama.index', compact('kerjasama'));
    }

    /**
     * Form untuk perusahaan MENGAJUKAN (mengirim) penawaran kerjasama baru.
     * Perusahaan MEMILIH sendiri jenis dokumen yang ingin diunggah:
     * MoU, MoA, atau Surat Kerjasama.
     */
    public function createPerusahaan()
    {
        return view('perusahaan.kerjasama.create');
    }

    /**
     * [Tahap 1] Menyimpan pengajuan kerjasama dari perusahaan beserta dokumen
     * yang dipilih (MoU/MoA/Surat Kerjasama). Status awal selalu 'proposal'
     * (menunggu ACC dari admin).
     */
    public function storePerusahaan(Request $request)
    {
        $validated = $request->validate([
            'jenis_kerjasama' => 'required|in:pkl,rekrutmen,pelatihan,penelitian,sponsorship,lainnya',
            'jenis_dokumen' => 'required|in:mou,moa,surat_kerjasama',
            'lingkup_kerjasama' => 'required|in:dalam_negeri,luar_negeri,swasta,lainnya',
            'judul' => 'required|string|max:255',
            'bentuk_kegiatan' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_berakhir' => 'nullable|date|after:tanggal_mulai',
            'dokumen_kerjasama' => 'required|file|mimes:pdf|max:10240',
            'pic_industri' => 'nullable|string|max:150',
            'jabatan_pic_industri' => 'nullable|string|max:100',
            'no_telp_pic_industri' => 'nullable|string|max:20',
            'email_pic_industri' => 'nullable|email|max:255',
            'nilai_kontrak' => 'nullable|numeric',
            'catatan' => 'nullable|string',
        ]);

        // Simpan file yang diunggah ke kolom yang sesuai dengan jenis dokumen
        // yang dipilih perusahaan (MoU / MoA / Surat Kerjasama).
        $kolomDokumen = [
            'mou' => 'dokumen_mou',
            'moa' => 'dokumen_moa',
            'surat_kerjasama' => 'dokumen_surat_kerjasama',
        ][$validated['jenis_dokumen']];

        $folderDokumen = [
            'mou' => 'mou',
            'moa' => 'moa',
            'surat_kerjasama' => 'surat-kerjasama',
        ][$validated['jenis_dokumen']];

        $validated[$kolomDokumen] = $request->file('dokumen_kerjasama')->store('kerjasama/' . $folderDokumen, 'public');
        unset($validated['dokumen_kerjasama']);

        $validated['perusahaan_id'] = auth()->user()->perusahaan->id;
        // Pengajuan baru dari perusahaan selalu berstatus 'proposal'
        // dan menunggu persetujuan (ACC) langsung dari admin.
        $validated['status'] = 'proposal';

        KerjasamaIndustri::create($validated);

        return redirect()->route('perusahaan.kerjasama.index')
                        ->with('success', 'Pengajuan kerja sama beserta dokumen berhasil dikirim. Menunggu persetujuan (ACC) dari admin.');
    }

    /**
     * Perusahaan hanya dapat MEMBATALKAN pengajuannya sendiri selama
     * belum di-ACC oleh admin.
     */
    public function updateStatusPerusahaan(Request $request, KerjasamaIndustri $kerjasama)
    {
        if ($kerjasama->perusahaan_id != auth()->user()->perusahaan->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah kerjasama ini.');
        }

        if (!in_array($kerjasama->status, ['draft', 'proposal', 'negosiasi'])) {
            return back()->with('error', 'Pengajuan ini sudah diproses oleh admin dan tidak dapat dibatalkan secara mandiri.');
        }

        $kerjasama->update(['status' => 'batal']);

        return back()->with('success', 'Pengajuan kerjasama berhasil dibatalkan.');
    }

    /**
     * [Perusahaan] Menyetujui (ACC) kerjasama yang dikirimkan oleh Admin.
     */
    public function approvePerusahaan(KerjasamaIndustri $kerjasama)
    {
        if ($kerjasama->perusahaan_id != auth()->user()->perusahaan->id) {
            abort(403);
        }

        if ($kerjasama->pengirim !== 'admin' || $kerjasama->status !== 'menunggu_persetujuan_perusahaan') {
            return back()->with('error', 'Kerjasama ini tidak dapat disetujui.');
        }

        $kerjasama->update([
            'status'                 => 'aktif',
            'disetujui_perusahaan_at'=> now(),
            'disetujui_at'           => now(),
            'alasan_penolakan_perusahaan' => null,
        ]);

        return back()->with('success', 'Kerjasama berhasil disetujui (ACC). Status kini Aktif.');
    }

    /**
     * [Perusahaan] Menolak kerjasama yang dikirimkan oleh Admin.
     */
    public function rejectPerusahaan(Request $request, KerjasamaIndustri $kerjasama)
    {
        if ($kerjasama->perusahaan_id != auth()->user()->perusahaan->id) {
            abort(403);
        }

        if ($kerjasama->pengirim !== 'admin' || $kerjasama->status !== 'menunggu_persetujuan_perusahaan') {
            return back()->with('error', 'Kerjasama ini tidak dapat ditolak.');
        }

        $validated = $request->validate([
            'alasan_penolakan_perusahaan' => 'nullable|string',
        ]);

        $kerjasama->update([
            'status'                      => 'batal',
            'alasan_penolakan_perusahaan' => $validated['alasan_penolakan_perusahaan'] ?? null,
        ]);

        return back()->with('success', 'Kerjasama telah ditolak.');
    }

    // ─── Import Data Kerjasama ─────────────────────────────────────────────────

    /**
     * Download template CSV untuk import kerjasama industri.
     */
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="template-import-kerjasama.csv"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF"); // BOM UTF-8
            fputcsv($file, [
                'nama_perusahaan',
                'jenis_kerjasama',
                'jenis_dokumen',
                'lingkup_kerjasama',
                'bentuk_kegiatan',
                'judul',
                'deskripsi',
                'tanggal_mulai',
                'tanggal_berakhir',
                'status',
                'nilai_kontrak',
                'pic_sekolah',
                'catatan',
            ]);
            fputcsv($file, [
                'PT Maju Sejahtera',
                'pkl',
                'mou',
                'dalam_negeri',
                'Pendidikan',
                'Kerjasama PKL Mahasiswa 2025',
                'Kerjasama PKL untuk mahasiswa Teknik Informatika',
                '2025-01-01',
                '2025-12-31',
                'aktif',
                '',
                'Dr. Budi Santoso',
                '',
            ]);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Proses import data kerjasama industri dari file CSV.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:5120',
        ], [
            'file.required' => 'File CSV harus diunggah.',
            'file.mimes'    => 'File harus berformat CSV.',
            'file.max'      => 'Ukuran file maksimal 5 MB.',
        ]);

        $path   = $request->file('file')->getRealPath();
        $handle = fopen($path, 'r');

        if (!$handle) {
            return back()->with('error', 'Gagal membaca file CSV.');
        }

        $header = fgetcsv($handle);
        if ($header) {
            $header[0] = ltrim($header[0], "\xEF\xBB\xBF");
            $header    = array_map('trim', $header);
        }

        $requiredCols = ['nama_perusahaan', 'judul', 'jenis_kerjasama'];
        foreach ($requiredCols as $col) {
            if (!in_array($col, $header)) {
                fclose($handle);
                return back()->with('error', "Kolom wajib '{$col}' tidak ditemukan di file CSV.");
            }
        }

        $imported = 0;
        $skipped  = 0;
        $errors   = [];
        $row      = 1;

        $validJenis    = ['pkl', 'rekrutmen', 'pelatihan', 'penelitian', 'sponsorship', 'lainnya'];
        $validDokumen  = ['mou', 'moa', 'surat_kerjasama'];
        $validLingkup  = ['dalam_negeri', 'luar_negeri', 'swasta', 'lainnya'];
        $validStatus   = ['draft', 'proposal', 'aktif', 'selesai', 'batal', 'nonaktif'];

        while (($line = fgetcsv($handle)) !== false) {
            $row++;
            if (count($line) < 2) continue;

            $data         = array_combine($header, array_pad($line, count($header), null));
            $namaPerusahaan = trim($data['nama_perusahaan'] ?? '');
            $judul          = trim($data['judul']           ?? '');

            if (empty($namaPerusahaan) || empty($judul)) {
                $skipped++;
                continue;
            }

            // Cari perusahaan berdasarkan nama (case-insensitive)
            $perusahaan = Perusahaan::whereRaw('LOWER(nama_pt) LIKE ?', ['%' . strtolower($namaPerusahaan) . '%'])
                ->orWhereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($namaPerusahaan) . '%'])
                ->first();

            if (!$perusahaan) {
                $errors[] = "Baris {$row}: Perusahaan '{$namaPerusahaan}' tidak ditemukan dalam database.";
                $skipped++;
                continue;
            }

            $jenis   = trim($data['jenis_kerjasama'] ?? 'lainnya');
            $dokumen = trim($data['jenis_dokumen']   ?? 'mou');
            $lingkup = trim($data['lingkup_kerjasama'] ?? 'dalam_negeri');
            $status  = trim($data['status'] ?? 'draft');

            if (!in_array($jenis,   $validJenis))   $jenis   = 'lainnya';
            if (!in_array($dokumen, $validDokumen)) $dokumen = 'mou';
            if (!in_array($lingkup, $validLingkup)) $lingkup = 'dalam_negeri';
            if (!in_array($status,  $validStatus))  $status  = 'draft';

            KerjasamaIndustri::create([
                'perusahaan_id'    => $perusahaan->id,
                'jenis_kerjasama'  => $jenis,
                'jenis_dokumen'    => $dokumen,
                'lingkup_kerjasama'=> $lingkup,
                'bentuk_kegiatan'  => trim($data['bentuk_kegiatan'] ?? 'Pendidikan'),
                'judul'            => $judul,
                'deskripsi'        => $data['deskripsi']      ?? null,
                'tanggal_mulai'    => !empty($data['tanggal_mulai'])    ? $data['tanggal_mulai']    : now()->toDateString(),
                'tanggal_berakhir' => !empty($data['tanggal_berakhir']) ? $data['tanggal_berakhir'] : null,
                'status'           => $status,
                'nilai_kontrak'    => is_numeric($data['nilai_kontrak'] ?? '') ? $data['nilai_kontrak'] : null,
                'pic_sekolah'      => $data['pic_sekolah'] ?? null,
                'catatan'          => $data['catatan']     ?? null,
                'pengirim'         => 'admin',
            ]);

            $imported++;
        }

        fclose($handle);

        $message = "Import selesai: {$imported} data kerjasama berhasil ditambahkan, {$skipped} dilewati.";
        if (!empty($errors)) {
            $message .= ' Catatan: ' . implode('; ', array_slice($errors, 0, 5));
            if (count($errors) > 5) {
                $message .= ' ... dan ' . (count($errors) - 5) . ' pesan lainnya.';
            }
        }

        return redirect()->route('admin.kerjasama.index')->with('success', $message);
    }
}