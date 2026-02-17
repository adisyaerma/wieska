<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cafe_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cafe_id');
            $table->unsignedBigInteger('menu_id'); 
            $table->integer('jumlah');
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();

            $table->foreign('cafe_id')->references('id')->on('cafe')->onDelete('cascade');
            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cafe_detail');
    }
};
