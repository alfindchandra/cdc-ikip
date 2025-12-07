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
// app/Http/Controllers/Admin/SiswaController.php (Asumsi)

public function index(Request $request)
{
    // 1. Inisialisasi Query dengan Eager Loading
    $query = Siswa::with(['user', 'fakultas', 'programStudi']); 

    // 2. Filter Pencarian (Search) - NIM atau Nama User
    if ($request->has('search') && $request->search != '') {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            // Cek di kolom 'nim'
            $q->where('nim', 'like', "%$search%");
            
            // Cek di relasi 'user' (nama)
            $q->orWhereHas('user', function($q2) use ($search) {
                // Asumsi kolom nama di tabel user adalah 'name'
                $q2->where('name', 'like', "%$search%");
            });
        });
    }

    // 3. Filter Fakultas
    if ($request->has('fakultas') && $request->fakultas != '') {
        // Menggunakan nama field yang benar di tabel Siswa untuk relasi Fakultas (e.g., fakultas_id)
        $query->where('fakultas_id', $request->fakultas); 
    }

    // 4. Filter Program Studi
    if ($request->has('program_studi') && $request->program_studi != '') {
        // Menggunakan nama field yang benar di tabel Siswa untuk relasi Program Studi (e.g., program_studi_id)
        $query->where('program_studi_id', $request->program_studi);
    }
    
    // 5. Filter Status
    if ($request->has('status') && $request->status != '') {
        $query->where('status', $request->status);
    }
    
    // 6. Eksekusi Query dan Pagination
    // **PERBAIKAN UTAMA:** Gunakan withQueryString() agar semua parameter filter (search, fakultas, dll.) 
    // tetap ada saat berpindah halaman (pagination).
    $siswa = $query->latest()->paginate(20)->withQueryString(); 
    
    // 7. Ambil Data Lain untuk Form Filter
    $fakultas = Fakultas::all();
    $program_studi = Program_studi::all();

    return view('admin.siswa.index', compact('siswa', 'fakultas', 'program_studi'));
}
    public function create()
    {
        $fakultas = Fakultas::orderBy('nama')->get();
        $programStudis = Program_studi::orderBy('nama')->get();
        return view('admin.siswa.create', compact('fakultas', 'programStudis'));
    }

    public function store(Request $request)
    {
       $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'nim' => 'required|string|max:20|unique:siswa,nim',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'agama' => 'required|string|max:20',
            'alamat' => 'required|string',
            'no_telp' => 'required|string|max:15',
            'fakultas_id' => 'required|exists:fakultas,id',
            'program_studi_id' => 'required|exists:program_studis,id',
            'tahun_masuk' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'nama_ortu' => 'required|string|max:255',
            'pekerjaan_ortu' => 'nullable|string|max:100',
            'no_telp_ortu' => 'nullable|string|max:15',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'siswa',
            'is_active' => true,
        ]);

        Siswa::create([
            'user_id' => $user->id,
            'nim' => $validated['nim'],
            'tempat_lahir' => $validated['tempat_lahir'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'agama' => $validated['agama'],
            'alamat' => $validated['alamat'],
            'no_telp' => $validated['no_telp'], 
            'fakultas_id' => $validated['fakultas_id'],
            'program_studi_id' => $validated['program_studi_id'],
            'tahun_masuk' => $validated['tahun_masuk'],
            'nama_ortu' => $validated['nama_ortu'],
            'pekerjaan_ortu' => $validated['pekerjaan_ortu'],
            'no_telp_ortu' => $validated['no_telp_ortu'],
            'status' => 'aktif',
        ]);

       

        return redirect()->route('admin.siswa.index')->with('success', 'Registrasi siswa berhasil! Selamat datang.');
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
    // 1. VALIDASI DATA
    $validated = $request->validate([
        // Data User
        'name'      => 'required|string|max:255',
        'email'     => 'required|email|unique:users,email,' . $siswa->user_id,
        'password'  => 'nullable|string|min:6', // Tambahkan validasi minimal karakter password
        
        // Data Siswa
        'nim'            => 'required|unique:siswa,nim,' . $siswa->id,
        'tempat_lahir'   => 'nullable|string',
        'tanggal_lahir'  => 'nullable|date',
        'jenis_kelamin'  => 'required|in:L,P',
        'agama'          => 'nullable|string',
        'alamat'         => 'nullable|string',
        'no_telp'        => 'nullable|string',
        
        // PERBAIKAN DI SINI (Validasi Relasi ID)
        'fakultas_id'      => 'nullable|exists:fakultas,id',      // Pastikan tabelnya bernama 'fakultas'
        'program_studi_id' => 'nullable|exists:program_studis,id', // Pastikan tabelnya bernama 'program_studi'
        
        'tahun_masuk'    => 'nullable|integer',
        'tahun_lulus'    => 'nullable|integer',
        'nama_ortu'      => 'nullable|string',
        'pekerjaan_ortu' => 'nullable|string',
        'no_telp_ortu'   => 'nullable|string',
        'status'         => 'required|in:aktif,lulus,pindah,keluar',
    ]);

    // 2. UPDATE DATA USER (Login)
    $userData = [
        'name'  => $request->name,
        'email' => $request->email,
    ];

    // Cek apakah password diisi (jika kosong, jangan diupdate)
    if ($request->filled('password')) {
        $userData['password'] = Hash::make($request->password);
    }

    $siswa->user->update($userData);

    // 3. UPDATE DATA SISWA (Profile)
    $siswa->update([
        'nim'              => $request->nim,
        'tempat_lahir'     => $request->tempat_lahir,
        'tanggal_lahir'    => $request->tanggal_lahir,
        'jenis_kelamin'    => $request->jenis_kelamin,
        'agama'            => $request->agama,
        'alamat'           => $request->alamat,
        'no_telp'          => $request->no_telp,
        
        // PERBAIKAN DI SINI (Simpan ID ke kolom foreign key)
        'fakultas_id'      => $request->fakultas_id,      // Pastikan kolom di DB siswa bernama 'fakultas_id'
        'program_studi_id' => $request->program_studi_id, // Pastikan kolom di DB siswa bernama 'program_studi_id'
        
        'tahun_masuk'      => $request->tahun_masuk,
        'nama_ortu'        => $request->nama_ortu,
        'pekerjaan_ortu'   => $request->pekerjaan_ortu,
        'no_telp_ortu'     => $request->no_telp_ortu,
        'status'           => $request->status,
        'tahun_lulus'      => $request->tahun_lulus
    ]);

    // 4. REDIRECT
    // Pastikan route ini sesuai dengan route list Anda (biasanya admin.siswa.index)
    return redirect()->route('admin.siswa.index')
                     ->with('success', 'Data siswa berhasil diperbarui');
}

    public function destroy(Siswa $siswa)
    {
        $siswa->user->delete(); // Will cascade delete siswa
        
        return redirect()->route('siswa.index')
                        ->with('success', 'Data siswa berhasil dihapus');
    }
}