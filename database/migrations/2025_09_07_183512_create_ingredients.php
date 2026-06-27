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
        Schema::create('ingredients', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name')->index('idx_ingredients');
            $table->float('unit')->default(0);// unit reference default price 
            $table->char('satuan')->default('gram')->nullable();
            $table->decimal('default_price', 12, 2)->nullable(); 
            // $table->ulid('supplier_id')->nullable();
            // $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade'); 
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
        Schema::dropIfExists('ingredients');
    }
};
