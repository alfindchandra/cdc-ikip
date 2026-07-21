<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tracer_study', function (Blueprint $table) {
            $table->json('additional_answers')
                  ->nullable()
                  ->after('kompetensi_yang_digunakan')
                  ->comment('Jawaban untuk pertanyaan tambahan yang dibuat admin lewat tracer_study_questions');
        });
    }

    public function down(): void
    {
        Schema::table('tracer_study', function (Blueprint $table) {
            $table->dropColumn('additional_answers');
        });
    }
};
