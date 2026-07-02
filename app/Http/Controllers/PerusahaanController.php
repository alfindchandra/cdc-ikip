<?php

namespace App\Http\Controllers;

use App\Models\Perusahaan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class PerusahaanController extends Controller
{
public function index(Request $request)
{
    $query = Perusahaan::with('user');

    
    if ($request->has('search') && $request->search != '') {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('nama_perusahaan', 'like', "%$search%")
              ->orWhere('bidang_usaha', 'like', "%$search%")
              ->orWhere('kota', 'like', "%$search%");
        });
    }

    
    
 
    if ($request->has('status') && $request->status != '') { 
        $query->where('status_kerjasama', $request->status);
    }

  
    $perusahaan = $query->latest()->paginate(20)->withQueryString();

   

    return view('admin.perusahaan.index', compact('perusahaan'));
}
    public function create()
    {
        return view('admin.perusahaan.create');
    }
     public function edit(Perusahaan $perusahaan)
    {
        return view('admin.perusahaan.edit', compact('perusahaan'));
    }

    public function destroy(Perusahaan $perusahaan)
    {
        // Hapus logo jika ada
        if ($perusahaan->logo) {
            Storage::disk('public')->delete($perusahaan->logo);
        }

        // Hapus user (akan cascade delete perusahaan)
        $perusahaan->user->delete();

        return redirect()->route('admin.perusahaan.index')
                        ->with('success', 'Perusahaan berhasil dihapus beserta semua data terkait');
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'bidang_usaha' => 'nullable|string',
            'alamat' => 'nullable|string',
            'kota' => 'nullable|string',
            'provinsi' => 'nullable|string',
            'kode_pos' => 'nullable|string',
            'no_telp' => 'nullable|string',
            'website' => 'nullable|url',
            'nama_pic' => 'nullable|string',
            'jabatan_pic' => 'nullable|string',
            'no_telp_pic' => 'nullable|string',
            'email_pic' => 'nullable|email',
            'logo' => 'nullable|image|max:2048',
            'deskripsi' => 'nullable|string',
        ]);

        // Create user
        $user = User::create([
            'name' => $validated['nama_perusahaan'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'perusahaan',
            'is_active' => true,
        ]);

        // Handle logo upload
        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
        }

        // Create perusahaan profile
        $perusahaan = Perusahaan::create([
            'user_id' => $user->id,
            'nama_perusahaan' => $validated['nama_perusahaan'],
            'bidang_usaha' => $validated['bidang_usaha'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
            'kota' => $validated['kota'] ?? null,
            'provinsi' => $validated['provinsi'] ?? null,
            'kode_pos' => $validated['kode_pos'] ?? null,
            'no_telp' => $validated['no_telp'] ?? null,
            'email' => $validated['email'],
            'website' => $validated['website'] ?? null,
            'nama_pic' => $validated['nama_pic'] ?? null,
            'jabatan_pic' => $validated['jabatan_pic'] ?? null,
            'no_telp_pic' => $validated['no_telp_pic'] ?? null,
            'email_pic' => $validated['email_pic'] ?? null,
            'logo' => $logoPath,
            'deskripsi' => $validated['deskripsi'] ?? null,
            'status_kerjasama' => 'pending',
        ]);

        return redirect()->route('admin.perusahaan.index')
                        ->with('success', 'Data perusahaan berhasil ditambahkan');
    }

    public function show(Perusahaan $perusahaan)
    {
        $perusahaan->load(['lowonganKerja', 'pkl.mahasiswa', 'kerjasamaIndustri']);
        return view('admin.perusahaan.show', compact('perusahaan'));
    }

    public function updateStatus(Request $request, Perusahaan $perusahaan)
    {
        $validated = $request->validate([
            'status_kerjasama' => 'required|in:aktif,nonaktif,pending',
        ]);

        $perusahaan->update([
            'status_kerjasama' => $validated['status_kerjasama'],
            'tanggal_kerjasama' => $validated['status_kerjasama'] === 'aktif' ? now() : null,
        ]);

        return back()->with('success', 'Status kerjasama berhasil diperbarui');
    }
    

}