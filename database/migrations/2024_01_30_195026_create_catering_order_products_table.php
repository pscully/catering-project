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
        Schema::create('catering_order_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id');
            $table->foreignId('product_id');
            $table->integer('quantity')->default(1);
            $table->timestamps();

            $table->index(['order_id', 'product_id']);

            $table->foreign('order_id')->references('id')->on('catering_orders');
            $table->foreign('product_id')->references('id')->on('catering_products');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catering_order_products');
    }
};
