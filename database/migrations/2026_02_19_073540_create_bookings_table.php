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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            $table->date('tanggal');
            $table->string('nama');
            $table->text('keterangan')->nullable();
            $table->string('kontak');
            $table->string('acara');

            // ✅ status sesuai permintaan
            $table->enum('status', ['Pending', 'Hadir', 'Batal'])->default('Pending');

            $table->integer('harga');
            $table->unsignedBigInteger('id_karyawan')->nullable();
            $table->integer('jumlah_orang');

            $table->timestamps();

            $table->foreign('id_karyawan')
                ->references('id')
                ->on('karyawan')
                ->nullOnDelete();
                });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
