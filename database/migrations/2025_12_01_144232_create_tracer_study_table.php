<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tracer_study', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            
            // Status Utama
            $table->enum('status_pekerjaan', [
                'bekerja', 
                'wirausaha', 
                'melanjutkan_studi', 
                'belum_bekerja'
            ]);
            
            // Data Pekerjaan
            $table->string('nama_perusahaan')->nullable();
            $table->string('posisi')->nullable();
            $table->string('bidang_pekerjaan')->nullable();
            $table->text('alamat_perusahaan')->nullable();
            $table->enum('jenis_perusahaan', [
                'pemerintah', 
                'swasta', 
                'bumn', 
                'startup', 
                'lainnya'
            ])->nullable();
            $table->string('penghasilan')->nullable(); // Range gaji
            $table->enum('relevansi_pekerjaan', [
                'sangat_relevan', 
                'relevan', 
                'cukup_relevan', 
                'tidak_relevan'
            ])->nullable();
            $table->string('cara_mendapat_pekerjaan')->nullable();
            $table->integer('waktu_tunggu_kerja')->nullable()->comment('dalam bulan');
            
            // Data Melanjutkan Studi
            $table->string('nama_institusi')->nullable();
            $table->enum('jenjang_studi', [
                'd3', 
                's1', 
                's2', 
                's3', 
                'kursus', 
                'pelatihan'
            ])->nullable();
            $table->string('jurusan_studi')->nullable();
            $table->string('sumber_biaya')->nullable();
            
            // Data Wirausaha
            $table->string('nama_usaha')->nullable();
            $table->string('bidang_usaha')->nullable();
            $table->integer('jumlah_karyawan')->nullable();
            $table->string('omzet_usaha')->nullable(); // Range omzet
            
            // Kepuasan & Feedback
            $table->integer('kepuasan_pendidikan')->nullable()->comment('1-5 scale');
            $table->text('saran_kurikulum')->nullable();
            $table->text('saran_fasilitas')->nullable();
            $table->text('saran_umum')->nullable();
            $table->json('kompetensi_yang_digunakan')->nullable();
            
            // Kontak Terkini
            $table->string('email_saat_ini')->nullable();
            $table->string('no_telp_saat_ini', 15)->nullable();
            $table->string('linkedin')->nullable();
            
            $table->timestamp('tanggal_isi')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('status_pekerjaan');
            $table->index('tanggal_isi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tracer_study');
    }
};