<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lowongan_kerja', function (Blueprint $table) {
            // Judul lowongan (untuk admin yang buat lowongan sendiri)
            $table->string('judul', 200)->nullable()->after('id');
            // Buat perusahaan_id nullable (admin bisa buat lowongan tanpa perusahaan)
            $table->foreignId('perusahaan_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('lowongan_kerja', function (Blueprint $table) {
            $table->dropColumn('judul');
            $table->foreignId('perusahaan_id')->nullable(false)->change();
        });
    }
};
