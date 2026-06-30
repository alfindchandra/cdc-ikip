<?php

namespace App\Http\Controllers;

use App\Models\KerjasamaIndustri;
use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KerjasamaIndustriController extends Controller
{
    // Admin Methods
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
     * Admin meng-ACC (menyetujui) atau memproses status pengajuan kerjasama
     * yang dikirim oleh perusahaan. Hanya admin yang berwenang mengubah
     * status menjadi aktif/selesai/batal/dll.
     */
    public function updateStatus(Request $request, KerjasamaIndustri $kerjasama)
    {
        $validated = $request->validate([
            'status' => 'required|in:draft,proposal,negosiasi,aktif,selesai,batal,nonaktif',
        ]);

        $kerjasama->update(['status' => $validated['status']]);

        $pesan = $validated['status'] === 'aktif'
            ? 'Pengajuan kerjasama telah disetujui (ACC) dan kini berstatus Aktif.'
            : 'Status kerjasama berhasil diperbarui';

        return back()->with('success', $pesan);
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
     */
    public function createPerusahaan()
    {
        return view('perusahaan.kerjasama.create');
    }

    /**
     * Menyimpan pengajuan kerjasama dari perusahaan.
     * Status awal selalu 'proposal' (menunggu persetujuan/ACC admin).
     */
    public function storePerusahaan(Request $request)
    {
        $validated = $request->validate([
            'jenis_kerjasama' => 'required|in:pkl,rekrutmen,pelatihan,penelitian,sponsorship,lainnya',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_berakhir' => 'nullable|date|after:tanggal_mulai',
            'dokumen_mou' => 'nullable|file|mimes:pdf|max:10240',
            'dokumen_moa' => 'nullable|file|mimes:pdf|max:10240',
            'dokumen_kontrak' => 'nullable|file|mimes:pdf|max:10240',
            'pic_industri' => 'nullable|string',
            'nilai_kontrak' => 'nullable|numeric',
            'catatan' => 'nullable|string',
        ]);

        foreach (['dokumen_mou', 'dokumen_moa', 'dokumen_kontrak'] as $field) {
            if ($request->hasFile($field)) {
                $validated[$field] = $request->file($field)->store('kerjasama/' . str_replace('dokumen_', '', $field), 'public');
            }
        }

        $validated['perusahaan_id'] = auth()->user()->perusahaan->id;
        // Pengajuan baru dari perusahaan selalu berstatus 'proposal'
        // dan menunggu persetujuan (ACC) dari admin.
        $validated['status'] = 'proposal';

        KerjasamaIndustri::create($validated);

        return redirect()->route('perusahaan.kerjasama.index')
                        ->with('success', 'Pengajuan kerjasama berhasil dikirim. Menunggu persetujuan (ACC) dari admin.');
    }

    /**
     * Perusahaan hanya dapat MEMBATALKAN pengajuannya sendiri selama
     * belum disetujui (acc) oleh admin. Tidak diizinkan menyetujui
     * statusnya sendiri (mis. mengubah ke 'aktif'/'selesai').
     */
    public function updateStatusPerusahaan(Request $request, KerjasamaIndustri $kerjasama)
    {
        // Pastikan hanya perusahaan yang terkait yang bisa update
        if ($kerjasama->perusahaan_id != auth()->user()->perusahaan->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah kerjasama ini.');
        }

        // Perusahaan tidak boleh meng-ACC kerjasamanya sendiri,
        // satu-satunya aksi yang diizinkan adalah membatalkan pengajuan.
        if (!in_array($kerjasama->status, ['draft', 'proposal', 'negosiasi'])) {
            return back()->with('error', 'Pengajuan ini sudah diproses oleh admin dan tidak dapat dibatalkan secara mandiri.');
        }

        $kerjasama->update(['status' => 'batal']);

        return back()->with('success', 'Pengajuan kerjasama berhasil dibatalkan.');
    }
}