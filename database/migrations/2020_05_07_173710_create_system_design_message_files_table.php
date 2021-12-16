<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemDesignMessageFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_design_message_files', function (Blueprint $table) {
            $table->id();
            $table->string('path');
            $table->string('content_type');

            $table->foreignId('system_design_message_id');
            $table->foreign('system_design_message_id')->references('id')->on('system_design_messages');

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
        Schema::dropIfExists('system_design_message_files');
    }
}
