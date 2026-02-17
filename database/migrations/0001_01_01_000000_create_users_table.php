<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); 
            $table->string('nama');
            $table->enum('role', ['admin', 'kasir'])->default('kasir');
            $table->string('alamat')->nullable();
            $table->string('no_telpon', 20)->nullable();
            $table->string('email')->unique();
            $table->date('tgl_bergabung')->nullable();
            $table->string('foto')->nullable();
            $table->string('password');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
