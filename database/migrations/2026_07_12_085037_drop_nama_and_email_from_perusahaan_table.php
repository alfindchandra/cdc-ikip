<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('perusahaan', function (Blueprint $table) {
            // Menghapus kolom jika ada di database
            if (Schema::hasColumn('perusahaan', 'nama_perusahaan')) {
                $table->dropColumn('nama_perusahaan');
            }
            if (Schema::hasColumn('perusahaan', 'email_perusahaan')) {
                $table->dropColumn('email_perusahaan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('perusahaan', function (Blueprint $table) {
            // Kembalikan kolom jika dilakukan rollback
            $table->string('nama_perusahaan')->after('user_id');
            $table->string('email_perusahaan')->nullable()->after('nama_perusahaan');
        });
    }
};