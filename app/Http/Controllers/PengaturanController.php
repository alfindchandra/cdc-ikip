<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Fakultas;
use App\Models\Program_studi;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class PengaturanController extends Controller
{
     public function profile()
    {
        $user = auth()->user();
        $fakultas = Fakultas::all();
        $program_studi = Program_studi::all();
        return view('profile.index', compact('user', 'fakultas', 'program_studi'));
    }

    public function profileEdit()
    {
        return view('profile.edit');
    }
 
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        // 1. Validasi dasar global (User data)
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6|confirmed',
            'avatar' => 'nullable|image|max:2048',
        ];

        // 2. Tambah validasi sesuai role
        if ($user->isMahasiswa()) {
            $rules = array_merge($rules, [
                'nim' => 'nullable|string|max:20|unique:mahasiswa,nim,' . $user->mahasiswa->id,
                'tempat_lahir' => 'nullable|string|max:100',
                'tanggal_lahir' => 'nullable|date',
                'jenis_kelamin' => 'required|in:L,P',
                'agama' => 'nullable|string',
                'alamat' => 'nullable|string',
                'no_telp' => 'nullable|string|max:15',
                'kelas' => 'nullable|string|max:10',
                'jurusan' => 'nullable|string|max:50',
                'tahun_masuk' => 'nullable|integer|min:2000|max:' . date('Y'),
                'nama_ortu' => 'nullable|string',
                'pekerjaan_ortu' => 'nullable|string',
                'no_telp_ortu' => 'nullable|string|max:15',
            ]);
        } elseif ($user->isPerusahaan()) {
            // Sesuai $fillable Model Perusahaan Anda
            $rules = array_merge($rules, [
                'bidang_usaha' => 'nullable|string|max:255',
                'jenis_pt' => 'nullable|string|max:100',
                'alamat' => 'required|string',
                'kota' => 'required|string|max:100',
                'provinsi' => 'required|string|max:100',
                'kode_pos' => 'nullable|string|max:10',
                'no_telp' => 'required|string|max:20',
                'no_hp' => 'nullable|string|max:20',
                'website' => 'nullable|url|max:255',
                'nama_pimpinan' => 'required|string|max:255',
                'tahun_berdiri' => 'nullable|integer|min:1900|max:' . date('Y'),
                'jumlah_karyawan' => 'nullable|integer|min:0',
                'visi' => 'nullable|string',
                'misi' => 'nullable|string',
                'deskripsi' => 'nullable|string',
                'cv_perusahaan' => 'nullable|file|mimes:pdf|max:5120',
            ]);
        }

        $validated = $request->validate($rules);

        // 3. Handle upload Avatar (Logo/Foto Profil)
        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        // 4. Update data Tabel `users`
        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'avatar' => $validated['avatar'] ?? $user->avatar,
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($validated['password']);
        }

        $user->update($userData);

        // 5. Jalankan update ke tabel spesifik
        if ($user->isMahasiswa()) {
            $user->mahasiswa->update([
                'nim' => $validated['nim'] ?? null,
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
            ]);
        } elseif ($user->isPerusahaan()) {
            
            // Handle upload berkas PDF profil/CV Perusahaan jika ada file baru
            $cvPath = $user->perusahaan->cv_perusahaan;
            if ($request->hasFile('cv_perusahaan')) {
                if ($cvPath) {
                    Storage::disk('public')->delete($cvPath);
                }
                $cvPath = $request->file('cv_perusahaan')->store('perusahaan/cv', 'public');
            }

            $user->perusahaan->update([
                'bidang_usaha' => $validated['bidang_usaha'] ?? $user->perusahaan->bidang_usaha,
                'jenis_pt' => $validated['jenis_pt'] ?? $user->perusahaan->jenis_pt,
                'alamat' => $validated['alamat'],
                'kota' => $validated['kota'],
                'provinsi' => $validated['provinsi'],
                'kode_pos' => $validated['kode_pos'] ?? $user->perusahaan->kode_pos,
                'no_telp' => $validated['no_telp'],
                'no_hp' => $validated['no_hp'] ?? $user->perusahaan->no_hp,
                'website' => $validated['website'] ?? $user->perusahaan->website,
                'nama_pimpinan' => $validated['nama_pimpinan'],
                'tahun_berdiri' => $validated['tahun_berdiri'] ?? $user->perusahaan->tahun_berdiri,
                'jumlah_karyawan' => $validated['jumlah_karyawan'] ?? $user->perusahaan->jumlah_karyawan,
                'visi' => $validated['visi'] ?? $user->perusahaan->visi,
                'misi' => $validated['misi'] ?? $user->perusahaan->misi,
                'deskripsi' => $validated['deskripsi'] ?? $user->perusahaan->deskripsi,
                'cv_perusahaan' => $cvPath,
            ]);
        }

        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui');
    }
    public function updateProfilePerusahaan(Request $request)
{
    $user = auth()->user();
    $perusahaan = $user->perusahaan;

    $validated = $request->validate([
        'nama_perusahaan' => 'required|string|max:255',
        'bidang_usaha' => 'nullable|string|max:255',
        'alamat' => 'nullable|string',
        'kota' => 'nullable|string|max:100',
        'provinsi' => 'nullable|string|max:100',
        'kode_pos' => 'nullable|string|max:10',
        'no_telp' => 'nullable|string|max:20',
        'website' => 'nullable|url|max:255',
        'nama_pic' => 'nullable|string|max:100',
        'jabatan_pic' => 'nullable|string|max:100',
        'no_telp_pic' => 'nullable|string|max:20',
        'email_pic' => 'nullable|email|max:255',
        'avatar' => 'nullable|image|max:2048',
        'deskripsi' => 'nullable|string',
        'password' => 'nullable|min:6|confirmed',
    ]);

    // === Handle upload avatar ===
    if ($request->hasFile('avatar')) {
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }
        $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
    }

    // === Update data perusahaan ===
    $perusahaan->update([
        'nama_perusahaan' => $validated['nama_perusahaan'],
        'bidang_usaha' => $validated['bidang_usaha'] ?? $perusahaan->bidang_usaha,
        'alamat' => $validated['alamat'] ?? $perusahaan->alamat,
        'kota' => $validated['kota'] ?? $perusahaan->kota,
        'provinsi' => $validated['provinsi'] ?? $perusahaan->provinsi,
        'kode_pos' => $validated['kode_pos'] ?? $perusahaan->kode_pos,
        'no_telp' => $validated['no_telp'] ?? $perusahaan->no_telp,
        'website' => $validated['website'] ?? $perusahaan->website,
        'nama_pic' => $validated['nama_pic'] ?? $perusahaan->nama_pic,
        'jabatan_pic' => $validated['jabatan_pic'] ?? $perusahaan->jabatan_pic,
        'no_telp_pic' => $validated['no_telp_pic'] ?? $perusahaan->no_telp_pic,
        'email_pic' => $validated['email_pic'] ?? $perusahaan->email_pic,
        'deskripsi' => $validated['deskripsi'] ?? $perusahaan->deskripsi,
    ]);

    // === Update data user ===
    $user->update([
        'name' => $validated['nama_perusahaan'], // gunakan nama perusahaan sebagai nama user
        'email' => $validated['email_pic'] ?? $user->email, // gunakan email PIC jika ada
        'avatar' => $validated['avatar'] ?? $user->avatar, // gunakan avatar baru atau pertahankan lama
        'password' => !empty($validated['password'])
            ? Hash::make($validated['password'])
            : $user->password,
    ]);

    return redirect()->back()->with('success', 'Profil perusahaan berhasil diperbarui.');
}




}
