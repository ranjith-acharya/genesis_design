<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemArraysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_arrays', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger("panel_count");
            $table->unsignedInteger("inverter_count");
            $table->string("tilt");
            $table->string("azimuth");
            $table->string("solar_access");

            $table->foreignId("solar_panel_id");
            $table->foreignId("inverter_id");
            $table->foreignId("racking_id");
            $table->foreign('solar_panel_id')->references('id')->on('equipment');
            $table->foreign('inverter_id')->references('id')->on('equipment');
            $table->foreign('racking_id')->references('id')->on('equipment');

            $table->foreignId("system_design_id");
            $table->foreign('system_design_id')->references('id')->on('system_designs');

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
        Schema::dropIfExists('system_arrays');
    }
}
