<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\PerusahaanController;
use App\Http\Controllers\PklController;
use App\Http\Controllers\LowonganKerjaController;
use App\Http\Controllers\LamaranController;
use App\Http\Controllers\PelatihanController;
use App\Http\Controllers\KerjasamaIndustriController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\CatatanController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\TracerStudyController;

// Guest routes
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Route::get('/lowongan', [WelcomeController::class, 'lowongan'])->name('index.lowongan');
Route::get('/lowongan/{lowongan}', [WelcomeController::class, 'lowonganShow'])->name('lowongan.show');
Route::get('/pelatihan', [WelcomeController::class, 'pelatihan'])->name('index.pelatihan');
Route::get('/pelatihan/{pelatihan}', [WelcomeController::class, 'pelatihanShow'])->name('show.pelatihan');
Route::get('/kerjasama', [WelcomeController::class, 'kerjasama'])->name('index.kerjasama');
Route::get('/kerjasama/{kerjasama}', [WelcomeController::class, 'kerjasamaShow'])->name('show.kerjasama');
Route::get('/tracer-study', [WelcomeController::class, 'tracerStudy'])->name('index.tracer-study');

Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    // Register - Pilihan Role
    Route::get('/register', [AuthController::class, 'showRegisterChoice'])->name('register');
    
    // Register Mahasiswa
    Route::get('/register/mahasiswa', [AuthController::class, 'showRegisterMahasiswa'])->name('register.mahasiswa');
    Route::post('/register/mahasiswa', [AuthController::class, 'registerMahasiswa']);
    
    // Register Perusahaan
    Route::get('/register/perusahaan', [AuthController::class, 'showRegisterPerusahaan'])->name('register.perusahaan');
    Route::post('/register/perusahaan', [AuthController::class, 'registerPerusahaan']);
});


// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile
    Route::get('/profile', [PengaturanController::class, 'profile'])->name('profile');
    Route::get('/profile/edit', [PengaturanController::class, 'profileEdit'])->name('profile.edit');
    Route::put('/profile', [PengaturanController::class, 'updateProfile'])->name('profile.update');
    
    
    // Notifikasi
    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::post('/notifikasi/{notifikasi}/read', [NotifikasiController::class, 'markAsRead'])->name('notifikasi.read');
    Route::post('/notifikasi/read-all', [NotifikasiController::class, 'markAllAsRead'])->name('notifikasi.read-all');
    
    // Catatan
    Route::resource('catatan', CatatanController::class);
    Route::post('/catatan/{catatan}/pin', [CatatanController::class, 'togglePin'])->name('catatan.pin');
    
    // Admin routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {


        // Data Mahasiswa
        Route::resource('mahasiswa', MahasiswaController::class);
        Route::post('mahasiswa/import', [MahasiswaController::class, 'import'])->name('mahasiswa.import');
        Route::get('mahasiswa/export', [MahasiswaController::class, 'export'])->name('mahasiswa.export');
        
        // Data Perusahaan
        Route::resource('perusahaan', PerusahaanController::class);
        Route::post('perusahaan/{perusahaan}/status', [PerusahaanController::class, 'updateStatus'])->name('perusahaan.status');
        
        // PKL Management
        // Route::resource('pkl', PklController::class);
        // Route::post('pkl/{pkl}/status', [PklController::class, 'updateStatus'])->name('pkl.status');
        // Route::post('pkl/{pkl}/nilai', [PklController::class, 'inputNilai'])->name('pkl.nilai');
        // Route::get('pkl/{pkl}/jurnal', [PklController::class, 'showJurnal'])->name('pkl.jurnal');
        // Route::post('pkl/jurnal/{jurnal}/validasi', [PklController::class, 'validasiJurnal'])->name('pkl.jurnal.validasi');
        
        // Lowongan Kerja (Admin can view all)\
        Route::get('lowongan', [LowonganKerjaController::class, 'adminIndex'])->name('lowongan.index');
        Route::post('lowongan/search', [LowonganKerjaController::class, 'adminSearch'])->name('lowongan.search');
        Route::post('lowongan/{lowongan}/publish', [LowonganKerjaController::class, 'toggleStatus'])->name('lowongan.publish');
        Route::get('lowongan/pelamar/{lowongan}', [LowonganKerjaController::class, 'adminPelamar'])->name('lowongan.pelamar');
        Route::get('lowongan/{lowongan}/peserta', [LowonganKerjaController::class, 'peserta'])->name('lowongan.peserta');
        Route::post('lowongan/destroy', [LowonganKerjaController::class, 'destroy'])->name('lowongan.destroy');
        Route::get('lowongan/show/{lowongan}', [LowonganKerjaController::class, 'adminShow'])->name('lowongan.show');
        Route::post('lowongan/{lowongan}/status', [LowonganKerjaController::class, 'updateStatus'])->name('lowongan.status');

        // Lamaran (Admin can view all)
        Route::get('lamaran', [LamaranController::class, 'adminIndex'])->name('lamaran.index');
        
        // Pelatihan
        Route::resource('pelatihan', PelatihanController::class);
        Route::post('pelatihan/{pelatihan}/publish', [PelatihanController::class, 'publish'])->name('pelatihan.publish');
        Route::get('pelatihan/{pelatihan}/peserta', [PelatihanController::class, 'peserta'])->name('pelatihan.peserta');
        Route::post('pelatihan/{pelatihan}/peserta/{mahasiswa}/status', [PelatihanController::class, 'updateStatusPeserta'])->name('pelatihan.peserta.status');
Route::post('pelatihan/{pelatihan}/peserta/{mahasiswa}/nilai', [PelatihanController::class, 'inputNilai'])->name('pelatihan.peserta.nilai');
        // Kerjasama Industri
        Route::resource('kerjasama', KerjasamaIndustriController::class);
        Route::put('kerjasama/{kerjasama}/status', [KerjasamaIndustriController::class, 'updateStatus'])->name('kerjasama.status');
        
        // Laporan & Arsip
        Route::resource('laporan', LaporanController::class);
        Route::get('laporan/{laporan}/download', [LaporanController::class, 'download'])->name('laporan.download');
        Route::post('laporan/{laporan}/publish', [LaporanController::class, 'publish'])->name('laporan.publish');
        
          Route::resource('tracer-study', TracerStudyController::class);
    Route::get('tracer-study-laporan', [TracerStudyController::class, 'laporan'])->name('tracer-study.laporan');
    Route::get('tracer-study-export', [TracerStudyController::class, 'exportExcel'])->name('tracer-study.export');
        // Pengaturan
        Route::get('pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
        Route::put('pengaturan', [PengaturanController::class, 'update'])->name('pengaturan.update');
    });

    
    // Mahasiswa routes
    Route::middleware('role:mahasiswa')->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        // PKL
        // Route::get('pkl', [PklController::class, 'mahasiswaPkl'])->name('pkl.index');
        // Route::post('pkl/daftar', [PklController::class, 'daftar'])->name('pkl.daftar');
        // Route::get('pkl/{pkl}', [PklController::class, 'show'])->name('pkl.show');
        // Route::post('pkl/{pkl}/jurnal', [PklController::class, 'addJurnal'])->name('pkl.jurnal.add');
        // Route::put('pkl/jurnal/{jurnal}', [PklController::class, 'updateJurnal'])->name('pkl.jurnal.update');
        // Route::delete('pkl/jurnal/{jurnal}', [PklController::class, 'deleteJurnal'])->name('pkl.jurnal.delete');
        // Route::post('pkl/{pkl}/upload-laporan', [PklController::class, 'uploadLaporan'])->name('pkl.laporan');
        
        Route::prefix('tracer-study')->name('tracer-study.')->group(function () {
        Route::get('/', [TracerStudyController::class, 'alumniForm'])->name('form');
        Route::post('/', [TracerStudyController::class, 'alumniStore'])->name('store');
    });
        // Lowongan Kerja
        Route::get('lowongan', [LowonganKerjaController::class, 'index'])->name('lowongan.index');
        Route::get('lowongan/{lowongan}', [LowonganKerjaController::class, 'show'])->name('lowongan.show');
        Route::get('/lowongan/{lowongan}/apply', [LamaranController::class, 'create'])->name('lowongan.apply');
        // Lamaran
        Route::get('lamaran', [LamaranController::class, 'index'])->name('lamaran.index');
        Route::post('lamaran', [LamaranController::class, 'store'])->name('lamaran.store');
        Route::get('lamaran/{lamaran}', [LamaranController::class, 'show'])->name('lamaran.show');
        Route::delete('lamaran/{lamaran}', [LamaranController::class, 'destroy'])->name('lamaran.destroy');
        
        // Pelatihan
        Route::get('pelatihan', [PelatihanController::class, 'mahasiswaPelatihan'])->name('pelatihan.index');
        Route::get('pelatihan/{pelatihan}', [PelatihanController::class, 'show'])->name('pelatihan.show');
        Route::post('pelatihan/{pelatihan}/daftar', [PelatihanController::class, 'daftar'])->name('pelatihan.daftar');
        Route::delete('pelatihan/{pelatihan}/batal', [PelatihanController::class, 'batalDaftar'])->name('pelatihan.batal');
    });
    
    // Perusahaan routes
    Route::middleware('role:perusahaan')->prefix('perusahaan')->name('perusahaan.')->group(function () {
        // Profile Perusahaan
        Route::get('profile', [PerusahaanController::class, 'profileEdit'])->name('profile.edit');
        Route::put('profile', [PengaturanController::class, 'updateProfilePerusahaan'])->name('profile.update');
        
        // Lowongan Kerja
        Route::resource('lowongan', LowonganKerjaController::class)->except(['index']);
        Route::get('lowongan', [LowonganKerjaController::class, 'perusahaanIndex'])->name('lowongan.index');
        Route::get('lowongan/pelamar/{lowongan}', [LowonganKerjaController::class, 'pelamar'])->name('lowongan.pelamar');
        Route::post('lowongan/{lowongan}/toggle-status', [LowonganKerjaController::class, 'toggleStatus'])->name('lowongan.status');
        
        // Lamaran
        Route::get('lamaran', [LamaranController::class, 'perusahaanIndex'])->name('lamaran.index');
        Route::get('lamaran/{lamaran}', [LamaranController::class, 'show'])->name('lamaran.show');
        Route::post('lamaran/{lamaran}/status', [LamaranController::class, 'updateStatus'])->name('lamaran.status');
        
        // // PKL
        // Route::get('pkl', [PklController::class, 'perusahaanPkl'])->name('pkl.index');
        // Route::get('pkl/{pkl}', [PklController::class, 'show'])->name('pkl.show');
        // Route::post('pkl/{pkl}/terima', [PklController::class, 'terimaPkl'])->name('pkl.terima');
        // Route::post('pkl/{pkl}/tolak', [PklController::class, 'tolakPkl'])->name('pkl.tolak');
        // Route::get('pkl/{pkl}/jurnal', [PklController::class, 'showJurnal'])->name('pkl.jurnal');
        // Route::post('pkl/jurnal/{jurnal}/validasi', [PklController::class, 'validasiJurnal'])->name('pkl.jurnal.validasi');
        
        // Kerjasama
        Route::post('kerjasama/{kerjasama}/status', [KerjasamaIndustriController::class, 'updateStatusPerusahaan'])->name('kerjasama.status'); 
        Route::get('kerjasama', [KerjasamaIndustriController::class, 'perusahaanIndex'])->name('kerjasama.index');
        Route::get('kerjasama/{kerjasama}', [KerjasamaIndustriController::class, 'show'])->name('kerjasama.show');
    
    });
});