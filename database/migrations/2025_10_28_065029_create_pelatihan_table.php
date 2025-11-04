<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pelatihan', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi');
            $table->enum('jenis', ['soft_skill', 'hard_skill', 'sertifikasi', 'pembekalan']);
            $table->string('instruktur')->nullable();
            $table->string('tempat')->nullable();
            $table->dateTime('tanggal_mulai');
            $table->dateTime('tanggal_selesai');
            $table->integer('kuota')->nullable();
            $table->integer('jumlah_peserta')->default(0);
            $table->decimal('biaya', 15, 2)->default(0);
            $table->string('materi')->nullable();
            $table->string('sertifikat_template')->nullable();
            $table->enum('status', ['draft', 'published', 'ongoing', 'completed', 'cancelled'])->default('draft');
            $table->string('thumbnail')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pelatihan');
    }
};