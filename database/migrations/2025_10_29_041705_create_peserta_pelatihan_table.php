<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('peserta_pelatihan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('siswa_id');
            $table->unsignedBigInteger('pelatihan_id');

            $table->string('status_pendaftaran')->nullable();
            $table->string('status_kehadiran')->nullable();
            $table->decimal('nilai', 5, 2)->nullable();
            $table->string('sertifikat')->nullable(); // untuk menyimpan file atau kode sertifikat
            $table->text('feedback')->nullable();
            $table->timestamp('tanggal_daftar')->nullable();

            $table->timestamps();

            // Foreign key constraint
            $table->foreign('siswa_id')->references('id')->on('siswa')->onDelete('cascade');
            $table->foreign('pelatihan_id')->references('id')->on('pelatihan')->onDelete('cascade');

            // Pastikan kombinasi unik agar 1 siswa tidak bisa daftar pelatihan yang sama lebih dari sekali
            $table->unique(['siswa_id', 'pelatihan_id']);
        });
    }

    /**
     * Hapus tabel peserta_pelatihan jika dibatalkan.
     */
    public function down(): void
    {
        Schema::dropIfExists('peserta_pelatihan');
    }
};
