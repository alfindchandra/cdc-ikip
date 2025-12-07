<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB; // Tambahkan import DB
use App\Models\User;
use App\Models\Siswa;
use App\Models\Perusahaan;
use App\Models\Pengaturan;
// Impor seeder yang baru dibuat
use Database\Seeders\FakultasSeeder;
use Database\Seeders\ProgramStudiSeeder;


class DatabaseSeeder extends Seeder
{
    
    public function run(): void
    {
        // 1. Panggil Seeder Fakultas dan Program Studi terlebih dahulu
        $this->call([
            FakultasSeeder::class,
            ProgramStudiSeeder::class,
            // Anda bisa memanggil seeder lain di sini
        ]);

        // 2. AMBIL ID FAKULTAS DAN PROGRAM STUDI
        $fpbsId = DB::table('fakultas')->where('nama', 'Fakultas Pendidikan Bahasa dan Seni')->value('id');
        $fpmipaId = DB::table('fakultas')->where('nama', 'Fakultas Pendidikan Matematika dan Ilmu Pengetahuan Alam')->value('id');
        
        $pbsiId = DB::table('program_studis')->where('nama', 'Pendidikan Bahasa dan Sastra Indonesia')->value('id');
        $ptiId = DB::table('program_studis')->where('nama', 'Pendidikan Teknologi Informasi')->value('id');
        $pbiId = DB::table('program_studis')->where('nama', 'Pendidikan Bahasa Inggris')->value('id');

        // ==========================================================
        // CREATE USER ADMIN
        // ==========================================================
        $admin = User::create([
            'name' => 'Administrator CDC',
            'email' => 'admin@ikippgribojonegoro.ac.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        
        // ==========================================================
        // CREATE SAMPLE SISWA (Untuk Testing)
        // ==========================================================

        // Siswa 1: Siti Nurhaliza
        $siswaUser1 = User::create([
           'name' => 'Siti Nurhaliza',
           'email' => 'siti.nurhaliza@gmail.com',
           'password' => Hash::make('password'),
           'role' => 'siswa',
           'is_active' => true,
           'email_verified_at' => now(),
        ]);
        Siswa::create([
            'user_id' => $siswaUser1->id,
            'nim' => '2024001',
            'tempat_lahir' => 'Bojonegoro',
            'tanggal_lahir' => '2006-05-15',
            'jenis_kelamin' => 'P',
            'agama' => 'Islam',
            'alamat' => 'Jl. Raya Baureno No. 123',
            'no_telp' => '081234567890',
            
            // MENGGUNAKAN ID FAKULTAS DAN PROGRAM STUDI
            'fakultas_id' => $fpbsId, 
            'program_studi_id' => $pbsiId, 
            
            'tahun_masuk' => 2021,
            'nama_ortu' => 'Budi Santoso',
            'pekerjaan_ortu' => 'Wiraswasta',
            'no_telp_ortu' => '081234567891',
            'status' => 'aktif',
        ]);

        // Siswa 2: Salsabilla Zetia Ramadhani
        $siswaUser2 = User::create([
            'name' => 'Salsabilla Zetia Ramadhani',
            'email' => 'salsabilla@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'siswa',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        Siswa::create([
            'user_id' => $siswaUser2->id,
            'nim' => '2024002',
            'tempat_lahir' => 'Bojonegoro',
            'tanggal_lahir' => '2006-08-20',
            'jenis_kelamin' => 'P',
            'agama' => 'Islam',
            'alamat' => 'Jl. Merdeka No. 45',
            'no_telp' => '081234567892',
            
            // MENGGUNAKAN ID FAKULTAS DAN PROGRAM STUDI
            'fakultas_id' => $fpmipaId,
            'program_studi_id' => $ptiId,
            
            'tahun_masuk' => 2021,
            'nama_ortu' => 'Slamet Riyadi',
            'pekerjaan_ortu' => 'PNS',
            'no_telp_ortu' => '081234567893',
            'status' => 'aktif',
        ]);
        
        // Siswa 3: Ahmad Rizki
        $siswaUser3 = User::create([
            'name' => 'Ahmad Rizki',
            'email' => 'ahmad.rizki@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'siswa',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        Siswa::create([
            'user_id' => $siswaUser3->id,
            'nim' => '2024003',
            'tempat_lahir' => 'Bojonegoro',
            'tanggal_lahir' => '2005-01-10',
            'jenis_kelamin' => 'L',
            'agama' => 'Islam',
            'alamat' => 'Jl. Pahlawan No. 5',
            'no_telp' => '081300001111',
            
            // MENGGUNAKAN ID FAKULTAS DAN PROGRAM STUDI
            'fakultas_id' => $fpbsId,
            'program_studi_id' => $pbiId,
            
            'tahun_masuk' => 2020,
            'nama_ortu' => 'Agus Salim',
            'pekerjaan_ortu' => 'Petani',
            'no_telp_ortu' => '081300002222',
            'status' => 'aktif',
        ]);


        // ==========================================================
        // CREATE SAMPLE PERUSAHAAN
        // ==========================================================
        $perusahaanUser1 = User::create([
            'name' => 'PT Digital Teknologi Indonesia',
            'email' => 'hrd@digitaltek.co.id',
            'password' => Hash::make('password'),
            'role' => 'perusahaan',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        Perusahaan::create([
            'user_id' => $perusahaanUser1->id,
            'nama_perusahaan' => 'PT Digital Teknologi Indonesia',
            'bidang_usaha' => 'Teknologi Informasi',
            'alamat' => 'Jl. Sudirman No. 100',
            'kota' => 'Surabaya',
            'provinsi' => 'Jawa Timur',
            'kode_pos' => '60271',
            'no_telp' => '031-1234567',
            'email' => 'info@digitaltek.co.id',
            'website' => 'https://www.digitaltek.co.id',
            'nama_pic' => 'Rina Kusuma',
            'jabatan_pic' => 'HRD Manager',
            'no_telp_pic' => '081234567894',
            'email_pic' => 'rina@digitaltek.co.id',
            'status_kerjasama' => 'aktif',
            'tanggal_kerjasama' => now(),
            'deskripsi' => 'Perusahaan yang bergerak di bidang pengembangan software dan aplikasi mobile.',
        ]);

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
            'nama_perusahaan' => 'CV Maju Jaya Komputer',
            'bidang_usaha' => 'Penjualan dan Service Komputer',
            'alamat' => 'Jl. Ahmad Yani No. 50',
            'kota' => 'Bojonegoro',
            'provinsi' => 'Jawa Timur',
            'kode_pos' => '62111',
            'no_telp' => '0353-123456',
            'email' => 'info@majujaya.com',
            'website' => 'https://www.majujaya.com',
            'nama_pic' => 'Bambang Sutrisno',
            'jabatan_pic' => 'Owner',
            'no_telp_pic' => '081234567895',
            'email_pic' => 'bambang@majujaya.com',
            'status_kerjasama' => 'aktif',
            'tanggal_kerjasama' => now(),
            'deskripsi' => 'Toko komputer dan laptop yang melayani penjualan dan service.',
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
            // Menggunakan updateOrCreate untuk mencegah duplikasi jika seeder dijalankan berkali-kali
            Pengaturan::updateOrCreate(['key_name' => $setting['key_name']], $setting);
        }

       
    }
}