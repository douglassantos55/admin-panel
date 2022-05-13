<?php

use App\Models\Customer;
use App\Models\PaymentCondition;
use App\Models\PaymentMethod;
use App\Models\PaymentType;
use App\Models\Period;
use App\Models\Transporter;
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
        Schema::create('rents', function (Blueprint $table) {
            $table->id();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->integer('qty_days')->unsigned();
            $table->decimal('discount', 10)->nullable();
            $table->decimal('paid_value', 10)->nullable();
            $table->decimal('bill', 10)->nullable();
            $table->string('check_info')->nullable();
            $table->string('delivery_address')->nullable();
            $table->string('discount_reason')->nullable();
            $table->decimal('delivery_value', 10)->nullable();
            $table->string('usage_address')->nullable();
            $table->text('observations')->nullable();
            $table->foreignIdFor(Customer::class);
            $table->foreignIdFor(Period::class);
            $table->foreignIdFor(PaymentType::class);
            $table->foreignIdFor(PaymentCondition::class);
            $table->foreignIdFor(PaymentMethod::class);
            $table->foreignIdFor(Transporter::class);
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
        Schema::dropIfExists('rents');
    }
};
