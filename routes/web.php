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
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EPortfolioController;
use App\Http\Controllers\ApiController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;


// ========== API Routes (Proxy PDDikti) ==========
Route::prefix('api')->name('api.')->group(function () {
    Route::get('/prodi/search', [ApiController::class, 'searchProdi'])->name('prodi.search');
    Route::get('/universitas/search', [ApiController::class, 'searchUniversitas'])->name('universitas.search');
    Route::get('/fakultas/list', [ApiController::class, 'listFakultas'])->name('fakultas.list');
});

// Guest routes
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Route::get('/lowongan', [HomeController::class, 'lowongan'])->name('index.lowongan');
Route::get('/lowongan/{lowongan}', [HomeController::class, 'lowonganShow'])->name('lowongan.show');
Route::get('/perusahaan', [HomeController::class, 'perusahaan'])->name('index.perusahaan');
Route::get('/perusahaan/{perusahaan}', [HomeController::class, 'showPerusahaan'])
    ->name('show.perusahaan')
    ->whereNumber('perusahaan');
Route::get('/pelatihan', [HomeController::class, 'pelatihan'])->name('pelatihan.index');
Route::get('/pelatihan/{pelatihan}', [HomeController::class, 'showPelatihan'])->name('pelatihan.show');
Route::get('/tracer-study', [HomeController::class, 'tracerStudy'])->name('index.tracer-study');
Route::get('/eportfolio', [HomeController::class, 'ePortfolio'])->name('eportfolio.index');
Route::get('/faq', [HomeController::class, 'faxFaq'])->name('index.faq');


Route::get('/kerjasama', [WelcomeController::class, 'kerjasama'])->name('index.kerjasama');
Route::get('/kerjasama/{kerjasama}', [WelcomeController::class, 'kerjasamaShow'])->name('show.kerjasama');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/email/verify', function () {return view('auth.verify-email');})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/dashboard')->with('success', 'Email berhasil diverifikasi!');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Link verifikasi telah dikirim!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::middleware('guest')->group(function () {
    // Login
   
    
    // Register - Pilihan Role
    Route::get('/register', [AuthController::class, 'showRegisterChoice'])->name('register');
    
    // Register Mahasiswa
    Route::get('/register/mahasiswa', [AuthController::class, 'showRegisterMahasiswa'])->name('register.mahasiswa');
    Route::post('/register/mahasiswa', [AuthController::class, 'registerMahasiswa']);
    
    // Register Perusahaan
    Route::get('/register/perusahaan', [AuthController::class, 'showRegisterPerusahaan'])->name('register.perusahaan');
    Route::post('/register/perusahaan', [AuthController::class, 'registerPerusahaan']);

    // OTP Verification
    Route::get('/otp/verify', [AuthController::class, 'showOtpVerify'])->name('otp.verify.show');
    Route::post('/otp/verify', [AuthController::class, 'verifyOtp'])->name('otp.verify');
    Route::post('/otp/resend', [AuthController::class, 'resendOtp'])->name('otp.resend');
});


// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');    
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
        Route::post('mahasiswa/import', [MahasiswaController::class, 'import'])->name('mahasiswa.import');
        Route::get('mahasiswa/template', [MahasiswaController::class, 'downloadTemplate'])->name('mahasiswa.template');
        Route::get('mahasiswa/export', [MahasiswaController::class, 'export'])->name('mahasiswa.export');
        Route::resource('mahasiswa', MahasiswaController::class);
        
        // Data Perusahaan
        Route::post('perusahaan/import', [PerusahaanController::class, 'import'])->name('perusahaan.import');
        Route::get('perusahaan/template', [PerusahaanController::class, 'downloadTemplate'])->name('perusahaan.template');
        Route::resource('perusahaan', PerusahaanController::class);
        Route::post('perusahaan/{perusahaan}/status', [PerusahaanController::class, 'updateStatus'])->name('perusahaan.status');
        
        
        // Lowongan Kerja (Admin can view all and create)
        Route::get('lowongan', [LowonganKerjaController::class, 'adminIndex'])->name('lowongan.index');
        Route::get('lowongan/create', [LowonganKerjaController::class, 'adminCreate'])->name('lowongan.create');
        Route::post('lowongan', [LowonganKerjaController::class, 'adminStore'])->name('lowongan.store');
        Route::get('lowongan/{lowongan}/edit', [LowonganKerjaController::class, 'adminEdit'])->name('lowongan.edit');
        Route::put('lowongan/{lowongan}', [LowonganKerjaController::class, 'adminUpdate'])->name('lowongan.update');
        Route::delete('lowongan/{lowongan}', [LowonganKerjaController::class, 'adminDestroy'])->name('lowongan.destroy');
        Route::post('lowongan/search', [LowonganKerjaController::class, 'adminSearch'])->name('lowongan.search');
        Route::post('lowongan/{lowongan}/publish', [LowonganKerjaController::class, 'toggleStatus'])->name('lowongan.publish');
        Route::get('lowongan/pelamar/{lowongan}', [LowonganKerjaController::class, 'adminPelamar'])->name('lowongan.pelamar');
        Route::get('lowongan/{lowongan}/peserta', [LowonganKerjaController::class, 'peserta'])->name('lowongan.peserta');
        Route::get('lowongan/show/{lowongan}', [LowonganKerjaController::class, 'adminShow'])->name('lowongan.show');
        Route::post('lowongan/{lowongan}/status', [LowonganKerjaController::class, 'updateStatus'])->name('lowongan.status');
        Route::get('/admin/lowongan/pelamar', [LowonganKerjaController::class, 'adminPelamar'])->name('admin.lowongan.pelamar');
        // Lamaran (Admin can view all)
        Route::get('lamaran', [LamaranController::class, 'adminIndex'])->name('lamaran.index');
        
        // Pelatihan
        Route::resource('pelatihan', PelatihanController::class);
        Route::post('pelatihan/{pelatihan}/publish', [PelatihanController::class, 'publish'])->name('pelatihan.publish');
        Route::get('pelatihan/{pelatihan}/peserta', [PelatihanController::class, 'peserta'])->name('pelatihan.peserta');
        Route::post('pelatihan/{pelatihan}/peserta/{mahasiswa}/status', [PelatihanController::class, 'updateStatusPeserta'])->name('pelatihan.peserta.status');
Route::post('pelatihan/{pelatihan}/peserta/{mahasiswa}/nilai', [PelatihanController::class, 'inputNilai'])->name('pelatihan.peserta.nilai');
        // Kerjasama Industri (Admin mengelola & kirim ke Perusahaan / ACC pengajuan dari Perusahaan)
        Route::post('kerjasama/import', [KerjasamaIndustriController::class, 'import'])->name('kerjasama.import');
        Route::get('kerjasama/template', [KerjasamaIndustriController::class, 'downloadTemplate'])->name('kerjasama.template');
        Route::resource('kerjasama', KerjasamaIndustriController::class);
        Route::put('kerjasama/{kerjasama}/status', [KerjasamaIndustriController::class, 'updateStatus'])->name('kerjasama.status');
        // Admin ACC / Tolak pengajuan dari perusahaan
        Route::put('kerjasama/{kerjasama}/mou/approve', [KerjasamaIndustriController::class, 'approveMou'])->name('kerjasama.mou.approve');
        Route::put('kerjasama/{kerjasama}/mou/reject', [KerjasamaIndustriController::class, 'rejectMou'])->name('kerjasama.mou.reject');
        
        // Laporan
        Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('laporan/download', [LaporanController::class, 'download'])->name('laporan.download');
        
          Route::resource('tracer-study', TracerStudyController::class);
    Route::get('tracer-study-laporan', [TracerStudyController::class, 'laporan'])->name('tracer-study.laporan');
    Route::get('tracer-study-export', [TracerStudyController::class, 'exportExcel'])->name('tracer-study.export');
    Route::get('tracer-study-pertanyaan', [TracerStudyController::class, 'editPertanyaan'])->name('tracer-study.pertanyaan');
    Route::put('tracer-study-pertanyaan', [TracerStudyController::class, 'updatePertanyaan'])->name('tracer-study.pertanyaan.update');
    Route::post('tracer-study-import', [TracerStudyController::class, 'importTracerStudy'])->name('tracer-study.import');
    Route::get('tracer-study-template', [TracerStudyController::class, 'downloadTemplate'])->name('tracer-study.template');
        // Pengaturan
        Route::get('pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
        Route::put('pengaturan', [PengaturanController::class, 'update'])->name('pengaturan.update');
    });

    
    // Mahasiswa routes
    Route::middleware('role:mahasiswa')->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
      
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

        // E-Portfolio
        Route::get('eportfolio', [EPortfolioController::class, 'index'])->name('eportfolio.index');
        Route::post('eportfolio', [EPortfolioController::class, 'store'])->name('eportfolio.store');
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

        // E-Portfolio perusahaan
        Route::get('mahasiswa/{mahasiswa}/eportfolio', [EPortfolioController::class, 'showForCompany'])->name('mahasiswa.eportfolio.show');
        
 
        // Kerjasama (Perusahaan memilih jenis dokumen & mengajukan kerja sama, admin langsung meng-ACC)
        Route::get('kerjasama', [KerjasamaIndustriController::class, 'perusahaanIndex'])->name('kerjasama.index');
        Route::get('kerjasama/create', [KerjasamaIndustriController::class, 'createPerusahaan'])->name('kerjasama.create');
        Route::post('kerjasama', [KerjasamaIndustriController::class, 'storePerusahaan'])->name('kerjasama.store');
        Route::get('kerjasama/{kerjasama}', [KerjasamaIndustriController::class, 'show'])->name('kerjasama.show');
        Route::post('kerjasama/{kerjasama}/status', [KerjasamaIndustriController::class, 'updateStatusPerusahaan'])->name('kerjasama.status');
        // Perusahaan ACC / Tolak kerjasama yang dikirim Admin
        Route::put('kerjasama/{kerjasama}/approve', [KerjasamaIndustriController::class, 'approvePerusahaan'])->name('kerjasama.approve');
        Route::put('kerjasama/{kerjasama}/reject', [KerjasamaIndustriController::class, 'rejectPerusahaan'])->name('kerjasama.reject');
    
    });
});