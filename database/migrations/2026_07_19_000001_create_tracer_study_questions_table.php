<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tracer_study_questions', function (Blueprint $table) {
            $table->id();
            $table->string('section', 100)->comment('Bagian/kelompok pertanyaan, misal: status_pekerjaan, data_pekerjaan, feedback');
            $table->string('field_name', 100)->comment('Nama field di tabel tracer_study');
            $table->string('label', 255)->comment('Label yang ditampilkan di form');
            $table->string('type', 50)->default('text')->comment('Tipe input: text, textarea, radio, select, number, checkbox');
            $table->json('options')->nullable()->comment('Pilihan untuk tipe radio/select, format JSON');
            $table->boolean('is_required')->default(false);
            $table->boolean('is_active')->default(true)->comment('Apakah pertanyaan ini ditampilkan');
            $table->integer('sort_order')->default(0)->comment('Urutan tampil dalam section');
            $table->text('helper_text')->nullable()->comment('Teks bantuan di bawah field');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tracer_study_questions');
    }
};
