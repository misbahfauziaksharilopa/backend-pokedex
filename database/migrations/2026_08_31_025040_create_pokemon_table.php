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
        Schema::create('pokemon', function (Blueprint $table) {
            $table->id();
            $table->string('national_number')->unique();
            $table->string('name');
            $table->text('description')->nullable();

            $table->foreignId('type_1_id')->nullable()->constrained('types')->cascadeOnDelete();
            $table->foreignId('type_2_id')->nullable()->constrained('types')->cascadeOnDelete();

            $table->string('ability_1')->nullable();
            $table->string('ability_2')->nullable();
            $table->string('hidden_ability')->nullable();

            $table->string('sprite_image')->nullable();

            $table->foreignId('form_1_id')->nullable()->constrained('forms')->cascadeOnDelete();
            $table->foreignId('form_2_id')->nullable()->constrained('forms')->cascadeOnDelete();
            $table->foreignId('form_3_id')->nullable()->constrained('forms')->cascadeOnDelete();

            $table->unsignedSmallInteger('hp');
            $table->unsignedSmallInteger('atk');
            $table->unsignedSmallInteger('def');
            $table->unsignedSmallInteger('spa');
            $table->unsignedSmallInteger('spd');
            $table->unsignedSmallInteger('spe');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pokemon');
    }
};
