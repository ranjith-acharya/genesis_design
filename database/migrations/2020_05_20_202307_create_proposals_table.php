<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProposalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proposals', function (Blueprint $table) {
            $table->id();
            $table->longText('note')->nullable()->default(null);

            $table->foreignId('engineer_id');
            $table->foreign('engineer_id')->references('id')->on('users');
            $table->foreignId('change_request_id')->nullable()->default(null);
            $table->foreign('change_request_id')->references('id')->on('change_requests');
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
        Schema::dropIfExists('proposals');
    }
}
