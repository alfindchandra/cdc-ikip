<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi (Membuat tabel 'jurnal_pkl').
     */
    public function up(): void
    {
        Schema::create('jurnal_pkl', function (Blueprint $table) {
            $table->id(); 

            
            $table->foreignId('pkl_id')
                  ->constrained('pkl') 
                  ->onDelete('cascade');

            $table->date('tanggal'); // tanggal DATE NOT NULL
            $table->text('kegiatan'); // kegiatan TEXT NOT NULL
            $table->string('foto')->nullable(); // foto VARCHAR(255) NULL
            
            // status_validasi ENUM('pending', 'disetujui', 'ditolak') DEFAULT 'pending'
            $table->enum('status_validasi', ['pending', 'disetujui', 'ditolak'])
                  ->default('pending');

            $table->text('catatan_pembimbing')->nullable(); // catatan_pembimbing TEXT NULL

            $table->timestamps(); 
            // Laravel's timestamps() akan membuat kolom created_at dan updated_at
        });
    }

    /**
     * Balikkan migrasi (Menghapus tabel 'jurnal_pkl').
     */
    public function down(): void
    {
        Schema::dropIfExists('jurnal_pkl');
    }
};
