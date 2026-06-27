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
        Schema::create('menus_catering_ingredients', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('menus_catering_id');
            $table->ulid('ingredient_id')->nullable();
            $table->string('ingredient_label')->nullable();
            $table->decimal('quantity', 12, 2)->nullable(); // jumlah bahan per menu standard
            $table->foreign('menus_catering_id')->references('id')->on('menus_catering')->onDelete('cascade'); 
            $table->foreign('ingredient_id')->references('id')->on('ingredients')->onDelete('cascade'); 
            $table->timestamps();
            $table->softDeletes();
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->uuid('deleted_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus_catering_ingredients');
    }
};
