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
        Schema::create('ingredients_supplier', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('ingredient_id')->nullable();
            $table->foreign('ingredient_id')->references('id')->on('ingredients')->onDelete('cascade'); 
            $table->ulid('supplier_id')->nullable();
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade'); 
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredients_supplier');
    }
};
