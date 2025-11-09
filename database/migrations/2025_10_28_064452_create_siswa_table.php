<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nim', 20)->unique();
            $table->string('tempat_lahir', 100)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('agama', 20)->nullable();
            $table->text('alamat')->nullable();
            $table->string('no_telp', 15)->nullable();
            $table->string('fakultas', 100)->nullable();
            $table->string('program_studi', 100)->nullable();
            $table->year('tahun_masuk')->nullable();
            $table->string('nama_ortu')->nullable();
            $table->string('pekerjaan_ortu', 100)->nullable();
            $table->string('no_telp_ortu', 15)->nullable();
            $table->enum('status', ['aktif', 'lulus', 'pindah', 'keluar'])->default('aktif');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('siswa');
    }
};