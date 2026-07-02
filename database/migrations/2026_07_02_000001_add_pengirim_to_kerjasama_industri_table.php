<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kerjasama_industri', function (Blueprint $table) {
            // Pengirim: 'perusahaan' = perusahaan yang ajukan ke admin, 'admin' = admin yang kirim ke perusahaan
            $table->enum('pengirim', ['perusahaan', 'admin'])->default('perusahaan')->after('catatan');
            // Alasan penolakan dari perusahaan (ketika perusahaan menolak pengajuan dari admin)
            $table->text('alasan_penolakan_perusahaan')->nullable()->after('pengirim');
            // Timestamp ketika perusahaan menyetujui/menolak
            $table->timestamp('disetujui_perusahaan_at')->nullable()->after('alasan_penolakan_perusahaan');
        });
    }

    public function down(): void
    {
        Schema::table('kerjasama_industri', function (Blueprint $table) {
            $table->dropColumn(['pengirim', 'alasan_penolakan_perusahaan', 'disetujui_perusahaan_at']);
        });
    }
};
