<?php

namespace App\Http\Controllers;

use App\Models\Perusahaan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PerusahaanController extends Controller
{
public function index(Request $request)
{
    $query = Perusahaan::with('user');

    // Pencarian text (Nama Perusahaan via tabel users, Bidang Usaha, atau Kota)
    if ($request->has('search') && $request->search != '') {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            // PERBAIKAN: Mencari nama perusahaan lewat relasi ke tabel 'users' kolom 'name'
            $q->whereHas('user', function ($userQuery) use ($search) {
                $userQuery->where('name', 'like', "%$search%");
            })
            ->orWhere('bidang_usaha', 'like', "%$search%")
            ->orWhere('kota', 'like', "%$search%");
        });
    }

    // Filter Khusus: Bidang Usaha (Dropdown)
    if ($request->filled('bidang_usaha')) {
        $query->where('bidang_usaha', $request->bidang_usaha);
    }

    // Filter Khusus: Status Kerjasama
    if ($request->has('status') && $request->status != '') { 
        $query->where('status_kerjasama', $request->status);
    }

    $perusahaan = $query->latest()->paginate(20)->withQueryString();

    // Mengambil semua bidang usaha yang unik untuk opsi di filter dropdown
    $list_bidang = Perusahaan::whereNotNull('bidang_usaha')
                             ->where('bidang_usaha', '!=', '')
                             ->distinct()
                             ->pluck('bidang_usaha');

    return view('admin.perusahaan.index', compact('perusahaan', 'list_bidang'));
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

    public function update(Request $request, Perusahaan $perusahaan)
{
    $request->validate([
        'nama_perusahaan' => 'required|string|max:255',
        'email'           => 'required|email|unique:users,email,' . $perusahaan->user_id,
        'password'        => 'nullable|string|min:6',
        'bidang_usaha'    => 'required|string',
        'jenis_pt'        => 'required|string',
        'no_telp'         => 'required|string',
        'nama_pimpinan'   => 'required|string',
        'alamat'          => 'required|string',
        'kota'            => 'required|string',
        'provinsi'        => 'required|string',
        'logo'            => 'nullable|image|max:2048',
        'cv_perusahaan'   => 'nullable|mimes:pdf|max:5120',
    ]);

    // 1. Update Tabel Users (Akun Relasi)
    $userData = ['name' => $request->nama_perusahaan, 'email' => $request->email];
    if ($request->filled('password')) {
        $userData['password'] = bcrypt($request->password);
    }
    $perusahaan->user()->update($userData);

    // 2. Handle Upload File Logo & CV jika ada
    $data = $request->except(['email', 'password']);
    if ($request->hasFile('logo')) {
        $data['logo'] = $request->file('logo')->store('logos', 'public');
    }
    if ($request->hasFile('cv_perusahaan')) {
        $data['cv_perusahaan'] = $request->file('cv_perusahaan')->store('cv_perusahaan', 'public');
    }

    // 3. Update Tabel Perusahaan
    $perusahaan->update($data);

    return redirect()->route('admin.perusahaan.show', $perusahaan)
                     ->with('success', 'Profil Perusahaan berhasil disinkronkan dan diperbarui.');
}

    // ─── Import Data Perusahaan ───────────────────────────────────────────────

    /**
     * Download template CSV untuk import perusahaan.
     */
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="template-import-perusahaan.csv"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF"); // BOM UTF-8 agar Excel terbaca
            fputcsv($file, [
                'nama_perusahaan',   // wajib — juga dipakai sebagai nama akun login
                'email',             // wajib — email login perusahaan
                'password',          // opsional — jika kosong, dibuat otomatis
                'bidang_usaha',
                'jenis_pt',
                'alamat',
                'kota',
                'provinsi',
                'kode_pos',
                'no_telp',
                'no_hp',
                'website',
                'nama_pimpinan',
                'tahun_berdiri',
                'jumlah_karyawan',
                'deskripsi',
                'status_kerjasama',  // aktif / pending / nonaktif
            ]);
            // Baris contoh
            fputcsv($file, [
                'PT Maju Sejahtera',
                'maju.sejahtera@email.com',
                'password123',
                'Teknologi Informasi',
                'PT Persekutuan Modal',
                'Jl. Sudirman No. 10',
                'Surabaya',
                'Jawa Timur',
                '60115',
                '031-12345678',
                '08123456789',
                'https://majusejahtera.co.id',
                'Budi Santoso',
                '2010',
                '50',
                'Perusahaan teknologi terkemuka di Jawa Timur',
                'aktif',
            ]);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Proses import data perusahaan dari file CSV.
     * Nama perusahaan disimpan di tabel `users` (kolom `name`).
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

        // Baca & bersihkan header
        $header = fgetcsv($handle);
        if ($header) {
            $header[0] = ltrim($header[0], "\xEF\xBB\xBF");
            $header    = array_map('trim', $header);
        }

        // Cek kolom wajib
        $requiredCols = ['nama_perusahaan', 'email'];
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

        $validStatus = ['aktif', 'pending', 'nonaktif'];

        while (($line = fgetcsv($handle)) !== false) {
            $row++;
            if (count($line) < 2) continue;

            $data  = array_combine($header, array_pad($line, count($header), null));
            $nama  = trim($data['nama_perusahaan'] ?? '');
            $email = trim($data['email']           ?? '');

            if (empty($nama) || empty($email)) {
                $skipped++;
                continue;
            }

            // Cek duplikat email
            if (User::where('email', $email)->exists()) {
                $errors[] = "Baris {$row}: Email '{$email}' sudah terdaftar, dilewati.";
                $skipped++;
                continue;
            }

            // Buat akun User
            $rawPassword = trim($data['password'] ?? '') ?: Str::random(10);
            $user = User::create([
                'name'      => $nama,
                'email'     => $email,
                'password'  => Hash::make($rawPassword),
                'role'      => 'perusahaan',
                'is_active' => true,
            ]);

            $status = trim($data['status_kerjasama'] ?? 'pending');
            if (!in_array($status, $validStatus)) $status = 'pending';

            // Buat profil Perusahaan
            Perusahaan::create([
                'user_id'         => $user->id,
                'bidang_usaha'    => trim($data['bidang_usaha']    ?? '') ?: null,
                'jenis_pt'        => trim($data['jenis_pt']        ?? '') ?: null,
                'alamat'          => trim($data['alamat']          ?? '') ?: null,
                'kota'            => trim($data['kota']            ?? '') ?: null,
                'provinsi'        => trim($data['provinsi']        ?? '') ?: null,
                'kode_pos'        => trim($data['kode_pos']        ?? '') ?: null,
                'no_telp'         => trim($data['no_telp']         ?? '') ?: null,
                'no_hp'           => trim($data['no_hp']           ?? '') ?: null,
                'website'         => trim($data['website']         ?? '') ?: null,
                'nama_pimpinan'   => trim($data['nama_pimpinan']   ?? '') ?: null,
                'tahun_berdiri'   => is_numeric($data['tahun_berdiri'] ?? '') ? (int)$data['tahun_berdiri'] : null,
                'jumlah_karyawan' => is_numeric($data['jumlah_karyawan'] ?? '') ? (int)$data['jumlah_karyawan'] : null,
                'deskripsi'       => trim($data['deskripsi']       ?? '') ?: null,
                'status_kerjasama'=> $status,
            ]);

            $imported++;
        }

        fclose($handle);

        $message = "Import selesai: {$imported} perusahaan berhasil ditambahkan, {$skipped} dilewati.";
        if (!empty($errors)) {
            $message .= ' Catatan: ' . implode('; ', array_slice($errors, 0, 5));
            if (count($errors) > 5) {
                $message .= ' ... dan ' . (count($errors) - 5) . ' pesan lainnya.';
            }
        }

        return redirect()->route('admin.perusahaan.index')->with('success', $message);
    }
}