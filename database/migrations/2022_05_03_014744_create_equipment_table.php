<?php

use App\Models\Supplier;
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
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->string('description')->nullable();
            $table->string('unit')->nullable();
            $table->decimal('profit_percentage')->nullable();
            $table->decimal('weight', 10)->nullable();
            $table->unsignedInteger('in_stock')->nullable();
            $table->unsignedInteger('effective_qty')->nullable();
            $table->unsignedInteger('min_qty')->nullable();
            $table->decimal('purchase_value', 10)->nullable();
            $table->decimal('unit_value', 10)->nullable();
            $table->decimal('replace_value', 10)->nullable();
            $table->foreignIdFor(Supplier::class, 'supplier_id')->nullable();
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
        Schema::dropIfExists('equipment');
    }
};
