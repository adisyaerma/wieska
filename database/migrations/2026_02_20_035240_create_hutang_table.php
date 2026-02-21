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
        Schema::create('hutang', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');                 // Tanggal input hutang
            $table->string('pihak');                  // Nama pihak
            $table->text('keterangan')->nullable();   // Keterangan
            $table->integer('total_hutang');   // Total hutang
            $table->date('tanggal_bayar')->nullable();// Tanggal bayar
            $table->date('jatuh_tempo');              // Jatuh tempo
            $table->integer('sisa_hutang')->nullable();              // Jatuh tempo
            $table->enum('status', [
                'Belum Lunas',
                'Lunas',
                'Jatuh Tempo'
            ])->default('Belum Lunas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hutang');
    }
};
