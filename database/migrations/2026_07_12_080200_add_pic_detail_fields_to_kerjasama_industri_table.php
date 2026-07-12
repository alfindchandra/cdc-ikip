<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Data PIC (Person In Charge) dipindahkan dari form Registrasi PT
     * ke form pengajuan Kerja Sama, karena PIC bisa berbeda-beda
     * untuk tiap kerja sama yang diajukan.
     *
     * Kolom 'pic_industri' (sudah ada) tetap dipakai sebagai Nama PIC.
     * Kolom baru di bawah melengkapi jabatan, no. telepon, dan email PIC.
     */
    public function up(): void
    {
        Schema::table('kerjasama_industri', function (Blueprint $table) {
            $table->string('jabatan_pic_industri', 100)->nullable()->after('pic_industri');
            $table->string('no_telp_pic_industri', 20)->nullable()->after('jabatan_pic_industri');
            $table->string('email_pic_industri')->nullable()->after('no_telp_pic_industri');
        });
    }

    public function down(): void
    {
        Schema::table('kerjasama_industri', function (Blueprint $table) {
            $table->dropColumn(['jabatan_pic_industri', 'no_telp_pic_industri', 'email_pic_industri']);
        });
    }
};
