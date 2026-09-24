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
        Schema::create('effectivenesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attacking_type_id')->constrained('types')->cascadeOnDelete();
            $table->foreignId('defending_type_id')->constrained('types')->cascadeOnDelete();
            $table->enum('category', ['immune', 'resist', 'normal', 'weak'])->default('normal');
            $table->timestamps();

            $table->unique(['attacking_type_id', 'defending_type_id']); // 1 baris per kombinasi
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('effectivenesses');
    }
};
