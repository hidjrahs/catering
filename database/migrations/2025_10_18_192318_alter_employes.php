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
         Schema::table('employes', function (Blueprint $table) {
            // Informasi dasar tambahan
            $table->string('national_id')->nullable()->after('name'); // NO KTP/SIM
            $table->string('status')->nullable()->after('national_id'); // STATUS (Kawin, Belum Kawin, dll)
            $table->string('work_since')->nullable()->after('status'); // BEKERJA SEJAK
            $table->string('division')->nullable()->after('location'); // JABATAN / DIVISI
            $table->string('birth_place_date')->nullable()->after('division'); // TEMPAT/TANGGAL LAHIR
            $table->integer('height_cm')->nullable(); // TB (CM)
            $table->integer('weight_kg')->nullable(); // BB (KG)
            $table->string('religion')->nullable(); // AGAMA
            $table->uuid('user_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employes', function (Blueprint $table) {
            $table->dropColumn([
                'national_id', 'status', 'work_since', 'division',
                'birth_place_date', 'height_cm', 'weight_kg','religion',
                'user_id'
            ]);
        });
    }
};
