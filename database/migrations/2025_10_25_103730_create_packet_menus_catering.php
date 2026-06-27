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
        Schema::create('packet_menus_catering', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('packet_catering_id')->constrained('packet_catering')->onDelete('cascade');
            $table->foreignUlid('menus_catering_id')->constrained('menus_catering')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packet_menus_catering');
    }
};
