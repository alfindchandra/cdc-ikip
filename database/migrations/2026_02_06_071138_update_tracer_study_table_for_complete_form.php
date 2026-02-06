<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tracer_study', function (Blueprint $table) {
            // Tambahkan kolom baru yang mungkin belum ada
            if (!Schema::hasColumn('tracer_study', 'provinsi_kerja')) {
                $table->string('provinsi_kerja', 100)->nullable()->after('alamat_perusahaan');
            }
            if (!Schema::hasColumn('tracer_study', 'kota_kerja')) {
                $table->string('kota_kerja', 100)->nullable()->after('provinsi_kerja');
            }
            if (!Schema::hasColumn('tracer_study', 'tingkat_tempat_kerja')) {
                $table->string('tingkat_tempat_kerja', 100)->nullable()->after('kota_kerja');
            }
            if (!Schema::hasColumn('tracer_study', 'tingkat_pendidikan_pekerjaan')) {
                $table->string('tingkat_pendidikan_pekerjaan', 100)->nullable()->after('tingkat_tempat_kerja');
            }
            
            // Ubah tipe data kompetensi_yang_digunakan menjadi longText jika masih text
            $table->longText('kompetensi_yang_digunakan')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('tracer_study', function (Blueprint $table) {
            $table->dropColumn([
                'provinsi_kerja',
                'kota_kerja',
                'tingkat_tempat_kerja',
                'tingkat_pendidikan_pekerjaan'
            ]);
        });
    }
};