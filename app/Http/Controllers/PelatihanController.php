<?php

namespace App\Http\Controllers;

use App\Models\Pelatihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PelatihanController extends Controller
{
    // Admin Methods
    public function index(Request $request)
    {
        $query = Pelatihan::query();

        if ($request->has('search')) {
            $query->where('judul', 'like', "%{$request->search}%");
        }

        if ($request->has('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $pelatihan = $query->latest()->paginate(20);

        return view('admin.pelatihan.index', compact('pelatihan'));
    }

    public function create()
    {
        return view('admin.pelatihan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'jenis' => 'required|in:soft_skill,hard_skill,sertifikasi,pembekalan',
            'instruktur' => 'nullable|string',
            'tempat' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'kuota' => 'nullable|integer',
            'biaya' => 'nullable|numeric',
            'materi' => 'nullable|file|mimes:pdf|max:10240',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('materi')) {
            $validated['materi'] = $request->file('materi')->store('pelatihan/materi', 'public');
        }

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('pelatihan/thumbnails', 'public');
        }

        $validated['status'] = 'draft';

        Pelatihan::create($validated);

        return redirect()->route('admin.pelatihan.index')
                        ->with('success', 'Pelatihan berhasil ditambahkan');
    }

    public function edit(Pelatihan $pelatihan)
    {
        return view('admin.pelatihan.edit', compact('pelatihan'));
    }

    public function update(Request $request, Pelatihan $pelatihan)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'jenis' => 'required|in:soft_skill,hard_skill,sertifikasi,pembekalan',
            'instruktur' => 'nullable|string',
            'tempat' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'kuota' => 'nullable|integer',
            'biaya' => 'nullable|numeric',
            'materi' => 'nullable|file|mimes:pdf|max:10240',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('materi')) {
            if ($pelatihan->materi) {
                Storage::disk('public')->delete($pelatihan->materi);
            }
            $validated['materi'] = $request->file('materi')->store('pelatihan/materi', 'public');
        }

        if ($request->hasFile('thumbnail')) {
            if ($pelatihan->thumbnail) {
                Storage::disk('public')->delete($pelatihan->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')->store('pelatihan/thumbnails', 'public');
        }

        $pelatihan->update($validated);

        return redirect()->route('admin.pelatihan.index')
                        ->with('success', 'Pelatihan berhasil diperbarui');
    }

    public function destroy(Pelatihan $pelatihan)
    {
        if ($pelatihan->materi) Storage::disk('public')->delete($pelatihan->materi);
        if ($pelatihan->thumbnail) Storage::disk('public')->delete($pelatihan->thumbnail);

        $pelatihan->delete();

        return back()->with('success', 'Pelatihan berhasil dihapus');
    }

    public function publish(Pelatihan $pelatihan)
    {
        $pelatihan->update(['status' => 'published']);
        return back()->with('success', 'Pelatihan berhasil dipublikasikan');
    }

    public function peserta(Pelatihan $pelatihan)
    {
        $pelatihan->load(['peserta.user']);
        return view('admin.pelatihan.peserta', compact('pelatihan'));
    }

    public function updateStatusPeserta(Request $request, $pesertaId)
    {
        $validated = $request->validate([
            'status_pendaftaran' => 'required|in:daftar,diterima,ditolak',
        ]);

        $peserta = \DB::table('peserta_pelatihan')->where('id', $pesertaId)->first();
        
        \DB::table('peserta_pelatihan')
            ->where('id', $pesertaId)
            ->update(['status_pendaftaran' => $validated['status_pendaftaran']]);

        return back()->with('success', 'Status peserta berhasil diperbarui');
    }

    public function inputNilai(Request $request, $pesertaId)
    {
        $validated = $request->validate([
            'nilai' => 'required|numeric|min:0|max:100',
            'status_kehadiran' => 'required|in:hadir,tidak_hadir,izin',
        ]);

        \DB::table('peserta_pelatihan')
            ->where('id', $pesertaId)
            ->update($validated);

        return back()->with('success', 'Nilai berhasil disimpan');
    }

    // Siswa Methods
    public function siswaPelatihan(Request $request)
    {
        $query = Pelatihan::published();

        if ($request->has('search')) {
            $query->where('judul', 'like', "%{$request->search}%");
        }

        if ($request->has('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        $pelatihan = $query->where('tanggal_mulai', '>', now())
                           ->latest('tanggal_mulai')
                           ->paginate(12);

        return view('siswa.pelatihan.index', compact('pelatihan'));
    }

    public function show(Pelatihan $pelatihan)
    {
        if (auth()->user()->isAdmin()) {
            return view('admin.pelatihan.show', compact('pelatihan'));
        } elseif (auth()->user()->isSiswa()) {
            return view('siswa.pelatihan.show', compact('pelatihan'));
        }

        return view('pelatihan.show', compact('pelatihan'));
    }

    public function daftar(Request $request, Pelatihan $pelatihan)
    {
        $siswa = auth()->user()->siswa;

        // Check if already registered
        $exists = $pelatihan->peserta()->where('siswa_id', $siswa->id)->exists();

        if ($exists) {
            return back()->with('error', 'Anda sudah terdaftar di pelatihan ini');
        }

        // Check quota
        if ($pelatihan->kuota && $pelatihan->jumlah_peserta >= $pelatihan->kuota) {
            return back()->with('error', 'Kuota pelatihan sudah penuh');
        }

        $pelatihan->peserta()->attach($siswa->id, [
            'status_pendaftaran' => 'daftar',
            'tanggal_daftar' => now(),
        ]);

        $pelatihan->increment('jumlah_peserta');

        return back()->with('success', 'Pendaftaran pelatihan berhasil');
    }

    public function batalDaftar(Pelatihan $pelatihan)
    {
        $siswa = auth()->user()->siswa;

        $pelatihan->peserta()->detach($siswa->id);
        $pelatihan->decrement('jumlah_peserta');

        return back()->with('success', 'Pendaftaran pelatihan berhasil dibatalkan');
    }
}