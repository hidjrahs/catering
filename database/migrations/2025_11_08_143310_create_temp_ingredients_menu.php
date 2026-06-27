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
        Schema::create('temp_ingredients_menu', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('temp_recipe_menu_id')->constrained('temp_recipe_menu')->onDelete('cascade');
            $table->string('ingredient_name')->index('idx_temp_ingredients_menu');
            $table->string('qty')->nullable();
            $table->string('satuan')->nullable();
            $table->string('unit')->nullable();
            $table->string('price_per_unit')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temp_ingredients_menu');
    }
};
