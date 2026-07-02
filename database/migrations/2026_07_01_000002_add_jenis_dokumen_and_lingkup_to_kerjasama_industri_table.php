<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     *
     * Perubahan alur kerja sama menjadi lebih sederhana:
     * - Perusahaan kini dapat MEMILIH jenis dokumen yang ingin diunggah saat
     *   mengajukan kerja sama: MoU, MoA, atau Surat Kerjasama (kolom jenis_dokumen).
     * - Admin cukup meng-ACC (menyetujui) pengajuan tersebut secara langsung,
     *   tanpa perlu mengunggah dokumen tambahan secara bertahap.
     * - Ditambahkan kolom lingkup_kerjasama untuk mengelompokkan kerja sama:
     *   Dalam Negeri, Luar Negeri, Swasta, atau Lainnya.
     */
    public function up(): void
    {
        Schema::table('kerjasama_industri', function (Blueprint $table) {
            $table->enum('jenis_dokumen', ['mou', 'moa', 'surat_kerjasama'])
                  ->default('mou')
                  ->after('jenis_kerjasama');

            $table->enum('lingkup_kerjasama', ['dalam_negeri', 'luar_negeri', 'swasta', 'lainnya'])
                  ->nullable()
                  ->after('jenis_dokumen');

            $table->string('dokumen_surat_kerjasama')->nullable()->after('dokumen_kontrak');

            // Waktu admin melakukan ACC langsung (menggantikan alur bertahap lama)
            $table->timestamp('disetujui_at')->nullable()->after('disetujui_perusahaan_at');
        });
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        Schema::table('kerjasama_industri', function (Blueprint $table) {
            $table->dropColumn([
                'jenis_dokumen',
                'lingkup_kerjasama',
                'dokumen_surat_kerjasama',
                'disetujui_at',
            ]);
        });
    }
};
