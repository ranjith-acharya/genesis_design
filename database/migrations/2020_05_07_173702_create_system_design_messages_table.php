<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemDesignMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_design_messages', function (Blueprint $table) {
            $table->id();
            $table->longText('message');
            $table->boolean('read')->default(false);

            $table->foreignId('sender_id');
            $table->foreign('sender_id')->references('id')->on('users');
            $table->foreignId('system_design_id');
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
        Schema::dropIfExists('system_design_messages');
    }
}
