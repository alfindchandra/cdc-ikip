<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB; 
use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Perusahaan;
use App\Models\Pengaturan;
use Database\Seeders\FakultasSeeder;
use Database\Seeders\ProgramStudiSeeder;
use Database\Seeders\TracerStudyQuestionSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. CALL SEEDERS UTAMA
        $this->call([
            FakultasSeeder::class,
            ProgramStudiSeeder::class,
            TracerStudyQuestionSeeder::class,
        ]);

        // 2. FAKULTAS DAN PROGRAM STUDI
        $fpbsId = DB::table('fakultas')->where('nama', 'Fakultas Pendidikan Bahasa dan Seni')->value('id');
        $fpmipaId = DB::table('fakultas')->where('nama', 'Fakultas Pendidikan Matematika dan Ilmu Pengetahuan Alam')->value('id');
        
        $pbsiId = DB::table('program_studis')->where('nama', 'Pendidikan Bahasa dan Sastra Indonesia')->value('id');
        $ptiId = DB::table('program_studis')->where('nama', 'Pendidikan Teknologi Informasi')->value('id');
        $pbiId = DB::table('program_studis')->where('nama', 'Pendidikan Bahasa Inggris')->value('id');

        // ==========================================================
        // CREATE USER ADMIN
        // ==========================================================
        User::create([
            'name' => 'Administrator CDC',
            'email' => 'admin@ikippgribojonegoro.ac.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        
        User::create([
            'name' => 'Administrator CDC 2',
            'email' => 'admin2@ikippgribojonegoro.ac.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // ==========================================================
        // CREATE DATA MAHASISWA
        // ==========================================================
        // Mahasiswa 1
        $mahasiswaUser1 = User::create([
           'name' => 'Siti Nurhaliza',
           'email' => 'siti.nurhaliza@gmail.com',
           'password' => Hash::make('password'),
           'role' => 'mahasiswa',
           'is_active' => true,
           'email_verified_at' => now(),
        ]);
        Mahasiswa::create([
            'user_id' => $mahasiswaUser1->id,
            'nim' => '2024001',
            'tempat_lahir' => 'Bojonegoro',
            'tanggal_lahir' => '2006-05-15',
            'jenis_kelamin' => 'P',
            'agama' => 'Islam',
            'alamat' => 'Jl. Raya Baureno No. 123',
            'no_telp' => '081234567890',
            'fakultas_id' => $fpbsId, 
            'program_studi_id' => $pbsiId, 
            'tahun_masuk' => 2021,
            'nama_ortu' => 'Budi Santoso',
            'pekerjaan_ortu' => 'Wiraswasta',
            'no_telp_ortu' => '081234567891',
            'status' => 'aktif',
        ]);

        // Mahasiswa 2
        $mahasiswaUser2 = User::create([
            'name' => 'Salsabilla Zetia Ramadhani',
            'email' => 'salsabilla@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        Mahasiswa::create([
            'user_id' => $mahasiswaUser2->id,
            'nim' => '2024002',
            'tempat_lahir' => 'Bojonegoro',
            'tanggal_lahir' => '2006-08-20',
            'jenis_kelamin' => 'P',
            'agama' => 'Islam',
            'alamat' => 'Jl. Merdeka No. 45',
            'no_telp' => '081234567892',
            'fakultas_id' => $fpmipaId,
            'program_studi_id' => $ptiId,
            'tahun_masuk' => 2021,
            'nama_ortu' => 'Slamet Riyadi',
            'pekerjaan_ortu' => 'PNS',
            'no_telp_ortu' => '081234567893',
            'status' => 'aktif',
        ]);
        
        // Mahasiswa 3
        $mahasiswaUser3 = User::create([
            'name' => 'Ahmad Rizki',
            'email' => 'ahmad.rizki@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        Mahasiswa::create([
            'user_id' => $mahasiswaUser3->id,
            'nim' => '2024003',
            'tempat_lahir' => 'Bojonegoro',
            'tanggal_lahir' => '2005-01-10',
            'jenis_kelamin' => 'L',
            'agama' => 'Islam',
            'alamat' => 'Jl. Pahlawan No. 5',
            'no_telp' => '081300001111',
            'fakultas_id' => $fpbsId,
            'program_studi_id' => $pbiId,
            'tahun_masuk' => 2020,
            'nama_ortu' => 'Agus Salim',
            'pekerjaan_ortu' => 'Petani',
            'no_telp_ortu' => '081300002222',
            'status' => 'aktif',
        ]);


        // ==========================================================
        // CREATE SAMPLE PERUSAHAAN (DISESUAIKAN DENGAN $FILLABLE BARU)
        // ==========================================================
        
        // Perusahaan 1
        $perusahaanUser1 = User::create([
            'name' => 'PT Digital Teknologi Indonesia', // Nama perusahaan disimpan di tabel Users
            'email' => 'hrd@digitaltek.co.id',
            'password' => Hash::make('password'),
            'role' => 'perusahaan',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        Perusahaan::create([
            'user_id' => $perusahaanUser1->id,
            'bidang_usaha' => 'Teknologi Informasi',
            'jenis_pt' => 'Swasta',
            'alamat' => 'Jl. Sudirman No. 100',
            'kota' => 'Surabaya',
            'provinsi' => 'Jawa Timur',
            'kode_pos' => '60271',
            'no_telp' => '031-1234567',
            'no_hp' => '081234567894',
            'website' => 'https://www.digitaltek.co.id',
            'nama_pimpinan' => 'Rina Kusuma',
            'tahun_berdiri' => '2015',
            'jumlah_karyawan' => '50',
            'visi' => 'Menjadi perusahaan teknologi terdepan.',
            'misi' => 'Mengembangkan perangkat lunak inovatif berkualitas tinggi.',
            'cv_perusahaan' => null,
            'deskripsi' => 'Perusahaan yang bergerak di bidang pengembangan software dan aplikasi mobile.',
            'status_kerjasama' => 'aktif',
        ]);

        // Perusahaan 2
        $perusahaanUser2 = User::create([
            'name' => 'CV Maju Jaya Komputer', 
            'email' => 'admin@majujaya.com',
            'password' => Hash::make('password'),
            'role' => 'perusahaan',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        Perusahaan::create([
            'user_id' => $perusahaanUser2->id,
            'bidang_usaha' => 'Penjualan dan Service Komputer',
            'jenis_pt' => 'CV',
            'alamat' => 'Jl. Ahmad Yani No. 50',
            'kota' => 'Bojonegoro',
            'provinsi' => 'Jawa Timur',
            'kode_pos' => '62111',
            'no_telp' => '0353-123456',
            'no_hp' => '081234567895',
            'website' => 'https://www.majujaya.com',
            'nama_pimpinan' => 'Bambang Sutrisno',
            'tahun_berdiri' => '2018',
            'jumlah_karyawan' => '15',
            'visi' => 'Menyediakan solusi hardware terbaik bagi masyarakat.',
            'misi' => 'Memberikan pelayanan servis cepat dan terpercaya.',
            'cv_perusahaan' => null,
            'deskripsi' => 'Toko komputer dan laptop yang melayani penjualan dan service.',
            'status_kerjasama' => 'aktif',
        ]);

        // ==========================================================
        // CREATE PENGATURAN SISTEM
        // ==========================================================
        $pengaturan = [
            ['key_name' => 'nama_perguruan_tinggi', 'value' => 'IKIP PGRI Bojonegoro', 'description' => 'Nama Perguruan Tinggi', 'tipe' => 'text'],
            ['key_name' => 'alamat_kampus', 'value' => 'Jl. Panglima Polim No. 46 Bojonegoro', 'description' => 'Alamat Kampus', 'tipe' => 'text'],
            ['key_name' => 'email_kampus', 'value' => 'admin@ikippgribojonegoro.ac.id', 'description' => 'Email Kampus', 'tipe' => 'text'],
            ['key_name' => 'telp_kampus', 'value' => '(0353) 881046', 'description' => 'Nomor Telepon Kampus', 'tipe' => 'text'],
            ['key_name' => 'tahun_ajaran', 'value' => '2024/2025', 'description' => 'Tahun Ajaran Aktif', 'tipe' => 'text'],
            ['key_name' => 'batas_pkl', 'value' => '6', 'description' => 'Batas Minimal Bulan PKL', 'tipe' => 'number'],
            ['key_name' => 'auto_notif', 'value' => 'true', 'description' => 'Aktifkan Notifikasi Otomatis', 'tipe' => 'boolean'],
            ['key_name' => 'max_upload_size', 'value' => '5120', 'description' => 'Maksimal Ukuran Upload (KB)', 'tipe' => 'number'],
        ];

        foreach ($pengaturan as $setting) {
            Pengaturan::updateOrCreate(['key_name' => $setting['key_name']], $setting);
        }
    }
}