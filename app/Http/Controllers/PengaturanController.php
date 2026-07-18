<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Fakultas;
use App\Models\Program_studi;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
        $user = Auth::user();
        $isMahasiswa = $user->isMahasiswa();
        $isPerusahaan = $user->isPerusahaan();

        // 1. ATURAN VALIDASI DINAMIS BERDASARKAN ROLE
        $rules = [
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'avatar'=> 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'password' => 'nullable|string|min:6|confirmed',
        ];

        if ($isMahasiswa) {
            $mahasiswa = $user->mahasiswa;
            $tingkat = $request->input('tingkat_pendidikan');
            $isSekolahDasar = in_array($tingkat, ['SD', 'SMP']);
            $isMenengahAtas = in_array($tingkat, ['SMA', 'SMK']);

            $rules = array_merge($rules, [
                'nim' => 'required|string|max:20|unique:mahasiswa,nim,' . $mahasiswa->id,
                'tingkat_pendidikan' => 'required|in:SD,SMP,SMA,SMK,D1,D2,D3,S1,S2,S3',
                'asal_sekolah' => ($isSekolahDasar || $isMenengahAtas) ? 'required|string|max:200' : 'nullable|string|max:200',
                'tempat_lahir' => 'nullable|string|max:100',
                'tanggal_lahir' => 'nullable|date',
                'jenis_kelamin' => 'required|in:L,P',
                'agama' => 'nullable|string|max:20',
                'alamat' => 'nullable|string',
                'no_telp' => 'nullable|string|max:15',
                'fakultas' => (!$isSekolahDasar && !$isMenengahAtas) ? 'required|string|max:150' : 'nullable|string|max:150', 
                'program_studi' => ($isMenengahAtas || (!$isSekolahDasar && !$isMenengahAtas)) ? 'required|string|max:200' : 'nullable|string|max:200', 
                'nama_ortu' => 'nullable|string|max:255',
                'pekerjaan_ortu' => 'nullable|string|max:100',
                'no_telp_ortu' => 'nullable|string|max:15',
            ]);
        } elseif ($isPerusahaan) {
            $perusahaan = $user->perusahaan;
            $rules = array_merge($rules, [
                'bidang_usaha' => 'nullable|string|max:100',
                'jenis_pt' => 'required|string|max:100',
                'alamat' => 'required|string',
                'kota' => 'required|string|max:100',
                'provinsi' => 'required|string|max:100',
                'kode_pos' => 'nullable|string|max:10',
                'no_telp' => 'required|string|max:15',
                'no_hp' => 'nullable|string|max:15',
                'website' => 'nullable|url',
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

        // 2. PROSES UPDATE TABEL USERS (COMMON DATA)
        $userData = [
            'name'  => $validated['name'],
            'email' => $validated['email'],
        ];

        // Jika ganti password
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($validated['password']);
        }

        // Jika mengunggah Avatar / Logo baru
        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $userData['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($userData);

       if ($isMahasiswa) {
    $mahasiswa = $user->mahasiswa;
    $mahasiswa->update([
        'nim'                => $validated['nim'],
        'tingkat_pendidikan' => $validated['tingkat_pendidikan'],
        'tempat_lahir'       => $request->tempat_lahir, // Menggunakan $request lebih aman untuk data nullable
        'tanggal_lahir'      => $request->tanggal_lahir,
        'jenis_kelamin'      => $validated['jenis_kelamin'],
        'agama'              => $request->agama,
        'alamat'             => $request->alamat,
        'no_telp'            => $request->no_telp,
        'asal_sekolah'       => $request->asal_sekolah,
        
        // Menggunakan request agar data string bebas terpetakan dengan baik
        'fakultas_id'        => (!$isSekolahDasar && !$isMenengahAtas) ? $request->fakultas : null,
        'program_studi_id'   => ($isMenengahAtas || (!$isSekolahDasar && !$isMenengahAtas)) ? $request->program_studi : null,
        
        'nama_ortu'          => $request->nama_ortu,
        'pekerjaan_ortu'     => $request->pekerjaan_ortu, // Menggunakan $request mencegah error undefined key
        'no_telp_ortu'       => $request->no_telp_ortu,
    ]);

        } elseif ($isPerusahaan) {
            $perusahaan = $user->perusahaan;
            
            // Jika mengunggah berkas PDF baru
            $cvPath = $perusahaan->cv_perusahaan;
            if ($request->hasFile('cv_perusahaan')) {
                if ($perusahaan->cv_perusahaan) {
                    Storage::disk('public')->delete($perusahaan->cv_perusahaan);
                }
                $cvPath = $request->file('cv_perusahaan')->store('perusahaan/cv', 'public');
            }

            $perusahaan->update([
                'bidang_usaha'    => $validated['bidang_usaha'],
                'jenis_pt'        => $validated['jenis_pt'],
                'alamat'          => $validated['alamat'],
                'kota'            => $validated['kota'],
                'provinsi'        => $validated['provinsi'],
                'kode_pos'        => $validated['kode_pos'],
                'no_telp'         => $validated['no_telp'],
                'no_hp'           => $validated['no_hp'],
                'website'         => $validated['website'],
                'nama_pimpinan'   => $validated['nama_pimpinan'],
                'tahun_berdiri'   => $validated['tahun_berdiri'],
                'jumlah_karyawan' => $validated['jumlah_karyawan'],
                'visi'            => $validated['visi'],
                'misi'            => $validated['misi'],
                'deskripsi'       => $validated['deskripsi'],
                'cv_perusahaan'   => $cvPath,
            ]);
        }

        return redirect()->route('profile')->with('success', 'Profil Anda berhasil diperbarui.');
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
