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
        Schema::create('menus_catering_tumb', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('menus_catering_id');
            $table->string('filename')->nullable();
            $table->string('path')->nullable();
            $table->string('path_original')->nullable();
            $table->string('disk')->nullable();
            $table->foreign('menus_catering_id')->references('id')->on('menus_catering')->onDelete('cascade'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus_catering_tumb');
    }
};
