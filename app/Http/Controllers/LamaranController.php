<?php

namespace App\Http\Controllers;

use App\Models\Lamaran;
use App\Models\LowonganKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LamaranController extends Controller
{
    /* =======================
     * SISWA METHODS
     * ======================= */

    public function index(Request $request)
    {
        $siswa = auth()->user()->siswa;

        $query = $siswa->lamaran()->with(['lowongan.perusahaan']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $lamaran = $query->latest()->paginate(20)->withQueryString();

        return view('siswa.lamaran.index', compact('lamaran'));
    }

    public function create(LowonganKerja $lowongan)
    {
        $siswa = auth()->user()->siswa;

        // Cek sudah melamar
        $sudahMelamar = $siswa->lamaran()
            ->where('lowongan_id', $lowongan->id)
            ->exists();

        if ($sudahMelamar) {
            return redirect()->route('siswa.lamaran.index')
                ->with('info', 'Anda sudah melamar pekerjaan ini');
        }

        // Cek lowongan masih aktif
        if ($lowongan->tanggal_berakhir < now()) {
            return redirect()->route('siswa.lowongan.show', $lowongan)
                ->with('error', 'Lowongan ini sudah ditutup');
        }

        $lowongan->load('perusahaan');
        return view('siswa.lamaran.create', compact('lowongan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'lowongan_id'     => 'required|exists:lowongan_kerja,id',
            'cv'              => 'required|file|mimes:pdf|max:5120',
            'surat_lamaran'   => 'nullable|file|mimes:pdf|max:5120',
            'portofolio'      => 'nullable|file|mimes:pdf,zip|max:10240',
            'catatan'         => 'nullable|string|max:1000',
        ], [
            'cv.required' => 'CV wajib diupload',
            'cv.mimes'    => 'CV harus berformat PDF',
            'cv.max'      => 'Ukuran CV maksimal 5MB',
        ]);

        $siswa = auth()->user()->siswa;

        // Cek duplikasi lamaran
        $exists = Lamaran::where('siswa_id', $siswa->id)
            ->where('lowongan_id', $validated['lowongan_id'])
            ->exists();

        if ($exists) {
            return back()->with('error', 'Anda sudah melamar lowongan ini');
        }

        $data = [
            'lowongan_id'     => $validated['lowongan_id'],
            'siswa_id'        => $siswa->id,
            'status'          => 'dikirim',
            'catatan'         => $validated['catatan'] ?? null,
            'tanggal_melamar' => now(),
        ];

        if ($request->hasFile('cv')) {
            $data['cv'] = $request->file('cv')->store('lamaran/cv', 'public');
        }

        if ($request->hasFile('surat_lamaran')) {
            $data['surat_lamaran'] = $request->file('surat_lamaran')->store('lamaran/surat', 'public');
        }

        if ($request->hasFile('portofolio')) {
            $data['portofolio'] = $request->file('portofolio')->store('lamaran/portofolio', 'public');
        }

        Lamaran::create($data);

        // Tambah jumlah pelamar
        LowonganKerja::find($validated['lowongan_id'])
            ->increment('jumlah_pelamar');

        return redirect()->route('siswa.lamaran.index')
            ->with('success', 'Lamaran berhasil dikirim');
    }

    public function show(Lamaran $lamaran)
    {
        $lamaran->load('lowongan.perusahaan', 'siswa.user');

        if (auth()->user()->isAdmin()) {
            return view('admin.lamaran.show', compact('lamaran'));
        }

        if (auth()->user()->isSiswa() &&
            $lamaran->siswa_id === auth()->user()->siswa->id) {
            return view('siswa.lamaran.show', compact('lamaran'));
        }

        if (auth()->user()->isPerusahaan() &&
            $lamaran->lowongan->perusahaan_id === auth()->user()->perusahaan->id) {
            return view('perusahaan.lamaran.show', compact('lamaran'));
        }

        abort(403);
    }

    public function destroy(Lamaran $lamaran)
    {
        // Validasi kepemilikan
        if ($lamaran->siswa_id !== auth()->user()->siswa->id) {
            abort(403);
        }

        // Status yang boleh dibatalkan
        if (!in_array($lamaran->status, ['dikirim', 'dilihat'])) {
            return back()->with('error', 'Lamaran tidak dapat dibatalkan');
        }

        if ($lamaran->cv) Storage::disk('public')->delete($lamaran->cv);
        if ($lamaran->surat_lamaran) Storage::disk('public')->delete($lamaran->surat_lamaran);
        if ($lamaran->portofolio) Storage::disk('public')->delete($lamaran->portofolio);

        // Kurangi jumlah pelamar
        $lamaran->lowongan->decrement('jumlah_pelamar');

        $lamaran->delete();

        return back()->with('success', 'Lamaran berhasil dibatalkan');
    }

    /* =======================
     * ADMIN METHODS
     * ======================= */

    public function adminIndex(Request $request)
    {
        $query = Lamaran::with(['siswa.user', 'lowongan.perusahaan']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $lamaran = $query->latest()->paginate(20);

        return view('admin.lamaran.index', compact('lamaran'));
    }

    /* =======================
     * PERUSAHAAN METHODS
     * ======================= */

    public function perusahaanIndex(Request $request)
    {
        $perusahaan = auth()->user()->perusahaan;

        $query = Lamaran::whereHas('lowongan', function ($q) use ($perusahaan) {
            $q->where('perusahaan_id', $perusahaan->id);
        })->with(['siswa.user', 'lowongan']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $lamaran = $query->latest()->paginate(20);

        return view('perusahaan.lamaran.index', compact('lamaran'));
    }

    public function updateStatus(Request $request, Lamaran $lamaran)
    {
        $validated = $request->validate([
            'status'   => 'required|in:dilihat,diproses,diterima,ditolak',
            'catatan'  => 'nullable|string',
        ]);

        $lamaran->update($validated);

        return back()->with('success', 'Status lamaran berhasil diperbarui');
    }
}
