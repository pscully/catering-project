<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('delivery_locations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->string('address');
            $table->string('city');
            $table->string('state');
            $table->string('zip_code');
            $table->string('street');
            $table->string('latitude');
            $table->string('longitude');
            $table->string('apartment_suite')->nullable();
            $table->boolean('is_business')->default(false);
            $table->string('business_name')->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('catering_orders');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_locations');
    }
};
