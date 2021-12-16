<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemDesignPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_design_prices', function (Blueprint $table) {
            $table->id();
            $table->unsignedFloat('price');
            $table->unsignedFloat('change_price');

            $table->foreignId('system_design_type_id')->nullable()->default(null);
            $table->foreign('system_design_type_id')->references('id')->on('system_design_types');
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
        Schema::dropIfExists('system_design_prices');
    }
}
