<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            // optional: relasi ke stok_barang jika ingin mengaitkan
            $table->foreignId('stok_barang_id')->nullable()->constrained('stok_barang')->nullOnDelete();
            $table->string('gambar')->nullable();
            $table->decimal('harga_jual', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
