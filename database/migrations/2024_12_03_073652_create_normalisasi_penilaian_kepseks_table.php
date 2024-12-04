<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('normalisasi_penilaian_kepseks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('detail_penilaian_kepsek_id')->constrained('detail_penilaian_kepseks')->onDelete('cascade');
            $table->foreignId('periode_id')->constrained('periodes')->onDelete('cascade');
            $table->foreignId('kriteria_id')->constrained('kriterias')->onDelete('cascade');
            $table->foreignId('guru_id')->constrained('gurus')->onDelete('cascade');
            $table->float('normalisasi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('normalisasi_penilaian_kepseks');
    }
};
