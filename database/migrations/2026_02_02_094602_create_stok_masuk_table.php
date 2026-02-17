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
        Schema::create('stok_masuk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tas_id')->constrained('tas')->onDelete('cascade');
            $table->string('warna');
            $table->date('tanggal_masuk');
            $table->integer('jumlah');
            $table->string('keterangan')->nullable();
            $table->timestamps();
            
            $table->index('tas_id');
            $table->index('tanggal_masuk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_masuk');
    }
};
