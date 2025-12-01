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

    public function create()
    {
        $perusahaan = Perusahaan::where('status_kerjasama', 'aktif')->get();
        return view('admin.kerjasama.create', compact('perusahaan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'perusahaan_id' => 'required|exists:perusahaan,id',
            'jenis_kerjasama' => 'required|in:pkl,rekrutmen,pelatihan,penelitian,sponsorship,lainnya',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_berakhir' => 'nullable|date|after:tanggal_mulai',
            'dokumen_mou' => 'nullable|file|mimes:pdf|max:10240',
            'pic_sekolah' => 'nullable|string',
            'pic_industri' => 'nullable|string',
            'nilai_kontrak' => 'nullable|numeric',
            'catatan' => 'nullable|string',
        ]);

        if ($request->hasFile('dokumen_mou')) {
            $validated['dokumen_mou'] = $request->file('dokumen_mou')->store('kerjasama/mou', 'public');
        }

        $validated['status'] = 'draft';

        KerjasamaIndustri::create($validated);

        return redirect()->route('admin.kerjasama.index')
                        ->with('success', 'Kerjasama berhasil ditambahkan');
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
            'pic_sekolah' => 'nullable|string',
            'pic_industri' => 'nullable|string',
            'nilai_kontrak' => 'nullable|numeric',
            'catatan' => 'nullable|string',
        ]);

        if ($request->hasFile('dokumen_mou')) {
            if ($kerjasama->dokumen_mou) {
                Storage::disk('public')->delete($kerjasama->dokumen_mou);
            }
            $validated['dokumen_mou'] = $request->file('dokumen_mou')->store('kerjasama/mou', 'public');
        }

        $kerjasama->update($validated);

        return redirect()->route('admin.kerjasama.index')
                        ->with('success', 'Kerjasama berhasil diperbarui');
    }

    public function destroy(KerjasamaIndustri $kerjasama)
    {
        if ($kerjasama->dokumen_mou) {
            Storage::disk('public')->delete($kerjasama->dokumen_mou);
        }

        $kerjasama->delete();

        return back()->with('success', 'Kerjasama berhasil dihapus');
    }

    public function updateStatus(Request $request, KerjasamaIndustri $kerjasama)
    {
        $validated = $request->validate([
            'status' => 'required|in:draft,proposal,negosiasi,aktif,selesai,nonaktif',
        ]);

        $kerjasama->update(['status' => $validated['status']]);

        return back()->with('success', 'Status kerjasama berhasil diperbarui');
    }

    // Perusahaan Methods
    public function perusahaanIndex()
    {
        $perusahaan = auth()->user()->perusahaan;
        $kerjasama = KerjasamaIndustri::where('perusahaan_id', $perusahaan->id)
                                      ->latest()
                                      ->paginate(20);

        return view('perusahaan.kerjasama.index', compact('kerjasama'));
    }
    public function updateStatusPerusahaan(Request $request, KerjasamaIndustri $kerjasama)
{
    // Pastikan hanya perusahaan yang terkait yang bisa update
    if ($kerjasama->perusahaan_id != auth()->user()->perusahaan->id) {
        abort(403, 'Anda tidak memiliki akses untuk mengubah kerjasama ini.');
    }

    $validated = $request->validate([
        'status' => 'required|in:draft,proposal,negosiasi,aktif,selesai,batal',
    ]);

    $kerjasama->update(['status' => $validated['status']]);

    return back()->with('success', 'Status kerjasama berhasil diperbarui');
}
}