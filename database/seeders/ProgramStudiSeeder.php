<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Fakultas; // Asumsi Anda menggunakan model

class ProgramStudiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Ambil ID Fakultas yang sudah di-seed
        $fpbsId = DB::table('fakultas')->where('kode', 'FPBS')->value('id');
        $fpipsId = DB::table('fakultas')->where('kode', 'FPIPS')->value('id');
        $fpmipaId = DB::table('fakultas')->where('kode', 'FPMIPA')->value('id');

        // 2. Data Program Studi (dengan relasi ke Fakultas)
        $programStudis = [
            [
                'nama' => 'Pendidikan Bahasa dan Sastra Indonesia',
                'fakultas_id' => $fpbsId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Pendidikan Bahasa Inggris',
                'fakultas_id' => $fpbsId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Pendidikan Ekonomi',
                'fakultas_id' => $fpipsId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Pendidikan Pancasila dan Kewarganegaraan',
                'fakultas_id' => $fpipsId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Pendidikan Matematika',
                'fakultas_id' => $fpmipaId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                // Perbaikan duplikasi data 'program_studi3'
                'nama' => 'Pendidikan Teknologi Informasi',
                'fakultas_id' => $fpmipaId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Memasukkan data ke tabel 'program_studis'
        DB::table('program_studis')->insert($programStudis);

        $this->command->info('Program Studi berhasil di-seed!');
    }
}