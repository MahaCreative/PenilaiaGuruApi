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
        Schema::create('periodes', function (Blueprint $table) {
            $table->id();
            $table->string('bulan');
            $table->string('tahun');
            $table->date('tanggal_mulai');
            $table->date('tanggal_berakhir');
            // GUNAKAN NANTI INI
            $table->string('rangking_1')->nullable();
            $table->float('skor_1')->nullable();

            $table->string('rangking_2')->nullable();
            $table->float('skor_2')->nullable();

            $table->string('rangking_3')->nullable();
            $table->float('skor_3')->nullable();
            $table->string('status')->default('berlangsung');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periodes');
    }
};
