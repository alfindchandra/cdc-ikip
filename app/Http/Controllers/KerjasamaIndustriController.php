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
            'deskripsi' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_berakhir' => 'nullable|date|after:tanggal_mulai',
            'dokumen_kerjasama' => 'required|file|mimes:pdf|max:10240',
            'pic_industri' => 'nullable|string',
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
}