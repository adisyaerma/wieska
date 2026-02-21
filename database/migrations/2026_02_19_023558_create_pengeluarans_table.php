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
        Schema::create('pengeluarans', function (Blueprint $table) {
            $table->id();
            $table->dateTime('tanggal')->nullable();
            $table->enum('jenis_pengeluaran', ['Operasional', 'Gaji', 'Hutang',  'Kembalian','Lainnya']);
            $table->integer('refrensi_id');
            $table->string('tujuan_pengeluaran');
            $table->integer('nominal_pengeluaran');
            $table->integer('gaji_pokok')->nullable();
            $table->integer('potongan')->nullable();
            $table->integer('bonus')->nullable();
            $table->enum('status', ['Valid', 'Dibatalkan']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengeluarans');
    }
};
