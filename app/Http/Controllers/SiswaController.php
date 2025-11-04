<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Siswa::with('user');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nis', 'like', "%$search%")
                  ->orWhere('nisn', 'like', "%$search%")
                  ->orWhereHas('user', function($q2) use ($search) {
                      $q2->where('name', 'like', "%$search%");
                  });
            });
        }

        if ($request->has('kelas')) {
            $query->where('kelas', $request->kelas);
        }

        if ($request->has('jurusan')) {
            $query->where('jurusan', $request->jurusan);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $siswa = $query->latest()->paginate(20);

        return view('admin.siswa.index', compact('siswa'));
    }

    public function create()
    {
        return view('admin.siswa.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'nis' => 'required|unique:siswa,nis',
            'nisn' => 'nullable|unique:siswa,nisn',
            'tempat_lahir' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'required|in:L,P',
            'agama' => 'nullable|string',
            'alamat' => 'nullable|string',
            'no_telp' => 'nullable|string',
            'kelas' => 'nullable|string',
            'jurusan' => 'nullable|string',
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
            'nis' => $validated['nis'],
            'nisn' => $validated['nisn'] ?? null,
            'tempat_lahir' => $validated['tempat_lahir'] ?? null,
            'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'agama' => $validated['agama'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
            'no_telp' => $validated['no_telp'] ?? null,
            'kelas' => $validated['kelas'] ?? null,
            'jurusan' => $validated['jurusan'] ?? null,
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
        return view('admin.siswa.edit', compact('siswa'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $siswa->user_id,
            'nis' => 'required|unique:siswa,nis,' . $siswa->id,
            'nisn' => 'nullable|unique:siswa,nisn,' . $siswa->id,
            'tempat_lahir' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'required|in:L,P',
            'agama' => 'nullable|string',
            'alamat' => 'nullable|string',
            'no_telp' => 'nullable|string',
            'kelas' => 'nullable|string',
            'jurusan' => 'nullable|string',
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
            'nis' => $validated['nis'],
            'nisn' => $validated['nisn'],
            'tempat_lahir' => $validated['tempat_lahir'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'agama' => $validated['agama'],
            'alamat' => $validated['alamat'],
            'no_telp' => $validated['no_telp'],
            'kelas' => $validated['kelas'],
            'jurusan' => $validated['jurusan'],
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