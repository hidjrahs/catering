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
        Schema::create('temp_recipe_menu', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('import_berkas_id')->constrained('import_berkas')->onDelete('cascade');
            $table->string('recipe_name')->index('idx_temp_recipe_name');
            $table->string('category')->nullable();
            $table->string('paket')->nullable();
            $table->string('portion_standard')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temp_recipe_menu');
    }
};
