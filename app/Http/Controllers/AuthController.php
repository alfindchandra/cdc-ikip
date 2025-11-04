<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Siswa;
use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:siswa,perusahaan',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'is_active' => true,
        ]);

        // Create profile based on role
        if ($validated['role'] === 'siswa') {
            Siswa::create([
                'user_id' => $user->id,
                'nis' => 'AUTO-' . time(),
                'jenis_kelamin' => 'L',
                'status' => 'aktif',
            ]);
        } elseif ($validated['role'] === 'perusahaan') {
            Perusahaan::create([
                'user_id' => $user->id,
                'nama_perusahaan' => $validated['name'],
                'status_kerjasama' => 'pending',
            ]);
        }

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Registrasi berhasil! Selamat datang.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function profile()
    {
        return view('profile.index');
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6|confirmed',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($validated['password'])]);
        }

        return back()->with('success', 'Profil berhasil diperbarui');
    }
}

// app/Http/Controllers/PklController.php
namespace App\Http\Controllers;

use App\Models\Pkl;
use App\Models\JurnalPkl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PklController extends Controller
{
    // Siswa Methods
    public function siswaPkl()
    {
        return view('siswa.pkl.index');
    }

    public function daftar(Request $request)
    {
        $validated = $request->validate([
            'perusahaan_id' => 'required|exists:perusahaan,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'posisi' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        $siswa = auth()->user()->siswa;

        // Check if already has active PKL
        $hasActivePkl = Pkl::where('siswa_id', $siswa->id)
                          ->whereIn('status', ['pengajuan', 'diterima', 'berlangsung'])
                          ->exists();

        if ($hasActivePkl) {
            return back()->with('error', 'Anda masih memiliki PKL aktif');
        }

        Pkl::create([
            'siswa_id' => $siswa->id,
            'perusahaan_id' => $validated['perusahaan_id'],
            'tanggal_mulai' => $validated['tanggal_mulai'],
            'tanggal_selesai' => $validated['tanggal_selesai'],
            'posisi' => $validated['posisi'],
            'catatan' => $validated['catatan'],
            'status' => 'pengajuan',
        ]);

        return back()->with('success', 'Pengajuan PKL berhasil dikirim');
    }

    public function show(Pkl $pkl)
    {
        $pkl->load(['siswa.user', 'perusahaan', 'jurnalPkl']);
        
        // Authorization check
        if (auth()->user()->isAdmin()) {
            return view('admin.pkl.show', compact('pkl'));
        } elseif (auth()->user()->isSiswa() && $pkl->siswa_id == auth()->user()->siswa->id) {
            return view('siswa.pkl.show', compact('pkl'));
        } elseif (auth()->user()->isPerusahaan() && $pkl->perusahaan_id == auth()->user()->perusahaan->id) {
            return view('perusahaan.pkl.show', compact('pkl'));
        }

        abort(403);
    }

    public function addJurnal(Request $request, Pkl $pkl)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'kegiatan' => 'required|string',
            'foto' => 'nullable|image|max:2048',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('jurnal', 'public');
        }

        JurnalPkl::create([
            'pkl_id' => $pkl->id,
            'tanggal' => $validated['tanggal'],
            'kegiatan' => $validated['kegiatan'],
            'foto' => $fotoPath,
            'status_validasi' => 'pending',
        ]);

        return back()->with('success', 'Jurnal berhasil ditambahkan');
    }

    public function updateJurnal(Request $request, $id)
    {
        $jurnal = JurnalPkl::findOrFail($id);

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'kegiatan' => 'required|string',
            'foto' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if ($jurnal->foto) {
                Storage::disk('public')->delete($jurnal->foto);
            }
            $validated['foto'] = $request->file('foto')->store('jurnal', 'public');
        }

        $jurnal->update($validated);

        return back()->with('success', 'Jurnal berhasil diperbarui');
    }

    public function deleteJurnal($id)
    {
        $jurnal = JurnalPkl::findOrFail($id);
        
        if ($jurnal->foto) {
            Storage::disk('public')->delete($jurnal->foto);
        }

        $jurnal->delete();

        return back()->with('success', 'Jurnal berhasil dihapus');
    }

    public function uploadLaporan(Request $request, Pkl $pkl)
    {
        $validated = $request->validate([
            'laporan_pkl' => 'required|file|mimes:pdf|max:10240',
        ]);

        if ($request->hasFile('laporan_pkl')) {
            if ($pkl->laporan_pkl) {
                Storage::disk('public')->delete($pkl->laporan_pkl);
            }
            
            $laporanPath = $request->file('laporan_pkl')->store('laporan-pkl', 'public');
            $pkl->update(['laporan_pkl' => $laporanPath]);
        }

        return back()->with('success', 'Laporan PKL berhasil diupload');
    }

    // Admin Methods
    public function index(Request $request)
    {
        $query = Pkl::with(['siswa.user', 'perusahaan']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('siswa.user', function($q) use ($search) {
                $q->where('name', 'like', "%$search%");
            })->orWhereHas('perusahaan', function($q) use ($search) {
                $q->where('nama_perusahaan', 'like', "%$search%");
            });
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $pkl = $query->latest()->paginate(20);

        return view('admin.pkl.index', compact('pkl'));
    }

    public function updateStatus(Request $request, Pkl $pkl)
    {
        $validated = $request->validate([
            'status' => 'required|in:pengajuan,diterima,ditolak,berlangsung,selesai',
        ]);

        $pkl->update(['status' => $validated['status']]);

        return back()->with('success', 'Status PKL berhasil diperbarui');
    }

    public function inputNilai(Request $request, Pkl $pkl)
    {
        $validated = $request->validate([
            'nilai_akhir' => 'required|numeric|min:0|max:100',
        ]);

        $pkl->update(['nilai_akhir' => $validated['nilai_akhir']]);

        return back()->with('success', 'Nilai PKL berhasil disimpan');
    }

    public function showJurnal(Pkl $pkl)
    {
        $pkl->load(['jurnalPkl' => function($query) {
            $query->latest('tanggal');
        }]);

        return view('admin.pkl.jurnal', compact('pkl'));
    }

    public function validasiJurnal(Request $request, $jurnalId)
    {
        $jurnal = JurnalPkl::findOrFail($jurnalId);

        $validated = $request->validate([
            'status_validasi' => 'required|in:disetujui,ditolak',
            'catatan_pembimbing' => 'nullable|string',
        ]);

        $jurnal->update($validated);

        return back()->with('success', 'Jurnal berhasil divalidasi');
    }

    // Perusahaan Methods
    public function perusahaanPkl()
    {
        $perusahaan = auth()->user()->perusahaan;
        $pkl = Pkl::where('perusahaan_id', $perusahaan->id)
                  ->with('siswa.user')
                  ->latest()
                  ->paginate(20);

        return view('perusahaan.pkl.index', compact('pkl'));
    }

    public function terimaPkl(Request $request, Pkl $pkl)
    {
        $validated = $request->validate([
            'pembimbing_industri' => 'required|string',
            'divisi' => 'nullable|string',
        ]);

        $pkl->update([
            'status' => 'diterima',
            'pembimbing_industri' => $validated['pembimbing_industri'],
            'divisi' => $validated['divisi'],
        ]);

        return back()->with('success', 'Pengajuan PKL telah diterima');
    }

    public function tolakPkl(Request $request, Pkl $pkl)
    {
        $validated = $request->validate([
            'catatan' => 'required|string',
        ]);

        $pkl->update([
            'status' => 'ditolak',
            'catatan' => $validated['catatan'],
        ]);

        return back()->with('success', 'Pengajuan PKL telah ditolak');
    }
}

// app/Http/Controllers/LowonganKerjaController.php
namespace App\Http\Controllers;

use App\Models\LowonganKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LowonganKerjaController extends Controller
{
    // Siswa Methods
    public function index(Request $request)
    {
        $query = LowonganKerja::with('perusahaan')->aktif();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%$search%")
                  ->orWhere('posisi', 'like', "%$search%")
                  ->orWhereHas('perusahaan', function($q2) use ($search) {
                      $q2->where('nama_perusahaan', 'like', "%$search%");
                  });
            });
        }

        if ($request->has('tipe')) {
            $query->where('tipe_pekerjaan', $request->tipe);
        }

        $lowongan = $query->latest()->paginate(12);

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

        return view('lowongan.show', compact('lowongan'));
    }

    // Admin Methods
    public function adminIndex(Request $request)
    {
        $query = LowonganKerja::with('perusahaan');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('judul', 'like', "%$search%")
                  ->orWhere('posisi', 'like', "%$search%");
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $lowongan = $query->latest()->paginate(20);

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

        $perusahaan = auth()->user()->perusahaan;

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('lowongan', 'public');
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

// app/Http/Controllers/LamaranController.php
namespace App\Http\Controllers;

use App\Models\Lamaran;
use App\Models\LowonganKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LamaranController extends Controller
{
    // Siswa Methods
    public function index()
    {
        $siswa = auth()->user()->siswa;
        $lamaran = Lamaran::where('siswa_id', $siswa->id)
                         ->with('lowongan.perusahaan')
                         ->latest()
                         ->paginate(20);

        return view('siswa.lamaran.index', compact('lamaran'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'lowongan_id' => 'required|exists:lowongan_kerja,id',
            'cv' => 'required|file|mimes:pdf|max:5120',
            'surat_lamaran' => 'nullable|file|mimes:pdf|max:5120',
            'portofolio' => 'nullable|file|mimes:pdf,zip|max:10240',
            'catatan' => 'nullable|string',
        ]);

        $siswa = auth()->user()->siswa;

        // Check if already applied
        $exists = Lamaran::where('siswa_id', $siswa->id)
                        ->where('lowongan_id', $validated['lowongan_id'])
                        ->exists();

        if ($exists) {
            return back()->with('error', 'Anda sudah melamar lowongan ini');
        }

        $data = [
            'lowongan_id' => $validated['lowongan_id'],
            'siswa_id' => $siswa->id,
            'status' => 'dikirim',
            'catatan' => $validated['catatan'],
        ];

        if ($request->hasFile('cv')) {
            $data['cv'] = $request->file('cv')->store('lamaran/cv', 'public');
        }

        if ($request->hasFile('surat_lamaran')) {
            $data['surat_lamaran'] = $request->file('surat_lamaran')->store('lamaran/surat', 'public');
        }

        if ($request->hasFile('portofolio')) {
            $data['portofolio'] = $request->file('portofolio')->store('lamaran/portofolio', 'public');
        }

        Lamaran::create($data);

        // Increment jumlah pelamar
        LowonganKerja::find($validated['lowongan_id'])->increment('jumlah_pelamar');

        return redirect()->route('siswa.lamaran.index')
                        ->with('success', 'Lamaran berhasil dikirim');
    }

    public function show(Lamaran $lamaran)
    {
        $lamaran->load('lowongan.perusahaan', 'siswa.user');

        if (auth()->user()->isAdmin()) {
            return view('admin.lamaran.show', compact('lamaran'));
        } elseif (auth()->user()->isSiswa() && $lamaran->siswa_id == auth()->user()->siswa->id) {
            return view('siswa.lamaran.show', compact('lamaran'));
        } elseif (auth()->user()->isPerusahaan() && $lamaran->lowongan->perusahaan_id == auth()->user()->perusahaan->id) {
            return view('perusahaan.lamaran.show', compact('lamaran'));
        }

        abort(403);
    }

    public function destroy(Lamaran $lamaran)
    {
        // Only allow deletion if status is 'dikirim'
        if ($lamaran->status !== 'dikirim') {
            return back()->with('error', 'Lamaran tidak dapat dibatalkan');
        }

        if ($lamaran->cv) Storage::disk('public')->delete($lamaran->cv);
        if ($lamaran->surat_lamaran) Storage::disk('public')->delete($lamaran->surat_lamaran);
        if ($lamaran->portofolio) Storage::disk('public')->delete($lamaran->portofolio);

        // Decrement jumlah pelamar
        $lamaran->lowongan->decrement('jumlah_pelamar');

        $lamaran->delete();

        return back()->with('success', 'Lamaran berhasil dibatalkan');
    }

    // Admin Methods
    public function adminIndex(Request $request)
    {
        $query = Lamaran::with(['siswa.user', 'lowongan.perusahaan']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $lamaran = $query->latest()->paginate(20);

        return view('admin.lamaran.index', compact('lamaran'));
    }

    // Perusahaan Methods
    public function perusahaanIndex(Request $request)
    {
        $perusahaan = auth()->user()->perusahaan;
        
        $query = Lamaran::whereHas('lowongan', function($q) use ($perusahaan) {
            $q->where('perusahaan_id', $perusahaan->id);
        })->with(['siswa.user', 'lowongan']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $lamaran = $query->latest()->paginate(20);

        return view('perusahaan.lamaran.index', compact('lamaran'));
    }

    public function updateStatus(Request $request, Lamaran $lamaran)
    {
        $validated = $request->validate([
            'status' => 'required|in:dilihat,diproses,diterima,ditolak',
            'catatan' => 'nullable|string',
        ]);

        $lamaran->update($validated);

        return back()->with('success', 'Status lamaran berhasil diperbarui');
    }
}

// app/Http/Controllers/PelatihanController.php
namespace App\Http\Controllers;

use App\Models\Pelatihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PelatihanController extends Controller
{
    // Admin Methods
    public function index(Request $request)
    {
        $query = Pelatihan::query();

        if ($request->has('search')) {
            $query->where('judul', 'like', "%{$request->search}%");
        }

        if ($request->has('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $pelatihan = $query->latest()->paginate(20);

        return view('admin.pelatihan.index', compact('pelatihan'));
    }

    public function create()
    {
        return view('admin.pelatihan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'jenis' => 'required|in:soft_skill,hard_skill,sertifikasi,pembekalan',
            'instruktur' => 'nullable|string',
            'tempat' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'kuota' => 'nullable|integer',
            'biaya' => 'nullable|numeric',
            'materi' => 'nullable|file|mimes:pdf|max:10240',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('materi')) {
            $validated['materi'] = $request->file('materi')->store('pelatihan/materi', 'public');
        }

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('pelatihan/thumbnails', 'public');
        }

        $validated['status'] = 'draft';

        Pelatihan::create($validated);

        return redirect()->route('admin.pelatihan.index')
                        ->with('success', 'Pelatihan berhasil ditambahkan');
    }

    public function edit(Pelatihan $pelatihan)
    {
        return view('admin.pelatihan.edit', compact('pelatihan'));
    }

    public function update(Request $request, Pelatihan $pelatihan)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'jenis' => 'required|in:soft_skill,hard_skill,sertifikasi,pembekalan',
            'instruktur' => 'nullable|string',
            'tempat' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'kuota' => 'nullable|integer',
            'biaya' => 'nullable|numeric',
            'materi' => 'nullable|file|mimes:pdf|max:10240',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('materi')) {
            if ($pelatihan->materi) {
                Storage::disk('public')->delete($pelatihan->materi);
            }
            $validated['materi'] = $request->file('materi')->store('pelatihan/materi', 'public');
        }

        if ($request->hasFile('thumbnail')) {
            if ($pelatihan->thumbnail) {
                Storage::disk('public')->delete($pelatihan->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')->store('pelatihan/thumbnails', 'public');
        }

        $pelatihan->update($validated);

        return redirect()->route('admin.pelatihan.index')
                        ->with('success', 'Pelatihan berhasil diperbarui');
    }

    public function destroy(Pelatihan $pelatihan)
    {
        if ($pelatihan->materi) Storage::disk('public')->delete($pelatihan->materi);
        if ($pelatihan->thumbnail) Storage::disk('public')->delete($pelatihan->thumbnail);

        $pelatihan->delete();

        return back()->with('success', 'Pelatihan berhasil dihapus');
    }

    public function publish(Pelatihan $pelatihan)
    {
        $pelatihan->update(['status' => 'published']);
        return back()->with('success', 'Pelatihan berhasil dipublikasikan');
    }

    public function peserta(Pelatihan $pelatihan)
    {
        $pelatihan->load(['peserta.user']);
        return view('admin.pelatihan.peserta', compact('pelatihan'));
    }

    public function updateStatusPeserta(Request $request, $pesertaId)
    {
        $validated = $request->validate([
            'status_pendaftaran' => 'required|in:daftar,diterima,ditolak',
        ]);

        $peserta = \DB::table('peserta_pelatihan')->where('id', $pesertaId)->first();
        
        \DB::table('peserta_pelatihan')
            ->where('id', $pesertaId)
            ->update(['status_pendaftaran' => $validated['status_pendaftaran']]);

        return back()->with('success', 'Status peserta berhasil diperbarui');
    }

    public function inputNilai(Request $request, $pesertaId)
    {
        $validated = $request->validate([
            'nilai' => 'required|numeric|min:0|max:100',
            'status_kehadiran' => 'required|in:hadir,tidak_hadir,izin',
        ]);

        \DB::table('peserta_pelatihan')
            ->where('id', $pesertaId)
            ->update($validated);

        return back()->with('success', 'Nilai berhasil disimpan');
    }

    // Siswa Methods
    public function siswaPelatihan(Request $request)
    {
        $query = Pelatihan::published();

        if ($request->has('search')) {
            $query->where('judul', 'like', "%{$request->search}%");
        }

        if ($request->has('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        $pelatihan = $query->where('tanggal_mulai', '>', now())
                           ->latest('tanggal_mulai')
                           ->paginate(12);

        return view('siswa.pelatihan.index', compact('pelatihan'));
    }

    public function show(Pelatihan $pelatihan)
    {
        if (auth()->user()->isAdmin()) {
            return view('admin.pelatihan.show', compact('pelatihan'));
        } elseif (auth()->user()->isSiswa()) {
            return view('siswa.pelatihan.show', compact('pelatihan'));
        }

        return view('pelatihan.show', compact('pelatihan'));
    }

    public function daftar(Request $request, Pelatihan $pelatihan)
    {
        $siswa = auth()->user()->siswa;

        // Check if already registered
        $exists = $pelatihan->peserta()->where('siswa_id', $siswa->id)->exists();

        if ($exists) {
            return back()->with('error', 'Anda sudah terdaftar di pelatihan ini');
        }

        // Check quota
        if ($pelatihan->kuota && $pelatihan->jumlah_peserta >= $pelatihan->kuota) {
            return back()->with('error', 'Kuota pelatihan sudah penuh');
        }

        $pelatihan->peserta()->attach($siswa->id, [
            'status_pendaftaran' => 'daftar',
            'tanggal_daftar' => now(),
        ]);

        $pelatihan->increment('jumlah_peserta');

        return back()->with('success', 'Pendaftaran pelatihan berhasil');
    }

    public function batalDaftar(Pelatihan $pelatihan)
    {
        $siswa = auth()->user()->siswa;

        $pelatihan->peserta()->detach($siswa->id);
        $pelatihan->decrement('jumlah_peserta');

        return back()->with('success', 'Pendaftaran pelatihan berhasil dibatalkan');
    }
}

// app/Http/Controllers/KerjasamaIndustriController.php
namespace App\Http\Controllers;

use App\Models\KerjasamaIndustri;
use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KerjasamaIndustriController extends Controller
{
    // Admin Methods
    public function index(Request $request)
    {
        $query = KerjasamaIndustri::with('perusahaan');

        if ($request->has('search')) {
            $query->where('judul', 'like', "%{$request->search}%");
        }

        if ($request->has('jenis')) {
            $query->where('jenis_kerjasama', $request->jenis);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $kerjasama = $query->latest()->paginate(20);

        return view('admin.kerjasama.index', compact('kerjasama'));
    }

    public function create()
    {
        $perusahaan = Perusahaan::where('status_kerjasama', 'aktif')->get();
        return view('admin.kerjasama.create', compact('perusahaan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'perusahaan_id' => 'required|exists:perusahaan,id',
            'jenis_kerjasama' => 'required|in:pkl,rekrutmen,pelatihan,penelitian,sponsorship,lainnya',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_berakhir' => 'nullable|date|after:tanggal_mulai',
            'dokumen_mou' => 'nullable|file|mimes:pdf|max:10240',
            'pic_sekolah' => 'nullable|string',
            'pic_industri' => 'nullable|string',
            'nilai_kontrak' => 'nullable|numeric',
            'catatan' => 'nullable|string',
        ]);

        if ($request->hasFile('dokumen_mou')) {
            $validated['dokumen_mou'] = $request->file('dokumen_mou')->store('kerjasama/mou', 'public');
        }

        $validated['status'] = 'draft';

        KerjasamaIndustri::create($validated);

        return redirect()->route('admin.kerjasama.index')
                        ->with('success', 'Kerjasama berhasil ditambahkan');
    }

    public function show(KerjasamaIndustri $kerjasama)
    {
        $kerjasama->load('perusahaan');

        if (auth()->user()->isAdmin()) {
            return view('admin.kerjasama.show', compact('kerjasama'));
        } elseif (auth()->user()->isPerusahaan() && $kerjasama->perusahaan_id == auth()->user()->perusahaan->id) {
            return view('perusahaan.kerjasama.show', compact('kerjasama'));
        }

        abort(403);
    }

    public function edit(KerjasamaIndustri $kerjasama)
    {
        $perusahaan = Perusahaan::where('status_kerjasama', 'aktif')->get();
        return view('admin.kerjasama.edit', compact('kerjasama', 'perusahaan'));
    }

    public function update(Request $request, KerjasamaIndustri $kerjasama)
    {
        $validated = $request->validate([
            'perusahaan_id' => 'required|exists:perusahaan,id',
            'jenis_kerjasama' => 'required|in:pkl,rekrutmen,pelatihan,penelitian,sponsorship,lainnya',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_berakhir' => 'nullable|date|after:tanggal_mulai',
            'dokumen_mou' => 'nullable|file|mimes:pdf|max:10240',
            'pic_sekolah' => 'nullable|string',
            'pic_industri' => 'nullable|string',
            'nilai_kontrak' => 'nullable|numeric',
            'catatan' => 'nullable|string',
        ]);

        if ($request->hasFile('dokumen_mou')) {
            if ($kerjasama->dokumen_mou) {
                Storage::disk('public')->delete($kerjasama->dokumen_mou);
            }
            $validated['dokumen_mou'] = $request->file('dokumen_mou')->store('kerjasama/mou', 'public');
        }

        $kerjasama->update($validated);

        return redirect()->route('admin.kerjasama.index')
                        ->with('success', 'Kerjasama berhasil diperbarui');
    }

    public function destroy(KerjasamaIndustri $kerjasama)
    {
        if ($kerjasama->dokumen_mou) {
            Storage::disk('public')->delete($kerjasama->dokumen_mou);
        }

        $kerjasama->delete();

        return back()->with('success', 'Kerjasama berhasil dihapus');
    }

    public function updateStatus(Request $request, KerjasamaIndustri $kerjasama)
    {
        $validated = $request->validate([
            'status' => 'required|in:draft,proposal,negosiasi,aktif,selesai,batal',
        ]);

        $kerjasama->update(['status' => $validated['status']]);

        return back()->with('success', 'Status kerjasama berhasil diperbarui');
    }

    // Perusahaan Methods
    public function perusahaanIndex()
    {
        $perusahaan = auth()->user()->perusahaan;
        $kerjasama = KerjasamaIndustri::where('perusahaan_id', $perusahaan->id)
                                      ->latest()
                                      ->paginate(20);

        return view('perusahaan.kerjasama.index', compact('kerjasama'));
    }
}

// app/Http/Controllers/LaporanController.php
namespace App\Http\Controllers;

use App\Models\Laporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = Laporan::with('creator');

        if ($request->has('search')) {
            $query->where('judul', 'like', "%{$request->search}%");
        }

        if ($request->has('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        $laporan = $query->latest()->paginate(20);

        return view('admin.laporan.index', compact('laporan'));
    }

    public function create()
    {
        return view('admin.laporan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'jenis' => 'required|in:pkl,pelatihan,rekrutmen,kerjasama,tahunan,lainnya',
            'periode_mulai' => 'required|date',
            'periode_selesai' => 'required|date|after:periode_mulai',
            'deskripsi' => 'nullable|string',
            'file_laporan' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        if ($request->hasFile('file_laporan')) {
            $validated['file_laporan'] = $request->file('file_laporan')->store('laporan', 'public');
        }

        $validated['created_by'] = auth()->id();
        $validated['status'] = 'draft';

        Laporan::create($validated);

        return redirect()->route('admin.laporan.index')
                        ->with('success', 'Laporan berhasil ditambahkan');
    }

    public function show(Laporan $laporan)
    {
        return view('admin.laporan.show', compact('laporan'));
    }

    public function edit(Laporan $laporan)
    {
        return view('admin.laporan.edit', compact('laporan'));
    }

    public function update(Request $request, Laporan $laporan)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'jenis' => 'required|in:pkl,pelatihan,rekrutmen,kerjasama,tahunan,lainnya',
            'periode_mulai' => 'required|date',
            'periode_selesai' => 'required|date|after:periode_mulai',
            'deskripsi' => 'nullable|string',
            'file_laporan' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        if ($request->hasFile('file_laporan')) {
            if ($laporan->file_laporan) {
                Storage::disk('public')->delete($laporan->file_laporan);
            }
            $validated['file_laporan'] = $request->file('file_laporan')->store('laporan', 'public');
        }

        $laporan->update($validated);

        return redirect()->route('admin.laporan.index')
                        ->with('success', 'Laporan berhasil diperbarui');
    }

    public function destroy(Laporan $laporan)
    {
        if ($laporan->file_laporan) {
            Storage::disk('public')->delete($laporan->file_laporan);
        }

        $laporan->delete();

        return back()->with('success', 'Laporan berhasil dihapus');
    }

    public function download(Laporan $laporan)
    {
        if (!$laporan->file_laporan) {
            return back()->with('error', 'File laporan tidak tersedia');
        }

        return Storage::disk('public')->download($laporan->file_laporan);
    }

    public function publish(Laporan $laporan)
    {
        $laporan->update(['status' => 'published']);
        return back()->with('success', 'Laporan berhasil dipublikasikan');
    }
}

// app/Http/Controllers/NotifikasiController.php
namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index()
    {
        $notifikasi = auth()->user()->notifikasi()
                                   ->latest()
                                   ->paginate(20);

        return view('notifikasi.index', compact('notifikasi'));
    }

    public function markAsRead(Notifikasi $notifikasi)
    {
        $notifikasi->markAsRead();
        
        if ($notifikasi->link) {
            return redirect($notifikasi->link);
        }

        return back();
    }

    public function markAllAsRead()
    {
        auth()->user()->notifikasi()
                     ->where('is_read', false)
                     ->update(['is_read' => true]);

        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca');
    }
}

// app/Http/Controllers/CatatanController.php
namespace App\Http\Controllers;

use App\Models\Catatan;
use Illuminate\Http\Request;

class CatatanController extends Controller
{
    public function index()
    {
        $catatan = auth()->user()->catatan()
                               ->orderBy('is_pinned', 'desc')
                               ->latest()
                               ->paginate(20);

        return view('catatan.index', compact('catatan'));
    }

    public function create()
    {
        return view('catatan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'kategori' => 'nullable|string',
        ]);

        $validated['user_id'] = auth()->id();

        Catatan::create($validated);

        return redirect()->route('catatan.index')
                        ->with('success', 'Catatan berhasil ditambahkan');
    }

    public function show(Catatan $catatan)
    {
        return view('catatan.show', compact('catatan'));
    }

    public function edit(Catatan $catatan)
    {
        return view('catatan.edit', compact('catatan'));
    }

    public function update(Request $request, Catatan $catatan)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'kategori' => 'nullable|string',
        ]);

        $catatan->update($validated);

        return redirect()->route('catatan.index')
                        ->with('success', 'Catatan berhasil diperbarui');
    }

    public function destroy(Catatan $catatan)
    {
        $catatan->delete();

        return back()->with('success', 'Catatan berhasil dihapus');
    }

    public function togglePin(Catatan $catatan)
    {
        $catatan->update(['is_pinned' => !$catatan->is_pinned]);

        return back()->with('success', 'Status pin berhasil diubah');
    }
}

// app/Http/Controllers/PengaturanController.php
namespace App\Http\Controllers;

use App\Models\Pengaturan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengaturanController extends Controller
{
    public function index()
    {
        $pengaturan = Pengaturan::all()->groupBy('tipe');

        return view('admin.pengaturan.index', compact('pengaturan'));
    }

    public function update(Request $request)
    {
        foreach ($request->except('_token', '_method') as $key => $value) {
            if ($request->hasFile($key)) {
                $setting = Pengaturan::where('key_name', $key)->first();
                
                if ($setting && $setting->value) {
                    Storage::disk('public')->delete($setting->value);
                }

                $value = $request->file($key)->store('settings', 'public');
            }

            Pengaturan::set($key, $value);
        }

        return back()->with('success', 'Pengaturan berhasil diperbarui');
    }
}