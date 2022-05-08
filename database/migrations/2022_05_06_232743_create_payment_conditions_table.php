<?php

use App\Models\PaymentType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_conditions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('title');
            $table->decimal('increment')->nullable();
            $table->json('installments')->nullable();
            $table->foreignIdFor(PaymentType::class);
            $table->timestamp('createdAt')->nullable();
            $table->timestamp('updatedAt')->nullable();
            $table->softDeletes('deletedAt');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_conditions');
    }
};
