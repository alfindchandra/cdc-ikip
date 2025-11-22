<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Fakultas;
use App\Models\Program_studi;

class SiswaController extends Controller
{
    public function index(Request $request)
{
    // Eager load 'user', 'fakultas', dan 'programStudi'
    $query = Siswa::with(['user', 'fakultas', 'programStudi']); 

    if ($request->has('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('nim', 'like', "%$search%")
              ->orWhereHas('user', function($q2) use ($search) {
                  $q2->where('name', 'like', "%$search%");
              });
        });
    }

    if ($request->has('fakultas')) {
        // Asumsi kolom di tabel Siswa yang menyimpan ID adalah 'fakultas_id'
        $query->where('fakultas_id', $request->fakultas); 
    }

    if ($request->has('program_studi')) {
        // Asumsi kolom di tabel Siswa yang menyimpan ID adalah 'program_studi_id'
        $query->where('program_studi_id', $request->program_studi);
    }
    
    // ... filter status

    if ($request->has('status')) {
        $query->where('status', $request->status);
    }
    
    // Pastikan Anda memfilter berdasarkan ID kolom di tabel Siswa (fakultas_id / program_studi_id) jika menggunakan input filter dari ID.

    $siswa = $query->latest()->paginate(20);
    $fakultas = Fakultas::all();
    $program_studi = Program_studi::all();

    return view('admin.siswa.index', compact('siswa', 'fakultas', 'program_studi'));
}
    public function create()
    {
        $fakultas = Fakultas::all();
        $program_studi = Program_studi::all();
        return view('admin.siswa.create', compact('fakultas', 'program_studi'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'nim' => 'required|unique:siswa,nim',
            'tempat_lahir' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'required|in:L,P',
            'agama' => 'nullable|string',
            'alamat' => 'nullable|string',
            'no_telp' => 'nullable|string',
            'fakultas' => 'nullable|string',
            'program_studi' => 'nullable|string',
            'tahun_masuk' => 'nullable|integer',
            'nama_ortu' => 'nullable|string',
            'pekerjaan_ortu' => 'nullable|string',
            'no_telp_ortu' => 'nullable|string',
        ]);

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'siswa',
            'is_active' => true,
        ]);

        // Create siswa profile
        $siswa = Siswa::create([
            'user_id' => $user->id,
            'nim' => $validated['nim'],
            'tempat_lahir' => $validated['tempat_lahir'] ?? null,
            'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'agama' => $validated['agama'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
            'no_telp' => $validated['no_telp'] ?? null,
            'fakultas' => $validated['fakultas'] ?? null,
            'program_studi' => $validated['program_studi'] ?? null,
            'tahun_masuk' => $validated['tahun_masuk'] ?? null,
            'nama_ortu' => $validated['nama_ortu'] ?? null,
            'pekerjaan_ortu' => $validated['pekerjaan_ortu'] ?? null,
            'no_telp_ortu' => $validated['no_telp_ortu'] ?? null,
            'status' => 'aktif',
        ]);
        

        return redirect()->route('siswa.index')
                        ->with('success', 'Data siswa berhasil ditambahkan');
    }

    public function show(Siswa $siswa)
    {
        $siswa->load(['user', 'pkl.perusahaan', 'lamaran.lowongan', 'pelatihan']);
        return view('admin.siswa.show', compact('siswa'));
    }

    public function edit(Siswa $siswa)
    {
        $fakultas = Fakultas::all();
        $program_studi = Program_studi::all();
        return view('admin.siswa.edit', compact('siswa', 'fakultas', 'program_studi'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $siswa->user_id,
            'nim' => 'required|unique:siswa,nim,' . $siswa->id,
            'tempat_lahir' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'required|in:L,P',
            'agama' => 'nullable|string',
            'alamat' => 'nullable|string',
            'no_telp' => 'nullable|string',
            'fakultas' => 'nullable|string',
            'program_studi' => 'nullable|string',
            'tahun_masuk' => 'nullable|integer',
            'nama_ortu' => 'nullable|string',
            'pekerjaan_ortu' => 'nullable|string',
            'no_telp_ortu' => 'nullable|string',
            'status' => 'required|in:aktif,lulus,pindah,keluar',
        ]);

        // Update user
        $siswa->user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Update password if provided
        if ($request->filled('password')) {
            $siswa->user->update([
                'password' => Hash::make($request->password)
            ]);
        }

        // Update siswa profile
        $siswa->update([
            'nim' => $validated['nim'],
            'tempat_lahir' => $validated['tempat_lahir'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'agama' => $validated['agama'],
            'alamat' => $validated['alamat'],
            'no_telp' => $validated['no_telp'],
            'fakultas' => $validated['fakultas'],
            'program_studi' => $validated['program_studi'],
            'tahun_masuk' => $validated['tahun_masuk'],
            'nama_ortu' => $validated['nama_ortu'],
            'pekerjaan_ortu' => $validated['pekerjaan_ortu'],
            'no_telp_ortu' => $validated['no_telp_ortu'],
            'status' => $validated['status'],
        ]);

        return redirect()->route('siswa.index')
                        ->with('success', 'Data siswa berhasil diperbarui');
    }

    public function destroy(Siswa $siswa)
    {
        $siswa->user->delete(); // Will cascade delete siswa
        
        return redirect()->route('siswa.index')
                        ->with('success', 'Data siswa berhasil dihapus');
    }
}