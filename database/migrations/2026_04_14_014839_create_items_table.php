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
        Schema::create('items', function (Blueprint $table) {
            $table->id(); 
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('name'); 
            $table->integer('total');
            $table->integer('repair')->default(0);
            $table->integer('available')->default(0);
            $table->integer('lending_total')->default(0);

            $table->timestamps(); // Melacak waktu pendataan barang.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
