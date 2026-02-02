<?php

namespace App\Http\Controllers;

use App\Models\Pkl;
use App\Models\JurnalPkl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Kerjasamaindustri; 

class PklController extends Controller
{
    // Mahasiswa Methods
    public function mahasiswaPkl()
    {
        return view('mahasiswa.pkl.index');
    }

    public function daftar(Request $request)
    {
        $validated = $request->validate([
            'perusahaan_id' => 'required|exists:perusahaan,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'posisi' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        $mahasiswa = auth()->user()->mahasiswa;

        // Check if already has active PKL
        $hasActivePkl = Pkl::where('mahasiswa_id', $mahasiswa->id)
                          ->whereIn('status', ['pengajuan', 'diterima', 'berlangsung'])
                          ->exists();

        if ($hasActivePkl) {
            return back()->with('error', 'Anda masih memiliki PKL aktif');
        }

        Pkl::create([
            'mahasiswa_id' => $mahasiswa->id,
            'perusahaan_id' => $validated['perusahaan_id'],
            'tanggal_mulai' => $validated['tanggal_mulai'],
            'tanggal_selesai' => $validated['tanggal_selesai'],
            'posisi' => $validated['posisi'],
            'catatan' => $validated['catatan'],
            'status' => 'pengajuan',
        ]);

        return back()->with('success', 'Pengajuan PKL berhasil dikirim');
    }

   public function show(Pkl $pkl)
    {
        $pkl->load(['mahasiswa.user', 'perusahaan', 'jurnalPkl']);
        
        // Authorization check
        if (auth()->user()->isAdmin()) {
            return view('admin.pkl.show', compact('pkl'));

        } elseif (auth()->user()->isMahasiswa() && $pkl->mahasiswa_id == auth()->user()->mahasiswa->id) {
            return view('mahasiswa.pkl.show', compact('pkl'));

        } elseif (auth()->user()->isPerusahaan() && $pkl->perusahaan_id == auth()->user()->perusahaan->id) {
            
            // 2. PERBAIKAN DI SINI: Definisikan variabel $kerjasama
            // Pastikan nama kolom foreign key sesuai tabel database (misal: 'perusahaan_id' atau 'dudi_id')
            $kerjasama = Kerjasamaindustri::where('perusahaan_id', $pkl->perusahaan_id)->get(); 
            
            // Jika Anda belum punya Model/Tabel Kerjasama, pakai baris ini agar tidak error:
            // $kerjasama = []; 

            // 3. Masukkan 'kerjasama' ke dalam compact
            return view('perusahaan.pkl.show', compact('pkl', 'kerjasama'));
        }

        abort(403);
    }

    public function addJurnal(Request $request, Pkl $pkl)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'kegiatan' => 'required|string',
            'foto' => 'nullable|image|max:2048',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('jurnal', 'public');
        }

        JurnalPkl::create([
            'pkl_id' => $pkl->id,
            'tanggal' => $validated['tanggal'],
            'kegiatan' => $validated['kegiatan'],
            'foto' => $fotoPath,
            'status_validasi' => 'pending',
        ]);

        return back()->with('success', 'Jurnal berhasil ditambahkan');
    }

    public function updateJurnal(Request $request, $id)
    {
        $jurnal = JurnalPkl::findOrFail($id);

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'kegiatan' => 'required|string',
            'foto' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if ($jurnal->foto) {
                Storage::disk('public')->delete($jurnal->foto);
            }
            $validated['foto'] = $request->file('foto')->store('jurnal', 'public');
        }

        $jurnal->update($validated);

        return back()->with('success', 'Jurnal berhasil diperbarui');
    }

    public function deleteJurnal($id)
    {
        $jurnal = JurnalPkl::findOrFail($id);
        
        if ($jurnal->foto) {
            Storage::disk('public')->delete($jurnal->foto);
        }

        $jurnal->delete();

        return back()->with('success', 'Jurnal berhasil dihapus');
    }

    public function uploadLaporan(Request $request, Pkl $pkl)
    {
        $validated = $request->validate([
            'laporan_pkl' => 'required|file|mimes:pdf|max:10240',
        ]);

        if ($request->hasFile('laporan_pkl')) {
            if ($pkl->laporan_pkl) {
                Storage::disk('public')->delete($pkl->laporan_pkl);
            }
            
            $laporanPath = $request->file('laporan_pkl')->store('laporan-pkl', 'public');
            $pkl->update(['laporan_pkl' => $laporanPath]);
        }

        return back()->with('success', 'Laporan PKL berhasil diupload');
    }

    // Admin Methods
    public function index(Request $request)
    {
        $query = Pkl::with(['mahasiswa.user', 'perusahaan']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('mahasiswa.user', function($q) use ($search) {
                $q->where('name', 'like', "%$search%");
            })->orWhereHas('perusahaan', function($q) use ($search) {
                $q->where('nama_perusahaan', 'like', "%$search%");
            });
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $pkl = $query->latest()->paginate(20);

        return view('admin.pkl.index', compact('pkl'));
    }

    public function destroy(Pkl $pkl)
    {
        // Hapus file laporan jika ada
        if ($pkl->laporan_pkl) {
            Storage::disk('public')->delete($pkl->laporan_pkl);
        }

        // Hapus jurnal terkait
        foreach ($pkl->jurnalPkl as $jurnal) {
            if ($jurnal->foto) {
                Storage::disk('public')->delete($jurnal->foto);
            }
            $jurnal->delete();
        }

        $pkl->delete();

        return back()->with('success', 'Data PKL berhasil dihapus');
    }

    public function updateStatus(Request $request, Pkl $pkl)
    {
        $validated = $request->validate([
            'status' => 'required|in:pengajuan,diterima,ditolak,berlangsung,selesai',
        ]);

        $pkl->update(['status' => $validated['status']]);

        return back()->with('success', 'Status PKL berhasil diperbarui');
    }

    public function inputNilai(Request $request, Pkl $pkl)
    {
        $validated = $request->validate([
            'nilai_akhir' => 'required|numeric|min:0|max:100',
        ]);

        $pkl->update(['nilai_akhir' => $validated['nilai_akhir']]);

        return back()->with('success', 'Nilai PKL berhasil disimpan');
    }

    public function showJurnal(Pkl $pkl)
    {
        $pkl->load(['jurnalPkl' => function($query) {
            $query->latest('tanggal');
        }]);

        return view('admin.pkl.jurnal', compact('pkl'));
    }

    public function validasiJurnal(Request $request, $jurnalId)
    {
        $jurnal = JurnalPkl::findOrFail($jurnalId);

        $validated = $request->validate([
            'status_validasi' => 'required|in:disetujui,ditolak',
            'catatan_pembimbing' => 'nullable|string',
        ]);

        $jurnal->update($validated);

        return back()->with('success', 'Jurnal berhasil divalidasi');
    }

    // Perusahaan Methods
    public function perusahaanPkl()
    {
        $perusahaan = auth()->user()->perusahaan;
        $pkl = Pkl::where('perusahaan_id', $perusahaan->id)
                  ->with('mahasiswa.user')
                  ->latest()
                  ->paginate(20);

        return view('perusahaan.pkl.index', compact('pkl'));
    }

    public function terimaPkl(Request $request, Pkl $pkl)
    {
        $validated = $request->validate([
            'pembimbing_industri' => 'required|string',
            'divisi' => 'nullable|string',
        ]);

        $pkl->update([
            'status' => 'diterima',
            'pembimbing_industri' => $validated['pembimbing_industri'],
            'divisi' => $validated['divisi'],
        ]);

        return back()->with('success', 'Pengajuan PKL telah diterima');
    }

    public function tolakPkl(Request $request, Pkl $pkl)
    {
        $validated = $request->validate([
            'catatan' => 'required|string',
        ]);

        $pkl->update([
            'status' => 'ditolak',
            'catatan' => $validated['catatan'],
        ]);

        return back()->with('success', 'Pengajuan PKL telah ditolak');
    }
}
