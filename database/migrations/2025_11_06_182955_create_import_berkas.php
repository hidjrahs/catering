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
        Schema::create('import_berkas', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('source',50)->index('idx_import_berkas');
            $table->string('filename');
            $table->string('path');
            $table->string('disk');
            $table->boolean('is_preview')->default(false);
            $table->boolean('is_previewed')->default(false);
            $table->boolean('is_process')->default(false);
            $table->boolean('is_done')->default(false);
            $table->longText('desc')->nullable();
            $table->timestamps();
            $table->bigInteger('created_by')->nullable();
            $table->timestamp('done_at',0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('import_berkas');
    }
};
