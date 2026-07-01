<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     *
     * Memperbarui alur kerja sama menjadi bertahap:
     * 1. proposal                        -> Perusahaan mengirim MoU, menunggu review admin (kampus)
     * 2. mou_disetujui                   -> Admin sudah meng-ACC MoU
     * 3. menunggu_persetujuan_perusahaan -> Admin sudah membuat & mengunggah MoA + dokumen kontrak,
     *                                        menunggu persetujuan (ACC) dari perusahaan
     * 4. aktif                           -> Perusahaan sudah menyetujui MoA & kontrak, kerja sama berjalan
     *
     * Status lain (draft, negosiasi, selesai, batal, nonaktif) tetap dipertahankan
     * untuk fleksibilitas pengelolaan oleh admin.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE kerjasama_industri MODIFY status ENUM(
            'draft',
            'proposal',
            'negosiasi',
            'mou_disetujui',
            'menunggu_persetujuan_perusahaan',
            'aktif',
            'selesai',
            'batal',
            'nonaktif'
        ) DEFAULT 'proposal'");

        Schema::table('kerjasama_industri', function ($table) {
            // Alasan penolakan MoU oleh admin ATAU alasan penolakan MoA/Kontrak oleh perusahaan
            $table->text('alasan_penolakan')->nullable()->after('catatan');
            // Jejak waktu tiap tahap persetujuan, untuk transparansi alur
            $table->timestamp('mou_disetujui_at')->nullable()->after('alasan_penolakan');
            $table->timestamp('moa_kontrak_diunggah_at')->nullable()->after('mou_disetujui_at');
            $table->timestamp('disetujui_perusahaan_at')->nullable()->after('moa_kontrak_diunggah_at');
        });
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        Schema::table('kerjasama_industri', function ($table) {
            $table->dropColumn([
                'alasan_penolakan',
                'mou_disetujui_at',
                'moa_kontrak_diunggah_at',
                'disetujui_perusahaan_at',
            ]);
        });

        DB::statement("ALTER TABLE kerjasama_industri MODIFY status ENUM(
            'aktif','nonaktif','selesai','draft','negosiasi','proposal','batal'
        ) DEFAULT 'aktif'");
    }
};