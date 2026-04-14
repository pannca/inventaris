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
        Schema::create('lendings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained();
            $table->string('name');
            $table->integer('total');
            $table->text('ket')->nullable();
            $table->date('date');
            $table->date('return_date')->nullable();
            $table->string('edited_by')->nullable();
            $table->string('signature')->nullable();
            $table->integer('returned_total')->default(0);
            $table->text('return_ket')->nullable();
            $table->string('return_signature')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lendings');
    }
};
