<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Fakultas;
use App\Models\Program_studi;
use Illuminate\Support\Str;

class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Mahasiswa::with(['user', 'fakultas', 'programStudi']); 

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nim', 'like', "%$search%")
                  ->orWhereHas('user', function($q2) use ($search) {
                      $q2->where('name', 'like', "%$search%");
                  });
            });
        }

        if ($request->has('fakultas') && $request->fakultas != '') {
            $query->where('fakultas_id', $request->fakultas); 
        }

        if ($request->has('program_studi') && $request->program_studi != '') {
            $query->where('program_studi_id', $request->program_studi);
        }
        
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        $mahasiswa = $query->latest()->paginate(20)->withQueryString(); 
        
        $fakultas = Fakultas::all();
        $program_studi = Program_studi::all();

        return view('admin.mahasiswa.index', compact('mahasiswa', 'fakultas', 'program_studi'));
    }

    public function create()
    {
        $fakultas = Fakultas::orderBy('nama')->get();
        $programStudis = Program_studi::orderBy('nama')->get();
        return view('admin.mahasiswa.create', compact('fakultas', 'programStudis'));
    }

    public function store(Request $request)
    {
        // Logika dinamis untuk store (opsional namun disarankan agar sinkron dengan create)
        $isSekolah = in_array($request->tingkat_pendidikan, ['SD', 'SMP', 'SMA', 'SMK']);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'nim' => 'required|string|max:20|unique:mahasiswa,nim',
            'tingkat_pendidikan' => 'required|in:SD,SMP,SMA,SMK,D1,D2,D3,S1,S2,S3',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'agama' => 'required|string|max:20',
            'alamat' => 'required|string',
            'no_telp' => 'required|string|max:15',
            'asal_sekolah' => $isSekolah ? 'required|string|max:255' : 'nullable|string',
            'fakultas_id' => !$isSekolah ? 'required|exists:fakultas,id' : 'nullable',
            'program_studi_id' => !$isSekolah ? 'required|exists:program_studis,id' : 'nullable',
            'tahun_masuk' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'nama_ortu' => 'required|string|max:255',
            'pekerjaan_ortu' => 'nullable|string|max:100',
            'no_telp_ortu' => 'nullable|string|max:15',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'mahasiswa',
            'is_active' => true,
        ]);

        Mahasiswa::create([
            'user_id' => $user->id,
            'nim' => $validated['nim'],
            'tingkat_pendidikan' => $validated['tingkat_pendidikan'],
            'tempat_lahir' => $validated['tempat_lahir'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'agama' => $validated['agama'],
            'alamat' => $validated['alamat'],
            'no_telp' => $validated['no_telp'], 
            'asal_sekolah' => $isSekolah ? $validated['asal_sekolah'] : null,
            'fakultas_id' => !$isSekolah ? $validated['fakultas_id'] : null,
            'program_studi_id' => !$isSekolah ? $validated['program_studi_id'] : null,
            'tahun_masuk' => $validated['tahun_masuk'],
            'nama_ortu' => $validated['nama_ortu'],
            'pekerjaan_ortu' => $validated['pekerjaan_ortu'],
            'no_telp_ortu' => $validated['no_telp_ortu'],
            'status' => 'aktif',
        ]);

        return redirect()->route('admin.mahasiswa.index')->with('success', 'Registrasi mahasiswa berhasil!');
    }

    public function show(Mahasiswa $mahasiswa)
    {
        $mahasiswa->load(['user', 'pkl.perusahaan', 'lamaran.lowongan', 'pelatihan']);
        return view('admin.mahasiswa.show', compact('mahasiswa'));
    }

    public function edit(Mahasiswa $mahasiswa)
    {
        $fakultas = Fakultas::all();
        $program_studi = Program_studi::all();
        return view('admin.mahasiswa.edit', compact('mahasiswa', 'fakultas', 'program_studi'));
    }

public function update(Request $request, Mahasiswa $mahasiswa)
{
    $tingkat = $request->tingkat_pendidikan;
    $isSekolahDasar = in_array($tingkat, ['SD', 'SMP']);
    $isMengahAtas   = in_array($tingkat, ['SMA', 'SMK']);

    // 1. VALIDASI DATA
    $validated = $request->validate([
        'name'             => 'required|string|max:255',
        'email'            => 'required|email|unique:users,email,' . $mahasiswa->user_id,
        'password'         => 'nullable|string|min:6',
        'nim'              => 'required|unique:mahasiswa,nim,' . $mahasiswa->id,
        'tingkat_pendidikan'=> 'required|in:SD,SMP,SMA,SMK,D1,D2,D3,S1,S2,S3',
        'tempat_lahir'     => 'nullable|string',
        'tanggal_lahir'    => 'nullable|date',
        'jenis_kelamin'    => 'required|in:L,P',
        'agama'            => 'nullable|string',
        'alamat'           => 'nullable|string',
        'no_telp'          => 'nullable|string',
        
        // Asal sekolah wajib diisi untuk semua tingkat sekolah
        'asal_sekolah'     => ($isSekolahDasar || $isMengahAtas) ? 'required|string|max:255' : 'nullable|string',
        
        // Fakultas hanya wajib untuk Kuliah (D1-S3)
        'fakultas'         => (!$isSekolahDasar && !$isMengahAtas) ? 'required|string|max:255' : 'nullable|string', 
        
        // Program studi wajib diisi untuk Kuliah ATAU SMA/SMK (sebagai Jurusan)
        'program_studi'    => ($isMengahAtas || (!$isSekolahDasar && !$isMengahAtas)) ? 'required|string|max:255' : 'nullable|string', 
        
        'tahun_masuk'      => 'nullable|integer',
        'tahun_lulus'      => 'nullable|integer',
        'nama_ortu'        => 'nullable|string',
        'pekerjaan_ortu'   => 'nullable|string',
        'no_telp_ortu'     => 'nullable|string',
        'status'           => 'required|in:aktif,lulus,pindah,keluar',
    ]);

    // 2. UPDATE DATA USER
    $userData = [
        'name'  => $request->name,
        'email' => $request->email,
    ];
    if ($request->filled('password')) {
        $userData['password'] = Hash::make($request->password);
    }
    $mahasiswa->user->update($userData);

    // 3. UPDATE DATA MAHASISWA
    $mahasiswa->update([
        'nim'               => $request->nim,
        'tingkat_pendidikan'=> $request->tingkat_pendidikan,
        'tempat_lahir'      => $request->tempat_lahir,
        'tanggal_lahir'     => $request->tanggal_lahir,
        'jenis_kelamin'     => $request->jenis_kelamin,
        'agama'             => $request->agama,
        'alamat'            => $request->alamat,
        'no_telp'           => $request->no_telp,
        'asal_sekolah'      => $request->asal_sekolah,
        
        // Simpan nama Fakultas hanya jika Kuliah, selain itu null
        'fakultas_id'       => (!$isSekolahDasar && !$isMengahAtas) ? $request->fakultas : null,
        
        // Simpan nama Prodi/Jurusan jika Kuliah atau SMA/SMK, jika SD/SMP set null
        'program_studi_id'  => ($isMengahAtas || (!$isSekolahDasar && !$isMengahAtas)) ? $request->program_studi : null,
        
        'tahun_masuk'       => $request->tahun_masuk,
        'nama_ortu'         => $request->nama_ortu,
        'pekerjaan_ortu'    => $request->pekerjaan_ortu,
        'no_telp_ortu'      => $request->no_telp_ortu,
        'status'            => $request->status,
        'tahun_lulus'       => $request->status === 'lulus' ? $request->tahun_lulus : null
    ]);

    return redirect()->route('admin.mahasiswa.index')
                     ->with('success', 'Data siswa/mahasiswa berhasil diperbarui');
}
    public function destroy(Mahasiswa $mahasiswa)
    {
        $mahasiswa->user->delete(); 
        return redirect()->route('admin.mahasiswa.index')
                         ->with('success', 'Data mahasiswa berhasil dihapus');
    }

    // ─── Import ──────────────────────────────────────────────────────────────

    /**
     * Download template CSV untuk import mahasiswa.
     */
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="template-import-mahasiswa.csv"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF"); // BOM UTF-8
            fputcsv($file, [
                'name',
                'email',
                'password',
                'nim',
                'tingkat_pendidikan',
                'tempat_lahir',
                'tanggal_lahir',
                'jenis_kelamin',
                'agama',
                'alamat',
                'no_telp',
                'asal_sekolah',
                'fakultas_id',
                'program_studi_id',
                'tahun_masuk',
                'tahun_lulus',
                'nama_ortu',
                'pekerjaan_ortu',
                'no_telp_ortu',
                'status',
            ]);
            fputcsv($file, [
                'Budi Santoso',
                'budi@email.com',
                'password123',
                '202300001',
                'S1',
                'Jakarta',
                '2000-01-15',
                'L',
                'Islam',
                'Jl. Contoh No. 1, Jakarta',
                '08123456789',
                '',
                '1',
                '1',
                '2023',
                '',
                'Siti Rahayu',
                'Guru',
                '08987654321',
                'aktif',
            ]);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Proses import data mahasiswa dari file CSV.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:10240',
        ], [
            'file.required' => 'File CSV harus diunggah.',
            'file.mimes'    => 'File harus berformat CSV.',
            'file.max'      => 'Ukuran file maksimal 10 MB.',
        ]);

        $path   = $request->file('file')->getRealPath();
        $handle = fopen($path, 'r');

        if (!$handle) {
            return back()->with('error', 'Gagal membaca file CSV.');
        }

        $header = fgetcsv($handle);
        if ($header) {
            $header[0] = ltrim($header[0], "\xEF\xBB\xBF");
            $header    = array_map('trim', $header);
        }

        $requiredCols = ['name', 'email', 'nim'];
        foreach ($requiredCols as $col) {
            if (!in_array($col, $header)) {
                fclose($handle);
                return back()->with('error', "Kolom wajib '{$col}' tidak ditemukan di file CSV.");
            }
        }

        $imported = 0;
        $skipped  = 0;
        $errors   = [];
        $row      = 1;

        while (($line = fgetcsv($handle)) !== false) {
            $row++;
            if (count($line) < 2) continue;

            $data  = array_combine($header, array_pad($line, count($header), null));
            $nim   = trim($data['nim']   ?? '');
            $email = trim($data['email'] ?? '');
            $name  = trim($data['name']  ?? '');

            if (empty($nim) || empty($email) || empty($name)) {
                $skipped++;
                continue;
            }

            // Cek duplikat
            if (User::where('email', $email)->exists()) {
                $errors[] = "Baris {$row}: Email '{$email}' sudah terdaftar, dilewati.";
                $skipped++;
                continue;
            }
            if (Mahasiswa::where('nim', $nim)->exists()) {
                $errors[] = "Baris {$row}: NIM '{$nim}' sudah terdaftar, dilewati.";
                $skipped++;
                continue;
            }

            // Buat User
            $rawPassword = trim($data['password'] ?? '') ?: Str::random(10);
            $user = User::create([
                'name'      => $name,
                'email'     => $email,
                'password'  => Hash::make($rawPassword),
                'role'      => 'mahasiswa',
                'is_active' => true,
            ]);

            $tingkat   = trim($data['tingkat_pendidikan'] ?? 'S1');
            $isSekolah = in_array($tingkat, ['SD', 'SMP', 'SMA', 'SMK']);

            Mahasiswa::create([
                'user_id'          => $user->id,
                'nim'              => $nim,
                'tingkat_pendidikan' => $tingkat,
                'tempat_lahir'     => trim($data['tempat_lahir']    ?? ''),
                'tanggal_lahir'    => $data['tanggal_lahir']        ?? null,
                'jenis_kelamin'    => strtoupper(trim($data['jenis_kelamin'] ?? 'L')),
                'agama'            => trim($data['agama']            ?? ''),
                'alamat'           => trim($data['alamat']           ?? ''),
                'no_telp'          => trim($data['no_telp']          ?? ''),
                'asal_sekolah'     => $isSekolah ? trim($data['asal_sekolah'] ?? '') : null,
                'fakultas_id'      => !$isSekolah && is_numeric($data['fakultas_id'] ?? '') ? (int)$data['fakultas_id'] : null,
                'program_studi_id' => is_numeric($data['program_studi_id'] ?? '') ? (int)$data['program_studi_id'] : null,
                'tahun_masuk'      => is_numeric($data['tahun_masuk'] ?? '') ? (int)$data['tahun_masuk'] : null,
                'tahun_lulus'      => is_numeric($data['tahun_lulus'] ?? '') ? (int)$data['tahun_lulus'] : null,
                'nama_ortu'        => trim($data['nama_ortu']        ?? ''),
                'pekerjaan_ortu'   => trim($data['pekerjaan_ortu']   ?? ''),
                'no_telp_ortu'     => trim($data['no_telp_ortu']     ?? ''),
                'status'           => trim($data['status']           ?? 'aktif'),
            ]);

            $imported++;
        }

        fclose($handle);

        $message = "Import selesai: {$imported} mahasiswa berhasil ditambahkan, {$skipped} dilewati.";
        if (!empty($errors)) {
            $message .= ' Catatan: ' . implode('; ', array_slice($errors, 0, 5));
            if (count($errors) > 5) {
                $message .= ' ... dan ' . (count($errors) - 5) . ' pesan lainnya.';
            }
        }

        return redirect()->route('admin.mahasiswa.index')->with('success', $message);
    }
}