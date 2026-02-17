<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_xx_xx_create_tiket_detail_table.php
    public function up(): void
    {
        Schema::create('tiket_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_tiket');          // relasi ke tiket utama
            $table->unsignedBigInteger('id_jenis_tiket');    // relasi ke jenis_tiket
            $table->integer('jumlah');
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();

            // Foreign key ke tabel tiket
            $table->foreign('id_tiket')->references('id')->on('tiket')->onDelete('cascade');

            // Foreign key ke tabel jenis_tiket
            $table->foreign('id_jenis_tiket')->references('id')->on('jenis_tiket')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tiket_detail');
    }
};
