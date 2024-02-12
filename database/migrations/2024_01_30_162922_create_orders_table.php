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
        Schema::create('catering_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // foreign key
            $table->unsignedBigInteger('catering_status_id')->default(1); // foreign key
            $table->date('order_date')->nullable();
            $table->string('order_time');
            $table->string('closest_location')->nullable();
            $table->string('pickup_first_name')->nullable();
            $table->text('notes')->nullable();
            $table->integer('number_people')->nullable();
            $table->boolean('delivery')->nullable();
            $table->boolean('setup')->default(false);
            $table->string('coffee_type')->nullable();
            $table->string('charge_id')->nullable();
            $table->string('pp_capture_id')->nullable();
            $table->decimal('total', 13, 2)->nullable();
            $table->decimal('refunded_sum', 13, 2)->nullable();
            $table->string('image_filename')->nullable();
            $table->timestamps();
            $table->timestamp('notified_at')->nullable();
            $table->timestamp('status_updated_at')->nullable();
            $table->softDeletes();

            $table->index('pp_capture_id');

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('catering_status_id')->references('id')->on('catering_statuses');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
