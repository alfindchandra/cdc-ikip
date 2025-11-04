<?php

namespace App\Http\Controllers;

use App\Models\Lamaran;
use App\Models\LowonganKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LamaranController extends Controller
{
    // Siswa Methods
    public function index()
    {
        $siswa = auth()->user()->siswa;
        $lamaran = Lamaran::where('siswa_id', $siswa->id)
                         ->with('lowongan.perusahaan')
                         ->latest()
                         ->paginate(20);

        return view('siswa.lamaran.index', compact('lamaran'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'lowongan_id' => 'required|exists:lowongan_kerja,id',
            'cv' => 'required|file|mimes:pdf|max:5120',
            'surat_lamaran' => 'nullable|file|mimes:pdf|max:5120',
            'portofolio' => 'nullable|file|mimes:pdf,zip|max:10240',
            'catatan' => 'nullable|string',
        ]);

        $siswa = auth()->user()->siswa;

        // Check if already applied
        $exists = Lamaran::where('siswa_id', $siswa->id)
                        ->where('lowongan_id', $validated['lowongan_id'])
                        ->exists();

        if ($exists) {
            return back()->with('error', 'Anda sudah melamar lowongan ini');
        }

        $data = [
            'lowongan_id' => $validated['lowongan_id'],
            'siswa_id' => $siswa->id,
            'status' => 'dikirim',
            'catatan' => $validated['catatan'],
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

        // Increment jumlah pelamar
        LowonganKerja::find($validated['lowongan_id'])->increment('jumlah_pelamar');

        return redirect()->route('siswa.lamaran.index')
                        ->with('success', 'Lamaran berhasil dikirim');
    }

    public function show(Lamaran $lamaran)
    {
        $lamaran->load('lowongan.perusahaan', 'siswa.user');

        if (auth()->user()->isAdmin()) {
            return view('admin.lamaran.show', compact('lamaran'));
        } elseif (auth()->user()->isSiswa() && $lamaran->siswa_id == auth()->user()->siswa->id) {
            return view('siswa.lamaran.show', compact('lamaran'));
        } elseif (auth()->user()->isPerusahaan() && $lamaran->lowongan->perusahaan_id == auth()->user()->perusahaan->id) {
            return view('perusahaan.lamaran.show', compact('lamaran'));
        }

        abort(403);
    }

    public function destroy(Lamaran $lamaran)
    {
        // Only allow deletion if status is 'dikirim'
        if ($lamaran->status !== 'dikirim') {
            return back()->with('error', 'Lamaran tidak dapat dibatalkan');
        }

        if ($lamaran->cv) Storage::disk('public')->delete($lamaran->cv);
        if ($lamaran->surat_lamaran) Storage::disk('public')->delete($lamaran->surat_lamaran);
        if ($lamaran->portofolio) Storage::disk('public')->delete($lamaran->portofolio);

        // Decrement jumlah pelamar
        $lamaran->lowongan->decrement('jumlah_pelamar');

        $lamaran->delete();

        return back()->with('success', 'Lamaran berhasil dibatalkan');
    }

    // Admin Methods
    public function adminIndex(Request $request)
    {
        $query = Lamaran::with(['siswa.user', 'lowongan.perusahaan']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $lamaran = $query->latest()->paginate(20);

        return view('admin.lamaran.index', compact('lamaran'));
    }

    // Perusahaan Methods
    public function perusahaanIndex(Request $request)
    {
        $perusahaan = auth()->user()->perusahaan;
        
        $query = Lamaran::whereHas('lowongan', function($q) use ($perusahaan) {
            $q->where('perusahaan_id', $perusahaan->id);
        })->with(['siswa.user', 'lowongan']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $lamaran = $query->latest()->paginate(20);

        return view('perusahaan.lamaran.index', compact('lamaran'));
    }

    public function updateStatus(Request $request, Lamaran $lamaran)
    {
        $validated = $request->validate([
            'status' => 'required|in:dilihat,diproses,diterima,ditolak',
            'catatan' => 'nullable|string',
        ]);

        $lamaran->update($validated);

        return back()->with('success', 'Status lamaran berhasil diperbarui');
    }
}
