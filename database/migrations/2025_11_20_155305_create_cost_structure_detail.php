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
        Schema::create('cost_structure_detail', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('cost_structure_id');
            $table->string('name')->nullable();
            $table->boolean('fixed')->default(false);
            $table->string('kategori')->nullable(); 
            $table->decimal('prosentase', 4, 2)->nullable();
            
            $table->foreign('cost_structure_id')->references('id')->on('cost_structure')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cost_structure_detail');
    }
};
