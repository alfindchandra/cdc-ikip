<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi (Membuat tabel 'pengaturan').
     */
    public function up(): void
    {
        Schema::create('pengaturan', function (Blueprint $table) {
            $table->id(); // Primary Key

            // Nama kunci unik untuk pengaturan (misalnya: 'site_title', 'email_contact')
            $table->string('key_name')->unique(); 

            // Nilai dari pengaturan tersebut (bisa berupa string, angka, atau JSON yang diserialisasi)
            $table->text('value')->nullable();

            // Deskripsi singkat tentang fungsi pengaturan
            $table->string('description')->nullable();

            // Tipe data yang diharapkan (e.g., 'string', 'integer', 'boolean', 'json')
            $table->string('tipe')->default('string'); 

            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Balikkan migrasi (Menghapus tabel 'pengaturan').
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaturan');
    }
};
