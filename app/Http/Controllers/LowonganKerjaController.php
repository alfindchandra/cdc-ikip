<?php

namespace App\Http\Controllers;

use App\Models\LowonganKerja;
use App\Models\Lamaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LowonganKerjaController extends Controller
{
    // Mahasiswa Methods
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

    return view('mahasiswa.lowongan.index', compact('lowongan'));
}

       

    public function show(LowonganKerja $lowongan)
    {
        $lowongan->load('perusahaan');
        
        if (auth()->user()->isAdmin()) {
            return view('admin.lowongan.show', compact('lowongan'));
        } elseif (auth()->user()->isMahasiswa()) {
            return view('mahasiswa.lowongan.show', compact('lowongan'));
        }

        return view('perusahaan.lowongan.show', compact('lowongan'));
    }

    public function pelamar(LowonganKerja $lowongan)
    {
        $lowongan->load(['lamaran.mahasiswa']);

        return view('perusahaan.lowongan.pelamar', compact('lowongan'));
    }
   
    public function adminShow(LowonganKerja $lowongan)
    {
        $lowongan->load('perusahaan', 'lamaran.mahasiswa');

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

public function adminPelamar(Request $request, $lowonganId)
    {
        // Menangkap kata kunci pencarian dari input text admin
        $search = $request->input('search');

        // Query dasar mengambil data lamaran berdasarkan lowongan_id tertentu
        $query = Lamaran::where('lowongan_id', $lowonganId)->with(['lowongan', 'mahasiswa.user']);

        // Jika admin mengisi kolom pencarian, lakukan filter di database
        if ($search) {
            $query->where(function($q) use ($search) {
                // Cari berdasarkan nama user mahasiswa (kolom 'name' di tabel users)
                $q->whereHas('mahasiswa.user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%");
                })
                // Atau cari berdasarkan NIM (kolom 'nim' di tabel mahasiswa)
                // Kolom 'nama' dihapus dari sini karena menyebabkan error di database Anda
                ->orWhereHas('mahasiswa', function($mahasiswaQuery) use ($search) {
                    $mahasiswaQuery->where('nim', 'like', "%{$search}%");
                });
            });
        }

        // Ambil data terbaru hasil filter database
        $pelamar = $query->latest()->get();

        // Mengirimkan data pelamar, keyword pencarian, dan ID Lowongan ke view
        return view('admin.lowongan.pelamar', compact('pelamar', 'search', 'lowonganId'));
    }

    public function create()
    {
        return view('perusahaan.lowongan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'posisi' => 'required|string|max:100',
            'category' => 'required|string|max:100',
            'deskripsi' => 'required|string',
            'pendidikan' => 'required|string|max:100',
            'kualifikasi' => 'required|string',
            'benefit' => 'nullable|string',
            'tipe_pekerjaan' => 'required|in:full_time,part_time,kontrak,magang',
            'lokasi' => 'required|string',
            'gaji_min' => 'nullable|numeric',
            'gaji_max' => 'nullable|numeric',
            'kuota' => 'nullable|integer',
            'tanggal_mulai' => 'required|date',
            'tanggal_berakhir' => 'required|date|after:tanggal_mulai',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $perusahaan = auth()->user()->perusahaan;

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('lowongan-thumbnails', 'public');
        }

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
            'posisi' => 'required|string|max:100',
            'category' => 'required|string|max:100',
            'deskripsi' => 'required|string',
            'pendidikan' => 'required|string|max:100',
            'kualifikasi' => 'required|string',
            'benefit' => 'nullable|string',
            'tipe_pekerjaan' => 'required|in:full_time,part_time,kontrak,magang',
            'lokasi' => 'required|string',
            'gaji_min' => 'nullable|numeric',
            'gaji_max' => 'nullable|numeric',
            'kuota' => 'nullable|integer',
            'tanggal_mulai' => 'required|date',
            'tanggal_berakhir' => 'required|date|after:tanggal_mulai',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($lowongan->thumbnail) {
                Storage::disk('public')->delete($lowongan->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')->store('lowongan-thumbnails', 'public');
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

    // ===================== Admin CRUD Methods =====================

    /**
     * [Admin] Tampilkan form buat lowongan mandiri (tanpa perusahaan).
     */
    public function adminCreate()
    {
        return view('admin.lowongan.create');
    }

    /**
     * [Admin] Simpan lowongan baru yang dibuat admin.
     */
    public function adminStore(Request $request)
    {
        $validated = $request->validate([
            'judul'           => 'required|string|max:200',
            'posisi'          => 'required|string|max:100',
            'category'        => 'required|string|max:100',
            'deskripsi'       => 'required|string',
            'pendidikan'      => 'required|string|max:100',
            'kualifikasi'     => 'required|string',
            'benefit'         => 'nullable|string',
            'tipe_pekerjaan'  => 'required|in:full_time,part_time,kontrak,magang',
            'lokasi'          => 'required|string',
            'gaji_min'        => 'nullable|numeric',
            'gaji_max'        => 'nullable|numeric',
            'kuota'           => 'nullable|integer',
            'tanggal_mulai'   => 'required|date',
            'tanggal_berakhir'=> 'required|date|after:tanggal_mulai',
            'thumbnail'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('lowongan-thumbnails', 'public');
        }

        $validated['perusahaan_id'] = null; // Admin lowongan tidak terikat perusahaan
        $validated['status'] = 'aktif';

        LowonganKerja::create($validated);

        return redirect()->route('admin.lowongan.index')
                         ->with('success', 'Lowongan berhasil dibuat.');
    }

    /**
     * [Admin] Tampilkan form edit lowongan.
     */
    public function adminEdit(LowonganKerja $lowongan)
    {
        return view('admin.lowongan.edit', compact('lowongan'));
    }

    /**
     * [Admin] Simpan perubahan lowongan.
     */
    public function adminUpdate(Request $request, LowonganKerja $lowongan)
    {
        $validated = $request->validate([
            'judul'           => 'nullable|string|max:200',
            'posisi'          => 'required|string|max:100',
            'category'        => 'required|string|max:100',
            'deskripsi'       => 'required|string',
            'pendidikan'      => 'required|string|max:100',
            'kualifikasi'     => 'required|string',
            'benefit'         => 'nullable|string',
            'tipe_pekerjaan'  => 'required|in:full_time,part_time,kontrak,magang',
            'lokasi'          => 'required|string',
            'gaji_min'        => 'nullable|numeric',
            'gaji_max'        => 'nullable|numeric',
            'kuota'           => 'nullable|integer',
            'tanggal_mulai'   => 'required|date',
            'tanggal_berakhir'=> 'required|date|after:tanggal_mulai',
            'thumbnail'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($lowongan->thumbnail) {
                Storage::disk('public')->delete($lowongan->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')->store('lowongan-thumbnails', 'public');
        }

        $lowongan->update($validated);

        return redirect()->route('admin.lowongan.index')
                         ->with('success', 'Lowongan berhasil diperbarui.');
    }

    /**
     * [Admin] Hapus lowongan.
     */
    public function adminDestroy(LowonganKerja $lowongan)
    {
        if ($lowongan->thumbnail) {
            Storage::disk('public')->delete($lowongan->thumbnail);
        }
        $lowongan->delete();

        return back()->with('success', 'Lowongan berhasil dihapus.');
    }
}