<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  
    public function up(): void
    {
        Schema::create('lamaran', function (Blueprint $table) {
            $table->id(); 

            $table->foreignId('lowongan_id')
                  ->constrained('lowongan_kerja')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

         
            $table->foreignId('siswa_id')
                  ->constrained('siswa')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

          
            $table->string('cv');          
            $table->string('surat_lamaran'); 
            $table->string('portofolio')->nullable(); 

          
            $table->enum('status', ['dikirim', 'dilihat', 'diproses', 'diterima', 'ditolak'])
                  ->default('dikirim');

          
            $table->timestamp('tanggal_melamar')->useCurrent(); 

            $table->text('catatan')->nullable();

            $table->timestamps(); 
        });
    }

   
    public function down(): void
    {
        Schema::dropIfExists('lamaran');
    }
};
