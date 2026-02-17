<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cafe', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('nama_pelanggan');
            $table->unsignedBigInteger('id_karyawan'); // foreign ke tabel karyawan

            // total transaksi
            $table->decimal('subtotal', 15, 2)->default(0); 

            // tambahan kolom pembayaran
            $table->decimal('dibayarkan', 15, 2)->default(0);
            $table->decimal('kembalian', 15, 2)->default(0);

            $table->timestamps();

            // relasi ke tabel karyawan
            $table->foreign('id_karyawan')
                ->references('id')
                ->on('karyawan')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cafe');
    }
};
