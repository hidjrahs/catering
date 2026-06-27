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
        Schema::table('menus_catering', function (Blueprint $table) {
            $table->ulid('category_menus_catering_id')->after('name');
            $table->boolean('is_active')->default(false);
            $table->double('porsi_standard')->default(0);
            $table->foreign('category_menus_catering_id')->references('id')->on('category_menus_catering')->onDelete('cascade'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menus_catering', function (Blueprint $table) {
            $table->dropColumn('category_menus_catering_id');
            $table->dropColumn('is_active');
        });
    }
};
