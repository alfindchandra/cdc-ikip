<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::create('kerjasama_industri', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perusahaan_id')->constrained('perusahaan')->onDelete('cascade');
            $table->string('jenis_kerjasama', 100);
            $table->string('judul', 200);
            $table->text('deskripsi')->nullable();
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_berakhir')->nullable();
            $table->string('dokumen_mou')->nullable(); 
            $table->enum('status', ['aktif', 'nonaktif', 'selesai','draft'])->default('aktif');
            $table->string('pic_sekolah', 150)->nullable();
            $table->string('pic_industri', 150)->nullable();
            $table->decimal('nilai_kontrak', 15, 2)->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('kerjasama_industri');
    }
};
