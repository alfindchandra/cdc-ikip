<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Perusahaan;
use App\Models\Pengaturan;

class DatabaseSeeder extends Seeder
{
    
    public function run(): void
    {
        // Create Admin User
        $admin = User::create([
            'name' => 'Administrator CDC',
            'email' => 'admin@smkn1baureno.ac.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create Sample Siswa
        $siswaUser1 = User::create([
            'name' => 'salsabilla zetia Ramadhani',
            'email' => 'salsabilla@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'siswa',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        Siswa::create([
            'user_id' => $siswaUser1->id,
            'nis' => '2024001',
            'nisn' => '0012345678',
            'tempat_lahir' => 'Bojonegoro',
            'tanggal_lahir' => '2006-05-15',
            'jenis_kelamin' => 'P',
            'agama' => 'Islam',
            'alamat' => 'Jl. Raya Baureno No. 123',
            'no_telp' => '081234567890',
            'kelas' => 'XII',
            'jurusan' => 'Rekayasa Perangkat Lunak',
            'tahun_masuk' => 2021,
            'nama_ortu' => 'Budi Santoso',
            'pekerjaan_ortu' => 'Wiraswasta',
            'no_telp_ortu' => '081234567891',
            'status' => 'aktif',
        ]);

        $siswaUser2 = User::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'siti.nurhaliza@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'siswa',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        Siswa::create([
            'user_id' => $siswaUser2->id,
            'nis' => '2024002',
            'nisn' => '0012345679',
            'tempat_lahir' => 'Bojonegoro',
            'tanggal_lahir' => '2006-08-20',
            'jenis_kelamin' => 'P',
            'agama' => 'Islam',
            'alamat' => 'Jl. Merdeka No. 45',
            'no_telp' => '081234567892',
            'kelas' => 'XII',
            'jurusan' => 'Teknik Komputer dan Jaringan',
            'tahun_masuk' => 2021,
            'nama_ortu' => 'Slamet Riyadi',
            'pekerjaan_ortu' => 'PNS',
            'no_telp_ortu' => '081234567893',
            'status' => 'aktif',
        ]);

        // Create Sample Perusahaan
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

        // Create Pengaturan
        $pengaturan = [
            ['key_name' => 'nama_sekolah', 'value' => 'SMK Negeri 1 Baureno', 'description' => 'Nama Sekolah', 'tipe' => 'text'],
            ['key_name' => 'alamat_sekolah', 'value' => 'Jl. Raya Baureno, Bojonegoro, Jawa Timur', 'description' => 'Alamat Sekolah', 'tipe' => 'text'],
            ['key_name' => 'email_sekolah', 'value' => 'smkn1baureno@gmail.com', 'description' => 'Email Sekolah', 'tipe' => 'text'],
            ['key_name' => 'telp_sekolah', 'value' => '0353-123456', 'description' => 'Nomor Telepon Sekolah', 'tipe' => 'text'],
            ['key_name' => 'tahun_ajaran', 'value' => '2024/2025', 'description' => 'Tahun Ajaran Aktif', 'tipe' => 'text'],
            ['key_name' => 'batas_pkl', 'value' => '6', 'description' => 'Batas Minimal Bulan PKL', 'tipe' => 'number'],
            ['key_name' => 'auto_notif', 'value' => 'true', 'description' => 'Aktifkan Notifikasi Otomatis', 'tipe' => 'boolean'],
            ['key_name' => 'max_upload_size', 'value' => '5120', 'description' => 'Maksimal Ukuran Upload (KB)', 'tipe' => 'number'],
        ];

        foreach ($pengaturan as $setting) {
            Pengaturan::create($setting);
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('');
        $this->command->info('Login credentials:');
        $this->command->info('Admin:');
        $this->command->info('  Email: admin@smkn1baureno.sch.id');
        $this->command->info('  Password: password');
        $this->command->info('');
        $this->command->info('Siswa 1:');
        $this->command->info('  Email: ahmad.rizki@gmail.com');
        $this->command->info('  Password: password');
        $this->command->info('');
        $this->command->info('Siswa 2:');
        $this->command->info('  Email: siti.nurhaliza@gmail.com');
        $this->command->info('  Password: password');
        $this->command->info('');
        $this->command->info('Perusahaan 1:');
        $this->command->info('  Email: hrd@digitaltek.co.id');
        $this->command->info('  Password: password');
        $this->command->info('');
        $this->command->info('Perusahaan 2:');
        $this->command->info('  Email: admin@majujaya.com');
        $this->command->info('  Password: password');
    }
}