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
        Schema::create('stok_barang', function (Blueprint $table) {
            $table->id();
            // $table->unsignedBigInteger('barang_id'); 
            $table->string('nama_barang', 150);
            $table->string('kode_barang', 50)->unique();
            $table->unsignedBigInteger('kategori_id');
            $table->unsignedBigInteger('satuan_id'); // relasi ke satuan
            $table->integer('total_stok')->default(0);
            $table->timestamps();

            // foreign key
            // $table->foreign('barang_id')->references('id')->on('barang')->onDelete('cascade');
            $table->foreign('satuan_id')->references('id')->on('satuan')->onDelete('cascade');
            $table->foreign('kategori_id')->references('id')->on('kategori')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_barang');
    }
};
