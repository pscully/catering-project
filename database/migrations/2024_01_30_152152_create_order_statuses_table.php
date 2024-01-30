<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\CateringStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('catering_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        $this->postCreate('NEW', 'IN PROGRESS', 'DECLINED', 'COMPLETED', 'DELIVERED', 'REFUNDED', 'FULFILLED');
    }

    private function postCreate(string ...$statuses)
    {
        foreach ($statuses as $status) {
            $model = new CateringStatus();
            $model->setAttribute('name', $status);
            $model->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_statuses');
    }
};
