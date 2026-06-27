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
        Schema::create('cost_estimation_detail', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('cost_estimation_id');
            $table->string('name');
            $table->boolean('fixed')->default(false);
            $table->string('kategori')->nullable(); 
            $table->decimal('prosentase', 4, 2)->nullable();
            $table->decimal('prosentase_price', 12, 2)->nullable();
            $table->decimal('fixed_price', 12, 2)->nullable();
            $table->decimal('fixed_qty', 12, 2)->nullable();
            

            $table->foreign('cost_estimation_id')->references('id')->on('cost_estimations')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cost_estimation_detail');
    }
};
