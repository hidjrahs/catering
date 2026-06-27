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
        Schema::table('cost_estimations', function (Blueprint $table) {
            $table->ulid('cost_structure_id')->nullable();
            $table->foreign('cost_structure_id')->references('id')->on('cost_structure')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cost_estimations', function (Blueprint $table) {
            $table->dropColumn('cost_structure_id');
        });
    }
};
