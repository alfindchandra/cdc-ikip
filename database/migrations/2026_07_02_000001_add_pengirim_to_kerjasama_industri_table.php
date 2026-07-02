<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kerjasama_industri', function (Blueprint $table) {
            // Hanya buat kolom jika kolom tersebut BELUM ADA di database
            if (!Schema::hasColumn('kerjasama_industri', 'pengirim')) {
                $table->enum('pengirim', ['perusahaan', 'admin'])->default('perusahaan')->after('catatan');
            }
            if (!Schema::hasColumn('kerjasama_industri', 'alasan_penolakan_perusahaan')) {
                $table->text('alasan_penolakan_perusahaan')->nullable()->after('pengirim');
            }
            if (!Schema::hasColumn('kerjasama_industri', 'disetujui_perusahaan_at')) {
                $table->timestamp('disetujui_perusahaan_at')->nullable()->after('alasan_penolakan_perusahaan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('kerjasama_industri', function (Blueprint $table) {
            // Hanya hapus kolom jika kolom tersebut ADA di database
            if (Schema::hasColumn('kerjasama_industri', 'pengirim')) {
                $table->dropColumn('pengirim');
            }
            if (Schema::hasColumn('kerjasama_industri', 'alasan_penolakan_perusahaan')) {
                $table->dropColumn('alasan_penolakan_perusahaan');
            }
            if (Schema::hasColumn('kerjasama_industri', 'disetujui_perusahaan_at')) {
                $table->dropColumn('disetujui_perusahaan_at');
            }
        });
    }
};