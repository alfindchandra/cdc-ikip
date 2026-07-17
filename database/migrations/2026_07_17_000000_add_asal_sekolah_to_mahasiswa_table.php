<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('mahasiswa', function (Blueprint $table) {
            // Menyimpan nama sekolah (untuk SD/SMP/SMA/SMK) atau nama
            // kampus/universitas asal (untuk D1/D2/D3/S1/S2/S3).
            $table->string('asal_sekolah', 200)->nullable()->after('tingkat_pendidikan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->dropColumn('asal_sekolah');
        });
    }
};
