<?php

namespace App\Http\Controllers;

use App\Models\KerjasamaIndustri;
use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KerjasamaIndustriController extends Controller
{
    /**
     * Alur kerja sama (lihat juga KerjasamaIndustri::tahapanLabel()):
     *
     *  1. Perusahaan mengajukan & mengirim dokumen MoU      -> status: proposal
     *  2. Admin (kampus) meninjau & meng-ACC / menolak MoU  -> status: mou_disetujui / batal
     *  3. Admin membuat & mengunggah dokumen MoA + Kontrak  -> status: menunggu_persetujuan_perusahaan
     *  4. Perusahaan meninjau & menyetujui / menolak         -> status: aktif / negosiasi
     */

    // ===================== Admin Methods =====================

    public function index(Request $request)
    {
        $query = KerjasamaIndustri::with('perusahaan');

        if ($request->has('search')) {
            $query->where('judul', 'like', "%{$request->search}%");
        }

        if ($request->has('jenis')) {
            $query->where('jenis_kerjasama', $request->jenis);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $kerjasama = $query->latest()->paginate(20);

        return view('admin.kerjasama.index', compact('kerjasama'));
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

    public function update(Request $request, KerjasamaIndustri $kerjasama)
    {
        $validated = $request->validate([
            'perusahaan_id' => 'required|exists:perusahaan,id',
            'jenis_kerjasama' => 'required|in:pkl,rekrutmen,pelatihan,penelitian,sponsorship,lainnya',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_berakhir' => 'nullable|date|after:tanggal_mulai',
            'dokumen_mou' => 'nullable|file|mimes:pdf|max:10240',
            'dokumen_moa' => 'nullable|file|mimes:pdf|max:10240',
            'dokumen_kontrak' => 'nullable|file|mimes:pdf|max:10240',
            'pic_sekolah' => 'nullable|string',
            'pic_industri' => 'nullable|string',
            'nilai_kontrak' => 'nullable|numeric',
            'catatan' => 'nullable|string',
        ]);

        foreach (['dokumen_mou', 'dokumen_moa', 'dokumen_kontrak'] as $field) {
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
     * [Tahap 2] Admin meng-ACC (menyetujui) dokumen MoU yang dikirim perusahaan.
     * Setelah disetujui, admin diarahkan untuk mengunggah dokumen MoA & Kontrak.
     */
    public function approveMou(KerjasamaIndustri $kerjasama)
    {
        if (!in_array($kerjasama->status, ['draft', 'proposal', 'negosiasi'])) {
            return back()->with('error', 'MoU pada pengajuan ini sudah diproses sebelumnya.');
        }

        $kerjasama->update([
            'status' => 'mou_disetujui',
            'mou_disetujui_at' => now(),
            'alasan_penolakan' => null,
        ]);

        return back()->with('success', 'MoU berhasil disetujui (ACC). Silakan lengkapi dan unggah dokumen MoA & Kontrak.');
    }

    /**
     * [Tahap 2] Admin menolak dokumen MoU yang dikirim perusahaan.
     */
    public function rejectMou(Request $request, KerjasamaIndustri $kerjasama)
    {
        if (!in_array($kerjasama->status, ['draft', 'proposal', 'negosiasi'])) {
            return back()->with('error', 'MoU pada pengajuan ini sudah diproses sebelumnya.');
        }

        $validated = $request->validate([
            'alasan_penolakan' => 'nullable|string',
        ]);

        $kerjasama->update([
            'status' => 'batal',
            'alasan_penolakan' => $validated['alasan_penolakan'] ?? null,
        ]);

        return back()->with('success', 'Pengajuan MoU telah ditolak.');
    }

    /**
     * [Tahap 3] Admin (kampus) membuat & mengunggah dokumen MoA dan Kontrak
     * setelah MoU disetujui. Setelah diunggah, menunggu persetujuan (ACC) perusahaan.
     */
    public function storeMoaKontrak(Request $request, KerjasamaIndustri $kerjasama)
    {
        if ($kerjasama->status !== 'mou_disetujui') {
            return back()->with('error', 'MoA & Kontrak hanya dapat diunggah setelah MoU disetujui.');
        }

        $validated = $request->validate([
            'dokumen_moa' => 'required|file|mimes:pdf|max:10240',
            'dokumen_kontrak' => 'required|file|mimes:pdf|max:10240',
            'pic_sekolah' => 'nullable|string',
            'nilai_kontrak' => 'nullable|numeric',
        ]);

        foreach (['dokumen_moa', 'dokumen_kontrak'] as $field) {
            if ($kerjasama->$field) {
                Storage::disk('public')->delete($kerjasama->$field);
            }
            $validated[$field] = $request->file($field)->store('kerjasama/' . str_replace('dokumen_', '', $field), 'public');
        }

        $validated['status'] = 'menunggu_persetujuan_perusahaan';
        $validated['moa_kontrak_diunggah_at'] = now();

        $kerjasama->update($validated);

        return back()->with('success', 'Dokumen MoA & Kontrak berhasil diunggah. Menunggu persetujuan (ACC) dari perusahaan.');
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
     * Form untuk perusahaan MENGAJUKAN (mengirim) penawaran kerjasama baru
     * beserta dokumen MoU. Dokumen MoA & Kontrak akan disiapkan oleh
     * kampus setelah MoU disetujui (ACC).
     */
    public function createPerusahaan()
    {
        return view('perusahaan.kerjasama.create');
    }

    /**
     * [Tahap 1] Menyimpan pengajuan kerjasama dari perusahaan beserta dokumen MoU.
     * Status awal selalu 'proposal' (menunggu review/ACC MoU oleh admin).
     */
    public function storePerusahaan(Request $request)
    {
        $validated = $request->validate([
            'jenis_kerjasama' => 'required|in:pkl,rekrutmen,pelatihan,penelitian,sponsorship,lainnya',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_berakhir' => 'nullable|date|after:tanggal_mulai',
            'dokumen_mou' => 'required|file|mimes:pdf|max:10240',
            'pic_industri' => 'nullable|string',
            'nilai_kontrak' => 'nullable|numeric',
            'catatan' => 'nullable|string',
        ]);

        $validated['dokumen_mou'] = $request->file('dokumen_mou')->store('kerjasama/mou', 'public');
        $validated['perusahaan_id'] = auth()->user()->perusahaan->id;
        // Pengajuan baru dari perusahaan selalu berstatus 'proposal'
        // dan menunggu persetujuan (ACC) MoU dari admin.
        $validated['status'] = 'proposal';

        KerjasamaIndustri::create($validated);

        return redirect()->route('perusahaan.kerjasama.index')
                        ->with('success', 'Dokumen MoU berhasil dikirim. Menunggu persetujuan (ACC) dari admin.');
    }

    /**
     * Perusahaan hanya dapat MEMBATALKAN pengajuannya sendiri selama
     * MoU belum disetujui (ACC) oleh admin.
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
     * [Tahap 4] Perusahaan menyetujui (ACC) dokumen MoA & Kontrak
     * yang telah disiapkan oleh kampus. Kerjasama menjadi Aktif.
     */
    public function approveByPerusahaan(KerjasamaIndustri $kerjasama)
    {
        if ($kerjasama->perusahaan_id != auth()->user()->perusahaan->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah kerjasama ini.');
        }

        if ($kerjasama->status !== 'menunggu_persetujuan_perusahaan') {
            return back()->with('error', 'Tidak ada dokumen MoA/Kontrak yang sedang menunggu persetujuan Anda.');
        }

        $kerjasama->update([
            'status' => 'aktif',
            'disetujui_perusahaan_at' => now(),
            'alasan_penolakan' => null,
        ]);

        return back()->with('success', 'MoA & Kontrak berhasil disetujui (ACC). Kerjasama kini berstatus Aktif.');
    }

    /**
     * [Tahap 4] Perusahaan menolak dokumen MoA & Kontrak yang disiapkan kampus.
     * Status dikembalikan ke 'negosiasi' agar admin dapat merevisi dokumen.
     */
    public function rejectByPerusahaan(Request $request, KerjasamaIndustri $kerjasama)
    {
        if ($kerjasama->perusahaan_id != auth()->user()->perusahaan->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah kerjasama ini.');
        }

        if ($kerjasama->status !== 'menunggu_persetujuan_perusahaan') {
            return back()->with('error', 'Tidak ada dokumen MoA/Kontrak yang sedang menunggu persetujuan Anda.');
        }

        $validated = $request->validate([
            'alasan_penolakan' => 'nullable|string',
        ]);

        $kerjasama->update([
            'status' => 'negosiasi',
            'alasan_penolakan' => $validated['alasan_penolakan'] ?? null,
        ]);

        return back()->with('success', 'Dokumen MoA & Kontrak ditolak. Admin akan meninjau kembali dan merevisi dokumen.');
    }
}