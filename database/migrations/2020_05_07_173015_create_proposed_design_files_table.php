<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProposedDesignFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proposed_design_files', function (Blueprint $table) {
            $table->id();
            $table->string('path');
            $table->string('content_type');

            $table->foreignId('proposed_design_id')->nullable()->default(null);
            $table->foreign('proposed_design_id')->references('id')->on('proposed_designs');

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
        Schema::dropIfExists('proposed_design_files');
    }
}
