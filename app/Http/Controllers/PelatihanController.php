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

        return view('admin.pelatihan.show', compact('pelatihan'));
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
    public function updateStatusPeserta(Request $request, $pelatihanId, $siswaId)
{
    $validated = $request->validate([
        'status_pendaftaran' => 'required|in:pending,diterima,ditolak',
        'nilai' => 'nullable|numeric|min:0|max:100',
        'sertifikat' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        'catatan' => 'nullable|string'
    ]);

    $pelatihan = \App\Models\Pelatihan::findOrFail($pelatihanId);
    $siswa = \App\Models\Siswa::findOrFail($siswaId);

    // Get current pivot data
    $pivotData = $siswa->pelatihan()->where('pelatihan_id', $pelatihanId)->first();
    
    if (!$pivotData) {
        return redirect()->back()->with('error', 'Data peserta tidak ditemukan');
    }

    // Prepare update data
    $updateData = [
        'status_pendaftaran' => $validated['status_pendaftaran']
    ];

    // Add nilai if provided
    if ($request->filled('nilai')) {
        $updateData['nilai'] = $validated['nilai'];
    }

    // Add catatan if provided
    if ($request->filled('catatan')) {
        $updateData['catatan'] = $validated['catatan'];
    }

    // Handle sertifikat upload
    if ($request->hasFile('sertifikat')) {
        // Delete old sertifikat if exists
        if ($pivotData->pivot->sertifikat) {
            \Storage::delete($pivotData->pivot->sertifikat);
        }
        
        $sertifikatPath = $request->file('sertifikat')->store('sertifikat', 'public');
        $updateData['sertifikat'] = $sertifikatPath;
    }

    // Update pivot table
    $siswa->pelatihan()->updateExistingPivot($pelatihanId, $updateData);

    // Create notification for siswa
    \App\Models\Notifikasi::create([
        'user_id' => $siswa->user_id,
        'judul' => 'Status Pelatihan Diperbarui',
        'pesan' => "Status pendaftaran Anda untuk pelatihan '{$pelatihan->judul}' telah diperbarui menjadi {$validated['status_pendaftaran']}",
        'kategori' => 'pelatihan',
        'link' => route('siswa.pelatihan.show', $pelatihan->id)
    ]);

    return redirect()->back()->with('success', 'Status peserta berhasil diperbarui');
}

/**
 * Input nilai peserta (legacy method - bisa dihapus jika tidak digunakan)
 */
public function inputNilai(Request $request, $pelatihanId, $siswaId)
{
    $validated = $request->validate([
        'nilai' => 'required|numeric|min:0|max:100'
    ]);

    $pelatihan = \App\Models\Pelatihan::findOrFail($pelatihanId);
    $siswa = \App\Models\Siswa::findOrFail($siswaId);

    // Update nilai in pivot table
    $siswa->pelatihan()->updateExistingPivot($pelatihanId, [
        'nilai' => $validated['nilai']
    ]);

    // Create notification
    \App\Models\Notifikasi::create([
        'user_id' => $siswa->user_id,
        'judul' => 'Nilai Pelatihan Tersedia',
        'pesan' => "Nilai Anda untuk pelatihan '{$pelatihan->judul}' adalah {$validated['nilai']}",
        'tipe' => 'pelatihan',
        'link' => route('siswa.pelatihan.show', $pelatihan->id)
    ]);

    return redirect()->back()->with('success', 'Nilai berhasil diinput');
}
}