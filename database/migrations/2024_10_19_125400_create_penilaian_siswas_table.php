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
        Schema::create('penilaian_siswas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periode_id')->constrained('periodes')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('jumlah_guru_dinilain')->default(0);
            // HAPUS NANTI INI
            // $table->string('rangking_1')->nullable();
            // $table->float('skor_1')->nullable();
            // $table->string('rangking_2')->nullable();
            // $table->float('skor_3')->nullable();
            // $table->string('rangking_3')->nullable();
            // $table->float('skor_2')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaian_siswas');
    }
};
