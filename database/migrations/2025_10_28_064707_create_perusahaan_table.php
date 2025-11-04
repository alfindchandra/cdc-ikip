<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('perusahaan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('nama_perusahaan');
            $table->string('bidang_usaha', 100)->nullable();
            $table->text('alamat')->nullable();
            $table->string('kota', 100)->nullable();
            $table->string('provinsi', 100)->nullable();
            $table->string('kode_pos', 10)->nullable();
            $table->string('no_telp', 15)->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('nama_pic')->nullable();
            $table->string('jabatan_pic', 100)->nullable();
            $table->string('no_telp_pic', 15)->nullable();
            $table->string('email_pic')->nullable();
            $table->enum('status_kerjasama', ['aktif', 'nonaktif', 'pending'])->default('pending');
            $table->date('tanggal_kerjasama')->nullable();
            $table->string('logo')->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('perusahaan');
    }
};