<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('karyawan', function (Blueprint $table) {
            $table->id();
            $table->string('foto')->nullable();
            $table->string('nama', 150);
            $table->string('jabatan', 100);
            $table->string('email', 150)->unique()->nullable();;
            $table->string('no_telp', 20)->nullable();;
            $table->text('alamat')->nullable();;
            $table->date('tgl_bergabung')->nullable();;
            $table->string('password')->nullable();; 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('karyawan');
    }
};
