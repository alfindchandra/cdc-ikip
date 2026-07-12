<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan kolom-kolom data PT sesuai form Registrasi PT:
     * Visi, Misi, Jenis PT, Nama Pimpinan, No. HP, Tahun Berdiri,
     * Jumlah Karyawan, dan CV Perusahaan.
     */
    public function up(): void
    {
        Schema::table('perusahaan', function (Blueprint $table) {
            $table->text('visi')->nullable()->after('deskripsi');
            $table->text('misi')->nullable()->after('visi');
            $table->string('jenis_pt', 100)->nullable()->after('misi');
            $table->string('nama_pimpinan')->nullable()->after('jenis_pt');
            $table->string('no_hp', 15)->nullable()->after('nama_pimpinan');
            $table->unsignedSmallInteger('tahun_berdiri')->nullable()->after('no_hp');
            $table->unsignedInteger('jumlah_karyawan')->nullable()->after('tahun_berdiri');
            $table->string('cv_perusahaan')->nullable()->after('jumlah_karyawan');
        });
    }

    public function down(): void
    {
        Schema::table('perusahaan', function (Blueprint $table) {
            $table->dropColumn([
                'visi', 'misi', 'jenis_pt', 'nama_pimpinan', 'no_hp',
                'tahun_berdiri', 'jumlah_karyawan', 'cv_perusahaan',
            ]);
        });
    }
};
