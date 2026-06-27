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
        Schema::create('employee_educations', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('employee_id')->constrained('employes')->onDelete('cascade');
            $table->string('education_level')->nullable(); // SD/SMP/SMA/D3/S1
            $table->string('school_name')->nullable();
            $table->string('city')->nullable();
            $table->string('major')->nullable();
            $table->year('year_start')->nullable();
            $table->year('year_graduated')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_educations');
    }
};
