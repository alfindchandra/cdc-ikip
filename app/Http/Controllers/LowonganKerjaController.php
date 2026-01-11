<?php

namespace App\Http\Controllers;

use App\Models\LowonganKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LowonganKerjaController extends Controller
{
    // Siswa Methods
    public function index(Request $request)
{
    $query = LowonganKerja::with('perusahaan');

    if ($request->has('search') && $request->search != '') {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('posisi', 'like', "%$search%")
              ->orWhereHas('perusahaan', function($q2) use ($search) {
 
                  $q2->where('nama_perusahaan', 'like', "%$search%");
              });
        });
    }

    if ($request->filled('tipe_pekerjaan')) {
    $query->where('tipe_pekerjaan', $request->tipe_pekerjaan);
}

  
    $lowongan = $query->latest()->paginate(20)->withQueryString();

    return view('siswa.lowongan.index', compact('lowongan'));
}

       

    public function show(LowonganKerja $lowongan)
    {
        $lowongan->load('perusahaan');
        
        if (auth()->user()->isAdmin()) {
            return view('admin.lowongan.show', compact('lowongan'));
        } elseif (auth()->user()->isSiswa()) {
            return view('siswa.lowongan.show', compact('lowongan'));
        }

        return view('perusahaan.lowongan.show', compact('lowongan'));
    }

    public function pelamar(LowonganKerja $lowongan)
    {
        $lowongan->load(['lamaran.siswa']);

        return view('perusahaan.lowongan.pelamar', compact('lowongan'));
    }
   
    public function adminShow(LowonganKerja $lowongan)
    {
        $lowongan->load('perusahaan', 'lamaran.siswa');

        return view('admin.lowongan.show', compact('lowongan'));
    }
    // Admin Methods
    public function adminIndex(Request $request)
    {
      $query = LowonganKerja::with('perusahaan');

    if ($request->has('search') && $request->search != '') {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('posisi', 'like', "%$search%")
              ->orWhereHas('perusahaan', function($q2) use ($search) {
                  $q2->where('nama_perusahaan', 'like', "%$search%");
              });
        });
    }

    if ($request->filled('status')) {
    $query->where('tipe_pekerjaan', $request->status);
}

  
    $lowongan = $query->latest()->paginate(20)->withQueryString();


        return view('admin.lowongan.index', compact('lowongan'));
    }

    public function updateStatus(Request $request, LowonganKerja $lowongan)
    {
        $validated = $request->validate([
            'status' => 'required|in:aktif,nonaktif,expired',
        ]);

        $lowongan->update(['status' => $validated['status']]);

        return back()->with('success', 'Status lowongan berhasil diperbarui');
    }

    // Perusahaan Methods
    public function perusahaanIndex()
    {
        $perusahaan = auth()->user()->perusahaan;
        $lowongan = LowonganKerja::where('perusahaan_id', $perusahaan->id)
                                 ->latest()
                                 ->paginate(20);

        return view('perusahaan.lowongan.index', compact('lowongan'));
    }

    public function create()
    {
        return view('perusahaan.lowongan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
      
            'posisi' => 'required|string',
            'deskripsi' => 'required|string',
            'pendidikan' => 'required|string',
            'kualifikasi' => 'required|string',
            'benefit' => 'nullable|string',
            'tipe_pekerjaan' => 'required|in:full_time,part_time,kontrak,magang',
            'lokasi' => 'required|string',
            'gaji_min' => 'nullable|numeric',
            'gaji_max' => 'nullable|numeric',
            'kuota' => 'nullable|integer',
            'tanggal_mulai' => 'required|date',
            'tanggal_berakhir' => 'required|date|after:tanggal_mulai',
           
        ]);

        $perusahaan = auth()->user()->perusahaan;

        
        $validated['perusahaan_id'] = $perusahaan->id;
        $validated['status'] = 'aktif';

        LowonganKerja::create($validated);

        return redirect()->route('perusahaan.lowongan.index')
                        ->with('success', 'Lowongan berhasil ditambahkan');
    }

    public function edit(LowonganKerja $lowongan)
    {
        return view('perusahaan.lowongan.edit', compact('lowongan'));
    }

    public function update(Request $request, LowonganKerja $lowongan)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'posisi' => 'required|string',
            'deskripsi' => 'required|string',
            'kualifikasi' => 'required|string',
            'benefit' => 'nullable|string',
            'tipe_pekerjaan' => 'required|in:full_time,part_time,kontrak,magang',
            'lokasi' => 'required|string',
            'gaji_min' => 'nullable|numeric',
            'gaji_max' => 'nullable|numeric',
            'kuota' => 'nullable|integer',
            'tanggal_mulai' => 'required|date',
            'tanggal_berakhir' => 'required|date|after:tanggal_mulai',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($lowongan->thumbnail) {
                Storage::disk('public')->delete($lowongan->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')->store('lowongan', 'public');
        }

        $lowongan->update($validated);

        return redirect()->route('perusahaan.lowongan.index')
                        ->with('success', 'Lowongan berhasil diperbarui');
    }

    public function destroy(LowonganKerja $lowongan)
    {
        if ($lowongan->thumbnail) {
            Storage::disk('public')->delete($lowongan->thumbnail);
        }

        $lowongan->delete();

        return back()->with('success', 'Lowongan berhasil dihapus');
    }

    public function toggleStatus(LowonganKerja $lowongan)
    {
        $newStatus = $lowongan->status === 'aktif' ? 'nonaktif' : 'aktif';
        $lowongan->update(['status' => $newStatus]);

        return back()->with('success', 'Status lowongan berhasil diubah');
    }
}