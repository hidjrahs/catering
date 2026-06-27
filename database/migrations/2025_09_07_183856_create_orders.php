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
        Schema::create('orders', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('customer_id');
            $table->decimal('estimate_price', 12, 2)->nullable();
            $table->dateTime('delivery_date')->nullable();
            $table->dateTime('event_date')->nullable();
            $table->char('order_ticket',18)->nullable();

            $table->string('event_type')->nullable(); // wedding, ulang tahun, dll
            $table->string('package_type')->nullable(); // buffet, gubug, paket breakfast, premium, dll
            $table->string('venue')->nullable(); 

            // $table->integer('total_guest')->nullable(); // jumlah tamu / porsi
            $table->string('total_guest')->nullable(); // jumlah tamu / porsi
            $table->enum('status', ['pending', 'approved', 'purchased', 'completed', 'cancelled'])->default('pending');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade'); 
            $table->longText('desc')->nullable();
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
        Schema::dropIfExists('orders');
    }
};
