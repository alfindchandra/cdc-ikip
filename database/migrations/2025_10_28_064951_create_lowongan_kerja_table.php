<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('lowongan_kerja', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perusahaan_id')->constrained('perusahaan')->onDelete('cascade');
            $table->string('posisi', 100);
            $table->string('pendidikan', 100);
            $table->text('deskripsi');
            $table->text('kualifikasi');
            $table->text('benefit')->nullable();
            $table->enum('tipe_pekerjaan', ['full_time', 'part_time', 'kontrak', 'magang']);
            $table->string('lokasi');
            $table->decimal('gaji_min', 15, 2)->nullable();
            $table->decimal('gaji_max', 15, 2)->nullable();
            $table->integer('kuota')->nullable();
            $table->date('tanggal_mulai');
            $table->date('tanggal_berakhir');
            $table->enum('status', ['aktif', 'nonaktif', 'expired'])->default('aktif');
            $table->integer('jumlah_pelamar')->default(0);
            $table->string('thumbnail')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lowongan_kerja');
    }
};

