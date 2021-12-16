<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDesignFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('design_files', function (Blueprint $table) {
            $table->id();
            $table->string('path');
            $table->string('content_type');

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
        Schema::dropIfExists('design_files');
    }
}
