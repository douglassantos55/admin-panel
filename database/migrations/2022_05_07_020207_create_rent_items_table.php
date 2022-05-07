<?php

use App\Models\Equipment;
use App\Models\Rent;
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
        Schema::create('rent_items', function (Blueprint $table) {
            $table->id();
            $table->integer('qty')->unsigned();
            $table->decimal('rent_value', 10);
            $table->decimal('unit_value', 10);
            $table->foreignIdFor(Rent::class);
            $table->foreignIdFor(Equipment::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rent_items');
    }
};
