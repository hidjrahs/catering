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
        Schema::create('stocks_movements', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('ingredient_id');
            $table->enum('type', ['in', 'out']); // in = masuk (pembelian), out = keluar (pakai untuk order)
            $table->decimal('quantity', 12, 2);
            $table->string('reference_type')->nullable(); // purchase / order
            $table->uuid('reference_id')->nullable();
            $table->foreign('ingredient_id')->references('id')->on('ingredients')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks_movements');
    }
};
