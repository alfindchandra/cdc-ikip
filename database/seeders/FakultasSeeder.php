<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FakultasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fakultas = [
            [
                'nama' => 'Fakultas Pendidikan Bahasa dan Seni',
                'kode' => 'FPBS', // Kode singkat fakultas
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Fakultas Pendidikan Ilmu Pengetahuan Sosial',
                'kode' => 'FPIPS',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Fakultas Pendidikan Matematika dan Ilmu Pengetahuan Alam',
                'kode' => 'FPMIPA',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Memasukkan data ke tabel 'fakultas'
        DB::table('fakultas')->insert($fakultas);

        $this->command->info('Fakultas berhasil di-seed!');
    }
}