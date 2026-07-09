<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('e_portfolios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->cascadeOnDelete();
            $table->text('profil_kompetensi')->nullable();
            $table->text('pengalaman_kerja')->nullable();
            $table->text('prestasi')->nullable();
            $table->text('sertifikat')->nullable();
            $table->string('profil_path')->nullable();
            $table->string('pengalaman_path')->nullable();
            $table->string('prestasi_path')->nullable();
            $table->string('sertifikat_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('e_portfolios');
    }
};
