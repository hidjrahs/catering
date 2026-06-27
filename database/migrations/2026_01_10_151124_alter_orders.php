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
        Schema::table('orders', function (Blueprint $table) {
            // $table->integer('total_invite')->nullable();
            $table->string('total_invite')->nullable(); 
            $table->longText('desc_extra')->nullable();
            $table->time('event_time')->nullable();
            $table->decimal('dp', 12, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('total_invite');
            $table->dropColumn('desc_extra');
            $table->dropColumn('event_time');
            $table->dropColumn('dp');
        });
    }
};
