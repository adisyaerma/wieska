<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang_masuk', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            
            // relasi ke tabel barang
            $table->unsignedBigInteger('stok_barang_id');
            $table->foreign('stok_barang_id')
                  ->references('id')
                  ->on('stok_barang')
                  ->onDelete('cascade');
            
            $table->integer('jumlah');
            
            // relasi ke tabel satuan
            $table->unsignedBigInteger('satuan_id');
            $table->foreign('satuan_id')
                  ->references('id')
                  ->on('satuan')
                  ->onDelete('cascade');
            
            $table->decimal('harga_satuan', 15, 2); // contoh Rp999.999.999.999,99
            $table->decimal('total_harga', 15, 2);
            
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang_masuk');
    }
};
