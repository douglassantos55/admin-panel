<?php

use App\Models\Equipment;
use App\Models\Period;
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
        Schema::create('renting_values', function (Blueprint $table) {
            $table->id();
            $table->decimal('value');
            $table->foreignIdFor(Period::class);
            $table->foreignIdFor(Equipment::class);
            $table->timestamp('createdAt')->nullable();
            $table->timestamp('updatedAt')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('renting_values');
    }
};
