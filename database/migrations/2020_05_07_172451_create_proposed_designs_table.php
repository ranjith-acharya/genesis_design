<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProposedDesignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proposed_designs', function (Blueprint $table) {
            $table->id();
            $table->json('fields')->nullable()->default(null);
            $table->foreignId('system_design_id')->nullable()->default(null);
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
        Schema::dropIfExists('proposed_designs');
    }
}
